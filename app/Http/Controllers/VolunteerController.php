<?php
namespace App\Http\Controllers;
use App\Models\{CleanupDrive, CleanupDriveRegistration, CleanupDriveEvidence, VolunteerApplication, VolunteerPointTransaction, VolunteerViolation, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Storage};
class VolunteerController extends Controller
{
    /** Return the authenticated application user with a concrete model type for PHP and IDE analysis. */
    private function authenticatedUser(): User
    {
        abort_unless(Auth::check(), 401);
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }

    private function volunteer()
    {
        abort_unless($this->authenticatedUser()->role === 'volunteer', 403, 'Volunteer access required.');
    }

    public function drives()
    {
        $user = $this->authenticatedUser();
        $driveQuery = CleanupDrive::with('organizer')
            ->withCount(['registrations' => fn ($q) => $q->whereNotIn('attendance_status', ['cancelled'])])
            ->where('start_at', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_at');
        if ($user->barangay_id) {
            $driveQuery->where('barangay_id', $user->barangay_id);
        } else {
            $driveQuery->whereRaw('1 = 0');
        }
        $drives = $driveQuery->get();
        $joined = $user
            ->cleanupRegistrations()
            ->whereNotIn('attendance_status', ['cancelled', 'no_show'])
            ->pluck('cleanup_drive_id')
            ->all();
        $application = $user->volunteerApplications()->latest()->first();
        return view('user-cleanup-drives', compact('drives', 'joined', 'application', 'user'));
    }

    public function applyForm()
    {
        abort_unless($this->authenticatedUser()->role === 'resident', 403);
        $existing = $this->authenticatedUser()->volunteerApplications()->where('status', 'pending')->exists();
        if ($existing) {
            return redirect()->route('user-cleanup-drives')->with('notice', 'Your volunteer application is awaiting review.');
        }
        return view('volunteer.apply');
    }
    public function apply(Request $r)
    {
        abort_unless($this->authenticatedUser()->role === 'resident', 403);
        $data = $r->validate(['id_type' => 'required|string|max:80', 'id_number' => 'required|string|max:100', 'id_front' => 'required|image|max:5120', 'id_back' => 'nullable|image|max:5120', 'guardian_name' => 'nullable|string|max:150', 'guardian_phone' => 'nullable|string|max:30', 'organization_name' => 'nullable|string|max:150', 'organization_type' => 'nullable|string|max:100', 'organization_role' => 'nullable|string|max:100', 'skills' => 'nullable|array', 'skills.*' => 'string|max:80', 'availability' => 'nullable|array', 'availability.*' => 'string|max:80', 'emergency_contact_name' => 'required|string|max:150', 'emergency_contact_phone' => 'required|string|max:30', 'emergency_contact_relationship' => 'required|string|max:80', 'terms' => 'accepted', 'privacy' => 'accepted']);
        if ($this->authenticatedUser()->birthdate && $this->authenticatedUser()->birthdate->age < 18) {
            $r->validate(['guardian_name' => 'required', 'guardian_phone' => 'required']);
        }
        if ($this->authenticatedUser()->volunteerApplications()->where('status', 'pending')->exists()) {
            return back()->with('notice', 'You already have a pending application.');
        }
        $front = $r->file('id_front')->store('volunteer-ids');
        $back = $r->file('id_back')?->store('volunteer-ids');
        VolunteerApplication::create(['user_id' => Auth::id(), 'status' => 'pending', 'id_type' => $data['id_type'], 'id_number' => $data['id_number'], 'id_front_path' => $front, 'id_back_path' => $back, 'guardian_name' => $data['guardian_name'] ?? null, 'guardian_phone' => $data['guardian_phone'] ?? null, 'organization_name' => $data['organization_name'] ?? null, 'organization_type' => $data['organization_type'] ?? null, 'organization_role' => $data['organization_role'] ?? null, 'skills' => $data['skills'] ?? [], 'availability' => $data['availability'] ?? [], 'emergency_contact_name' => $data['emergency_contact_name'], 'emergency_contact_phone' => $data['emergency_contact_phone'], 'emergency_contact_relationship' => $data['emergency_contact_relationship']]);
        return redirect()->route('user-cleanup-drives')->with('notice', 'Application submitted. You can check its status on the Cleanup Drives page.');
    }
    public function myActivities()
    {
        $this->volunteer();
        $regs = $this->authenticatedUser()->cleanupRegistrations()->with('drive', 'evidence')->orderBy('registered_at', 'desc')->get();
        foreach ($regs as $reg) {
            $displayStatus = $reg->drive->status === 'cancelled' ? 'cancelled' : $reg->attendance_status;
            if ($reg->drive->status !== 'cancelled' && !$reg->verified_at && !$reg->penalty_processed_at && $displayStatus === 'registered' && now()->between($reg->drive->start_at, $reg->drive->end_at)) {
                $displayStatus = 'ongoing';
            } elseif ($reg->drive->status !== 'cancelled' && !$reg->verified_at && !$reg->penalty_processed_at && $displayStatus === 'registered' && now()->greaterThan($reg->drive->end_at)) {
                $displayStatus = 'awaiting_verification';
            }
            $reg->setAttribute('display_status', $displayStatus);
        }
        return view('volunteer.activities', compact('regs'));
    }
    public function join(CleanupDrive $drive)
    {
        $this->volunteer();
        $user = $this->authenticatedUser();
        abort_unless($user->barangay_id && $drive->barangay_id === $user->barangay_id, 404);

        DB::transaction(function () use ($drive, $user) {
            $lockedDrive = CleanupDrive::query()->lockForUpdate()->findOrFail($drive->id);
            abort_unless($lockedDrive->status === 'scheduled' && $lockedDrive->start_at->isFuture(), 422, 'Registration is closed.');

            $registration = CleanupDriveRegistration::query()
                ->where('cleanup_drive_id', $lockedDrive->id)
                ->where('volunteer_id', $user->id)
                ->lockForUpdate()
                ->first();

            if ($registration && $registration->attendance_status !== 'cancelled') {
                abort(422, 'You already have a registration for this cleanup drive.');
            }

            $activeCount = $lockedDrive->registrations()
                ->whereNotIn('attendance_status', ['cancelled'])
                ->count();
            abort_if($activeCount >= $lockedDrive->slots, 422, 'This drive is full.');

            if ($registration) {
                $oldEvidencePaths = $registration->evidence()->pluck('photo_path')->all();
                Storage::disk('public')->delete($oldEvidencePaths);
                $registration->evidence()->delete();
                $registration->update([
                    'registered_at' => now(),
                    'attendance_status' => 'registered',
                    'verification_method' => null,
                    'verified_at' => null,
                    'evidence_submitted_at' => null,
                    'penalty_processed_at' => null,
                    'points_awarded' => 0,
                    'points_deducted' => 0,
                ]);
            } else {
                CleanupDriveRegistration::create([
                    'cleanup_drive_id' => $lockedDrive->id,
                    'volunteer_id' => $user->id,
                    'registered_at' => now(),
                    'attendance_status' => 'registered',
                ]);
            }
        });

        return back()->with('notice', 'You are registered for this cleanup drive.');
    }
    public function cancel(CleanupDriveRegistration $registration)
    {
        $this->volunteer();
        abort_unless($registration->volunteer_id === Auth::id(), 404);
        abort_unless($registration->drive->start_at->isFuture() && $registration->attendance_status === 'registered', 422, 'This registration can no longer be cancelled.');
        $registration->update(['attendance_status' => 'cancelled']);
        return back()->with('notice', 'Registration cancelled.');
    }
    public function evidence(Request $r, CleanupDriveRegistration $registration)
    {
        $this->volunteer();
        abort_unless($registration->volunteer_id === Auth::id(), 404);
        $data = $r->validate(['photos' => 'required|array|min:1|max:5', 'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120']);

        DB::transaction(function () use ($registration, $data) {
            $locked = CleanupDriveRegistration::query()->lockForUpdate()->findOrFail($registration->id);
            abort_unless($locked->drive->status !== 'cancelled' && !in_array($locked->attendance_status, ['cancelled', 'no_show'], true), 422, 'This registration is no longer eligible for evidence submission.');
            abort_unless(now()->gte($locked->drive->start_at) && now()->lte($locked->drive->end_at->copy()->addDays(3)), 422, 'Evidence submission is only available during the event and within 3 days after it ends.');
            abort_if($locked->verified_at, 422, 'Attendance is already verified.');
            abort_if($locked->evidence_submitted_at || $locked->evidence()->exists() || $locked->verification_method, 422, 'You have already submitted your attendance proof. Only one verification method is allowed.');

            foreach ($data['photos'] as $photo) {
                $path = $photo->store('cleanup-evidence', 'public');
                $locked->evidence()->create(['photo_path' => $path, 'uploaded_at' => now()]);
            }
            $locked->update(['evidence_submitted_at' => now(), 'attendance_status' => 'awaiting_verification']);
        });

        return back()->with('notice', 'Photo evidence uploaded. It will be reviewed by the organizer.');
    }
    public function verifyCode(Request $r, CleanupDriveRegistration $registration)
    {
        $this->volunteer();
        abort_unless($registration->volunteer_id === Auth::id(), 404);
        $data = $r->validate(['code' => 'required|string|max:24']);

        $verified = DB::transaction(function () use ($registration, $data) {
            $locked = CleanupDriveRegistration::query()->lockForUpdate()->findOrFail($registration->id);
            abort_unless($locked->drive->status !== 'cancelled' && !in_array($locked->attendance_status, ['cancelled', 'no_show'], true), 422, 'This registration is no longer eligible for verification.');
            abort_unless(now()->gte($locked->drive->start_at) && now()->lte($locked->drive->end_at->copy()->addDays(3)), 422, 'Code verification is unavailable for this event.');
            abort_if($locked->verified_at, 422, 'Attendance is already verified.');
            abort_if($locked->evidence_submitted_at || $locked->evidence()->exists(), 422, 'Photo evidence has already been submitted. Only one verification method is allowed.');

            if (!Hash::check(strtoupper(trim($data['code'])), $locked->drive->attendance_code_hash)) {
                return false;
            }

            $this->verifyRegistration($locked, 'code');
            return true;
        });

        if (!$verified) {
            return back()->withErrors(['code' => 'That attendance code is incorrect.'])->withInput();
        }

        return back()->with('notice', 'Attendance verified successfully.');
    }
    public function history()
    {
        $this->volunteer();
        $regs = $this->authenticatedUser()
            ->cleanupRegistrations()
            ->with(['drive', 'evidence'])
            ->where('attendance_status', '!=', 'cancelled')
            ->whereHas('drive', fn ($q) => $q->where('start_at', '<=', now())->where('status', '!=', 'cancelled'))
            ->orderByDesc('updated_at')
            ->get();
        return view('volunteer.history', compact('regs'));
    }
    public function gallery(CleanupDrive $drive)
    {
        $this->volunteer();
        $user = $this->authenticatedUser();
        abort_unless($drive->barangay_id === $user->barangay_id && $drive->status !== 'cancelled', 404);
        abort_unless($drive->registrations()->where('volunteer_id', $user->id)->where('attendance_status', '!=', 'cancelled')->exists(), 404);
        $evidence = CleanupDriveEvidence::with('registration.volunteer')->whereHas('registration', fn ($q) => $q->where('cleanup_drive_id', $drive->id))->latest()->get();
        return view('volunteer.gallery', compact('drive', 'evidence'));
    }
    public function log()
    {
        $this->volunteer();
        $transactions = $this->authenticatedUser()->pointTransactions()->with('registration.drive')->latest()->get();
        $violations = VolunteerViolation::where('volunteer_id', Auth::id())->with('registration.drive')->latest()->get();
        $points = $this->authenticatedUser()->volunteer_points;
        $tier = $points >= 100 ? 'Community Champion' : ($points >= 60 ? 'Dedicated Volunteer' : ($points >= 30 ? 'Active Volunteer' : 'New Volunteer'));
        return view('volunteer.log', compact('transactions', 'violations', 'points', 'tier'));
    }
    public function idDocument(VolunteerApplication $application, string $side)
    {
        $this->leader();
        abort_unless(in_array($side, ['front', 'back']), 404);
        abort_unless($this->authenticatedUser()->role === 'admin' || ($this->authenticatedUser()->barangay_id && $application->user->barangay_id === $this->authenticatedUser()->barangay_id), 403);
        $path = $side === 'front' ? $application->id_front_path : $application->id_back_path;
        abort_if(!$path, 404);
        abort_unless(Storage::disk('local')->exists($path), 404);
        return Storage::disk('local')->download($path, basename($path));
    }
    public function reviewApplications()
    {
        $this->leader();
        $q = VolunteerApplication::with('user')->where('status', 'pending');
        if ($this->authenticatedUser()->role !== 'admin') {
            abort_unless($this->authenticatedUser()->barangay_id, 403, 'Leader account must be assigned to a barangay.');
            $q->whereHas('user', fn($u) => $u->where('barangay_id', $this->authenticatedUser()->barangay_id));
        }
        $apps = $q->latest()->get();
        return view('purok.volunteer-applications', compact('apps'));
    }
    public function review(Request $r, VolunteerApplication $application)
    {
        $this->leader();
        abort_unless($this->authenticatedUser()->role === 'admin' || ($this->authenticatedUser()->barangay_id && $application->user->barangay_id === $this->authenticatedUser()->barangay_id), 403);
        $data = $r->validate(['decision' => 'required|in:approve,reject', 'review_notes' => 'nullable|string|max:1000']);
        DB::transaction(function () use ($r, $application, $data) {
            $application->update(['status' => $data['decision'] === 'approve' ? 'approved' : 'rejected', 'reviewed_by' => Auth::id(), 'reviewed_at' => now(), 'review_notes' => $data['review_notes'] ?? null]);
            if ($data['decision'] === 'approve') {
                $application->user->update(['role' => 'volunteer', 'volunteer_points' => 0]);
            }
        });
        return back()->with('notice', 'Application ' . $data['decision'] . 'd.');
    }
    private function leader()
    {
        abort_unless(in_array($this->authenticatedUser()->role, ['leader', 'admin']), 403, 'Purok Leader access required.');
    }
    public static function verifyRegistration(CleanupDriveRegistration $reg, string $method)
    {
        if ($reg->verified_at) {
            return;
        }
        DB::transaction(function () use ($reg, $method) {
            $locked = CleanupDriveRegistration::lockForUpdate()->find($reg->id);
            if ($locked->verified_at) {
                return;
            }
            $reg = $locked;
            $reg->update(['attendance_status' => 'verified', 'verification_method' => $method, 'verified_at' => now(), 'points_awarded' => 10]);
            $user = User::find($reg->volunteer_id);
            $user->volunteer_points += 10;
            $user->save();
            VolunteerPointTransaction::create(['volunteer_id' => $user->id, 'registration_id' => $reg->id, 'points' => 10, 'type' => 'earned', 'description' => 'Completed cleanup drive: ' . $reg->drive->title, 'balance_after' => $user->volunteer_points]);
        });
    }
    public static function processNoShows()
    {
        CleanupDriveRegistration::whereNull('penalty_processed_at')
            ->whereNull('verified_at')
            ->whereNull('evidence_submitted_at')
            ->where('attendance_status', '!=', 'cancelled')
            ->whereHas('drive', fn($q) => $q->where('end_at', '<=', now()->subDays(3))->where('status', '!=', 'cancelled'))
            ->with('drive')
            ->chunkById(100, function ($regs) {
                foreach ($regs as $reg) {
                    DB::transaction(function () use ($reg) {
                        $reg = CleanupDriveRegistration::lockForUpdate()->find($reg->id);
                        if ($reg->penalty_processed_at || $reg->verified_at || $reg->evidence_submitted_at) {
                            return;
                        }
                        $user = User::lockForUpdate()->find($reg->volunteer_id);
                        $deduct = min(10, (int) $user->volunteer_points);
                        $user->volunteer_points -= $deduct;
                        if ($user->volunteer_points <= 0) {
                            $user->volunteer_points = 0;
                            $user->role = 'resident';
                        }
                        $user->save();
                        $reg->update(['attendance_status' => 'no_show', 'points_deducted' => $deduct, 'penalty_processed_at' => now()]);
                        VolunteerViolation::create(['volunteer_id' => $user->id, 'registration_id' => $reg->id, 'type' => 'missed_verification_deadline', 'description' => 'Did not submit photo or attendance code within 3 days after ' . $reg->drive->title, 'recorded_at' => now(), 'points_deducted' => $deduct]);
                        VolunteerPointTransaction::create(['volunteer_id' => $user->id, 'registration_id' => $reg->id, 'points' => -$deduct, 'type' => 'penalty', 'description' => 'Missed attendance verification: ' . $reg->drive->title, 'balance_after' => $user->volunteer_points]);
                    });
                }
            });
    }
}

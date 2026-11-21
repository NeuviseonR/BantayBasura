<?php

namespace App\Http\Controllers;

use App\Models\CleanupDrive;
use App\Models\CleanupDriveRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CleanupDriveLeaderController extends Controller
{
    private function check(): void
    {
        abort_unless(in_array(Auth::user()->role, ['leader', 'admin', 'purok_leader', 'official'], true), 403);
    }

    private function canManage(CleanupDrive $drive): bool
    {
        return Auth::user()->role === 'admin' || $drive->organizer_id === Auth::id();
    }

    private function calculatedStatus(CleanupDrive $drive): string
    {
        if ($drive->status === 'cancelled') return 'cancelled';
        if (now()->lt($drive->start_at)) return 'scheduled';
        if (now()->lt($drive->end_at)) return 'ongoing';
        return 'completed';
    }

    private function tierFor(int $points): string
    {
        return $points >= 100 ? 'Community Champion' : ($points >= 60 ? 'Dedicated Volunteer' : ($points >= 30 ? 'Active Volunteer' : 'New Volunteer'));
    }

    public function index()
    {
        $this->check();
        $query = CleanupDrive::with(['registrations.volunteer'])->withCount(['registrations' => fn ($q) => $q->whereNotIn('attendance_status', ['cancelled'])]);
        if (Auth::user()->role !== 'admin') $query->where('organizer_id', Auth::id());
        $drives = $query->orderByDesc('start_at')->get();
        foreach ($drives as $drive) {
            $drive->display_status = $this->calculatedStatus($drive);
            $drive->participant_list = $drive->registrations->map(function ($registration) {
                $volunteer = $registration->volunteer;
                $points = (int) ($volunteer->volunteer_points ?? 0);
                return [
                    'id' => $registration->id,
                    'name' => $volunteer->name ?? 'Deleted account',
                    'email' => $volunteer->email ?? '',
                    'tier' => $this->tierFor($points),
                    'points' => $points,
                    'registered_at' => optional($registration->registered_at)->format('Y-m-d\\TH:i') ?? '',
                    'attendance_status' => $registration->attendance_status,
                    'verified_at' => optional($registration->verified_at)->format('M d, Y g:i A'),
                    'evidence_count' => $registration->evidence->count(),
                ];
            })->values();
        }
        return view('purok.activities-db', compact('drives'));
    }

    public function store(Request $request)
    {
        $this->check();
        $data = $request->validate([
            'title' => 'required|string|max:180', 'description' => 'required|string|max:5000',
            'location' => 'required|string|max:255', 'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180', 'start_at' => 'required|date|after:now',
            'end_at' => 'required|date|after:start_at', 'slots' => 'required|integer|min:1|max:1000',
        ]);
        abort_unless(Auth::user()->barangay_id || Auth::user()->role === 'admin', 422, 'Assign a barangay to this leader account before creating a drive.');
        $code = Str::upper(Str::random(8));
        $data['organizer_id'] = Auth::id();
        $data['barangay_id'] = Auth::user()->barangay_id;
        $data['attendance_code_hash'] = Hash::make($code);
        $data['status'] = 'scheduled';
        CleanupDrive::create($data);
        return redirect()->route('purok-activities-db')->with('generated_code', $code)->with('notice', 'Cleanup drive created. Keep the attendance code safe; it is shown only once.');
    }

    public function update(Request $request, CleanupDrive $drive)
    {
        $this->check();
        abort_unless($this->canManage($drive), 403);
        abort_unless($this->calculatedStatus($drive) === 'scheduled', 422, 'A drive can only be edited before it starts.');
        $data = $request->validate([
            'title' => 'required|string|max:180', 'description' => 'required|string|max:5000',
            'location' => 'required|string|max:255', 'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180', 'start_at' => 'required|date|after:now',
            'end_at' => 'required|date|after:start_at', 'slots' => 'required|integer|min:1|max:1000',
        ]);
        $drive->update($data);
        return back()->with('notice', 'Cleanup drive updated. Its status is determined automatically from its schedule.');
    }

    public function cancel(CleanupDrive $drive)
    {
        $this->check();
        abort_unless($this->canManage($drive), 403);
        abort_if($this->calculatedStatus($drive) === 'completed', 422, 'Completed drives cannot be cancelled.');
        $drive->update(['status' => 'cancelled']);
        return back()->with('notice', 'Cleanup drive cancelled.');
    }

    public function regenerate(CleanupDrive $drive)
    {
        $this->check();
        abort_unless($this->canManage($drive), 403);
        abort_unless($this->calculatedStatus($drive) === 'scheduled', 422, 'Attendance codes can only be changed before the drive starts.');
        $code = Str::upper(Str::random(8));
        $drive->update(['attendance_code_hash' => Hash::make($code)]);
        return back()->with('generated_code', $code)->with('code_drive_id', $drive->id)->with('notice', 'Attendance code changed. The previous code is no longer valid.');
    }

    public function updateParticipant(Request $request, CleanupDriveRegistration $registration)
    {
        $this->check();
        $registration->load('drive');
        abort_unless($this->canManage($registration->drive), 403);
        abort_unless($this->calculatedStatus($registration->drive) === 'scheduled', 422, 'Participants cannot be edited once the drive starts.');
        $data = $request->validate(['attendance_status' => 'required|in:registered,cancelled', 'registered_at' => 'required|date|before_or_equal:now']);
        $registration->update($data);
        return back()->with('notice', 'Participant registration updated.');
    }

    public function verifyPhoto(CleanupDriveRegistration $registration)
    {
        $this->check();
        $registration->load('drive');
        abort_unless($this->canManage($registration->drive), 403);
        abort_if(!$registration->evidence()->exists(), 422, 'No photo evidence submitted.');
        abort_unless(now()->greaterThanOrEqualTo($registration->drive->end_at), 422, 'Photo evidence can only be approved after the drive ends.');
        VolunteerController::verifyRegistration($registration, 'photo');
        return back()->with('notice', 'Photo evidence verified; participation points awarded.');
    }

    public function show(CleanupDrive $drive)
    {
        $this->check();
        abort_unless($this->canManage($drive), 403);
        return redirect()->route('purok-activities-db')->with('notice', 'Participant management is now available from the Cleanup Activities list.');
    }
}

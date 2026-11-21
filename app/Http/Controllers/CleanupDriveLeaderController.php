<?php
namespace App\Http\Controllers;
use App\Models\CleanupDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Support\Str;
class CleanupDriveLeaderController extends Controller

{
    private function check()
    {
        abort_unless(in_array(Auth::user()->role, ['official', 'admin', 'purok_leader']), 403);
    }
    public function index()
    {
        $this->check();
        $q = CleanupDrive::withCount(['registrations' => fn($q) => $q->whereNotIn('attendance_status', ['cancelled'])])->with('registrations.volunteer');
        if (Auth::user()->role !== 'admin') {
            $q->where('organizer_id', Auth::id());
        }
        $drives = $q->orderByDesc('start_at')->get();
        return view('purok.activities-db', compact('drives'));
    }
    public function store(Request $r)
    {
        $this->check();
        $d = $r->validate(['title' => 'required|string|max:180', 'description' => 'required|string', 'location' => 'required|string|max:255', 'start_at' => 'required|date|after:now', 'end_at' => 'required|date|after:start_at', 'slots' => 'required|integer|min:1|max:1000']);
        $code = Str::upper(Str::random(8));
        $d['organizer_id'] = Auth::id();
        $d['barangay_id'] = Auth::user()->barangay_id;
        $d['attendance_code_hash'] = Hash::make($code);
        $drive = CleanupDrive::create($d);
        return redirect()->route('purok-activities-db')->with('generated_code', $code)->with('notice', 'Drive created. Save the attendance code; it will only be shown now.');
    }
    public function update(Request $r, CleanupDrive $drive)
    {
        $this->check();
        abort_unless(Auth::user()->role === 'admin' || $drive->organizer_id === Auth::id(), 403);
        $d = $r->validate(['title' => 'required|string|max:180', 'description' => 'required|string', 'location' => 'required|string|max:255', 'start_at' => 'required|date', 'end_at' => 'required|date|after:start_at', 'slots' => 'required|integer|min:1|max:1000', 'status' => 'required|in:scheduled,ongoing,completed,cancelled']);
        $drive->update($d);
        return back()->with('notice', 'Cleanup drive updated.');
    }
    public function regenerate(CleanupDrive $drive)
    {
        $this->check();
        abort_unless(Auth::user()->role === 'admin' || $drive->organizer_id === Auth::id(), 403);
        $code = Str::upper(Str::random(8));
        $drive->update(['attendance_code_hash' => Hash::make($code)]);
        return back()->with('generated_code', $code)->with('code_drive_id', $drive->id)->with('notice', 'Attendance code changed. The old code is no longer valid.');
    }
    public function verifyPhoto(\App\Models\CleanupDriveRegistration $registration)
    {
        $this->check();
        abort_unless(Auth::user()->role === 'admin' || $registration->drive->organizer_id === Auth::id(), 403);
        abort_if(!$registration->evidence()->exists(), 422, 'No photo evidence submitted.');
        abort_unless(now()->greaterThanOrEqualTo($registration->drive->end_at), 422, 'Photo evidence can only be approved after the drive ends.');
        \App\Http\Controllers\VolunteerController::verifyRegistration($registration, 'photo');
        return back()->with('notice', 'Photo evidence verified; participation points awarded.');
    }
    public function show(CleanupDrive $drive)
    {
        $this->check();
        abort_unless(Auth::user()->role === 'admin' || $drive->organizer_id === Auth::id(), 403);
        $drive->load('registrations.volunteer', 'registrations.evidence');
        return view('purok.drive-details', compact('drive'));
    }
}

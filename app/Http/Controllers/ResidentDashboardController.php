<?php

namespace App\Http\Controllers;

use App\Models\CleanupDrive;
use Illuminate\Http\Request;

class ResidentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $reports = $user->wasteReports();

        $reportStats = [
            'total' => (clone $reports)->count(),
            'pending' => (clone $reports)->whereIn('status', ['pending', 'verified'])->count(),
            'resolved' => (clone $reports)->where('status', 'resolved')->count(),
        ];

        $recentReports = (clone $reports)->latest()->limit(3)->get();

        $driveQuery = CleanupDrive::query()
            ->with('organizer')
            ->withCount(['registrations' => fn ($query) => $query->whereNotIn('attendance_status', ['cancelled'])])
            ->where('start_at', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_at')
            ->limit(3);

        if ($user->barangay_id) {
            $driveQuery->where('barangay_id', $user->barangay_id);
        } else {
            $driveQuery->whereRaw('1 = 0');
        }

        $upcomingDrives = $driveQuery->get();

        return view('user-dash', compact('reportStats', 'recentReports', 'upcomingDrives'));
    }
}

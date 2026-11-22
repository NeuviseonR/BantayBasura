<?php

namespace App\Http\Controllers;

use App\Models\CleanupDrive;
use Illuminate\Http\Request;

class ResidentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // All report statistics and recent reports are scoped to the signed-in resident.
        $reports = $user->wasteReports();

        $reportStats = [
            'total' => (clone $reports)->count(),
            'pending' => (clone $reports)->where('status', 'pending')->count(),
            'resolved' => (clone $reports)->where('status', 'resolved')->count(),
        ];

        $recentReports = (clone $reports)
            ->latest()
            ->limit(3)
            ->get();

        // Match the barangay visibility used by the resident cleanup-drives page.
        $driveQuery = CleanupDrive::query()
            ->where('start_at', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_at')
            ->limit(3);

        if ($user->barangay_id) {
            $driveQuery->where('barangay_id', $user->barangay_id);
        }

        $upcomingDrives = $driveQuery->get();

        return view('user-dash', compact('reportStats', 'recentReports', 'upcomingDrives'));
    }
}

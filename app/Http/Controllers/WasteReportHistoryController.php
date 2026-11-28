<?php

namespace App\Http\Controllers;

class WasteReportHistoryController extends Controller
{
    public function index()
    {
        $user = request()->user();

        $counts = $user->wasteReports()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN LOWER(status) IN ('pending', 'verified') THEN 1 ELSE 0 END) as pending")
            ->selectRaw("SUM(CASE WHEN LOWER(status) = 'resolved' THEN 1 ELSE 0 END) as resolved")
            ->first();

        // Place names are resolved and saved when a report is submitted.
        // Do not call Nominatim while rendering this page; that would repeat
        // requests whenever the resident refreshes or revisits report history.
        $reports = $user->wasteReports()->latest()->get();

        return view('user-my-reports', [
            'reports' => $reports,
            'totalReports' => (int) ($counts->total ?? 0),
            'pendingReports' => (int) ($counts->pending ?? 0),
            'resolvedReports' => (int) ($counts->resolved ?? 0),
        ]);
    }
}

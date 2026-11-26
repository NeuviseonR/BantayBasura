@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'Manager Action History';
        $pageDescription = 'Review a traceable record of important actions performed in this workspace.';
        $headingAction = 'Recent manager actions';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Date & time', 'Actor', 'Action', 'Affected record', 'Result'];
        $rows = [['Nov 24 · 9:42 AM', 'A. Reyes', 'Approved account', 'USR-00812 · M. Dela Cruz', '<span class="staff-status success">Success</span>'],
['Nov 24 · 9:18 AM', 'A. Reyes', 'Forwarded ticket', 'BB-2026-0142', '<span class="staff-status success">Success</span>'],
['Nov 24 · 8:55 AM', 'A. Reyes', 'Approved volunteer role', 'APP-00231 · K. Mendoza', '<span class="staff-status success">Success</span>'],
['Nov 23 · 4:12 PM', 'System', 'Recorded point appeal', 'APL-00028 · R. Flores', '<span class="staff-status warning">Pending</span>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-clipboard-list', 'infoTitle' => 'Review guidance', 'infoText' => 'This page contains sample records for preview. In the integrated system, access and changes will be governed by role permissions and audit history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'Manager Action History', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

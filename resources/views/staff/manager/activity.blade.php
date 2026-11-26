@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'Suspicious Account Monitoring';
        $pageDescription = 'Review signals that may indicate duplicate accounts, abuse, or suspicious registrations.';
        $headingAction = 'Flagged account activity';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Account', 'Signal', 'Detected', 'Risk level', 'Review state', 'Action'];
        $rows = [['USR-00814 · Noel Garcia', 'Possible duplicate identity', 'Nov 24, 2026', '<span class="staff-status danger">High</span>', '<span class="staff-status warning">Needs review</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Inspect <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00602 · Ben Salazar', 'Repeated incomplete registrations', 'Nov 23, 2026', '<span class="staff-status info">Medium</span>', '<span class="staff-status warning">Under review</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Inspect <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00491 · J. Cruz', 'Unusual request volume', 'Nov 22, 2026', '<span class="staff-status neutral">Low</span>', '<span class="staff-status info">Monitoring</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Inspect <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-shield-halved', 'infoTitle' => 'Review guidance', 'infoText' => 'This page contains sample records for preview. In the integrated system, access and changes will be governed by role permissions and audit history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'Suspicious Account Monitoring', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

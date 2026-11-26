@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'Account Suspensions & Bans';
        $pageDescription = 'Review enforcement requests and approve serious account restrictions with documented reasons.';
        $headingAction = 'Enforcement requests';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Account', 'Requested action', 'Reason', 'Requested by', 'Request status', 'Action'];
        $rows = [['USR-00814 · Noel Garcia', 'Permanent ban', 'Verified duplicate identity evidence', 'A. Reyes · Manager', '<span class="staff-status warning">Awaiting approval</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00602 · Ben Salazar', 'Temporary suspension · 7 days', 'Repeated fraudulent registrations', 'C. Mendoza · Manager', '<span class="staff-status warning">Awaiting approval</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00491 · J. Cruz', 'Additional verification', 'Unusual activity pattern', 'D. Santos · Manager', '<span class="staff-status warning">In review</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-ban', 'infoTitle' => 'Review guidance', 'infoText' => 'Check the submitted information carefully. Record a clear reason for every decision and keep the original history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'Account Suspensions & Bans', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

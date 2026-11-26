@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'Volunteer Point Disputes & Appeals';
        $pageDescription = 'Review disputed deductions and preserve a traceable point transaction history.';
        $headingAction = 'Appeal queue';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Appeal ID', 'Volunteer', 'Deduction', 'Appeal reason', 'Submitted', 'Status', 'Action'];
        $rows = [['APL-00028', 'Ramon Flores', '-20 points', 'Missed attendance verification', 'Nov 24, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['APL-00026', 'Kyla Mendoza', '-10 points', 'Evidence uploaded late', 'Nov 23, 2026', '<span class="staff-status warning">Under review</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['APL-00021', 'Joel Ramirez', '-15 points', 'Incorrect drive attendance', 'Nov 20, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-scale-balanced', 'infoTitle' => 'Review guidance', 'infoText' => 'Check the submitted information carefully. Record a clear reason for every decision and keep the original history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'Volunteer Point Disputes & Appeals', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

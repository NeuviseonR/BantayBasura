@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'New Account Verification';
        $pageDescription = 'Review new registrations and verify account information before activation.';
        $headingAction = 'Review queue';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Account ID', 'Applicant', 'Barangay', 'Registered', 'Verification status', 'Action'];
        $rows = [['USR-00814', 'Noel Garcia', 'Tungkil', 'Nov 24, 2026', '<span class="staff-status warning">Needs review</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00812', 'Maria Dela Cruz', 'Poblacion', 'Nov 24, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00809', 'Paolo Reyes', 'Calajo-an', 'Nov 23, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00803', 'Liza Tan', 'Poblacion', 'Nov 22, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-user-check', 'infoTitle' => 'Review guidance', 'infoText' => 'Check the submitted information carefully. Record a clear reason for every decision and keep the original history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'New Account Verification', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

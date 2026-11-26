@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'Volunteer & Purok Leader Applications';
        $pageDescription = 'Review role requests, supporting details, and barangay assignments.';
        $headingAction = 'Review applications';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Application ID', 'Applicant', 'Requested role', 'Barangay', 'Submitted', 'Status', 'Action'];
        $rows = [['APP-00231', 'Kyla Mendoza', 'Volunteer', 'Poblacion', 'Nov 24, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['APP-00230', 'Ana Robles', 'Purok Leader', 'Calajo-an', 'Nov 23, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['APP-00228', 'Ramon Flores', 'Volunteer', 'Tungkil', 'Nov 22, 2026', '<span class="staff-status warning">Under review</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['APP-00225', 'Joel Ramirez', 'Volunteer', 'Poblacion', 'Nov 21, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-id-card', 'infoTitle' => 'Review guidance', 'infoText' => 'Check the submitted information carefully. Record a clear reason for every decision and keep the original history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'Volunteer & Purok Leader Applications', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

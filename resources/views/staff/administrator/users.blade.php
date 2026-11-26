@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'All Registered Accounts';
        $pageDescription = 'Search accounts across the platform and review their role and current standing.';
        $headingAction = null;
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Account ID', 'User', 'Role', 'Barangay', 'Joined', 'Status', 'Action'];
        $rows = [['USR-00812', 'Maria Dela Cruz', 'Resident', 'Poblacion', 'Oct 12, 2026', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00790', 'Rosa Dizon', 'Volunteer', 'Poblacion', 'Sep 08, 2026', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00741', 'Joel Ramirez', 'Resident', 'Tungkil', 'Aug 21, 2026', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00814', 'Noel Garcia', 'Resident', 'Tungkil', 'Nov 24, 2026', '<span class="staff-status warning">Pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['USR-00620', 'Ana Robles', 'Purok Leader', 'Calajo-an', 'Jun 02, 2026', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-users', 'infoTitle' => 'Review guidance', 'infoText' => 'Check the submitted information carefully. Record a clear reason for every decision and keep the original history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'All Registered Accounts', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

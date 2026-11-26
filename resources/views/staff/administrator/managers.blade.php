@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'Manager Account & Permission Management';
        $pageDescription = 'Manage staff accounts and assign only the permissions required for each role.';
        $headingAction = 'Add manager';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Manager ID', 'Manager', 'Access scope', 'Permission set', 'Last active', 'Status', 'Action'];
        $rows = [['MGR-001', 'Alyssa Reyes', 'All barangays', 'Standard manager', 'Today · 9:42 AM', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Manage <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['MGR-002', 'Carlo Mendoza', 'All barangays', 'Standard manager', 'Today · 8:10 AM', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Manage <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['MGR-003', 'Dina Santos', 'Ticket reviewer', 'Ticket reviewer', 'Nov 22 · 2:18 PM', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Manage <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['MGR-004', 'Marco Lim', 'All barangays', 'Standard manager', 'Nov 18 · 11:02 AM', '<span class="staff-status danger">Suspended</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Manage <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-user-shield', 'infoTitle' => 'Review guidance', 'infoText' => 'Check the submitted information carefully. Record a clear reason for every decision and keep the original history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'Manager Account & Permission Management', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

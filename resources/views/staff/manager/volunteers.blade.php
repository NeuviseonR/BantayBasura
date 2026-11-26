@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'Volunteer Records & Points';
        $pageDescription = 'Review volunteer standing, participation history, and point balances.';
        $headingAction = 'Volunteer directory';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Volunteer ID', 'Volunteer', 'Tier', 'Points', 'Participation', 'Status', 'Action'];
        $rows = [['VOL-00108', 'Rosa Dizon', 'Gold', '420', '18 drives', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View record <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['VOL-00107', 'Kyla Mendoza', 'Silver', '285', '12 drives', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View record <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['VOL-00102', 'Ramon Flores', 'Bronze', '160', '7 drives', '<span class="staff-status success">Active</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View record <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'],
['VOL-00098', 'Joel Ramirez', 'Bronze', '95', '4 drives', 'On hold', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">View record <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-people-group', 'infoTitle' => 'Review guidance', 'infoText' => 'Check the submitted information carefully. Record a clear reason for every decision and keep the original history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'Volunteer Records & Points', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

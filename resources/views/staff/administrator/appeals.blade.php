@extends('layouts.staff')
@section('content')
    @php
        $pageTitle = 'Account Ban Appeals';
        $pageDescription = 'Independently review appeals against account bans and document the final decision.';
        $headingAction = 'Ban appeal queue';
        $headingActionIcon = 'fa-plus';
        $headingActionMessage = 'This action is a static prototype and is not saved.';
        $headers = ['Appeal ID', 'Account', 'Original decision', 'Submitted', 'Review status', 'Action'];
        $rows = [['APL-00031', 'USR-00722 · J. Santos', 'Duplicate identity · Nov 18', 'Nov 23, 2026', '<span class="staff-status warning">Independent review pending</span>', '<button type="button" class="staff-row-action" data-demo-toast="Record details will be available after database integration.">Review <i class="fa-solid fa-arrow-up-right-from-square"></i></button>']];
    @endphp
    @include('staff.partials.page-heading')
    @include('staff.partials.info-panel', ['infoIcon' => 'fa-scale-balanced', 'infoTitle' => 'Review guidance', 'infoText' => 'Check the submitted information carefully. Record a clear reason for every decision and keep the original history.'])
    @include('staff.partials.data-table', ['tableTitle' => 'Account Ban Appeals', 'tableDescription' => 'Sample records for the dashboard prototype', 'rows' => $rows, 'headers' => $headers])
@endsection

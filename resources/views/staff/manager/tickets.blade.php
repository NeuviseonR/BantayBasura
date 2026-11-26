@extends('layouts.staff')
@section('content')
@php
$pageTitle = 'Resident & Volunteer Support Tickets';
$pageDescription = 'Screen incoming concerns, respond to account and platform questions, or forward local matters to the correct Purok Leader.';
$rows = [
['BB-2026-0147','Joel Ramirez','Cleanup drive inquiry','Manager','In progress','Open'],
['BB-2026-0146','Kyla Mendoza','Volunteer registration','Manager','Waiting for resident','Open'],
['BB-2026-0142','Ramon Flores','Waste report concern','Purok 2 Leader','Forwarded','Open'],
['BB-2026-0139','Liza Tan','Account assistance','Manager','Resolved','Open'],
['BB-2026-0138','Noel Garcia','Unrelated personal request','Closed','Out of scope','Open'],
];
@endphp
@include('staff.partials.page-heading', ['headingAction' => 'Export queue', 'headingActionIcon' => 'fa-download', 'headingActionMessage' => 'Ticket export will be connected during database integration.'])
<div class="staff-ticket-status-row">
    <div><span class="staff-ticket-dot new"></span><small>Open / screening</small><strong>5</strong></div>
    <div><span class="staff-ticket-dot progress"></span><small>In progress</small><strong>7</strong></div>
    <div><span class="staff-ticket-dot waiting"></span><small>Waiting for resident</small><strong>4</strong></div>
    <div><span class="staff-ticket-dot forwarded"></span><small>Forwarded</small><strong>2</strong></div>
</div>
@include('staff.partials.data-table', ['tableTitle' => 'Ticket queue', 'tableDescription' => 'Each concern stays within its own ticket and conversation history.', 'headers' => ['Ticket ID','Submitted by','Category','Assigned to','Status','Action'], 'rows' => array_map(fn($row) => array_map(function($v) { if ($v === 'Open') return '<button type="button" class="staff-row-action" data-demo-toast="Ticket details are sample-only.">Open ticket <i class="fa-solid fa-arrow-up-right-from-square"></i></button>'; if (in_array($v, ['In progress','Forwarded','Waiting for resident'])) return '<span class="staff-status info">'.$v.'</span>'; if ($v === 'Resolved') return '<span class="staff-status success">Resolved</span>'; if ($v === 'Out of scope') return '<span class="staff-status danger">Out of scope</span>'; return $v; }, $row), $rows)])
@include('staff.partials.info-panel', ['infoIcon' => 'fa-route', 'infoTitle' => 'Ticket routing', 'infoText' => 'Managers screen all new tickets. Account and platform concerns stay with the manager; valid local concerns are forwarded to the appropriate Purok Leader. Waste reports remain separate from support tickets.'])
@endsection
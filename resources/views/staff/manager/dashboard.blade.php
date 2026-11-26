@extends('layouts.staff')
@section('content')
@php
$pageTitle = 'Manager Dashboard';
$pageDescription = 'A clear view of account reviews, role applications, volunteer concerns, and community support tickets.';
$stats = [
['label'=>'Accounts to verify','value'=>'12','note'=>'4 received today','icon'=>'fa-user-clock','tone'=>'orange'],
['label'=>'Role applications','value'=>'6','note'=>'3 volunteer · 3 leader','icon'=>'fa-id-card','tone'=>'green'],
['label'=>'Open support tickets','value'=>'18','note'=>'5 awaiting first review','icon'=>'fa-ticket','tone'=>'blue'],
['label'=>'Pending point appeals','value'=>'3','note'=>'Decisions required','icon'=>'fa-scale-balanced','tone'=>'red'],
];
@endphp
@include('staff.partials.page-heading')
<div class="staff-welcome-strip"><span class="staff-welcome-icon"><i class="fa-solid fa-shield-heart"></i></span><div><strong>Welcome to your workspace</strong><p>Start with the items waiting for review. Keep decisions documented and route local concerns to the correct Purok Leader.</p></div><span class="staff-date-label"><i class="fa-regular fa-calendar"></i> {{ now()->format('M d, Y') }}</span></div>
@include('staff.partials.stat-grid')
<div class="staff-dashboard-grid">
<section class="staff-panel"><div class="staff-panel-heading"><div><h2>Needs your attention</h2><p>Sample tasks waiting for a decision</p></div><span class="staff-count-pill">26 items</span></div>
<div class="staff-task-list">
<a href="{{ route('account-manager.preview',['section'=>'accounts']) }}"><span class="staff-task-icon orange"><i class="fa-solid fa-user-check"></i></span><span><strong>New account registrations</strong><small>12 accounts are awaiting verification</small></span><b>12 <i class="fa-solid fa-chevron-right"></i></b></a>
<a href="{{ route('account-manager.preview',['section'=>'applications']) }}"><span class="staff-task-icon green"><i class="fa-solid fa-id-card"></i></span><span><strong>Role applications</strong><small>Volunteer and Purok Leader requests</small></span><b>6 <i class="fa-solid fa-chevron-right"></i></b></a>
<a href="{{ route('account-manager.preview',['section'=>'points']) }}"><span class="staff-task-icon red"><i class="fa-solid fa-scale-balanced"></i></span><span><strong>Volunteer point appeals</strong><small>Decisions requested by volunteers</small></span><b>3 <i class="fa-solid fa-chevron-right"></i></b></a>
<a href="{{ route('account-manager.preview',['section'=>'tickets']) }}"><span class="staff-task-icon blue"><i class="fa-solid fa-ticket"></i></span><span><strong>Unscreened tickets</strong><small>New concerns need scope validation</small></span><b>5 <i class="fa-solid fa-chevron-right"></i></b></a>
</div></section>
<section class="staff-panel"><div class="staff-panel-heading"><div><h2>Ticket workload</h2><p>Current sample queue</p></div><a class="staff-text-link" href="{{ route('account-manager.preview',['section'=>'tickets']) }}">View queue</a></div>
<div class="staff-ticket-summary"><div><span class="staff-ticket-dot new"></span><span>New / screening</span><strong>5</strong></div><div><span class="staff-ticket-dot progress"></span><span>In progress</span><strong>7</strong></div><div><span class="staff-ticket-dot waiting"></span><span>Waiting for resident</span><strong>4</strong></div><div><span class="staff-ticket-dot forwarded"></span><span>Forwarded to Purok Leader</span><strong>2</strong></div></div>
<div class="staff-note"><i class="fa-solid fa-circle-info"></i><span>Tickets outside Bantay Basura's scope should be closed with a clear reason. Valid local concerns may be forwarded to the relevant Purok Leader.</span></div></section>
</div>
@include('staff.partials.data-table', ['tableTitle'=>'Recent manager activity','tableDescription'=>'Latest sample actions in this workspace','headers'=>['Activity','Account','Date','Status'],'rows'=>[['Account approved','Maria Dela Cruz','Today · 9:42 AM','<span class="staff-status success">Approved</span>'],['Ticket forwarded · BB-2026-0142','Joel Ramirez','Today · 9:18 AM','<span class="staff-status info">Forwarded</span>'],['Volunteer application reviewed','Kyla Mendoza','Today · 8:55 AM','<span class="staff-status success">Approved</span>'],['Point appeal submitted','Ramon Flores','Yesterday · 4:12 PM','<span class="staff-status warning">Pending</span>']]])
@endsection
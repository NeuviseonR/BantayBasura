@extends('layouts.staff')
@section('content')
@php
$pageTitle = 'Administrator Dashboard';
$pageDescription = 'Oversee staff access, account enforcement, platform activity, and system integrity.';
$stats = [
['label'=>'Registered accounts','value'=>'1,284','note'=>'Across all barangays','icon'=>'fa-users','tone'=>'green'],
['label'=>'Active managers','value'=>'8','note'=>'2 administrator accounts','icon'=>'fa-user-shield','tone'=>'blue'],
['label'=>'Enforcement reviews','value'=>'3','note'=>'2 awaiting approval','icon'=>'fa-shield-halved','tone'=>'orange'],
['label'=>'Ban appeals','value'=>'1','note'=>'Independent review pending','icon'=>'fa-scale-balanced','tone'=>'red'],
];
@endphp
@include('staff.partials.page-heading')
<div class="staff-welcome-strip"><span class="staff-welcome-icon"><i class="fa-solid fa-building-shield"></i></span><div><strong>Platform oversight</strong><p>Review staff access, exceptional enforcement requests, and system-wide accountability from one workspace.</p></div><span class="staff-date-label"><i class="fa-regular fa-calendar"></i> {{ now()->format('M d, Y') }}</span></div>
@include('staff.partials.stat-grid')
<div class="staff-dashboard-grid">
<section class="staff-panel"><div class="staff-panel-heading"><div><h2>Administrator review queue</h2><p>Items requiring elevated access</p></div><span class="staff-count-pill">3 reviews</span></div>
<div class="staff-task-list">
<a href="{{ route('administrator.preview',['section'=>'enforcement']) }}"><span class="staff-task-icon red"><i class="fa-solid fa-ban"></i></span><span><strong>Permanent ban requests</strong><small>Review manager recommendations before approval</small></span><b>1 <i class="fa-solid fa-chevron-right"></i></b></a>
<a href="{{ route('administrator.preview',['section'=>'enforcement']) }}"><span class="staff-task-icon orange"><i class="fa-solid fa-user-clock"></i></span><span><strong>Temporary restriction requests</strong><small>Check evidence and enforcement duration</small></span><b>1 <i class="fa-solid fa-chevron-right"></i></b></a>
<a href="{{ route('administrator.preview',['section'=>'appeals']) }}"><span class="staff-task-icon blue"><i class="fa-solid fa-scale-balanced"></i></span><span><strong>Ban appeal</strong><small>Independent review requested</small></span><b>1 <i class="fa-solid fa-chevron-right"></i></b></a>
</div></section>
<section class="staff-panel"><div class="staff-panel-heading"><div><h2>Governance reminders</h2><p>Controls to keep the platform accountable</p></div></div>
<div class="staff-reminder-list"><div><i class="fa-solid fa-user-lock"></i><span><strong>Manager permissions</strong><small>Grant only the access needed for assigned duties.</small></span></div><div><i class="fa-solid fa-file-shield"></i><span><strong>Audit history</strong><small>Keep sensitive actions traceable and uneditable.</small></span></div><div><i class="fa-solid fa-id-card"></i><span><strong>Private documents</strong><small>Restrict access to uploaded identification records.</small></span></div></div>
</section></div>
@include('staff.partials.data-table', ['tableTitle'=>'Recent system activity','tableDescription'=>'Latest sample actions across the platform','headers'=>['Date & time','Actor','Action','Affected record','Result'],'rows'=>[['Nov 24 · 9:42 AM','A. Reyes · Manager','Approved account','USR-00812 · M. Dela Cruz','<span class="staff-status success">Success</span>'],['Nov 24 · 9:18 AM','A. Reyes · Manager','Forwarded ticket','BB-2026-0142','<span class="staff-status success">Success</span>'],['Nov 24 · 8:55 AM','A. Reyes · Manager','Approved volunteer role','APP-00231 · K. Mendoza','<span class="staff-status success">Success</span>'],['Nov 23 · 4:12 PM','System','Recorded point appeal','APL-00028 · R. Flores','<span class="staff-status warning">Pending</span>']]])
@endsection
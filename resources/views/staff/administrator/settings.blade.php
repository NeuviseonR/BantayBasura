@extends('layouts.staff')
@section('content')
@php
$pageTitle = 'System Configuration & Security';
$pageDescription = 'Review platform-wide access, retention, verification, and moderation policies.';
$policies = [
['fa-user-check','Account verification','Email + risk-based manual review','Reduce bots and duplicate registrations'],
['fa-ban','Permanent bans','Administrator approval required','Prevent unchecked irreversible action'],
['fa-scale-balanced','Ban appeals','One formal appeal + exceptional evidence review','Provide a fair review process'],
['fa-network-wired','IP restrictions','Exceptional, time-limited control','Avoid blocking shared networks'],
['fa-clock-rotate-left','Audit retention','Retain according to approved policy','Maintain accountability'],
['fa-id-card','ID document access','Restricted reviewer access','Protect sensitive information'],
];
@endphp
@include('staff.partials.page-heading')
<div class="staff-policy-grid">
@foreach($policies as $policy)
<article class="staff-policy-card"><div class="staff-policy-top"><span class="staff-policy-icon"><i class="fa-solid {{ $policy[0] }}"></i></span><span class="staff-policy-label">CURRENT POLICY</span></div><h2>{{ $policy[1] }}</h2><p>{{ $policy[3] }}</p><div class="staff-policy-current"><small>Prototype setting</small><strong>{{ $policy[2] }}</strong></div><button type="button" class="staff-button staff-button-outline staff-button-small" data-demo-toast="System settings are display-only in this prototype.">Review setting</button></article>
@endforeach
</div>
@include('staff.partials.info-panel', ['infoIcon'=>'fa-lock','infoTitle'=>'Security and permission reminder','infoText'=>'Sensitive settings should be restricted to authorized Administrators. Changes to security policies must be recorded in the system-wide audit log.'])
@endsection
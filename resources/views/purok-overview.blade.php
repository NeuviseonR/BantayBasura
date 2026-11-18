@extends('layouts.purok')

@section('title', 'Purok Overview')

@section('content')
@php
  // Sample leader. Later, build this from the logged-in user.
  $leader = $leader ?? ['name' => 'Ana Santos', 'initials' => 'AS', 'purok' => 'Purok 3'];
  // SAMPLE DATA for the view-only prototype. Keep the same keys when you pass real data.
  $puroks = ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5', 'Purok 6', 'Purok 7'];

  $summary = [
    ['label' => 'Residents',       'value' => '742',  'icon' => 'fa-solid fa-users',           'tone' => 'primary', 'note' => '186 households'],
    ['label' => 'Reports this month','value' => '24', 'icon' => 'fa-regular fa-file-lines',     'tone' => 'warning', 'note' => '8 still pending'],
    ['label' => 'Resolution rate', 'value' => '67%',  'icon' => 'fa-regular fa-circle-check',   'tone' => 'primary', 'note' => 'up from 52%'],
    ['label' => 'Active volunteers','value' => '12',  'icon' => 'fa-solid fa-hand-holding-heart','tone' => 'info',   'note' => '5 on tasks'],
  ];

  $zones = [
    ['name' => 'Zone 1', 'residents' => 168, 'reports' => 5, 'pending' => 1, 'crew' => 'Team Alpha',   'days' => 'Mon, Thu'],
    ['name' => 'Zone 2', 'residents' => 174, 'reports' => 6, 'pending' => 2, 'crew' => 'Team Bravo',   'days' => 'Mon, Thu'],
    ['name' => 'Zone 3', 'residents' => 151, 'reports' => 4, 'pending' => 2, 'crew' => 'Team Charlie', 'days' => 'Tue, Fri'],
    ['name' => 'Zone 4', 'residents' => 139, 'reports' => 7, 'pending' => 3, 'crew' => 'Team Delta',   'days' => 'Tue, Fri'],
    ['name' => 'Zone 5', 'residents' => 110, 'reports' => 2, 'pending' => 0, 'crew' => 'Team Echo',    'days' => 'Wed, Sat'],
  ];

  $categoryBars = [
    ['label' => 'Illegal dumping',   'value' => 9], ['label' => 'Overflowing bin', 'value' => 5], ['label' => 'Plastic waste', 'value' => 4],
    ['label' => 'Missed collection', 'value' => 3], ['label' => 'Broken bin',      'value' => 2], ['label' => 'Hazardous waste', 'value' => 1],
  ];

  $trend = [
    ['m' => 'Nov', 'reports' => 14, 'resolved' => 9],  ['m' => 'Dec', 'reports' => 17, 'resolved' => 12], ['m' => 'Jan', 'reports' => 12, 'resolved' => 10],
    ['m' => 'Feb', 'reports' => 19, 'resolved' => 13], ['m' => 'Mar', 'reports' => 21, 'resolved' => 15], ['m' => 'Apr', 'reports' => 24, 'resolved' => 16],
  ];

  $schedule = [
    ['type' => 'General Waste', 'icon' => 'fa-solid fa-trash-can',  'days' => 'Every Monday and Thursday', 'time' => '6:00 - 9:00 AM'],
    ['type' => 'Recyclables',   'icon' => 'fa-solid fa-recycle',    'days' => 'Every Tuesday',             'time' => '8:00 - 11:00 AM'],
    ['type' => 'Biodegradable', 'icon' => 'fa-solid fa-apple-whole','days' => 'Every Saturday',            'time' => '6:00 - 8:30 AM'],
  ];

  $hotspots = [
    ['x' => 118, 'y' => 78,  'severity' => 'high',   'label' => 'Accumulated garbage near the creek'],
    ['x' => 214, 'y' => 128, 'severity' => 'high',   'label' => 'Open burning in the vacant lot'],
    ['x' => 82,  'y' => 168, 'severity' => 'medium', 'label' => 'Illegal dumping behind the market'],
    ['x' => 178, 'y' => 198, 'severity' => 'medium', 'label' => 'Overflowing trash bin'],
    ['x' => 252, 'y' => 70,  'severity' => 'low',    'label' => 'Plastic waste on the roadside'],
    ['x' => 142, 'y' => 238, 'severity' => 'low',    'label' => 'Scattered litter'],
  ];

  $maxCat = max(array_column($categoryBars, 'value'));
  $maxTrend = max(array_column($trend, 'reports'));
@endphp

<div class="pl-title-row">
  <div class="page-title">
    <h1>Purok Overview</h1>
    <p>A snapshot of <span data-purok>{{ $leader['purok'] }}</span>: its zones, hotspots, trends and collection schedule.</p>
  </div>
  <div class="purok-select">
    <label for="purokSelect" class="sr-only">Choose purok</label>
    <select id="purokSelect">
      @foreach ($puroks as $p)
        <option value="{{ $p }}" @selected($p === $leader['purok'])>{{ $p }}</option>
      @endforeach
    </select>
    <i class="fa-solid fa-chevron-down"></i>
  </div>
</div>

<section class="stats-grid four">
  @foreach ($summary as $s)
    <div class="stat-card">
      <div class="stat-icon {{ $s['tone'] }}"><i class="{{ $s['icon'] }}"></i></div>
      <div class="stat-info">
        <div class="title">{{ $s['label'] }}</div>
        <div class="count">{{ $s['value'] }}</div>
        <div class="subtitle">{{ $s['note'] }}</div>
      </div>
    </div>
  @endforeach
</section>

<section class="ov-grid">
  <div class="pg-card">
    <div class="pg-card-head"><h3>Hotspot map</h3></div>
    <div class="ov-map">@include('partials.hotspot-map')</div>
  </div>

  <div class="pg-card">
    <div class="pg-card-head"><h3>Zones in <span data-purok>{{ $leader['purok'] }}</span></h3></div>
    <div class="table-scroll">
      <table class="report-table">
        <thead>
          <tr><th scope="col">Zone</th><th scope="col">Residents</th><th scope="col">Reports</th><th scope="col">Pending</th><th scope="col">Crew</th><th scope="col">Collection</th></tr>
        </thead>
        <tbody>
          @foreach ($zones as $z)
            <tr>
              <td><strong>{{ $z['name'] }}</strong></td>
              <td class="rt-muted">{{ $z['residents'] }}</td>
              <td class="rt-muted">{{ $z['reports'] }}</td>
              <td>
                @if ($z['pending'] > 0)
                  <span class="badge pending">{{ $z['pending'] }}</span>
                @else
                  <span class="rt-muted">0</span>
                @endif
              </td>
              <td class="rt-muted">{{ $z['crew'] }}</td>
              <td class="rt-muted">{{ $z['days'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="ov-grid three">
  <div class="pg-card">
    <div class="pg-card-head"><h3>Reports by category</h3><span class="muted">This month</span></div>
    <ul class="bar-list">
      @foreach ($categoryBars as $b)
        <li>
          <span class="bar-label">{{ $b['label'] }}</span>
          <span class="bar-track"><span class="bar-fill" style="width: {{ round($b['value'] / $maxCat * 100) }}%"></span></span>
          <span class="bar-num">{{ $b['value'] }}</span>
        </li>
      @endforeach
    </ul>
  </div>

  <div class="pg-card">
    <div class="pg-card-head"><h3>6-month trend</h3>
      <span class="legend"><span class="lg a"></span>Reports <span class="lg b"></span>Resolved</span>
    </div>
    <div class="trend-chart" role="img" aria-label="Reports and resolved reports for the last six months">
      @foreach ($trend as $t)
        <div class="tc-col">
          <div class="tc-bars">
            <span class="tc-bar a" style="height: {{ round($t['reports'] / $maxTrend * 100) }}%" title="{{ $t['reports'] }} reports"></span>
            <span class="tc-bar b" style="height: {{ round($t['resolved'] / $maxTrend * 100) }}%" title="{{ $t['resolved'] }} resolved"></span>
          </div>
          <span class="tc-label">{{ $t['m'] }}</span>
        </div>
      @endforeach
    </div>
  </div>

  <div class="pg-card">
    <div class="pg-card-head"><h3>Collection schedule</h3></div>
    <div class="list-items">
      @foreach ($schedule as $s)
        <div class="list-item">
          <div class="item-main">
            <div class="circle-icon"><i class="{{ $s['icon'] }}"></i></div>
            <div class="item-details">
              <div class="item-title">{{ $s['type'] }}</div>
              <div class="item-subtext">{{ $s['days'] }}</div>
            </div>
          </div>
          <span class="rt-muted">{{ $s['time'] }}</span>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection

@extends('layouts.purok')

@section('title', 'Volunteers')

@section('content')
@php
  // Sample leader. Later, build this from the logged-in user.
  $leader = $leader ?? ['name' => 'Ana Santos', 'initials' => 'AS', 'purok' => 'Purok 3'];
  // SAMPLE DATA for the view-only prototype. Keep the same keys when you pass real data.
  // status: active | assigned | inactive      level: 1 = verified volunteer, 2 = team leader
  $volunteers = $volunteers ?? [
    ['id' => 1, 'name' => 'Rosa Dizon',     'level' => 2, 'zone' => 'Zone 2', 'mobile' => '0917 100 0001', 'drives' => 9, 'hours' => 31, 'status' => 'assigned', 'org' => 'Zone 2 Women\'s Association', 'skills' => ['Coordinating groups', 'First aid'],  'free' => 'Saturdays, Sundays'],
    ['id' => 2, 'name' => 'Mark Villar',    'level' => 1, 'zone' => 'Zone 2', 'mobile' => '0917 100 0002', 'drives' => 5, 'hours' => 17, 'status' => 'active',   'org' => null,                            'skills' => ['Heavy lifting', 'Driving'],        'free' => 'Weekday mornings'],
    ['id' => 3, 'name' => 'Joy Abella',     'level' => 1, 'zone' => 'Zone 1', 'mobile' => '0917 100 0003', 'drives' => 4, 'hours' => 12, 'status' => 'assigned', 'org' => 'Barangay Youth Council',      'skills' => ['Photography'],                     'free' => 'Saturdays'],
    ['id' => 4, 'name' => 'Ben Salazar',    'level' => 1, 'zone' => 'Zone 4', 'mobile' => '0917 100 0004', 'drives' => 6, 'hours' => 22, 'status' => 'active',   'org' => 'Coastal Guardians',            'skills' => ['Heavy lifting'],                   'free' => 'Weekday afternoons, Sundays'],
    ['id' => 5, 'name' => 'Lea Navarro',    'level' => 2, 'zone' => 'Zone 3', 'mobile' => '0917 100 0005', 'drives' => 11,'hours' => 40, 'status' => 'assigned', 'org' => 'Green Youth Club',           'skills' => ['Coordinating groups', 'Cooking / food'], 'free' => 'Saturdays'],
    ['id' => 6, 'name' => 'Carl Medina',    'level' => 1, 'zone' => 'Zone 3', 'mobile' => '0917 100 0006', 'drives' => 3, 'hours' => 9,  'status' => 'active',   'org' => null,                            'skills' => ['Driving'],                         'free' => 'Sundays'],
    ['id' => 7, 'name' => 'Gina Pascual',   'level' => 1, 'zone' => 'Zone 5', 'mobile' => '0917 100 0007', 'drives' => 2, 'hours' => 6,  'status' => 'assigned', 'org' => null,                            'skills' => ['First aid'],                       'free' => 'Weekday mornings'],
    ['id' => 8, 'name' => 'Noel Ortega',    'level' => 1, 'zone' => 'Zone 1', 'mobile' => '0917 100 0008', 'drives' => 1, 'hours' => 3,  'status' => 'inactive', 'org' => null,                            'skills' => [],                                  'free' => 'Sundays'],
  ];

  // Applications waiting for review. The barangay does the final ID check.
  $applications = $applications ?? [
    ['id' => 101, 'name' => 'Tina Robles',   'age' => 24, 'zone' => 'Zone 2', 'sent' => 'Apr 27, 2025', 'id_type' => 'National ID (PhilSys)', 'org' => 'St. Cecilia\'s College', 'skills' => ['Photography', 'Coordinating groups'], 'free' => 'Saturdays'],
    ['id' => 102, 'name' => 'Paolo Reyes',   'age' => 31, 'zone' => 'Zone 4', 'sent' => 'Apr 26, 2025', 'id_type' => 'Driver\'s license',    'org' => null,                      'skills' => ['Driving', 'Heavy lifting'],        'free' => 'Weekday afternoons'],
    ['id' => 103, 'name' => 'Cheska Lim',    'age' => 17, 'zone' => 'Zone 3', 'sent' => 'Apr 25, 2025', 'id_type' => 'Student ID',           'org' => 'Zone 3 Elementary School','skills' => ['First aid'],                   'free' => 'Sundays'],
  ];

  $activities = ['Brgy. Cleanup Drive (Apr 30)', 'Coastal Clean-up (May 5)', 'Tree Planting Activity (May 10)', 'Canal Clearing (May 17)'];
@endphp

<div class="pl-title-row">
  <div class="page-title">
    <h1>Volunteers</h1>
    <p>See who is helping in <span data-purok>{{ $leader['purok'] }}</span>, assign them to activities and review new applications.</p>
  </div>
</div>

<section class="stats-grid four" id="volStats"></section>

<div class="tabs" role="tablist" id="volTabs">
  <button type="button" role="tab" data-tab="volunteers" aria-selected="true">Volunteers</button>
  <button type="button" role="tab" data-tab="applications" aria-selected="false">Applications <span class="tab-count" id="appCount"></span></button>
</div>

{{-- Volunteers --}}
<section class="pg-card" id="volPanel">
  <div class="pg-toolbar">
    <div class="search-box">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="search" id="volSearch" placeholder="Search by name or zone" aria-label="Search volunteers">
    </div>
    <div class="pg-select">
      <select id="volStatus" aria-label="Filter by status">
        <option value="">All statuses</option>
        <option value="active">Active</option>
        <option value="assigned">Assigned to a task</option>
        <option value="inactive">Inactive</option>
      </select>
      <i class="fa-solid fa-chevron-down"></i>
    </div>
  </div>

  <div class="table-scroll">
    <table class="report-table data-table">
      <thead>
        <tr><th scope="col">Volunteer</th><th scope="col">Level</th><th scope="col">Zone</th><th scope="col">Drives</th><th scope="col">Status</th><th scope="col"><span class="sr-only">Actions</span></th></tr>
      </thead>
      <tbody id="volRows"></tbody>
    </table>
  </div>
  <div class="empty-state" id="volEmpty" hidden><i class="fa-solid fa-hand-holding-heart"></i><p>No volunteers match your search.</p></div>
</section>

{{-- Applications --}}
<section id="appPanel" hidden>
  <p class="panel-note"><i class="fa-solid fa-circle-info"></i> You can endorse or decline applicants from your purok. The barangay team checks the ID and gives the final approval.</p>
  <div class="app-grid" id="appGrid"></div>
</section>
@endsection

@push('scripts')
<script>
  let VOLS = @json($volunteers);
  let APPS = @json($applications);
  const ACTS = @json($activities);
  const SLABEL = { active: 'Active', assigned: 'Assigned', inactive: 'Inactive' };
  const initials = (n) => n.split(' ').map((w) => w[0]).slice(0, 2).join('');
  const levelTag = (l) => '<span class="tag ' + (l === 2 ? 'lv-2' : 'lv-1') + '">' + (l === 2 ? 'Level 2 &middot; Team Leader' : 'Level 1 &middot; Volunteer') + '</span>';

  let tab = 'volunteers', q = '', st = '';

  function renderStats() {
    const hours = VOLS.reduce((s, v) => s + v.hours, 0);
    const defs = [
      ['Active volunteers', VOLS.filter((v) => v.status !== 'inactive').length, 'fa-solid fa-users', 'primary', 'in your purok'],
      ['Assigned to tasks', VOLS.filter((v) => v.status === 'assigned').length, 'fa-solid fa-people-group', 'info', 'right now'],
      ['Pending applications', APPS.length, 'fa-regular fa-hourglass-half', 'warning', 'waiting for review'],
      ['Volunteer hours', hours, 'fa-regular fa-clock', 'primary', 'this year'],
    ];
    $('volStats').innerHTML = defs.map((d) => '<div class="stat-card"><div class="stat-icon ' + d[3] + '"><i class="' + d[2] + '"></i></div><div class="stat-info"><div class="title">' + d[0] + '</div><div class="count">' + d[1] + '</div><div class="subtitle">' + d[4] + '</div></div></div>').join('');
    $('appCount').textContent = APPS.length;
    $('appCount').hidden = APPS.length === 0;
  }

  function renderVols() {
    const needle = q.toLowerCase();
    const rows = VOLS.filter((v) => (!st || v.status === st) && (!needle || (v.name + ' ' + v.zone).toLowerCase().includes(needle)));
    $('volRows').innerHTML = rows.map((v) =>
      '<tr class="row-click" data-vol="' + v.id + '" tabindex="0"><td><div class="rt-report"><span class="av av-' + (v.level === 2 ? 'orange' : 'green') + '">' + esc(initials(v.name)) + '</span>'
      + '<span><span class="rt-title">' + esc(v.name) + '</span><span class="rt-id">' + esc(v.mobile) + '</span></span></div></td>'
      + '<td>' + levelTag(v.level) + '</td><td class="rt-muted">' + esc(v.zone) + '</td><td class="rt-muted">' + v.drives + '</td>'
      + '<td><span class="badge vs-' + v.status + '">' + SLABEL[v.status] + '</span></td>'
      + '<td class="rt-go"><button type="button" class="btn-ghost btn-sm" data-assign="' + v.id + '">Assign</button></td></tr>').join('');
    $('volEmpty').hidden = rows.length > 0;
  }

  function renderApps() {
    if (!APPS.length) { $('appGrid').innerHTML = '<div class="empty-state wide"><i class="fa-regular fa-circle-check"></i><p>All caught up. No applications waiting.</p></div>'; return; }
    $('appGrid').innerHTML = APPS.map((a) =>
      '<article class="app-card"><div class="app-top"><span class="av av-yellow">' + esc(initials(a.name)) + '</span>'
      + '<div><h3>' + esc(a.name) + '</h3><small>' + a.age + ' years old &middot; ' + esc(a.zone) + '</small></div></div>'
      + '<ul class="app-meta"><li><i class="fa-regular fa-id-card"></i>' + esc(a.id_type) + '</li>'
      + '<li><i class="fa-regular fa-calendar"></i>Sent ' + esc(a.sent) + '</li>'
      + '<li><i class="fa-solid fa-building"></i>' + esc(a.org || 'No organization') + '</li>'
      + '<li><i class="fa-regular fa-clock"></i>' + esc(a.free) + '</li></ul>'
      + (a.skills.length ? '<div class="chip-row">' + a.skills.map((s) => '<span class="chip">' + esc(s) + '</span>').join('') + '</div>' : '')
      + (a.age < 18 ? '<p class="warn-note"><i class="fa-solid fa-triangle-exclamation"></i> Under 18. Needs guardian consent.</p>' : '')
      + '<div class="app-actions"><button type="button" class="btn-ghost" data-decline="' + a.id + '">Decline</button>'
      + '<button type="button" class="btn-primary" data-endorse="' + a.id + '"><i class="fa-solid fa-check"></i> Endorse</button></div></article>').join('');
  }

  function openVolunteer(id) {
    const v = VOLS.find((x) => x.id === id);
    PL.openDrawer(
      '<header class="drawer-head"><div><span class="av av-' + (v.level === 2 ? 'orange' : 'green') + ' av-lg">' + esc(initials(v.name)) + '</span>'
      + '<h2>' + esc(v.name) + '</h2><p class="drawer-sub">' + levelTag(v.level) + '</p></div>'
      + '<button type="button" class="icon-btn" data-drawer-close aria-label="Close"><i class="fa-solid fa-xmark"></i></button></header>'
      + '<div class="drawer-body"><dl class="info-rows">'
      + '<div><dt><i class="fa-solid fa-phone"></i> Mobile</dt><dd>' + esc(v.mobile) + '</dd></div>'
      + '<div><dt><i class="fa-solid fa-location-dot"></i> Zone</dt><dd>' + esc(v.zone) + '</dd></div>'
      + '<div><dt><i class="fa-solid fa-building"></i> Organization</dt><dd>' + esc(v.org || 'None') + '</dd></div>'
      + '<div><dt><i class="fa-regular fa-clock"></i> Usually free</dt><dd>' + esc(v.free) + '</dd></div></dl>'
      + '<div class="mini-stats"><div><strong>' + v.drives + '</strong><span>Drives joined</span></div><div><strong>' + v.hours + '</strong><span>Hours</span></div>'
      + '<div><strong>' + (v.level === 2 ? 'Leader' : 'Member') + '</strong><span>Role</span></div></div>'
      + '<section><h3>Skills</h3>' + (v.skills.length ? '<div class="chip-row">' + v.skills.map((s) => '<span class="chip">' + esc(s) + '</span>').join('') + '</div>' : '<p class="muted">No skills listed.</p>') + '</section></div>'
      + '<footer class="drawer-foot"><button type="button" class="btn-primary block" data-assign="' + v.id + '"><i class="fa-solid fa-people-group"></i> Assign to an activity</button>'
      + '<a href="{{ route('purok-messages') }}" class="btn-ghost block"><i class="fa-regular fa-comment"></i> Message ' + esc(v.name.split(' ')[0]) + '</a></footer>');
  }

  function openAssign(id) {
    const v = VOLS.find((x) => x.id === id);
    PL.openModal(
      '<h3>Assign ' + esc(v.name) + '</h3><p class="modal-sub">Choose the activity they will help with.</p>'
      + '<div class="pg-select block"><select id="assignAct" aria-label="Activity">' + ACTS.map((a) => '<option>' + esc(a) + '</option>').join('') + '</select><i class="fa-solid fa-chevron-down"></i></div>'
      + '<div class="modal-actions"><button type="button" class="btn-ghost" data-modal-close>Cancel</button>'
      + '<button type="button" class="btn-primary" data-confirm-assign="' + v.id + '"><i class="fa-solid fa-check"></i> Assign</button></div>');
  }

  // ---------- Events ----------
  $('volTabs').addEventListener('click', (e) => {
    const b = e.target.closest('[data-tab]');
    if (!b) return;
    tab = b.dataset.tab;
    document.querySelectorAll('#volTabs button').forEach((x) => x.setAttribute('aria-selected', x === b));
    $('volPanel').hidden = tab !== 'volunteers';
    $('appPanel').hidden = tab !== 'applications';
  });

  $('volSearch').addEventListener('input', (e) => { q = e.target.value.trim(); renderVols(); });
  $('volStatus').addEventListener('change', (e) => { st = e.target.value; renderVols(); });

  $('volRows').addEventListener('click', (e) => {
    const a = e.target.closest('[data-assign]');
    if (a) return openAssign(Number(a.dataset.assign));
    const tr = e.target.closest('[data-vol]');
    if (tr) openVolunteer(Number(tr.dataset.vol));
  });

  $('appGrid').addEventListener('click', (e) => {
    const en = e.target.closest('[data-endorse]'), de = e.target.closest('[data-decline]');
    const id = Number((en || de || {}).dataset ? (en ? en.dataset.endorse : de.dataset.decline) : 0);
    if (!id) return;
    const a = APPS.find((x) => x.id === id);
    APPS = APPS.filter((x) => x.id !== id);
    renderStats(); renderApps();
    PL.toast(en ? a.name + ' was endorsed and sent to the barangay for ID checking' : a.name + '\'s application was declined');
  });

  document.addEventListener('click', (e) => {
    const a = e.target.closest('#drawerPanel [data-assign]');
    if (a) openAssign(Number(a.dataset.assign));
    const c = e.target.closest('[data-confirm-assign]');
    if (c) {
      const v = VOLS.find((x) => x.id === Number(c.dataset.confirmAssign));
      v.status = 'assigned';
      PL.closeModal(); renderStats(); renderVols();
      PL.toast(v.name + ' assigned to ' + $('assignAct').value);
    }
  });

  renderStats(); renderVols(); renderApps();
</script>
@endpush

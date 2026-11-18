@extends('layouts.purok')

@section('title', 'Waste Reports')

@section('content')
@php
  // Sample leader. Later, build this from the logged-in user.
  $leader = $leader ?? ['name' => 'Ana Santos', 'initials' => 'AS', 'purok' => 'Purok 3'];
  // SAMPLE DATA for the view-only prototype. Later, pass $reports from a controller
  // (only reports in the leader's purok). Keep the same keys.
  $categories = [
    'illegal_dumping' => 'Illegal dumping', 'overflowing_bin' => 'Overflowing bin', 'broken_bin' => 'Broken trash bin',
    'plastic_waste' => 'Plastic waste', 'hazardous_waste' => 'Hazardous waste', 'missed_collection' => 'Missed collection', 'other' => 'Other',
  ];
  $p1 = 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&q=80&w=600';
  $p2 = 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&q=80&w=600';
  $p3 = 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&q=80&w=600';

  $reports = $reports ?? [
    ['id' => 12, 'title' => 'Accumulated garbage near the creek', 'category' => 'illegal_dumping', 'zone' => 'Zone 4', 'status' => 'pending',  'date' => 'Apr 28, 2025', 'time' => '7:42 AM', 'reporter' => 'Maria Lopez',     'mobile' => '0917 000 0001', 'lat' => '10.315699', 'lng' => '123.885437', 'photo' => $p1, 'description' => 'Garbage bags and food waste are piling up along the creek bank. There is a strong smell and flies, and rain may wash it into the water.'],
    ['id' => 11, 'title' => 'Illegal dumping behind the market', 'category' => 'illegal_dumping', 'zone' => 'Zone 2', 'status' => 'progress', 'date' => 'Apr 27, 2025', 'time' => '5:55 PM', 'reporter' => 'Jose Ramirez',    'mobile' => '0917 000 0002', 'lat' => '10.317120', 'lng' => '123.890210', 'photo' => $p2, 'description' => 'Someone dumped old furniture and sacks of trash in the alley behind the market stalls.'],
    ['id' => 10, 'title' => 'Plastic waste on the roadside',     'category' => 'plastic_waste',   'zone' => 'Zone 1', 'status' => 'resolved', 'date' => 'Apr 26, 2025', 'time' => '9:10 AM', 'reporter' => 'Liza Mercado',    'mobile' => '0917 000 0003', 'lat' => '10.312884', 'lng' => '123.881960', 'photo' => $p3, 'description' => 'Plastic bottles and wrappers scattered along the road shoulder near the bus stop.'],
    ['id' => 9,  'title' => 'Open burning in the vacant lot',    'category' => 'hazardous_waste', 'zone' => 'Zone 3', 'status' => 'pending',  'date' => 'Apr 24, 2025', 'time' => '4:20 PM', 'reporter' => 'Pedro Alvarez',   'mobile' => '0917 000 0004', 'lat' => '10.314402', 'lng' => '123.887700', 'photo' => $p1, 'description' => 'A neighbor is burning mixed trash in the vacant lot. Smoke is drifting into nearby homes.'],
    ['id' => 8,  'title' => 'Overflowing trash bin',             'category' => 'overflowing_bin', 'zone' => 'Zone 5', 'status' => 'resolved', 'date' => 'Apr 22, 2025', 'time' => '6:30 AM', 'reporter' => 'Grace Tan',        'mobile' => '0917 000 0005', 'lat' => '10.318800', 'lng' => '123.892400', 'photo' => $p2, 'description' => 'The public bin near the chapel has been overflowing for three days.'],
    ['id' => 7,  'title' => 'Broken trash bin by the court',     'category' => 'broken_bin',      'zone' => 'Zone 1', 'status' => 'progress', 'date' => 'Apr 21, 2025', 'time' => '2:05 PM', 'reporter' => 'Ramon Cruz',      'mobile' => '0917 000 0006', 'lat' => '10.313001', 'lng' => '123.883301', 'photo' => $p3, 'description' => 'The bin has a cracked side and no lid. Trash falls out when it is windy.'],
    ['id' => 6,  'title' => 'Missed biodegradable pickup',       'category' => 'missed_collection','zone' => 'Zone 2', 'status' => 'resolved', 'date' => 'Apr 19, 2025', 'time' => '10:48 AM','reporter' => 'Nena Garcia',      'mobile' => '0917 000 0007', 'lat' => '10.316500', 'lng' => '123.889900', 'photo' => $p1, 'description' => 'The Saturday biodegradable truck skipped our street. Bags are still outside.'],
    ['id' => 5,  'title' => 'Dumped construction debris',        'category' => 'illegal_dumping', 'zone' => 'Zone 4', 'status' => 'pending',  'date' => 'Apr 18, 2025', 'time' => '8:15 AM', 'reporter' => 'Alfredo Bautista','mobile' => '0917 000 0008', 'lat' => '10.315100', 'lng' => '123.886200', 'photo' => $p2, 'description' => 'Broken tiles and cement bags were left at the end of the road overnight.'],
  ];
@endphp

<div class="pl-title-row">
  <div class="page-title">
    <h1>Waste Reports</h1>
    <p>Review what residents in <span data-purok>{{ $leader['purok'] }}</span> have reported and update each report's status.</p>
  </div>
</div>

{{-- Status counts (click one to filter) --}}
<section class="chip-stats" id="statChips" aria-label="Filter by status"></section>

<section class="pg-card">
  <div class="pg-toolbar">
    <div class="search-box">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="search" id="searchInput" placeholder="Search by title or reporter" aria-label="Search reports">
    </div>

    <div class="pg-select">
      <select id="catFilter" aria-label="Filter by category">
        <option value="">All categories</option>
        @foreach ($categories as $k => $label)
          <option value="{{ $k }}">{{ $label }}</option>
        @endforeach
      </select>
      <i class="fa-solid fa-chevron-down"></i>
    </div>

    <div class="pg-select">
      <select id="zoneFilter" aria-label="Filter by zone">
        <option value="">All zones</option>
        @foreach (['Zone 1', 'Zone 2', 'Zone 3', 'Zone 4', 'Zone 5'] as $z)
          <option>{{ $z }}</option>
        @endforeach
      </select>
      <i class="fa-solid fa-chevron-down"></i>
    </div>
  </div>

  <div class="table-scroll">
    <table class="report-table data-table">
      <thead>
        <tr>
          <th scope="col">Report</th>
          <th scope="col">Category</th>
          <th scope="col">Zone</th>
          <th scope="col">Status</th>
          <th scope="col">Date</th>
          <th scope="col"><span class="sr-only">Open</span></th>
        </tr>
      </thead>
      <tbody id="reportRows"></tbody>
    </table>
  </div>

  <div class="empty-state" id="emptyState" hidden>
    <i class="fa-regular fa-clipboard"></i>
    <p>No reports match your filters.</p>
  </div>

  <div class="table-foot" id="tableFoot"></div>
</section>
@endsection

@push('scripts')
<script>
  const CATS = @json($categories);
  let REPORTS = @json($reports);
  const LABEL = { pending: 'Pending', progress: 'In Progress', resolved: 'Resolved' };
  const code = (r) => 'BB-' + String(r.id).padStart(4, '0');

  let statusFilter = 'all', query = '', cat = '', zone = '';

  function filtered() {
    const q = query.toLowerCase();
    return REPORTS.filter((r) =>
      (statusFilter === 'all' || r.status === statusFilter) &&
      (!cat || r.category === cat) &&
      (!zone || r.zone === zone) &&
      (!q || (r.title + ' ' + r.reporter + ' ' + code(r)).toLowerCase().includes(q)));
  }

  function renderChips() {
    const n = (s) => REPORTS.filter((r) => s === 'all' || r.status === s).length;
    const defs = [['all', 'All reports', 'fa-regular fa-file-lines'], ['pending', 'Pending', 'fa-regular fa-hourglass-half'],
                  ['progress', 'In progress', 'fa-solid fa-spinner'], ['resolved', 'Resolved', 'fa-regular fa-circle-check']];
    $('statChips').innerHTML = defs.map((d) =>
      '<button type="button" class="chip-stat' + (statusFilter === d[0] ? ' is-on' : '') + '" data-status="' + d[0] + '" aria-pressed="' + (statusFilter === d[0]) + '">'
      + '<i class="' + d[2] + '"></i><span class="cs-num">' + n(d[0]) + '</span><span class="cs-label">' + d[1] + '</span></button>').join('');
  }

  function renderRows() {
    const rows = filtered();
    $('reportRows').innerHTML = rows.map((r) =>
      '<tr class="row-click" data-id="' + r.id + '" tabindex="0">'
      + '<td><div class="rt-report"><img src="' + esc(r.photo) + '" alt="" class="report-thumb" onerror="this.src=\'https://placehold.co/100x100/1e3a17/fff9a2?text=Issue\'">'
      + '<span><span class="rt-title">' + esc(r.title) + '</span><span class="rt-id">' + code(r) + ' &middot; ' + esc(r.reporter) + '</span></span></div></td>'
      + '<td class="rt-muted">' + esc(CATS[r.category]) + '</td>'
      + '<td class="rt-muted">' + esc(r.zone) + '</td>'
      + '<td><span class="badge ' + r.status + '">' + LABEL[r.status] + '</span></td>'
      + '<td class="rt-muted rt-date">' + esc(r.date) + '</td>'
      + '<td class="rt-go"><i class="fa-solid fa-chevron-right"></i></td></tr>').join('');
    $('emptyState').hidden = rows.length > 0;
    $('tableFoot').textContent = 'Showing ' + rows.length + ' of ' + REPORTS.length + ' reports';
  }

  const renderAll = () => { renderChips(); renderRows(); };

  function openReport(id) {
    const r = REPORTS.find((x) => x.id === id);
    if (!r) return;
    const history = (r.history || []).map((h) => '<li><span class="tl-dot"></span><div><strong>' + esc(h.text) + '</strong><small>' + esc(h.t) + '</small></div></li>').join('');
    PL.openDrawer(
      '<header class="drawer-head"><div><span class="badge ' + r.status + '">' + LABEL[r.status] + '</span>'
      + '<h2>' + esc(r.title) + '</h2><p class="drawer-sub">' + code(r) + ' &middot; ' + esc(CATS[r.category]) + '</p></div>'
      + '<button type="button" class="icon-btn" data-drawer-close aria-label="Close"><i class="fa-solid fa-xmark"></i></button></header>'
      + '<div class="drawer-body">'
      + '<img class="drawer-photo" src="' + esc(r.photo) + '" alt="Photo for ' + esc(r.title) + '" onerror="this.src=\'https://placehold.co/600x400/1e3a17/fff9a2?text=Photo\'">'
      + '<dl class="info-rows">'
      + '<div><dt><i class="fa-regular fa-user"></i> Reported by</dt><dd>' + esc(r.reporter) + '<small>' + esc(r.mobile) + '</small></dd></div>'
      + '<div><dt><i class="fa-regular fa-calendar"></i> Sent</dt><dd>' + esc(r.date) + ', ' + esc(r.time) + '</dd></div>'
      + '<div><dt><i class="fa-solid fa-location-dot"></i> Location</dt><dd>' + esc(r.zone) + '<small>' + esc(r.lat) + ', ' + esc(r.lng)
      + ' &middot; <a class="map-link" target="_blank" rel="noopener" href="https://www.google.com/maps?q=' + esc(r.lat) + ',' + esc(r.lng) + '">View on map</a></small></dd></div>'
      + '</dl>'
      + '<section><h3>Description</h3><p class="drawer-text">' + esc(r.description) + '</p></section>'
      + (history ? '<section><h3>Activity</h3><ul class="timeline">' + history + '</ul></section>' : '')
      + '<section class="update-box"><h3>Update status</h3>'
      + '<div class="pg-select block"><select id="newStatus" aria-label="New status">'
      + ['pending', 'progress', 'resolved'].map((s) => '<option value="' + s + '"' + (s === r.status ? ' selected' : '') + '>' + LABEL[s] + '</option>').join('')
      + '</select><i class="fa-solid fa-chevron-down"></i></div>'
      + '<textarea id="statusNote" rows="2" placeholder="Add a note for the resident (optional)"></textarea></section>'
      + '</div>'
      + '<footer class="drawer-foot"><button type="button" class="btn-primary block" data-save="' + r.id + '"><i class="fa-solid fa-check"></i> Save update</button>'
      + '<a href="{{ route('purok-messages') }}" class="btn-ghost block"><i class="fa-regular fa-comment"></i> Message ' + esc(r.reporter.split(' ')[0]) + '</a></footer>'
    );
  }

  // ---------- Events ----------
  $('statChips').addEventListener('click', (e) => {
    const b = e.target.closest('[data-status]');
    if (b) { statusFilter = b.dataset.status; renderAll(); }
  });
  $('searchInput').addEventListener('input', (e) => { query = e.target.value.trim(); renderRows(); });
  $('catFilter').addEventListener('change', (e) => { cat = e.target.value; renderRows(); });
  $('zoneFilter').addEventListener('change', (e) => { zone = e.target.value; renderRows(); });

  $('reportRows').addEventListener('click', (e) => {
    const tr = e.target.closest('[data-id]');
    if (tr) openReport(Number(tr.dataset.id));
  });
  $('reportRows').addEventListener('keydown', (e) => {
    if (e.key !== 'Enter') return;
    const tr = e.target.closest('[data-id]');
    if (tr) openReport(Number(tr.dataset.id));
  });

  $('drawerPanel').addEventListener('click', (e) => {
    const save = e.target.closest('[data-save]');
    if (!save) return;
    const r = REPORTS.find((x) => x.id === Number(save.dataset.save));
    const next = $('newStatus').value;
    const note = $('statusNote').value.trim();
    r.history = r.history || [];
    if (next !== r.status) r.history.unshift({ t: 'Just now', text: 'Status changed to ' + LABEL[next] });
    if (note) r.history.unshift({ t: 'Just now', text: 'Note: ' + note });
    r.status = next;
    renderAll();
    openReport(r.id);
    PL.toast('Report ' + code(r) + ' updated');
  });

  renderAll();
</script>
@endpush

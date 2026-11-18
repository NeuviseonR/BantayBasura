@extends('layouts.purok')

@section('title', 'Cleanup Activities')

@section('content')
@php
  // Sample leader. Later, build this from the logged-in user.
  $leader = $leader ?? ['name' => 'Ana Santos', 'initials' => 'AS', 'purok' => 'Purok 3'];
  // SAMPLE DATA for the view-only prototype. Keep the same keys when you pass real data.
  // status: confirmed | scheduled | completed | cancelled      level: 0 open, 1 verified volunteers, 2 team leaders
  $activities = $activities ?? [
    ['id' => 1, 'title' => 'Brgy. Cleanup Drive',    'mon' => 'Apr', 'day' => '30', 'full' => 'Wednesday, April 30, 2025', 'time' => '6:00 AM - 9:00 AM', 'zone' => 'Zone 2', 'meet' => 'Zone 2 Covered Court',  'status' => 'confirmed', 'level' => 0, 'slots' => 60, 'joined' => 38, 'goal' => 'Clear streets, canal edges and vacant lots and fill about 100 sacks of sorted waste.', 'people' => ['Elena Reyes', 'Carlo Mendoza', 'Rosa Dizon', 'Mark Villar', 'Joy Abella']],
    ['id' => 2, 'title' => 'Coastal Clean-up',       'mon' => 'May', 'day' => '5',  'full' => 'Monday, May 5, 2025',          'time' => '5:30 AM - 8:30 AM', 'zone' => 'Zone 4', 'meet' => 'Coastal Road entrance', 'status' => 'scheduled', 'level' => 1, 'slots' => 40, 'joined' => 31, 'goal' => 'Clear the shoreline of plastics and record what we find for the barangay report.',          'people' => ['Teresa Villanueva', 'Jun Dela Cruz', 'Ben Salazar']],
    ['id' => 3, 'title' => 'Tree Planting Activity', 'mon' => 'May', 'day' => '10', 'full' => 'Saturday, May 10, 2025',       'time' => '7:00 AM - 10:00 AM','zone' => 'Zone 3', 'meet' => 'Drainage canal footbridge','status' => 'scheduled','level' => 0, 'slots' => 80, 'joined' => 52, 'goal' => 'Plant 150 native seedlings along the canal buffer to reduce flooding.',                       'people' => ['Teresa Villanueva', 'Roberto Santos', 'Lea Navarro', 'Carl Medina']],
    ['id' => 4, 'title' => 'Canal Clearing',         'mon' => 'May', 'day' => '17', 'full' => 'Saturday, May 17, 2025',       'time' => '6:00 AM - 11:00 AM','zone' => 'Zones 1 and 3','meet' => 'Barangay Hall parking lot','status' => 'scheduled','level' => 2,'slots' => 20, 'joined' => 12, 'goal' => 'Remove silt and blockages from the main canals before the rainy season.',                    'people' => ['Roberto Santos', 'Carlo Mendoza']],
    ['id' => 5, 'title' => 'Recycling Collection Day','mon' => 'Apr', 'day' => '12', 'full' => 'Saturday, April 12, 2025',    'time' => '8:00 AM - 12:00 PM','zone' => 'Zone 2', 'meet' => 'Zone 2 Covered Court',  'status' => 'completed', 'level' => 0, 'slots' => 30, 'joined' => 27, 'goal' => 'Collect clean recyclables to fund new public bins. Collected 214 kg.',                         'people' => ['Elena Reyes', 'Jun Dela Cruz', 'Gina Pascual']],
  ];
@endphp

<div class="pl-title-row">
  <div class="page-title">
    <h1>Cleanup Activities</h1>
    <p>Plan and manage cleanup drives for <span data-purok>{{ $leader['purok'] }}</span>.</p>
  </div>
  <button type="button" class="btn-primary" id="newActivityBtn"><i class="fa-solid fa-plus"></i> New activity</button>
</div>

<div class="tabs" role="tablist" id="actTabs">
  <button type="button" role="tab" data-tab="upcoming" aria-selected="true">Upcoming</button>
  <button type="button" role="tab" data-tab="completed" aria-selected="false">Completed</button>
  <button type="button" role="tab" data-tab="all" aria-selected="false">All</button>
</div>

<section class="act-grid" id="actGrid"></section>
@endsection

@push('scripts')
<script>
  let ACTS = @json($activities);
  let nextId = 100;
  let tab = 'upcoming';
  const LABEL = { confirmed: 'Confirmed', scheduled: 'Scheduled', completed: 'Completed', cancelled: 'Cancelled' };
  const LV = { 0: ['lv-0', 'Open to everyone', 'fa-solid fa-users'], 1: ['lv-1', 'Verified volunteers', 'fa-solid fa-lock'], 2: ['lv-2', 'Team leaders only', 'fa-solid fa-lock'] };

  function render() {
    const items = ACTS.filter((a) => tab === 'all' || (tab === 'completed' ? a.status === 'completed' : ['confirmed', 'scheduled'].includes(a.status)));
    if (!items.length) {
      $('actGrid').innerHTML = '<div class="empty-state wide"><i class="fa-solid fa-people-group"></i><p>' + (tab === 'completed' ? 'No completed activities yet.' : 'No upcoming activities. Create one to get started.') + '</p></div>';
      return;
    }
    $('actGrid').innerHTML = items.map((a) => {
      const lv = LV[a.level];
      const pct = Math.min(100, Math.round((a.joined / a.slots) * 100));
      return '<article class="act-card">'
        + '<div class="act-top"><span class="date-block"><small>' + esc(a.mon) + '</small><strong>' + esc(a.day) + '</strong></span>'
        + '<div class="act-head"><h3>' + esc(a.title) + '</h3><span class="badge ' + a.status + '">' + LABEL[a.status] + '</span></div></div>'
        + '<ul class="act-meta"><li><i class="fa-regular fa-clock"></i>' + esc(a.time) + '</li><li><i class="fa-solid fa-location-dot"></i>' + esc(a.zone) + ' &middot; ' + esc(a.meet) + '</li></ul>'
        + '<span class="tag ' + lv[0] + '"><i class="' + lv[2] + '"></i> ' + lv[1] + '</span>'
        + '<div class="slot-row"><div class="mini-bar"><span style="width:' + pct + '%"></span></div><small>' + a.joined + ' of ' + a.slots + ' volunteers</small></div>'
        + '<button type="button" class="btn-ghost block" data-manage="' + a.id + '">Manage</button></article>';
    }).join('');
  }

  function openActivity(id) {
    const a = ACTS.find((x) => x.id === id);
    if (!a) return;
    const lv = LV[a.level];
    const open = ['confirmed', 'scheduled'].includes(a.status);
    PL.openDrawer(
      '<header class="drawer-head"><div><span class="badge ' + a.status + '">' + LABEL[a.status] + '</span><h2>' + esc(a.title) + '</h2>'
      + '<p class="drawer-sub"><span class="tag ' + lv[0] + '"><i class="' + lv[2] + '"></i> ' + lv[1] + '</span></p></div>'
      + '<button type="button" class="icon-btn" data-drawer-close aria-label="Close"><i class="fa-solid fa-xmark"></i></button></header>'
      + '<div class="drawer-body">'
      + '<div class="info-grid"><div><i class="fa-regular fa-calendar"></i><small>When</small><strong>' + esc(a.full) + '</strong><span>' + esc(a.time) + '</span></div>'
      + '<div><i class="fa-solid fa-location-dot"></i><small>Where</small><strong>' + esc(a.meet) + '</strong><span>' + esc(a.zone) + '</span></div></div>'
      + '<section><h3>Goal</h3><p class="drawer-text">' + esc(a.goal) + '</p></section>'
      + '<section><h3>Volunteers (' + a.joined + ' of ' + a.slots + ')</h3><div class="mini-bar big"><span style="width:' + Math.min(100, Math.round((a.joined / a.slots) * 100)) + '%"></span></div>'
      + '<ul class="people">' + (a.people || []).map((n) => '<li><span class="av av-green">' + esc(n.split(' ').map((w) => w[0]).slice(0, 2).join('')) + '</span><strong>' + esc(n) + '</strong></li>').join('') + '</ul>'
      + ((a.people || []).length < a.joined ? '<small class="muted">and ' + (a.joined - a.people.length) + ' more</small>' : '') + '</section>'
      + '</div>'
      + (open ? '<footer class="drawer-foot">'
        + (a.status === 'scheduled' ? '<button type="button" class="btn-primary block" data-confirm="' + a.id + '"><i class="fa-solid fa-check"></i> Confirm activity</button>' : '')
        + '<button type="button" class="btn-danger-ghost block" data-cancel="' + a.id + '"><i class="fa-regular fa-circle-xmark"></i> Cancel activity</button></footer>' : '')
    );
  }

  function openForm() {
    PL.openModal(
      '<h3>New cleanup activity</h3><p class="modal-sub">Volunteers in your purok will see it once you confirm it.</p>'
      + '<form id="actForm" novalidate><div class="form-grid">'
      + '<div class="field wide"><label for="fTitle">Title</label><input id="fTitle" type="text" placeholder="e.g. Zone 3 Street Clean-up" data-req></div>'
      + '<div class="field"><label for="fDate">Date</label><input id="fDate" type="date" data-req></div>'
      + '<div class="field"><label for="fTime">Time</label><input id="fTime" type="text" placeholder="6:00 AM - 9:00 AM" data-req></div>'
      + '<div class="field"><label for="fZone">Zone</label><div class="pg-select block"><select id="fZone" data-req>'
      + ['Zone 1', 'Zone 2', 'Zone 3', 'Zone 4', 'Zone 5'].map((z) => '<option>' + z + '</option>').join('') + '</select><i class="fa-solid fa-chevron-down"></i></div></div>'
      + '<div class="field"><label for="fMeet">Meeting point</label><input id="fMeet" type="text" data-req></div>'
      + '<div class="field"><label for="fSlots">Volunteer slots</label><input id="fSlots" type="number" min="1" value="30" data-req></div>'
      + '<div class="field"><label for="fLevel">Who can join</label><div class="pg-select block"><select id="fLevel">'
      + '<option value="0">Everyone</option><option value="1">Verified volunteers</option><option value="2">Team leaders only</option></select><i class="fa-solid fa-chevron-down"></i></div></div>'
      + '<div class="field wide"><label for="fGoal">Goal</label><textarea id="fGoal" rows="3" placeholder="What do you want to achieve?" data-req></textarea></div>'
      + '</div><div class="modal-actions"><button type="button" class="btn-ghost" data-modal-close>Cancel</button>'
      + '<button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Create activity</button></div></form>', true);
  }

  // ---------- Events ----------
  $('actTabs').addEventListener('click', (e) => {
    const b = e.target.closest('[data-tab]');
    if (!b) return;
    tab = b.dataset.tab;
    document.querySelectorAll('#actTabs button').forEach((x) => x.setAttribute('aria-selected', x === b));
    render();
  });

  $('actGrid').addEventListener('click', (e) => {
    const b = e.target.closest('[data-manage]');
    if (b) openActivity(Number(b.dataset.manage));
  });

  $('newActivityBtn').addEventListener('click', openForm);

  $('drawerPanel').addEventListener('click', (e) => {
    const c = e.target.closest('[data-confirm]'), x = e.target.closest('[data-cancel]');
    if (c) { const a = ACTS.find((i) => i.id === Number(c.dataset.confirm)); a.status = 'confirmed'; render(); openActivity(a.id); PL.toast('Activity confirmed'); }
    if (x) { const a = ACTS.find((i) => i.id === Number(x.dataset.cancel)); a.status = 'cancelled'; render(); openActivity(a.id); PL.toast('Activity cancelled'); }
  });

  $('modalDialog').addEventListener('submit', (e) => {
    e.preventDefault();
    let bad = false;
    e.target.querySelectorAll('[data-req]').forEach((el) => {
      const miss = !el.value.trim();
      el.classList.toggle('invalid', miss);
      if (miss) bad = true;
    });
    if (bad) return;
    const d = new Date($('fDate').value + 'T00:00:00');
    ACTS.unshift({
      id: ++nextId, title: $('fTitle').value.trim(), status: 'scheduled', level: Number($('fLevel').value),
      mon: d.toLocaleDateString('en-US', { month: 'short' }), day: String(d.getDate()),
      full: d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }),
      time: $('fTime').value.trim(), zone: $('fZone').value, meet: $('fMeet').value.trim(),
      slots: Number($('fSlots').value), joined: 0, goal: $('fGoal').value.trim(), people: [],
    });
    PL.closeModal();
    tab = 'upcoming';
    document.querySelectorAll('#actTabs button').forEach((x) => x.setAttribute('aria-selected', x.dataset.tab === 'upcoming'));
    render();
    PL.toast('Activity created (scheduled)');
  });

  render();
</script>
@endpush

@php
  // ---------------------------------------------------------------------------
  // SAMPLE DATA for the view-only stage. Replace with values from your controller
  // (or the barangay document) and delete this block. Keys must stay the same.
  // days: 0 = Sunday ... 6 = Saturday. Times are 24-hour "HH:MM".
  // ---------------------------------------------------------------------------
  $residentZone = $residentZone ?? 'Zone 2';

  $types = $types ?? [
    'general' => [
      'key' => 'general', 'label' => 'General Waste', 'icon' => 'fa-solid fa-trash-can',
      'days' => [1, 4], 'start' => '06:00', 'end' => '09:00',
      'crew' => 'Truck 1 - Team Alpha',
      'accepts' => ['Non-recyclable plastics and wrappers', 'Used tissue and diapers', 'Broken ceramics', 'Sweepings and dust'],
      'rejects' => ['Food scraps and garden waste', 'Clean paper, cans and bottles', 'Batteries and electronics'],
      'tip' => 'Tie bags securely and place them outside before 6:00 AM.',
    ],
    'recycle' => [
      'key' => 'recycle', 'label' => 'Recyclables', 'icon' => 'fa-solid fa-recycle',
      'days' => [2], 'start' => '08:00', 'end' => '11:00',
      'crew' => 'Truck 2 - Team Bravo',
      'accepts' => ['Paper and cardboard (flattened)', 'Plastic bottles and containers (rinsed)', 'Glass bottles and jars', 'Metal cans'],
      'rejects' => ['Greasy or food-soiled packaging', 'Styrofoam', 'Broken glass (wrap it first)'],
      'tip' => 'Rinse and dry items. Clean recyclables fetch a better price at the sorting center.',
    ],
    'bio' => [
      'key' => 'bio', 'label' => 'Biodegradable', 'icon' => 'fa-solid fa-apple-whole',
      'days' => [6], 'start' => '06:00', 'end' => '08:30',
      'crew' => 'Truck 3 - Team Charlie',
      'accepts' => ['Food scraps and peelings', 'Leaves, grass and garden trimmings', 'Eggshells and coffee grounds'],
      'rejects' => ['Cooked oil in liquid form', 'Plastic bags or wrappers', 'Animal waste'],
      'tip' => 'Drain excess liquid and use a breathable bag or a bin with a lid.',
    ],
  ];

  $zones = $zones ?? [
    ['name' => 'Zone 1', 'areas' => ['Purok 1', 'Purok 2', 'Public market area'],   'team' => 'Team Alpha',   'hours' => '5:30 AM - 12:00 PM', 'vehicle' => 'Truck 1'],
    ['name' => 'Zone 2', 'areas' => ['Purok 3', 'Purok 4', 'Barangay hall road'],   'team' => 'Team Bravo',   'hours' => '5:30 AM - 12:00 PM', 'vehicle' => 'Truck 2'],
    ['name' => 'Zone 3', 'areas' => ['Purok 5', 'Purok 6', 'Drainage canal side'],  'team' => 'Team Charlie', 'hours' => '6:00 AM - 1:00 PM',  'vehicle' => 'Truck 3'],
    ['name' => 'Zone 4', 'areas' => ['Purok 7', 'Coastal road', 'Beach front'],     'team' => 'Team Delta',   'hours' => '6:00 AM - 1:00 PM',  'vehicle' => 'Truck 4'],
  ];

  $contact = $contact ?? [
    'office'  => 'Barangay Solid Waste Management Office',
    'hotline' => '(032) 000-0000',
    'hours'   => 'Mon - Sat, 8:00 AM - 5:00 PM',
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bantay Basura - Collection Schedule</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/user-dash.css', 'resources/css/user-collection-schedule.css', 'resources/css/custom.css'])
</head>
<body>

  <aside class="sidebar">
    <div>
      <div class="brand">
        <div class="brand-logo">
          <i class="fa-solid fa-leaf"></i>
        </div>
        <div class="brand-name">Bantay<br>Basura</div>
        <button class="mobile-menu-toggle" id="menuToggle">
          <i class="fa-solid fa-bars"></i>
        </button>
      </div>

      <nav>
        <ul class="nav-list" id="navList">
          <li class="nav-item">
            <a href="{{ route('user-dash') }}"><i class="fa-solid fa-house"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('user-report') }}"><i class="fa-solid fa-circle-exclamation"></i> Report a Waste Issue</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('user-my-reports') }}"><i class="fa-regular fa-clipboard"></i> My Reports</a>
          </li>
          <li class="nav-item active">
            <a href="{{ route('user-collection-schedule') }}"><i class="fa-solid fa-calendar"></i> Collection Schedule</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('user-cleanup-drives') }}"><i class="fa-solid fa-people-group"></i> Cleanup Drives</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('user-messages') }}"><i class="fa-regular fa-comment"></i> Messages</a>
          </li>
          <li class="nav-item">
            <a href="{{ route('user-profile') }}"><i class="fa-regular fa-user"></i> Profile</a>
          </li>
        </ul>
      </nav>
    </div>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
            </button>
        </form>
    </div>
  </aside>

  <div class="main-wrapper">

    {{-- Top Navigation Header --}}
    <header class="top-header">
      <div class="user-greeting">
        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=120" alt="Maria Lopez" class="avatar-img" onerror="this.src='https://placehold.co/80x80/1e3a17/fff9a2?text=ML'">
        <div class="greeting-text">
          <h2>Hello, Maria Lopez!</h2>
        </div>
      </div>

      <div class="top-actions">
        <button class="notification-btn" aria-label="Notifications">
          <i class="fa-regular fa-bell"></i>
          <span class="notification-dot"></span>
        </button>

        <div class="profile-pill">
          <div class="avatar-initials">ML</div>
          <div class="profile-info">
            <div class="name">Maria Lopez</div>
            <div class="role">Resident</div>
          </div>
          <i class="fa-solid fa-chevron-down dropdown-icon"></i>
        </div>
      </div>
    </header>

    <main class="dashboard-content">

      <div class="page-title sched-title">
        <div>
          <h1>Collection Schedule</h1>
          <p>See when waste is collected in your area, and get reminded before the truck arrives.</p>
        </div>
        <span class="zone-pill"><i class="fa-solid fa-location-dot"></i> Your area: {{ $residentZone }}</span>
      </div>

      <section class="sched-layout">

        {{-- ================= Calendar ================= --}}
        <div class="sched-card calendar-card">
          <div class="cal-toolbar">
            <div class="cal-nav">
              <button type="button" class="icon-btn" id="calPrev" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
              <button type="button" class="icon-btn" id="calNext" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
              <button type="button" class="btn-today" id="calToday">Today</button>
              <h2 class="cal-range" id="calRange" aria-live="polite"></h2>
            </div>

            <div class="segmented" role="group" aria-label="Calendar view">
              <button type="button" id="viewWeek" aria-pressed="true">Week</button>
              <button type="button" id="viewMonth" aria-pressed="false">Month</button>
            </div>
          </div>

          <div class="cal-legend">
            @foreach ($types as $t)
              <span class="legend-item"><span class="legend-dot t-{{ $t['key'] }}"></span>{{ $t['label'] }}</span>
            @endforeach
          </div>

          <div id="calBody" class="cal-body"></div>
        </div>

        {{-- ================= Side column ================= --}}
        <div class="sched-side">

          <div class="sched-card" id="detailsCard" aria-live="polite"></div>

          <div class="sched-card">
            <div class="side-title">
              <span class="form-section-icon"><i class="fa-regular fa-bell"></i></span>
              <h3>Reminders</h3>
            </div>
            <p class="side-hint">Turn on a reminder for each collection you don't want to miss.</p>

            <div class="reminder-list" id="reminderList"></div>

            <div class="reminder-lead">
              <label for="leadTime">Remind me</label>
              <div class="select-wrap">
                <select id="leadTime">
                  <option value="30">30 minutes before</option>
                  <option value="60" selected>1 hour before</option>
                  <option value="720">The evening before (6:00 PM)</option>
                </select>
                <i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>
            <p class="reminder-status" id="reminderStatus"></p>
          </div>

        </div>
      </section>

      {{-- ================= Patrol areas ================= --}}
      <section class="sched-card">
        <div class="section-head">
          <div>
            <h3>Collection &amp; patrol areas</h3>
            <p>Which team covers which part of the barangay, and when they are on the road.</p>
          </div>
        </div>

        <div class="zone-grid">
          @foreach ($zones as $z)
            <article class="zone-card {{ $z['name'] === $residentZone ? 'is-yours' : '' }}">
              <header>
                <h4>{{ $z['name'] }}</h4>
                @if ($z['name'] === $residentZone)
                  <span class="badge resolved">Your zone</span>
                @endif
              </header>

              <ul class="zone-areas">
                @foreach ($z['areas'] as $a)
                  <li><i class="fa-solid fa-map-pin"></i>{{ $a }}</li>
                @endforeach
              </ul>

              <dl class="zone-meta">
                <div><dt><i class="fa-solid fa-people-group"></i> Team</dt><dd>{{ $z['team'] }}</dd></div>
                <div><dt><i class="fa-solid fa-truck"></i> Vehicle</dt><dd>{{ $z['vehicle'] }}</dd></div>
                <div><dt><i class="fa-regular fa-clock"></i> Patrol hours</dt><dd>{{ $z['hours'] }}</dd></div>
              </dl>
            </article>
          @endforeach
        </div>
      </section>

      {{-- ================= Guidelines + contact ================= --}}
      <section class="info-grid">
        <div class="sched-card">
          <div class="section-head">
            <div><h3>What goes where</h3></div>
          </div>
          <div class="guide-list">
            @foreach ($types as $t)
              <div class="guide-item">
                <span class="circle-icon"><i class="{{ $t['icon'] }}"></i></span>
                <div>
                  <div class="item-title">{{ $t['label'] }}</div>
                  <div class="item-subtext">{{ implode(', ', array_slice($t['accepts'], 0, 3)) }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="sched-card">
          <div class="section-head">
            <div><h3>Good to know</h3></div>
          </div>
          <ul class="know-list">
            <li><i class="fa-regular fa-clock"></i> Place your waste outside before 6:00 AM on collection day.</li>
            <li><i class="fa-solid fa-ban"></i> Don't leave waste out the night before. It attracts animals and clogs drains.</li>
            <li><i class="fa-solid fa-triangle-exclamation"></i> Schedules may change on holidays or during bad weather. Check your messages.</li>
            <li><i class="fa-solid fa-circle-exclamation"></i> Truck didn't come? <a href="#" class="view-all">Report a missed collection</a>.</li>
          </ul>

          <div class="contact-box">
            <div class="item-title">{{ $contact['office'] }}</div>
            <div class="item-subtext"><i class="fa-solid fa-phone"></i> {{ $contact['hotline'] }}</div>
            <div class="item-subtext"><i class="fa-regular fa-clock"></i> {{ $contact['hours'] }}</div>
          </div>
        </div>
      </section>

    </main>
  </div>

  <script>
    // Mobile menu
    document.getElementById('menuToggle').addEventListener('click', function () {
      document.getElementById('navList').classList.toggle('active');
    });

    // =========================================================================
    // Schedule calendar
    // =========================================================================
    const TYPES = @json($types);
    const ZONE = @json($residentZone);
    const HOUR_START = 5, HOUR_END = 14, HOUR_H = 56;
    const DOW = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    const $ = (id) => document.getElementById(id);
    const sod = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate());
    const addDays = (d, n) => { const x = new Date(d); x.setDate(x.getDate() + n); return sod(x); };
    const sameDay = (a, b) => a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
    const iso = (d) => d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    const fromIso = (s) => { const [y, m, d] = s.split('-').map(Number); return new Date(y, m - 1, d); };
    const hrs = (t) => { const [h, m] = t.split(':').map(Number); return h + m / 60; };
    const fmt = (t) => { const [h, m] = t.split(':').map(Number); return (h % 12 || 12) + ':' + String(m).padStart(2, '0') + ' ' + (h < 12 ? 'AM' : 'PM'); };
    const fmtHour = (h) => (h % 12 || 12) + ' ' + (h < 12 ? 'AM' : 'PM');
    const range = (t) => fmt(t.start) + ' - ' + fmt(t.end);
    const longDate = (d) => DOW[d.getDay()] + ', ' + MONTHS[d.getMonth()].slice(0, 3) + ' ' + d.getDate() + ', ' + d.getFullYear();
    const esc = (s) => String(s).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
    const daysSummary = (t) => t.days.map((d) => DOW[d]).join(' & ');

    const today = sod(new Date());
    let view = 'week';
    let cursor = new Date(today);
    let selected = null; // { key, date }

    const occurrences = (date) =>
      Object.values(TYPES).filter((t) => t.days.includes(date.getDay())).sort((a, b) => hrs(a.start) - hrs(b.start));

    function nextOccurrence(from) {
      for (let i = 0; i < 14; i++) {
        const d = addDays(from, i);
        const occ = occurrences(d)[0];
        if (occ) return { key: occ.key, date: d };
      }
      return null;
    }

    // ---------- Reminders (saved in this browser for now) ----------
    const STORE = 'bb_collection_reminders';
    let reminders = { types: {}, lead: '60' };
    try { reminders = Object.assign(reminders, JSON.parse(localStorage.getItem(STORE) || '{}')); } catch (e) {}
    const saveReminders = () => { try { localStorage.setItem(STORE, JSON.stringify(reminders)); } catch (e) {} };

    function toggleReminder(key) {
      const turningOn = !reminders.types[key];
      reminders.types[key] = turningOn;
      saveReminders();
      if (turningOn && 'Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(renderAll);
      }
      renderAll();
    }

    // ---------- Calendar ----------
    function renderRange() {
      let label;
      if (view === 'month') {
        label = MONTHS[cursor.getMonth()] + ' ' + cursor.getFullYear();
      } else {
        const s = addDays(cursor, -cursor.getDay()), e = addDays(s, 6);
        const sm = MONTHS[s.getMonth()].slice(0, 3), em = MONTHS[e.getMonth()].slice(0, 3);
        label = s.getMonth() === e.getMonth()
          ? sm + ' ' + s.getDate() + ' - ' + e.getDate() + ', ' + e.getFullYear()
          : sm + ' ' + s.getDate() + ' - ' + em + ' ' + e.getDate() + ', ' + e.getFullYear();
      }
      $('calRange').textContent = label;
    }

    function renderWeek() {
      const start = addDays(cursor, -cursor.getDay());
      const days = Array.from({ length: 7 }, (_, i) => addDays(start, i));
      const hours = Array.from({ length: HOUR_END - HOUR_START }, (_, i) => HOUR_START + i);

      let html = '<div class="wk-scroll"><div class="wk" style="--hh:' + HOUR_H + 'px">';
      html += '<div class="wk-head"><div class="wk-corner"></div>';
      days.forEach((d) => {
        html += '<div class="wk-dayhead' + (sameDay(d, today) ? ' is-today' : '') + '"><span>' + DOW[d.getDay()] + '</span><strong>' + d.getDate() + '</strong></div>';
      });
      html += '</div><div class="wk-body"><div class="wk-times">';
      hours.forEach((h) => { html += '<div class="wk-hour"><span>' + fmtHour(h) + '</span></div>'; });
      html += '</div>';

      days.forEach((d) => {
        html += '<div class="wk-col' + (sameDay(d, today) ? ' is-today' : '') + '">';
        occurrences(d).forEach((t) => {
          const top = (hrs(t.start) - HOUR_START) * HOUR_H;
          const h = Math.max((hrs(t.end) - hrs(t.start)) * HOUR_H, 30);
          const on = selected && selected.key === t.key && sameDay(selected.date, d);
          html += '<button type="button" class="ev t-' + t.key + (on ? ' is-selected' : '') + '" style="top:' + top + 'px;height:' + h + 'px" data-key="' + t.key + '" data-date="' + iso(d) + '">'
            + '<span class="ev-name"><i class="' + t.icon + '"></i> ' + esc(t.label) + '</span><span class="ev-time">' + range(t) + '</span></button>';
        });
        if (sameDay(d, today)) {
          const now = new Date(), nh = now.getHours() + now.getMinutes() / 60;
          if (nh >= HOUR_START && nh <= HOUR_END) html += '<div class="wk-now" style="top:' + (nh - HOUR_START) * HOUR_H + 'px"></div>';
        }
        html += '</div>';
      });
      html += '</div></div></div>';
      $('calBody').innerHTML = html;
    }

    function renderMonth() {
      const first = new Date(cursor.getFullYear(), cursor.getMonth(), 1);
      const gridStart = addDays(first, -first.getDay());
      let html = '<div class="mo-scroll"><div class="mo"><div class="mo-head">';
      DOW.forEach((n) => { html += '<div>' + n + '</div>'; });
      html += '</div><div class="mo-grid">';
      for (let i = 0; i < 42; i++) {
        const d = addDays(gridStart, i);
        const occ = occurrences(d);
        const cls = ['mo-cell'];
        if (d.getMonth() !== cursor.getMonth()) cls.push('is-out');
        if (sameDay(d, today)) cls.push('is-today');
        if (selected && sameDay(selected.date, d)) cls.push('is-selected');
        html += '<div class="' + cls.join(' ') + '" data-date="' + iso(d) + '"><span class="mo-num">' + d.getDate() + '</span><div class="mo-evs">';
        occ.forEach((t) => {
          html += '<button type="button" class="chip t-' + t.key + '" data-key="' + t.key + '" data-date="' + iso(d) + '" title="' + esc(t.label) + ', ' + range(t) + '">'
            + '<span class="chip-time">' + fmt(t.start).replace(':00', '') + '</span><span class="chip-name">' + esc(t.label) + '</span></button>';
        });
        html += '</div></div>';
      }
      html += '</div></div></div>';
      $('calBody').innerHTML = html;
    }

    // ---------- Details ----------
    function renderDetails() {
      const card = $('detailsCard');
      if (!selected) { card.innerHTML = ''; return; }

      if (!selected.key) {
        const nxt = nextOccurrence(addDays(selected.date, 1));
        card.innerHTML = '<div class="side-title"><span class="form-section-icon"><i class="fa-regular fa-calendar"></i></span><h3>' + longDate(selected.date) + '</h3></div>'
          + '<p class="side-hint">No collection scheduled on this day.</p>'
          + (nxt ? '<button type="button" class="btn-secondary" data-pick="' + nxt.key + '|' + iso(nxt.date) + '">Show next collection</button>' : '');
        return;
      }

      const t = TYPES[selected.key];
      const diff = Math.round((selected.date - today) / 86400000);
      const when = diff === 0 ? 'Today' : diff === 1 ? 'Tomorrow' : diff > 1 ? 'In ' + diff + ' days' : 'Past';
      const on = !!reminders.types[t.key];

      card.innerHTML =
        '<div class="detail-head t-' + t.key + '"><span class="detail-icon"><i class="' + t.icon + '"></i></span>'
        + '<div><h3>' + esc(t.label) + '</h3><p>' + longDate(selected.date) + '</p></div>'
        + '<span class="when-pill">' + when + '</span></div>'
        + '<dl class="detail-rows">'
        + '<div><dt><i class="fa-regular fa-clock"></i> Time</dt><dd>' + range(t) + '</dd></div>'
        + '<div><dt><i class="fa-solid fa-repeat"></i> Repeats</dt><dd>Every ' + daysSummary(t) + '</dd></div>'
        + '<div><dt><i class="fa-solid fa-location-dot"></i> Area</dt><dd>' + esc(ZONE) + '</dd></div>'
        + '<div><dt><i class="fa-solid fa-truck"></i> Crew</dt><dd>' + esc(t.crew) + '</dd></div>'
        + '</dl>'
        + '<div class="detail-lists"><div><h4><i class="fa-solid fa-check"></i> Put out</h4><ul>' + t.accepts.map((x) => '<li>' + esc(x) + '</li>').join('') + '</ul></div>'
        + '<div><h4 class="no"><i class="fa-solid fa-xmark"></i> Keep out</h4><ul>' + t.rejects.map((x) => '<li>' + esc(x) + '</li>').join('') + '</ul></div></div>'
        + '<p class="detail-tip"><i class="fa-regular fa-lightbulb"></i> ' + esc(t.tip) + '</p>'
        + '<button type="button" class="btn-remind' + (on ? ' is-on' : '') + '" data-remind="' + t.key + '">'
        + '<i class="' + (on ? 'fa-solid fa-bell' : 'fa-regular fa-bell') + '"></i> ' + (on ? 'Reminder on' : 'Remind me about this') + '</button>';
    }

    // ---------- Reminder panel ----------
    function renderReminders() {
      $('reminderList').innerHTML = Object.values(TYPES).map((t) => {
        const on = !!reminders.types[t.key];
        return '<div class="reminder-row"><span class="circle-icon"><i class="' + t.icon + '"></i></span>'
          + '<div class="reminder-text"><div class="item-title">' + esc(t.label) + '</div><div class="item-subtext">' + daysSummary(t) + ' &bull; ' + fmt(t.start) + '</div></div>'
          + '<button type="button" role="switch" class="switch" aria-checked="' + on + '" aria-label="Remind me about ' + esc(t.label) + '" data-switch="' + t.key + '"><span></span></button></div>';
      }).join('');

      $('leadTime').value = reminders.lead;

      const anyOn = Object.values(reminders.types).some(Boolean);
      const status = $('reminderStatus');
      if (!anyOn) {
        status.textContent = 'Reminders are off.';
      } else if (!('Notification' in window)) {
        status.textContent = 'Saved. This browser can\'t show notifications, so reminders will come through the app.';
      } else if (Notification.permission === 'denied') {
        status.textContent = 'Notifications are blocked in your browser. Allow them in site settings to get reminders.';
      } else if (Notification.permission === 'default') {
        status.textContent = 'Allow notifications when your browser asks.';
      } else {
        status.textContent = 'Reminders are on.';
      }
    }

    function renderAll() {
      renderRange();
      view === 'week' ? renderWeek() : renderMonth();
      renderDetails();
      renderReminders();
      $('viewWeek').setAttribute('aria-pressed', view === 'week');
      $('viewMonth').setAttribute('aria-pressed', view === 'month');
    }

    // ---------- Events ----------
    $('calPrev').addEventListener('click', () => {
      cursor = view === 'week' ? addDays(cursor, -7) : new Date(cursor.getFullYear(), cursor.getMonth() - 1, 1);
      renderAll();
    });
    $('calNext').addEventListener('click', () => {
      cursor = view === 'week' ? addDays(cursor, 7) : new Date(cursor.getFullYear(), cursor.getMonth() + 1, 1);
      renderAll();
    });
    $('calToday').addEventListener('click', () => { cursor = new Date(today); renderAll(); });
    $('viewWeek').addEventListener('click', () => { view = 'week'; renderAll(); });
    $('viewMonth').addEventListener('click', () => { view = 'month'; renderAll(); });

    $('calBody').addEventListener('click', (e) => {
      const item = e.target.closest('[data-key]');
      if (item) {
        selected = { key: item.dataset.key, date: fromIso(item.dataset.date) };
        return renderAll();
      }
      const cell = e.target.closest('.mo-cell');
      if (cell) {
        const d = fromIso(cell.dataset.date);
        const first = occurrences(d)[0];
        selected = { key: first ? first.key : null, date: d };
        renderAll();
      }
    });

    $('detailsCard').addEventListener('click', (e) => {
      const pick = e.target.closest('[data-pick]');
      if (pick) {
        const [key, date] = pick.dataset.pick.split('|');
        selected = { key, date: fromIso(date) };
        cursor = fromIso(date);
        return renderAll();
      }
      const btn = e.target.closest('[data-remind]');
      if (btn) toggleReminder(btn.dataset.remind);
    });

    $('reminderList').addEventListener('click', (e) => {
      const sw = e.target.closest('[data-switch]');
      if (sw) toggleReminder(sw.dataset.switch);
    });

    $('leadTime').addEventListener('change', (e) => { reminders.lead = e.target.value; saveReminders(); });

    selected = nextOccurrence(today);
    renderAll();
  </script>
</body>
</html>

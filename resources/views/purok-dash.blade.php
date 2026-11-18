@php
  // ---------------------------------------------------------------------------
  // SAMPLE DATA for the view-only prototype. Later, pass these from a controller
  // (filtered to the leader's purok) and delete this block. Keep the same keys.
  // ---------------------------------------------------------------------------
  $leader = $leader ?? ['name' => 'Ana Santos', 'initials' => 'AS', 'purok' => 'Purok 3'];
  $puroks = ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5', 'Purok 6', 'Purok 7'];

  $hour = now()->hour; // set 'timezone' => 'Asia/Manila' in config/app.php so this matches local time
  $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');

  $stats = $stats ?? [
    ['label' => 'Total Reports',       'value' => 24, 'icon' => 'fa-regular fa-file-lines',     'tone' => 'primary', 'trend' => ['up', '12%'],  'note' => 'vs. last month'],
    ['label' => 'Pending Reports',     'value' => 8,  'icon' => 'fa-regular fa-hourglass-half', 'tone' => 'warning', 'trend' => ['warn', '5%'], 'note' => 'vs. last month'],
    ['label' => 'Resolved Reports',    'value' => 16, 'icon' => 'fa-regular fa-circle-check',   'tone' => 'primary', 'trend' => ['up', '30%'],  'note' => 'vs. last month'],
    ['label' => 'Upcoming Activities', 'value' => 3,  'icon' => 'fa-regular fa-calendar-check', 'tone' => 'info',    'trend' => null,           'note' => 'Next 7 days'],
  ];

  // severity: high | medium | low. x/y place the pin on the sample map (0-320, 0-300).
  $hotspots = $hotspots ?? [
    ['x' => 118, 'y' => 78,  'severity' => 'high',   'label' => 'Accumulated garbage near the creek'],
    ['x' => 214, 'y' => 128, 'severity' => 'high',   'label' => 'Open burning in the vacant lot'],
    ['x' => 82,  'y' => 168, 'severity' => 'medium', 'label' => 'Illegal dumping behind the market'],
    ['x' => 178, 'y' => 198, 'severity' => 'medium', 'label' => 'Overflowing trash bin'],
    ['x' => 252, 'y' => 70,  'severity' => 'low',    'label' => 'Plastic waste on the roadside'],
    ['x' => 142, 'y' => 238, 'severity' => 'low',    'label' => 'Scattered litter'],
  ];

  $thumbs = [
    'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&q=80&w=100',
    'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&q=80&w=100',
    'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&q=80&w=100',
  ];

  // status: pending | progress | resolved
  $reports = $reports ?? [
    ['title' => 'Accumulated garbage near the creek', 'zone' => 'Zone 4', 'status' => 'pending',  'date' => 'Apr 28, 2025', 'photo' => $thumbs[0]],
    ['title' => 'Illegal dumping behind the market',  'zone' => 'Zone 2', 'status' => 'progress', 'date' => 'Apr 27, 2025', 'photo' => $thumbs[1]],
    ['title' => 'Plastic waste on the roadside',      'zone' => 'Zone 1', 'status' => 'resolved', 'date' => 'Apr 26, 2025', 'photo' => $thumbs[2]],
    ['title' => 'Open burning in the vacant lot',     'zone' => 'Zone 3', 'status' => 'pending',  'date' => 'Apr 24, 2025', 'photo' => $thumbs[0]],
    ['title' => 'Overflowing trash bin',              'zone' => 'Zone 5', 'status' => 'resolved', 'date' => 'Apr 22, 2025', 'photo' => $thumbs[1]],
  ];
  $statusLabels = ['pending' => 'Pending', 'progress' => 'In Progress', 'resolved' => 'Resolved'];

  $activities = $activities ?? [
    ['title' => 'Brgy. Cleanup Drive',   'when' => 'Apr 30, 2025 • Zone 2', 'status' => 'confirmed', 'label' => 'Confirmed', 'icon' => 'fa-regular fa-calendar-check'],
    ['title' => 'Coastal Clean-up',      'when' => 'May 5, 2025 • Zone 4',  'status' => 'scheduled', 'label' => 'Scheduled', 'icon' => 'fa-regular fa-calendar-check'],
    ['title' => 'Tree Planting Activity','when' => 'May 10, 2025 • Zone 3', 'status' => 'scheduled', 'label' => 'Scheduled', 'icon' => 'fa-regular fa-calendar-check'],
  ];

  $volunteers = $volunteers ?? ['active' => 12, 'assigned' => 5];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bantay Basura - Purok Leader Dashboard</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/user-dash.css', 'resources/css/purok-dash.css', 'resources/css/custom.css'])
</head>
<body>

  @include('partials.purok-sidebar')

  <div class="main-wrapper">

    {{-- Top Navigation Header --}}
    <header class="top-header">
      <div class="user-greeting">
        <div class="avatar-initials">{{ $leader['initials'] }}</div>
        <div class="greeting-text">
          <h2>{{ $greeting }}, {{ $leader['name'] }}!</h2>
          <p>Purok Leader &ndash; <span data-purok>{{ $leader['purok'] }}</span></p>
        </div>
      </div>

      <div class="top-actions">
        <button class="notification-btn" aria-label="Notifications">
          <i class="fa-regular fa-bell"></i>
          <span class="notification-dot"></span>
        </button>

        <div class="profile-pill">
          <div class="avatar-initials">{{ $leader['initials'] }}</div>
          <div class="profile-info">
            <div class="name">{{ $leader['name'] }}</div>
            <div class="role">Purok Leader</div>
          </div>
          <i class="fa-solid fa-chevron-down dropdown-icon"></i>
        </div>
      </div>
    </header>

    <main class="dashboard-content">

      {{-- Title + purok switcher --}}
      <div class="pl-title-row">
        <div class="page-title">
          <h1>Purok Leader Dashboard</h1>
          <p>Monitor waste reports, coordinate cleanup activities, and keep your purok clean and green.</p>
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

      {{-- ================= Stat cards ================= --}}
      <section class="stats-grid four">
        @foreach ($stats as $s)
          <div class="stat-card">
            <div class="stat-icon {{ $s['tone'] }}">
              <i class="{{ $s['icon'] }}"></i>
            </div>
            <div class="stat-info">
              <div class="title">{{ $s['label'] }}</div>
              <div class="count">{{ $s['value'] }}</div>
              <div class="subtitle">
                @if ($s['trend'])
                  <span class="trend {{ $s['trend'][0] }}"><i class="fa-solid fa-arrow-up"></i> {{ $s['trend'][1] }}</span>
                @endif
                {{ $s['note'] }}
              </div>
            </div>
          </div>
        @endforeach
      </section>

      {{-- ================= Map + reports + side ================= --}}
      <section class="pl-grid">

        {{-- Hotspot map --}}
        <div class="column-card">
          <div class="column-header">
            <h3>Waste Hotspot Map</h3>
          </div>

          <div class="map-wrap">
            <svg class="hotspot-map" viewBox="0 0 320 300" role="img" aria-label="Map of waste hotspots in your purok" xmlns="http://www.w3.org/2000/svg">
              <rect width="320" height="300" fill="#eef0e0"/>
              {{-- water + green areas --}}
              <path d="M0 214 C40 200 70 232 100 252 C124 268 150 282 176 300 L0 300 Z" fill="#cfe3ea"/>
              <path d="M222 0 L320 0 L320 96 C290 88 262 70 246 44 C238 28 230 12 222 0 Z" fill="#cfe0b8"/>
              <path d="M24 38 C46 24 82 28 96 48 C106 64 92 88 66 90 C40 92 14 72 24 38 Z" fill="#d7e6c2"/>
              {{-- roads --}}
              <g fill="none" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round">
                <path d="M-10 120 C60 112 120 136 190 120 C240 108 290 118 330 108" stroke-width="9"/>
                <path d="M96 -10 C104 60 92 120 108 180 C118 224 150 262 160 310" stroke-width="9"/>
                <path d="M210 -10 C206 40 226 80 220 130 C216 170 236 230 250 310" stroke-width="7"/>
                <path d="M-10 186 C50 178 120 196 180 186 C230 178 280 190 330 182" stroke-width="6"/>
                <path d="M150 20 C170 60 176 80 210 98" stroke-width="4"/>
                <path d="M30 140 C50 170 60 200 70 230" stroke-width="4"/>
              </g>
              <g fill="none" stroke="#d9dcc8" stroke-width="1" stroke-dasharray="3 4">
                <path d="M-10 120 C60 112 120 136 190 120 C240 108 290 118 330 108"/>
                <path d="M96 -10 C104 60 92 120 108 180 C118 224 150 262 160 310"/>
              </g>
              {{-- pins --}}
              @foreach ($hotspots as $h)
                <g class="pin {{ $h['severity'] }}" transform="translate({{ $h['x'] }} {{ $h['y'] }})" tabindex="0">
                  <title>{{ $h['label'] }} ({{ ucfirst($h['severity']) }})</title>
                  <path d="M0 0c-6-9-11-13-11-19a11 11 0 1 1 22 0c0 6-5 10-11 19z"/>
                  <circle cx="0" cy="-19" r="4" fill="#fff"/>
                </g>
              @endforeach
            </svg>

            <ul class="map-legend" aria-label="Map legend">
              <li><span class="dot high"></span> High</li>
              <li><span class="dot medium"></span> Medium</li>
              <li><span class="dot low"></span> Low</li>
            </ul>

            <a href="#" class="map-btn">View Full Map <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </div>

        {{-- Recent reports --}}
        <div class="column-card">
          <div class="column-header">
            <h3>Recent Waste Reports (<span data-purok>{{ $leader['purok'] }}</span>)</h3>
            <a href="#" class="view-all">View All</a>
          </div>

          <div class="table-scroll">
            <table class="report-table">
              <thead>
                <tr>
                  <th scope="col">Report</th>
                  <th scope="col">Location</th>
                  <th scope="col">Status</th>
                  <th scope="col">Date</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($reports as $r)
                  <tr>
                    <td>
                      <div class="rt-report">
                        <img src="{{ $r['photo'] }}" alt="" class="report-thumb" onerror="this.src='https://placehold.co/100x100/1e3a17/fff9a2?text=Issue'">
                        <span class="rt-title">{{ $r['title'] }}</span>
                      </div>
                    </td>
                    <td class="rt-muted">{{ $r['zone'] }}</td>
                    <td><span class="badge {{ $r['status'] }}">{{ $statusLabels[$r['status']] }}</span></td>
                    <td class="rt-muted rt-date">{{ $r['date'] }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        {{-- Right column --}}
        <div class="pl-side">

          <div class="column-card">
            <div class="column-header">
              <h3>Upcoming Cleanup Activities</h3>
              <a href="#" class="view-all">View All</a>
            </div>
            <div class="list-items">
              @foreach ($activities as $a)
                <div class="list-item">
                  <div class="item-main">
                    <div class="circle-icon"><i class="{{ $a['icon'] }}"></i></div>
                    <div class="item-details">
                      <div class="item-title">{{ $a['title'] }}</div>
                      <div class="item-subtext">{{ $a['when'] }}</div>
                    </div>
                  </div>
                  <span class="badge {{ $a['status'] }}">{{ $a['label'] }}</span>
                </div>
              @endforeach
            </div>
          </div>

          <div class="column-card">
            <div class="column-header">
              <h3>Volunteer Overview</h3>
              <a href="#" class="view-all">View All</a>
            </div>
            <div class="vol-stats">
              <div class="vol-stat">
                <span class="vol-icon"><i class="fa-solid fa-users"></i></span>
                <div><strong>{{ $volunteers['active'] }}</strong><span>Active Volunteers</span></div>
              </div>
              <div class="vol-stat">
                <span class="vol-icon"><i class="fa-solid fa-people-group"></i></span>
                <div><strong>{{ $volunteers['assigned'] }}</strong><span>Assigned to Tasks</span></div>
              </div>
            </div>
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

    // Purok switcher: updates the labels on the page (sample data stays the same for now)
    document.getElementById('purokSelect').addEventListener('change', function (e) {
      document.querySelectorAll('[data-purok]').forEach(function (el) { el.textContent = e.target.value; });
    });
  </script>
</body>
</html>

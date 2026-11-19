@php
  // Placeholder data for the view-only stage.
  // Later, pass $reports from your controller (only the logged-in resident's reports) and delete this block.
  $reports = $reports ?? [
    [
      'id' => 'BB-0003',
      'title' => 'Plastic waste on the sidewalk',
      'category' => 'Plastic waste',
      'date' => 'Apr 27, 2025',
      'time' => '8:14 AM',
      'location' => 'Zone 2',
      'lat' => '10.315699',
      'lng' => '123.885437',
      'status' => 'pending',
      'photo' => 'https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&q=80&w=600',
      'description' => 'A large pile of plastic bottles and wrappers has been left along the sidewalk near the corner store. It is spilling onto the walkway and people have to step into the road to get around it.',
      'note' => 'Your report is waiting for verification by the barangay team.',
    ],
    [
      'id' => 'BB-0002',
      'title' => 'Broken trash bin',
      'category' => 'Broken trash bin',
      'date' => 'Apr 22, 2025',
      'time' => '4:40 PM',
      'location' => 'Zone 1',
      'lat' => '10.317120',
      'lng' => '123.890210',
      'status' => 'resolved',
      'photo' => 'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&q=80&w=600',
      'description' => 'The public bin near the basketball court has a cracked side and no lid. Trash is falling out whenever it is windy.',
      'note' => 'Resolved on Apr 24, 2025. The bin was replaced.',
    ],
    [
      'id' => 'BB-0001',
      'title' => 'Illegal dumping',
      'category' => 'Illegal dumping',
      'date' => 'Apr 15, 2025',
      'time' => '6:05 AM',
      'location' => 'Zone 3',
      'lat' => '10.312884',
      'lng' => '123.881960',
      'status' => 'resolved',
      'photo' => 'https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&q=80&w=600',
      'description' => 'Bags of household garbage and old furniture were dumped beside the drainage canal overnight.',
      'note' => 'Resolved on Apr 18, 2025. The area was cleared.',
    ],
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bantay Basura - My Reports</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/user-dash.css', 'resources/css/user-my-reports.css', 'resources/css/custom.css'])
</head>
<body>
    @include('partials.sidebar')

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

      <div class="page-title">
        <h1>My Reports</h1>
        <p>Every waste issue you've reported. Select a report to see its full details.</p>
      </div>

      <section class="reports-card">

        {{-- Column labels (desktop only) --}}
        <div class="reports-head" aria-hidden="true">
          <span>Report</span>
          <span>Date</span>
          <span>Location</span>
          <span>Status</span>
          <span></span>
        </div>

        @forelse ($reports as $i => $r)
          <article class="report-row">
            <button type="button" class="report-toggle" aria-expanded="false" aria-controls="report-panel-{{ $i }}" id="report-btn-{{ $i }}">
              <span class="cell cell-title">{{ $r['title'] }}</span>
              <span class="cell cell-date"><i class="fa-regular fa-calendar"></i>{{ $r['date'] }}</span>
              <span class="cell cell-location"><i class="fa-solid fa-location-dot"></i>{{ $r['location'] }}</span>
              <span class="cell cell-status">
                <span class="badge {{ $r['status'] }}">{{ $r['status'] === 'resolved' ? 'Resolved' : 'Pending' }}</span>
              </span>
              <span class="cell cell-chevron"><i class="fa-solid fa-chevron-down"></i></span>
            </button>

            <div class="report-panel" id="report-panel-{{ $i }}" role="region" aria-labelledby="report-btn-{{ $i }}">
              <div class="report-panel-inner">
                <div class="report-detail">
                  <img src="{{ $r['photo'] }}" alt="Photo for {{ $r['title'] }}" class="detail-photo" onerror="this.src='https://placehold.co/600x400/1e3a17/fff9a2?text=Photo'">

                  <div class="detail-body">
                    <dl class="detail-grid">
                      <div>
                        <dt>Report ID</dt>
                        <dd>{{ $r['id'] }}</dd>
                      </div>
                      <div>
                        <dt>Category</dt>
                        <dd>{{ $r['category'] }}</dd>
                      </div>
                      <div>
                        <dt>Submitted</dt>
                        <dd>{{ $r['date'] }}, {{ $r['time'] }}</dd>
                      </div>
                      <div>
                        <dt>GPS location</dt>
                        <dd>
                          {{ $r['lat'] }}, {{ $r['lng'] }}
                          <a href="https://www.google.com/maps?q={{ $r['lat'] }},{{ $r['lng'] }}" target="_blank" rel="noopener" class="map-link">View on map</a>
                        </dd>
                      </div>
                    </dl>

                    <div class="detail-description">
                      <h4>Description</h4>
                      <p>{{ $r['description'] }}</p>
                    </div>

                    <div class="detail-note {{ $r['status'] }}">
                      <i class="{{ $r['status'] === 'resolved' ? 'fa-regular fa-circle-check' : 'fa-regular fa-hourglass-half' }}"></i>
                      <span>{{ $r['note'] }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </article>
        @empty
          <div class="reports-empty">
            <i class="fa-regular fa-clipboard"></i>
            <h3>No reports yet</h3>
            <p>When you report a waste issue, it will show up here.</p>
            <a href="#" class="btn-report"><i class="fa-solid fa-plus"></i> Report a Waste Issue</a>
          </div>
        @endforelse

      </section>

    </main>
  </div>

  <script>
    // Mobile menu
    document.getElementById('menuToggle').addEventListener('click', function () {
      document.getElementById('navList').classList.toggle('active');
    });

    // Report drop-downs (one open at a time)
    const toggles = document.querySelectorAll('.report-toggle');

    function setOpen(btn, open) {
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      btn.closest('.report-row').classList.toggle('open', open);
    }

    toggles.forEach((btn) => {
      btn.addEventListener('click', () => {
        const willOpen = btn.getAttribute('aria-expanded') !== 'true';
        toggles.forEach((other) => setOpen(other, false));
        setOpen(btn, willOpen);
      });
    });
  </script>
</body>
</html>

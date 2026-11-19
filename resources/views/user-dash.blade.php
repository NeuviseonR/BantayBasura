<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bantay Basura - Resident Dashboard</title>

  <!-- Google Font: Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Font Awesome Icons -->
  @vite(['resources/css/user-dash.css', 'resources/css/custom.css'])
</head>
<body>

    @include('partials.sidebar')

  <div class="main-wrapper">

    {{-- Top Navigation Header --}}
    <header class="top-header">
      <div class="user-greeting">
        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=120" alt="Maria Lopez" class="avatar-img" onerror="this.src='https://placehold.co/80x80/1e3a17/fff9a2?text=ML'">
        <div class="greeting-text">
          <h2>Hello, {{ auth()->user()->name }}!</h2>
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
            <div class="name">{{ auth()->user()->name }}</div>
            <div class="role">Resident</div>
          </div>
          <i class="fa-solid fa-chevron-down dropdown-icon"></i>
        </div>
      </div>
    </header>

    <!-- Main Dashboard Body -->
    <main class="dashboard-content">

        <div class="user-dash-tabs">
            <a href="{{ route('user-dash') }}"><div class="active-tab">Home</div></a>
            <a href="{{ route('user-collection-schedule') }}"><div class="dash-tab">Collection Schedule</div></a>
        </div>
      <!-- Hero Callout Banner -->
      <section class="hero-banner">
        <i class="fa-solid fa-leaf hero-leaf-overlay"></i>
        <div class="hero-content">
          <div class="hero-header">
            <i class="fa-solid fa-seedling hero-icon-sprout"></i>
            <div>
              <h2>See something, say something!</h2>
              <p>Help keep our community clean by reporting waste issues in your area.</p>
            </div>
          </div>
          <a href="{{ route('user-report') }}" class="btn-report">
            <i class="fa-solid fa-plus"></i> Report a Waste Issue
          </a>
        </div>

        <!-- Custom SVG Graphic -->
        <div class="hero-graphic">
          <svg class="hero-illustration" viewBox="0 0 200 160" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Trash Bin -->
            <rect x="135" y="60" width="45" height="75" rx="4" fill="rgb(30, 58, 23)"/>
            <rect x="130" y="52" width="55" height="10" rx="3" fill="rgb(17, 46, 17)"/>
            <path d="M152 75 L163 75 L163 115 L152 115 Z" fill="none" stroke="rgb(255, 249, 162)" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M157 70 L157 120" fill="none" stroke="rgb(255, 249, 162)" stroke-width="2" stroke-dasharray="3 3"/>
            <!-- Recycling Arrow Symbol on Bin -->
            <path d="M157 88 L163 96 L151 96 Z" fill="rgb(243, 202, 67)"/>
            <path d="M151 102 L157 110 L163 102 Z" fill="rgb(243, 202, 67)"/>

            <!-- Character Vector -->
            <path d="M72 25 C65 25 55 35 55 45 C55 52 60 58 60 58 L78 58 C78 58 82 45 80 35 C78 25 75 25 72 25 Z" fill="rgb(17, 46, 17)"/>
            <circle cx="70" cy="38" r="10" fill="rgb(255, 249, 162)"/>
            <path d="M52 60 L85 55 L95 95 L55 98 Z" fill="rgb(216, 119, 39)"/>
            <path d="M55 98 L70 145 L82 145 L85 95 Z" fill="rgb(30, 58, 23)"/>
            <path d="M82 65 L115 85 L125 95" stroke="rgb(255, 249, 162)" stroke-width="7" stroke-linecap="round"/>
            <path d="M110 85 C100 95 105 120 120 120 C135 120 135 95 125 85 Z" fill="rgb(17, 46, 17)"/>
          </svg>
        </div>
      </section>

      <!-- Stat Cards Grid -->
      <section class="stats-grid">
        <!-- Card 1 -->
        <div class="stat-card">
          <div class="stat-icon primary">
            <i class="fa-regular fa-file-lines"></i>
          </div>
          <div class="stat-info">
            <div class="title">My Reports</div>
            <div class="count">3</div>
            <div class="subtitle">Total Reports</div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="stat-card">
          <div class="stat-icon secondary">
            <i class="fa-regular fa-hourglass-half"></i>
          </div>
          <div class="stat-info">
            <div class="title">Pending</div>
            <div class="count">1</div>
            <div class="subtitle">Awaiting Verification</div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="stat-card">
          <div class="stat-icon primary">
            <i class="fa-regular fa-circle-check"></i>
          </div>
          <div class="stat-info">
            <div class="title">Resolved</div>
            <div class="count">2</div>
            <div class="subtitle">Completed</div>
          </div>
        </div>
      </section>

      <!-- Bottom Columns (3 Column Layout) -->
      <section class="content-columns">

        <!-- Column 1: My Recent Reports -->
        <div class="column-card">
          <div class="column-header">
            <h3>My Recent Reports</h3>
            <a href="#" class="view-all">View All</a>
          </div>
          <div class="list-items">
            <!-- Item 1 -->
            <div class="list-item">
              <div class="item-main">
                <img src="https://images.unsplash.com/photo-1611284446314-60a58ac0deb9?auto=format&fit=crop&q=80&w=100" alt="Plastic Waste" class="report-thumb" onerror="this.src='https://placehold.co/100x100/1e3a17/fff9a2?text=Issue'">
                <div class="item-details">
                  <div class="item-title">Plastic waste on the sidewalk</div>
                  <div class="item-subtext">Zone 2 • Apr 27, 2025</div>
                </div>
              </div>
              <span class="badge pending">Pending</span>
            </div>

            <!-- Item 2 -->
            <div class="list-item">
              <div class="item-main">
                <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&q=80&w=100" alt="Broken Trash Bin" class="report-thumb" onerror="this.src='https://placehold.co/100x100/1e3a17/fff9a2?text=Issue'">
                <div class="item-details">
                  <div class="item-title">Broken trash bin</div>
                  <div class="item-subtext">Zone 1 • Apr 22, 2025</div>
                </div>
              </div>
              <span class="badge resolved">Resolved</span>
            </div>

            <!-- Item 3 -->
            <div class="list-item">
              <div class="item-main">
                <img src="https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&q=80&w=100" alt="Illegal Dumping" class="report-thumb" onerror="this.src='https://placehold.co/100x100/1e3a17/fff9a2?text=Issue'">
                <div class="item-details">
                  <div class="item-title">Illegal dumping</div>
                  <div class="item-subtext">Zone 3 • Apr 15, 2025</div>
                </div>
              </div>
              <span class="badge resolved">Resolved</span>
            </div>
          </div>
        </div>

        <!-- Column 2: Collection Schedule -->
        <div class="column-card">
          <div class="column-header">
            <h3>Collection Schedule</h3>
            <a href="#" class="view-all">View All</a>
          </div>
          <div class="list-items">
            <div class="list-item">
              <div class="item-main">
                <div class="circle-icon">
                  <i class="fa-solid fa-trash-can"></i>
                </div>
                <div class="item-details">
                  <div class="item-title">General Waste</div>
                  <div class="item-subtext">Every Monday & Thursday</div>
                </div>
              </div>
            </div>

            <div class="list-item">
              <div class="item-main">
                <div class="circle-icon">
                  <i class="fa-solid fa-recycle"></i>
                </div>
                <div class="item-details">
                  <div class="item-title">Recyclables</div>
                  <div class="item-subtext">Every Tuesday</div>
                </div>
              </div>
            </div>

            <div class="list-item">
              <div class="item-main">
                <div class="circle-icon">
                  <i class="fa-solid fa-apple-whole"></i>
                </div>
                <div class="item-details">
                  <div class="item-title">Biodegradable</div>
                  <div class="item-subtext">Every Saturday</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Column 3: Upcoming Cleanup Drives -->
        <div class="column-card">
          <div class="column-header">
            <h3>Upcoming Cleanup Drives</h3>
            <a href="#" class="view-all">View All</a>
          </div>
          <div class="list-items">
            <div class="list-item">
              <div class="item-main">
                <div class="circle-icon">
                  <i class="fa-regular fa-calendar-check"></i>
                </div>
                <div class="item-details">
                  <div class="item-title">Community Clean-up</div>
                  <div class="item-subtext">Apr 30, 2025 • Zone 2</div>
                </div>
              </div>
            </div>

            <div class="list-item">
              <div class="item-main">
                <div class="circle-icon">
                  <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div class="item-details">
                  <div class="item-title">Beach Clean-up</div>
                  <div class="item-subtext">May 5, 2025 • Zone 4</div>
                </div>
              </div>
            </div>

            <div class="list-item">
              <div class="item-main">
                <div class="circle-icon">
                  <i class="fa-solid fa-tree"></i>
                </div>
                <div class="item-details">
                  <div class="item-title">Tree Planting</div>
                  <div class="item-subtext">May 10, 2025 • Zone 3</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

    </main>
  </div>

  <script>
    document.getElementById('menuToggle').addEventListener('click', function() {
      document.getElementById('navList').classList.toggle('active');
    });
  </script>
</body>
</html>

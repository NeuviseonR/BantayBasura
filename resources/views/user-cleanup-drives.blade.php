@php
  // ---------------------------------------------------------------------------
  // SAMPLE DATA for the view-only prototype (non-volunteer resident's point of view).
  // Names, partners and numbers are placeholders. Later, pass $drives and $levels
  // from your controller and delete this block. Keep the same keys.
  //   level: 0 = open to everyone, 1 = verified volunteer, 2 = team leader
  // ---------------------------------------------------------------------------
  $levels = $levels ?? [
    ['level' => 0, 'name' => 'Resident',            'desc' => 'Join drives that are open to everyone.'],
    ['level' => 1, 'name' => 'Verified Volunteer',  'desc' => 'ID verified by the barangay. Can join volunteer-only drives.'],
    ['level' => 2, 'name' => 'Team Leader',         'desc' => 'Completed 3 drives and safety training. Leads groups and joins specialized drives.'],
    ['level' => 3, 'name' => 'Drive Coordinator',   'desc' => 'Appointed by the barangay to organize and manage drives.'],
  ];

  $drives = $drives ?? [
    [
      'id' => 1, 'title' => 'Community Clean-up', 'icon' => 'fa-solid fa-broom', 'level' => 0,
      'date' => ['mon' => 'Apr', 'day' => '30', 'full' => 'Wednesday, April 30, 2025'], 'time' => '6:00 AM - 9:00 AM',
      'zone' => 'Zone 2', 'meet' => 'Zone 2 Covered Court',
      'goal' => 'Clear the streets, canal edges and vacant lots of Zone 2 and fill about 100 sacks of waste, sorted on the spot into biodegradable, recyclable and general.',
      'organizer' => ['name' => 'Elena Reyes', 'role' => 'Solid Waste Officer'],
      'leads' => [['name' => 'Carlo Mendoza', 'role' => 'Zone 2 Patrol Lead']],
      'partners' => ['Zone 2 Homeowners Association', 'Barangay Youth Council'],
      'slots' => 60, 'joined' => 38,
      'requirements' => ['Open to all residents aged 12 and up (under 15 must come with a guardian)', 'Arrive 15 minutes early to sign in', 'Bring a water bottle, a cap and closed shoes', 'Gloves and bags are provided'],
    ],
    [
      'id' => 2, 'title' => 'Beach Clean-up', 'icon' => 'fa-solid fa-umbrella-beach', 'level' => 1,
      'date' => ['mon' => 'May', 'day' => '5', 'full' => 'Monday, May 5, 2025'], 'time' => '5:30 AM - 8:30 AM',
      'zone' => 'Zone 4', 'meet' => 'Coastal Road entrance',
      'goal' => 'Clear the shoreline of plastics and record what we find (type and brand) so the barangay can report the data and push for source reduction.',
      'organizer' => ['name' => 'Teresa Villanueva', 'role' => 'Kagawad, Environment'],
      'leads' => [['name' => 'Jun Dela Cruz', 'role' => 'Barangay Secretary'], ['name' => 'Carlo Mendoza', 'role' => 'Safety marshal']],
      'partners' => ['Coastal Guardians (sample)', 'Fisherfolk Association'],
      'slots' => 40, 'joined' => 31,
      'requirements' => ['Verified Volunteer (Level 1) or higher', 'Ages 16 and up (16 to 17 need guardian consent on file)', 'Closed water shoes and sunscreen', 'Safety and tide briefing at 5:30 AM sharp'],
    ],
    [
      'id' => 3, 'title' => 'Tree Planting', 'icon' => 'fa-solid fa-tree', 'level' => 0,
      'date' => ['mon' => 'May', 'day' => '10', 'full' => 'Saturday, May 10, 2025'], 'time' => '7:00 AM - 10:00 AM',
      'zone' => 'Zone 3', 'meet' => 'Drainage canal footbridge',
      'goal' => 'Plant 150 native seedlings along the canal buffer strip to reduce flooding and keep the banks from becoming a dumping spot.',
      'organizer' => ['name' => 'Teresa Villanueva', 'role' => 'Kagawad, Environment'],
      'leads' => [['name' => 'Roberto Santos', 'role' => 'Barangay Captain']],
      'partners' => ['Green Youth Club', 'Zone 3 Elementary School'],
      'slots' => 80, 'joined' => 52,
      'requirements' => ['Open to all residents', 'Wear old clothes and closed shoes', 'Bring a cap and a water bottle', 'Seedlings and tools are provided'],
    ],
    [
      'id' => 4, 'title' => 'Drainage Canal Clearing', 'icon' => 'fa-solid fa-water', 'level' => 2,
      'date' => ['mon' => 'May', 'day' => '17', 'full' => 'Saturday, May 17, 2025'], 'time' => '6:00 AM - 11:00 AM',
      'zone' => 'Zones 1 and 3', 'meet' => 'Barangay Hall parking lot',
      'goal' => 'Remove silt and blockages from the main drainage canals before the rainy season to prevent street flooding.',
      'organizer' => ['name' => 'Roberto Santos', 'role' => 'Barangay Captain'],
      'leads' => [['name' => 'Carlo Mendoza', 'role' => 'Zone 2 Patrol Lead']],
      'partners' => ['Barangay DRRM Team'],
      'slots' => 20, 'joined' => 12,
      'requirements' => ['Team Leader (Level 2) only', 'Safety training certificate on file', 'Ages 18 and up', 'Bring rubber boots. Gloves and masks are provided', 'A tetanus shot is strongly recommended'],
    ],
    [
      'id' => 5, 'title' => 'Recycling Collection Day', 'icon' => 'fa-solid fa-recycle', 'level' => 0,
      'date' => ['mon' => 'May', 'day' => '24', 'full' => 'Saturday, May 24, 2025'], 'time' => '8:00 AM - 12:00 PM',
      'zone' => 'Zone 2', 'meet' => 'Zone 2 Covered Court',
      'goal' => 'Collect clean recyclables from households and sell them to the sorting center. Proceeds go toward new public bins.',
      'organizer' => ['name' => 'Elena Reyes', 'role' => 'Solid Waste Officer'],
      'leads' => [['name' => 'Jun Dela Cruz', 'role' => 'Barangay Secretary']],
      'partners' => ['Zone 2 Women\'s Association'],
      'slots' => 30, 'joined' => 14,
      'requirements' => ['Open to all residents', 'Bring your own clean, sorted recyclables, or help at the sorting booth', 'No glass shards, food-soiled paper or styrofoam'],
    ],
  ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bantay Basura - Cleanup Drives</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/user-dash.css', 'resources/css/user-cleanup-drives.css', 'resources/css/custom.css'])
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
        <h1>Cleanup Drives</h1>
        <p>Find a drive in your barangay, see what's needed, and join in.</p>
      </div>

      <section class="cd-layout">

        {{-- ================= Drive list ================= --}}
        <div class="cd-main">
          <div class="filter-tabs" role="tablist" id="filterTabs">
            <button type="button" role="tab" data-filter="all" aria-selected="true">All drives</button>
            <button type="button" role="tab" data-filter="open" aria-selected="false">Open to everyone</button>
            <button type="button" role="tab" data-filter="volunteer" aria-selected="false">Volunteers only</button>
            <button type="button" role="tab" data-filter="joined" aria-selected="false">I'm joining</button>
          </div>

          <div class="drive-list" id="driveList"></div>
        </div>

        {{-- ================= Side column ================= --}}
        <div class="cd-side">

          <div class="cd-card" id="statusCard" aria-live="polite"></div>

          <div class="cd-card">
            <div class="cd-title">
              <span class="cd-icon"><i class="fa-solid fa-ranking-star"></i></span>
              <h3>Volunteer levels</h3>
            </div>
            <ol class="level-list" id="levelList"></ol>
          </div>

        </div>
      </section>
    </main>
  </div>

  {{-- ================= Drive details drawer ================= --}}
  <div class="drawer" id="drawer" hidden>
    <div class="drawer-backdrop" data-drawer-close></div>
    <aside class="drawer-panel" id="drawerPanel" role="dialog" aria-modal="true" aria-labelledby="drawerTitle"></aside>
  </div>

  {{-- ================= Modals ================= --}}
  <div class="modal" id="modal" hidden>
    <div class="modal-backdrop" data-modal-close></div>

    {{-- Prompt: volunteer registration needed --}}
    <div class="modal-dialog" id="promptDialog" role="dialog" aria-modal="true" aria-labelledby="promptTitle" hidden></div>

    {{-- Volunteer registration --}}
    <div class="modal-dialog wide" id="regDialog" role="dialog" aria-modal="true" aria-labelledby="regTitle" hidden>
      <header class="reg-head">
        <div>
          <h3 id="regTitle">Volunteer registration</h3>
          <p id="regFor" class="reg-for"></p>
        </div>
        <button type="button" class="icon-btn" data-modal-close aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
      </header>

      <ol class="stepper" id="stepper" aria-label="Registration steps">
        <li class="is-current"><span>1</span> About you</li>
        <li><span>2</span> Identity</li>
        <li><span>3</span> Organization &amp; safety</li>
      </ol>

      <form id="regForm" novalidate>
        @csrf

        {{-- Step 1 --}}
        <div class="reg-step" data-step="1">
          <div class="reg-grid">
            <div class="field"><label for="rFirst">First name</label><input id="rFirst" type="text" value="Maria" autocomplete="given-name" data-req></div>
            <div class="field"><label for="rMiddle">Middle name <small>(optional)</small></label><input id="rMiddle" type="text" autocomplete="additional-name"></div>
            <div class="field"><label for="rLast">Last name</label><input id="rLast" type="text" value="Lopez" autocomplete="family-name" data-req></div>
            <div class="field"><label for="rBirth">Date of birth</label><input id="rBirth" type="date" data-req></div>
            <div class="field">
              <label for="rSex">Sex</label>
              <div class="select-wrap">
                <select id="rSex" data-req>
                  <option value="" selected disabled>Select</option>
                  <option>Female</option><option>Male</option><option>Prefer not to say</option>
                </select><i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>
            <div class="field"><label for="rPhone">Mobile number</label><input id="rPhone" type="tel" placeholder="09XX XXX XXXX" autocomplete="tel" data-req></div>
            <div class="field wide"><label for="rEmail">Email</label><input id="rEmail" type="email" value="maria.lopez@example.com" autocomplete="email" data-req></div>
            <div class="field">
              <label for="rZone">Zone</label>
              <div class="select-wrap">
                <select id="rZone" data-req>
                  <option>Zone 1</option><option selected>Zone 2</option><option>Zone 3</option><option>Zone 4</option>
                </select><i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>
            <div class="field"><label for="rAddress">Purok / street address</label><input id="rAddress" type="text" value="Purok 3" data-req></div>
          </div>

          <div class="guardian-box" id="guardianBox" hidden>
            <p><i class="fa-solid fa-circle-info"></i> Volunteers under 18 need a parent or guardian's consent.</p>
            <div class="reg-grid">
              <div class="field"><label for="rGuardName">Parent / guardian name</label><input id="rGuardName" type="text" data-req></div>
              <div class="field"><label for="rGuardPhone">Parent / guardian mobile</label><input id="rGuardPhone" type="tel" data-req></div>
            </div>
          </div>
        </div>

        {{-- Step 2 --}}
        <div class="reg-step" data-step="2" hidden>
          <p class="reg-note"><i class="fa-solid fa-shield-halved"></i> We use your ID only to confirm who you are. Barangay staff will review it, and it is not shown to other residents.</p>
          <div class="reg-grid">
            <div class="field">
              <label for="rIdType">ID type</label>
              <div class="select-wrap">
                <select id="rIdType" data-req>
                  <option value="" selected disabled>Select an ID</option>
                  <option>Barangay ID</option><option>National ID (PhilSys)</option><option>Driver's license</option>
                  <option>Passport</option><option>UMID / SSS</option><option>Student ID</option><option>Voter's ID</option>
                </select><i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>
            <div class="field"><label for="rIdNumber">ID number</label><input id="rIdNumber" type="text" data-req></div>

            <div class="field">
              <label for="rIdFront">ID photo (front)</label>
              <label class="upload" for="rIdFront">
                <input id="rIdFront" type="file" accept="image/*" data-req data-upload>
                <span class="upload-empty"><i class="fa-solid fa-id-card"></i><strong>Add front</strong><small>JPG or PNG</small></span>
                <img class="upload-preview" alt="Front of ID preview" hidden>
              </label>
            </div>
            <div class="field">
              <label for="rIdBack">ID photo (back)</label>
              <label class="upload" for="rIdBack">
                <input id="rIdBack" type="file" accept="image/*" data-upload>
                <span class="upload-empty"><i class="fa-solid fa-id-card"></i><strong>Add back</strong><small>If your ID has one</small></span>
                <img class="upload-preview" alt="Back of ID preview" hidden>
              </label>
            </div>
          </div>
        </div>

        {{-- Step 3 --}}
        <div class="reg-step" data-step="3" hidden>
          <fieldset class="field">
            <legend>Do you belong to an organization?</legend>
            <div class="radio-row">
              <label class="radio"><input type="radio" name="org" value="yes"> Yes</label>
              <label class="radio"><input type="radio" name="org" value="no" checked> No, I'm joining on my own</label>
            </div>
          </fieldset>

          <div class="reg-grid" id="orgBox" hidden>
            <div class="field"><label for="rOrgName">Organization name</label><input id="rOrgName" type="text" data-req></div>
            <div class="field">
              <label for="rOrgType">Type</label>
              <div class="select-wrap">
                <select id="rOrgType">
                  <option>School / university</option><option>Church / faith group</option><option>Company / business</option>
                  <option>Youth group</option><option>Homeowners' association</option><option>NGO / civic group</option><option>Other</option>
                </select><i class="fa-solid fa-chevron-down"></i>
              </div>
            </div>
            <div class="field wide"><label for="rOrgRole">Your position <small>(optional)</small></label><input id="rOrgRole" type="text" placeholder="e.g. Member, Officer"></div>
          </div>

          <fieldset class="field">
            <legend>Skills you can help with <small>(optional)</small></legend>
            <div class="chip-row">
              @foreach (['First aid', 'Heavy lifting', 'Driving', 'Coordinating groups', 'Photography', 'Cooking / food'] as $s)
                <label class="chip-check"><input type="checkbox" value="{{ $s }}"><span>{{ $s }}</span></label>
              @endforeach
            </div>
          </fieldset>

          <fieldset class="field">
            <legend>When are you usually free?</legend>
            <div class="chip-row">
              @foreach (['Weekday mornings', 'Weekday afternoons', 'Saturdays', 'Sundays'] as $s)
                <label class="chip-check"><input type="checkbox" value="{{ $s }}" data-avail><span>{{ $s }}</span></label>
              @endforeach
            </div>
            <span class="err" id="availErr" hidden>Choose at least one.</span>
          </fieldset>

          <div class="reg-grid">
            <div class="field"><label for="rEmName">Emergency contact name</label><input id="rEmName" type="text" data-req></div>
            <div class="field"><label for="rEmPhone">Emergency contact mobile</label><input id="rEmPhone" type="tel" data-req></div>
            <div class="field wide"><label for="rEmRel">Relationship</label><input id="rEmRel" type="text" placeholder="e.g. Spouse, Parent, Sibling" data-req></div>
          </div>

          <div class="consents">
            <label class="check"><input type="checkbox" id="rTerms" data-req> <span>I will follow the safety instructions and the volunteer guidelines during drives.</span></label>
            <label class="check"><input type="checkbox" id="rPrivacy" data-req> <span>I agree that the barangay may store and use my details and ID to verify me as a volunteer.</span></label>
          </div>
        </div>

        <div class="reg-actions">
          <button type="button" class="btn-cancel" id="regBack" hidden>Back</button>
          <button type="button" class="btn-cancel" data-modal-close id="regCancel">Cancel</button>
          <button type="button" class="btn-primary" id="regNext">Next <i class="fa-solid fa-arrow-right"></i></button>
          <button type="submit" class="btn-primary" id="regSubmit" hidden><i class="fa-solid fa-paper-plane"></i> Submit application</button>
        </div>
      </form>

      {{-- Success --}}
      <div class="reg-success" id="regSuccess" hidden>
        <span class="success-icon"><i class="fa-solid fa-circle-check"></i></span>
        <h3>Application submitted</h3>
        <p>Thanks, <strong id="successName">Maria</strong>. The barangay team will check your ID and details, usually within 2 to 3 working days. We'll send the result to your Messages.</p>
        <ul class="success-list">
          <li><i class="fa-regular fa-hourglass-half"></i> Status: <strong>Under review</strong></li>
          <li><i class="fa-solid fa-ranking-star"></i> You'll start as a <strong>Level 1 Verified Volunteer</strong> once approved</li>
        </ul>
        <button type="button" class="btn-primary" data-modal-close>Back to drives</button>
      </div>
    </div>
  </div>

  <div class="toast" id="toast" role="status" aria-live="polite" hidden></div>

  <script>
    // Mobile menu
    document.getElementById('menuToggle').addEventListener('click', function () {
      document.getElementById('navList').classList.toggle('active');
    });

    // =========================================================================
    // Cleanup drives prototype: a resident who is NOT yet a volunteer.
    // Nothing is saved. Reloading the page starts the flow again.
    // =========================================================================
    const DRIVES = @json($drives);
    const LEVELS = @json($levels);

    const $ = (id) => document.getElementById(id);
    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const LV = {
      0: { tag: 'Open to everyone', cls: 'lv-0', icon: 'fa-solid fa-users' },
      1: { tag: 'Volunteers only',  cls: 'lv-1', icon: 'fa-solid fa-lock' },
      2: { tag: 'Team Leaders only', cls: 'lv-2', icon: 'fa-solid fa-lock' },
    };
    const levelName = (n) => (LEVELS.find((l) => l.level === n) || {}).name || '';

    let volunteer = 'none';       // 'none' | 'pending'
    let filter = 'all';
    let activeId = null;
    let pendingDriveId = null;    // drive the resident was trying to join when asked to register
    const joined = new Set();
    let lastFocus = null;

    function toast(msg) {
      const el = $('toast');
      el.textContent = msg;
      el.hidden = false;
      clearTimeout(toast.t);
      toast.t = setTimeout(() => { el.hidden = true; }, 2800);
    }

    const byId = (id) => DRIVES.find((d) => d.id === id);
    const spots = (d) => d.slots - d.joined - (joined.has(d.id) ? 1 : 0);

    // ---------- List ----------
    function renderList() {
      const items = DRIVES.filter((d) => {
        if (filter === 'open') return d.level === 0;
        if (filter === 'volunteer') return d.level > 0;
        if (filter === 'joined') return joined.has(d.id);
        return true;
      });

      if (!items.length) {
        $('driveList').innerHTML = '<div class="list-empty"><i class="fa-solid fa-people-group"></i><p>'
          + (filter === 'joined' ? 'You haven\'t joined any drives yet.' : 'No drives here right now.') + '</p></div>';
        return;
      }

      $('driveList').innerHTML = items.map((d) => {
        const lv = LV[d.level];
        const going = joined.has(d.id);
        const total = d.joined + (going ? 1 : 0);
        const pct = Math.round((total / d.slots) * 100);
        return '<button type="button" class="drive" data-drive="' + d.id + '">'
          + '<span class="date-block"><small>' + esc(d.date.mon) + '</small><strong>' + esc(d.date.day) + '</strong></span>'
          + '<span class="drive-body">'
          +   '<span class="drive-top"><span class="drive-name">' + esc(d.title) + '</span>'
          +   '<span class="tag ' + lv.cls + '"><i class="' + lv.icon + '"></i> ' + lv.tag + '</span></span>'
          +   '<span class="drive-meta"><span><i class="fa-regular fa-clock"></i> ' + esc(d.time) + '</span>'
          +   '<span><i class="fa-solid fa-location-dot"></i> ' + esc(d.zone) + '</span></span>'
          +   '<span class="drive-foot"><span class="mini-bar"><span style="width:' + pct + '%"></span></span>'
          +   '<span class="drive-spots">' + total + ' of ' + d.slots + ' joined</span>'
          +   (going ? '<span class="going"><i class="fa-solid fa-circle-check"></i> You\'re going</span>' : '') + '</span>'
          + '</span><i class="fa-solid fa-chevron-right drive-arrow"></i></button>';
      }).join('');
    }

    // ---------- Side cards ----------
    function renderStatus() {
      const card = $('statusCard');
      if (volunteer === 'pending') {
        card.innerHTML = '<div class="cd-title"><span class="cd-icon pending"><i class="fa-regular fa-hourglass-half"></i></span><h3>Application under review</h3></div>'
          + '<p class="cd-text">We received your volunteer application. You can still join open drives while you wait.</p>'
          + '<div class="status-row"><span>Current level</span><strong>Level 0 - Resident</strong></div>'
          + '<div class="status-row"><span>After approval</span><strong>Level 1 - Verified Volunteer</strong></div>'
          + '<button type="button" class="link-btn" data-reset>Restart demo</button>';
      } else {
        card.innerHTML = '<div class="cd-title"><span class="cd-icon"><i class="fa-regular fa-user"></i></span><h3>Your volunteer status</h3></div>'
          + '<p class="cd-text">You\'re not a volunteer yet. Some drives are open to everyone. Others need a verified volunteer.</p>'
          + '<div class="status-row"><span>Current level</span><strong>Level 0 - Resident</strong></div>'
          + '<button type="button" class="btn-primary block" data-register="">Become a volunteer</button>';
      }

      $('levelList').innerHTML = LEVELS.map((l) => {
        const you = l.level === 0;
        return '<li class="level' + (you ? ' is-you' : '') + '"><span class="level-num">' + l.level + '</span>'
          + '<div><strong>' + esc(l.name) + (you ? ' <em>You are here</em>' : '') + '</strong><p>' + esc(l.desc) + '</p></div></li>';
      }).join('');
    }

    // ---------- Drawer ----------
    function ctaFor(d) {
      if (joined.has(d.id)) {
        return '<div class="cta-joined"><i class="fa-solid fa-circle-check"></i> You\'re going to this drive</div>'
          + '<button type="button" class="link-btn" data-leave="' + d.id + '">Cancel my spot</button>';
      }
      if (d.level === 0) {
        return '<button type="button" class="btn-primary block" data-join="' + d.id + '"><i class="fa-solid fa-hand"></i> Join this drive</button>';
      }
      if (volunteer === 'pending') {
        return '<button type="button" class="btn-primary block" disabled><i class="fa-regular fa-hourglass-half"></i> Waiting for volunteer approval</button>'
          + '<p class="cta-note">You can join once your application is approved' + (d.level === 2 ? ' and you reach Level 2.' : '.') + '</p>';
      }
      return '<button type="button" class="btn-primary block" data-join="' + d.id + '"><i class="fa-solid fa-lock"></i> ' + (d.level === 2 ? 'Team Leaders only' : 'Volunteers only') + ' - learn how to join</button>';
    }

    function openDrawer(id) {
      const d = byId(id);
      if (!d) return;
      activeId = id;
      lastFocus = document.activeElement;
      const lv = LV[d.level];
      const person = (p, icon) => '<li><span class="p-av"><i class="' + icon + '"></i></span><div><strong>' + esc(p.name) + '</strong><small>' + esc(p.role) + '</small></div></li>';
      const pct = Math.round(((d.joined + (joined.has(d.id) ? 1 : 0)) / d.slots) * 100);

      $('drawerPanel').innerHTML =
        '<header class="drawer-head"><div><span class="tag ' + lv.cls + '"><i class="' + lv.icon + '"></i> ' + lv.tag + '</span>'
        + '<h2 id="drawerTitle">' + esc(d.title) + '</h2></div>'
        + '<button type="button" class="icon-btn" data-drawer-close aria-label="Close"><i class="fa-solid fa-xmark"></i></button></header>'
        + '<div class="drawer-body">'
        +   '<div class="info-grid">'
        +     '<div><i class="fa-regular fa-calendar"></i><small>When</small><strong>' + esc(d.date.full) + '</strong><span>' + esc(d.time) + '</span></div>'
        +     '<div><i class="fa-solid fa-location-dot"></i><small>Where</small><strong>' + esc(d.meet) + '</strong><span>' + esc(d.zone) + '</span></div>'
        +   '</div>'
        +   '<section><h3><i class="fa-solid fa-bullseye"></i> Goal</h3><p>' + esc(d.goal) + '</p></section>'
        +   '<section><h3><i class="fa-solid fa-people-group"></i> People involved</h3>'
        +     '<ul class="people">' + person(d.organizer, 'fa-solid fa-user-tie').replace('<small>', '<small>Organizer &middot; ')
        +     d.leads.map((p) => person(p, 'fa-solid fa-user-shield').replace('<small>', '<small>Lead &middot; ')).join('') + '</ul>'
        +     '<div class="partners">' + d.partners.map((p) => '<span><i class="fa-regular fa-handshake"></i> ' + esc(p) + '</span>').join('') + '</div>'
        +     '<div class="slots"><div class="mini-bar big"><span style="width:' + pct + '%"></span></div>'
        +     '<small>' + (d.joined + (joined.has(d.id) ? 1 : 0)) + ' of ' + d.slots + ' joined &middot; ' + spots(d) + ' spots left</small></div>'
        +   '</section>'
        +   '<section><h3><i class="fa-solid fa-clipboard-check"></i> Requirements</h3>'
        +     '<ul class="req-list">' + d.requirements.map((r) => '<li><i class="fa-solid fa-check"></i>' + esc(r) + '</li>').join('') + '</ul></section>'
        + '</div>'
        + '<footer class="drawer-foot" id="drawerFoot">' + ctaFor(d) + '</footer>';

      $('drawer').hidden = false;
      document.body.classList.add('no-scroll');
      $('drawerPanel').querySelector('.icon-btn').focus();
    }

    function closeDrawer() {
      $('drawer').hidden = true;
      document.body.classList.remove('no-scroll');
      activeId = null;
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }

    function refreshDrawer() {
      if (activeId == null) return;
      const id = activeId;
      const scroll = $('drawerPanel').querySelector('.drawer-body');
      const y = scroll ? scroll.scrollTop : 0;
      openDrawer(id);
      $('drawerPanel').querySelector('.drawer-body').scrollTop = y;
    }

    // ---------- Modals ----------
    function showModal(which) {
      $('modal').hidden = false;
      $('promptDialog').hidden = which !== 'prompt';
      $('regDialog').hidden = which !== 'reg';
      document.body.classList.add('no-scroll');
    }

    function closeModal() {
      $('modal').hidden = true;
      $('promptDialog').hidden = true;
      $('regDialog').hidden = true;
      if ($('drawer').hidden) document.body.classList.remove('no-scroll');
    }

    function openPrompt(d) {
      pendingDriveId = d ? d.id : null;
      const lead = d
        ? (d.level === 2
            ? '<strong>' + esc(d.title) + '</strong> is for Team Leaders (Level 2). Everyone starts as a Level 1 Verified Volunteer, then moves up after 3 drives and safety training.'
            : '<strong>' + esc(d.title) + '</strong> is for verified volunteers only. We need to confirm who you are before you can join.')
        : 'Volunteers can join drives that are limited to verified people, and can move up through the levels.';
      $('promptDialog').innerHTML =
        '<span class="prompt-icon"><i class="fa-solid fa-hand-holding-heart"></i></span>'
        + '<h3 id="promptTitle">Become a volunteer?</h3>'
        + '<p class="modal-sub">' + lead + '</p>'
        + '<div class="prompt-box"><strong>You\'ll be asked for:</strong><ul>'
        + '<li><i class="fa-solid fa-id-card"></i> A valid ID (photo)</li>'
        + '<li><i class="fa-regular fa-user"></i> Your full name and date of birth</li>'
        + '<li><i class="fa-solid fa-phone"></i> Contact and emergency details</li>'
        + '<li><i class="fa-solid fa-building"></i> Your organization, if you belong to one</li></ul>'
        + '<small>Takes about 3 minutes.</small></div>'
        + '<div class="modal-actions"><button type="button" class="btn-cancel" data-modal-close>Not now</button>'
        + '<button type="button" class="btn-primary" id="promptYes">Yes, register me</button></div>';
      showModal('prompt');
      $('promptYes').focus();
    }

    // ---------- Registration form ----------
    let step = 1;
    const form = $('regForm');
    const total = 3;

    function ageFrom(iso) {
      if (!iso) return null;
      const b = new Date(iso), n = new Date();
      let a = n.getFullYear() - b.getFullYear();
      if (n.getMonth() < b.getMonth() || (n.getMonth() === b.getMonth() && n.getDate() < b.getDate())) a--;
      return a;
    }

    function setStep(n) {
      step = n;
      form.querySelectorAll('.reg-step').forEach((s) => { s.hidden = Number(s.dataset.step) !== n; });
      document.querySelectorAll('#stepper li').forEach((li, i) => {
        li.classList.toggle('is-current', i + 1 === n);
        li.classList.toggle('is-done', i + 1 < n);
      });
      $('regBack').hidden = n === 1;
      $('regCancel').hidden = n !== 1;
      $('regNext').hidden = n === total;
      $('regSubmit').hidden = n !== total;
      $('regDialog').scrollTop = 0;
    }

    function validateStep(n) {
      const scope = form.querySelector('[data-step="' + n + '"]');
      let first = null;
      scope.querySelectorAll('[data-req]').forEach((el) => {
        if (el.closest('[hidden]')) { el.classList.remove('invalid'); return; }
        let ok;
        if (el.type === 'checkbox') ok = el.checked;
        else if (el.type === 'file') ok = el.files && el.files.length > 0;
        else ok = el.value.trim() !== '';
        if (el.id === 'rBirth' && ok) {
          const a = ageFrom(el.value);
          ok = a !== null && a >= 8 && a < 100;
        }
        const target = el.type === 'file' ? el.closest('.upload') : el.type === 'checkbox' ? el.closest('.check') : el;
        target.classList.toggle('invalid', !ok);
        if (!ok && !first) first = target;
      });
      if (n === 3) {
        const any = Array.from(scope.querySelectorAll('[data-avail]')).some((c) => c.checked);
        $('availErr').hidden = any;
        if (!any && !first) first = $('availErr');
      }
      if (first) { first.scrollIntoView({ block: 'center', behavior: 'smooth' }); const f = first.matches('input, select') ? first : first.querySelector('input, select'); if (f) f.focus({ preventScroll: true }); }
      return !first;
    }

    function resetForm() {
      form.reset();
      form.querySelectorAll('.invalid').forEach((e) => e.classList.remove('invalid'));
      form.querySelectorAll('.upload-preview').forEach((i) => { i.hidden = true; i.removeAttribute('src'); });
      form.querySelectorAll('.upload-empty').forEach((i) => { i.hidden = false; });
      $('guardianBox').hidden = true;
      $('orgBox').hidden = true;
      $('availErr').hidden = true;
      $('regForm').hidden = false;
      $('stepper').hidden = false;
      $('regSuccess').hidden = true;
      setStep(1);
    }

    function openRegistration() {
      resetForm();
      const d = pendingDriveId ? byId(pendingDriveId) : null;
      $('regFor').textContent = d ? 'To join: ' + d.title : 'Takes about 3 minutes';
      showModal('reg');
      $('rMiddle').focus();
    }

    // ---------- Events ----------
    $('filterTabs').addEventListener('click', (e) => {
      const b = e.target.closest('[data-filter]');
      if (!b) return;
      filter = b.dataset.filter;
      document.querySelectorAll('#filterTabs button').forEach((x) => x.setAttribute('aria-selected', x === b));
      renderList();
    });

    $('driveList').addEventListener('click', (e) => {
      const b = e.target.closest('[data-drive]');
      if (b) openDrawer(Number(b.dataset.drive));
    });

    $('statusCard').addEventListener('click', (e) => {
      if (e.target.closest('[data-register]')) { pendingDriveId = null; openPrompt(null); }
      if (e.target.closest('[data-reset]')) {
        volunteer = 'none';
        joined.clear();
        renderStatus(); renderList();
        toast('Demo restarted');
      }
    });

    $('drawer').addEventListener('click', (e) => {
      if (e.target.closest('[data-drawer-close]')) return closeDrawer();
      const join = e.target.closest('[data-join]');
      if (join) {
        const d = byId(Number(join.dataset.join));
        if (d.level === 0) {
          joined.add(d.id);
          toast('You\'re in! See you on ' + d.date.mon + ' ' + d.date.day + '.');
          renderList(); refreshDrawer();
        } else {
          openPrompt(d);
        }
        return;
      }
      const leave = e.target.closest('[data-leave]');
      if (leave) {
        joined.delete(Number(leave.dataset.leave));
        toast('Your spot was cancelled');
        renderList(); refreshDrawer();
      }
    });

    $('modal').addEventListener('click', (e) => {
      if (e.target.closest('[data-modal-close]')) {
        const wasSuccess = !$('regSuccess').hidden;
        closeModal();
        if (wasSuccess) refreshDrawer();
        return;
      }
      if (e.target.closest('#promptYes')) openRegistration();
    });

    $('regNext').addEventListener('click', () => { if (validateStep(step)) setStep(step + 1); });
    $('regBack').addEventListener('click', () => setStep(step - 1));

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      if (!validateStep(3)) return;
      volunteer = 'pending';
      $('successName').textContent = $('rFirst').value.trim() || 'there';
      form.hidden = true;
      $('stepper').hidden = true;
      $('regSuccess').hidden = false;
      $('regDialog').scrollTop = 0;
      renderStatus();
      renderList();
    });

    // Under-18 guardian block
    $('rBirth').addEventListener('change', (e) => {
      const a = ageFrom(e.target.value);
      $('guardianBox').hidden = !(a !== null && a < 18);
    });

    // Organization toggle
    form.querySelectorAll('input[name="org"]').forEach((r) => {
      r.addEventListener('change', () => { $('orgBox').hidden = form.querySelector('input[name="org"]:checked').value !== 'yes'; });
    });

    // ID upload previews
    form.querySelectorAll('[data-upload]').forEach((inp) => {
      inp.addEventListener('change', () => {
        const box = inp.closest('.upload');
        const img = box.querySelector('.upload-preview');
        const empty = box.querySelector('.upload-empty');
        const file = inp.files[0];
        if (file) {
          img.src = URL.createObjectURL(file);
          img.hidden = false;
          empty.hidden = true;
          box.classList.remove('invalid');
        } else {
          img.hidden = true;
          empty.hidden = false;
        }
      });
    });

    // Clear error styling as people fix fields
    form.addEventListener('input', (e) => {
      const t = e.target;
      (t.type === 'checkbox' ? t.closest('.check') || t : t).classList.remove('invalid');
      if (t.matches('[data-avail]')) $('availErr').hidden = true;
    });

    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      if (!$('modal').hidden) closeModal();
      else if (!$('drawer').hidden) closeDrawer();
    });

    // ---------- Start ----------
    renderStatus();
    renderList();
  </script>
</body>
</html>

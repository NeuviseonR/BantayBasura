@php
  // Sample leader. Later, build this from the logged-in user.
  $authUser = auth()->user();
  $displayName = $authUser?->name ?? 'Purok Leader';
  $initials = collect(explode(' ', trim($displayName)))->filter()->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->implode('');
  $leader = $leader ?? ['name' => $displayName, 'initials' => $initials ?: 'PL', 'purok' => $authUser?->zone ?? 'Purok'];

  $hour = now()->hour; // set 'timezone' => 'Asia/Manila' in config/app.php
  $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Bantay Basura - @yield('title')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  @vite(['resources/css/user-dash.css', 'resources/css/purok-dash.css', 'resources/css/purok-pages.css', 'resources/css/custom.css'])
</head>
<body>

  @include('partials.purok-sidebar')

  <div class="main-wrapper">

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
      @yield('content')
    </main>
  </div>

  {{-- Shared slide-in panel, dialog and toast used by the pages --}}
  <div class="drawer" id="drawer" hidden>
    <div class="drawer-backdrop" data-drawer-close></div>
    <aside class="drawer-panel" id="drawerPanel" role="dialog" aria-modal="true"></aside>
  </div>

  <div class="modal" id="modal" hidden>
    <div class="modal-backdrop" data-modal-close></div>
    <div class="modal-dialog" id="modalDialog" role="dialog" aria-modal="true"></div>
  </div>

  <div class="toast" id="toast" role="status" aria-live="polite" hidden></div>

  <script>
    // ---------- Shared helpers (available to every page script) ----------
    const $ = (id) => document.getElementById(id);
    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    const PL = {
      toast(msg) {
        const el = $('toast');
        el.textContent = msg;
        el.hidden = false;
        clearTimeout(PL._t);
        PL._t = setTimeout(() => { el.hidden = true; }, 2800);
      },
      openDrawer(html) {
        $('drawerPanel').innerHTML = html;
        $('drawer').hidden = false;
        document.body.classList.add('no-scroll');
        const c = $('drawerPanel').querySelector('[data-drawer-close]');
        if (c) c.focus();
      },
      closeDrawer() {
        $('drawer').hidden = true;
        document.body.classList.remove('no-scroll');
      },
      openModal(html, wide) {
        $('modalDialog').innerHTML = html;
        $('modalDialog').classList.toggle('wide', !!wide);
        $('modal').hidden = false;
        document.body.classList.add('no-scroll');
        const f = $('modalDialog').querySelector('input, textarea, select, button:not([data-modal-close])');
        if (f) f.focus();
      },
      closeModal() {
        $('modal').hidden = true;
        if ($('drawer').hidden) document.body.classList.remove('no-scroll');
      },
    };

    document.addEventListener('click', (e) => {
      if (e.target.closest('[data-drawer-close]')) PL.closeDrawer();
      if (e.target.closest('[data-modal-close]')) PL.closeModal();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      if (!$('modal').hidden) PL.closeModal();
      else if (!$('drawer').hidden) PL.closeDrawer();
    });

    // Mobile menu
    $('menuToggle').addEventListener('click', () => $('navList').classList.toggle('active'));

    // Purok switcher (pages that have one): updates the labels only, sample data stays
    const purokSel = $('purokSelect');
    if (purokSel) {
      purokSel.addEventListener('change', (e) => {
        document.querySelectorAll('[data-purok]').forEach((el) => { el.textContent = e.target.value; });
      });
    }
  </script>
  @stack('scripts')
</body>
</html>

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
        <li class="nav-item {{ request()->routeIs('user-dash') ? 'active' : '' }}">
          <a href="{{ route('user-dash') }}"><i class="fa-solid fa-house"></i> Dashboard</a>
        </li>
        <li class="nav-item {{ request()->routeIs('user-report') ? 'active' : '' }}">
          <a href="{{ route('user-report') }}"><i class="fa-solid fa-circle-exclamation"></i> Report a Waste Issue</a>
        </li>
        <li class="nav-item {{ request()->routeIs('user-my-reports') ? 'active' : '' }}">
          <a href="{{ route('user-my-reports') }}"><i class="fa-regular fa-clipboard"></i> My Reports</a>
        </li>
        <li class="nav-item {{ request()->routeIs('user-cleanup-drives') ? 'active' : '' }}">
          <a href="{{ route('user-cleanup-drives') }}"><i class="fa-solid fa-people-group"></i> Cleanup Drives</a>
        </li>
        <li class="nav-item {{ request()->routeIs('user-messages') ? 'active' : '' }}">
          <a href="{{ route('user-messages') }}"><i class="fa-regular fa-comment"></i> Messages</a>
        </li>
        <li class="nav-item {{ request()->routeIs('user-profile') ? 'active' : '' }}">
          <a href="{{ route('user-profile') }}"><i class="fa-regular fa-user"></i> Profile</a>
        </li>
      </ul>
    </nav>
  </div>

  <div class="sidebar-footer">
    {{-- Logging out must be a POST request, so this is a form, not a link --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout-btn">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out
      </button>
    </form>
  </div>
</aside>

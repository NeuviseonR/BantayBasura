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
                <li class="nav-item {{ request()->routeIs('purok-dash') ? 'active' : '' }}">
                    <a href="{{ route('purok-dash') }}"><i class="fa-solid fa-house"></i> Dashboard</a>
                </li>
                <li class="nav-item {{ request()->routeIs('purok-reports') ? 'active' : '' }}">
                    <a href="{{ route('purok-reports') }}"><i class="fa-regular fa-clipboard"></i> Waste Reports</a>
                </li>
                <li class="nav-item {{ request()->routeIs('purok-activities-db*') ? 'active' : '' }}">
                    <a href="{{ route('purok-activities-db') }}"><i class="fa-solid fa-people-group"></i> Cleanup
                        Activities</a>
                </li>
                <li class="nav-item {{ request()->routeIs('purok-overview') ? 'active' : '' }}">
                    <a href="{{ route('purok-overview') }}"><i class="fa-solid fa-location-dot"></i> Purok Overview</a>
                </li>
                <li class="nav-item {{ request()->routeIs('purok-volunteers') ? 'active' : '' }}">
                    <a href="{{ route('purok-volunteers') }}"><i class="fa-solid fa-hand-holding-heart"></i> Volunteer Applications</a>
                </li>
                <li class="nav-item {{ request()->routeIs('purok-messages') ? 'active' : '' }}">
                    <a href="{{ route('purok-messages') }}"><i class="fa-regular fa-comment"></i> Messages</a>
                </li>
                <li class="nav-item {{ request()->routeIs('purok-profile') ? 'active' : '' }}">
                    <a href="{{ route('purok-profile') }}"><i class="fa-regular fa-user"></i> Profile</a>
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

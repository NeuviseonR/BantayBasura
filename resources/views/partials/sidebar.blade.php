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
                    <a href="{{ route('user-dash') }}"><i class="fa-solid fa-house"></i> Home</a>
                </li>
                <li class="nav-item {{ request()->routeIs('user-report') ? 'active' : '' }}">
                    <a href="{{ route('user-report') }}"><i class="fa-solid fa-circle-exclamation"></i> Report a Waste
                        Issue</a>
                </li>
                <li class="nav-item {{ request()->routeIs('user-my-reports') ? 'active' : '' }}">
                    <a href="{{ route('user-my-reports') }}"><i class="fa-regular fa-clipboard"></i> My Reports</a>
                </li>
                <li class="nav-item {{ request()->routeIs('user-cleanup-drives') ? 'active' : '' }}">
                    <a href="{{ route('user-cleanup-drives') }}"><i class="fa-solid fa-people-group"></i> Cleanup
                        Drives</a>
                </li>
                @if (auth()->user()->role === 'volunteer')
                    <li class="nav-item {{ request()->routeIs('volunteer.activities') ? 'active' : '' }}"><a
                            href="{{ route('volunteer.activities') }}"><i class="fa-solid fa-calendar-check"></i> My
                            Activities</a></li>
                    <li
                        class="nav-item {{ request()->routeIs('volunteer.history', 'volunteer.gallery') ? 'active' : '' }}">
                        <a href="{{ route('volunteer.history') }}"><i class="fa-solid fa-clock-rotate-left"></i>
                            Participation History</a></li>
                    <li class="nav-item {{ request()->routeIs('volunteer.log') ? 'active' : '' }}"><a
                            href="{{ route('volunteer.log') }}"><i class="fa-solid fa-award"></i> Volunteering Log</a>
                    </li>
                @endif
                <li class="nav-item {{ request()->routeIs('user-tickets.*', 'user-messages') ? 'active' : '' }}">
                    <a href="{{ route('user-tickets.index') }}"><i class="fa-solid fa-ticket"></i> Support Tickets</a>
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

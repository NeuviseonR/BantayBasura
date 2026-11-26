@php
    $staffUser = auth()->user();
    $staffName = $staffUser?->name ?? ($actor === 'administrator' ? 'System Administrator' : 'Account Manager');
    $staffInitials = collect(explode(' ', trim($staffName)))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'BB';
    $managerNav = [
        ['dashboard', 'Manager Dashboard', 'fa-gauge-high'],
        ['accounts', 'New Account Verification', 'fa-user-check', '4'],
        ['applications', 'Volunteer & Purok Leader Applications', 'fa-id-card', '6'],
        ['volunteers', 'Volunteer Records & Points', 'fa-people-group'],
        ['points', 'Volunteer Point Disputes & Appeals', 'fa-scale-balanced', '3'],
        ['tickets', 'Resident & Volunteer Support Tickets', 'fa-ticket', '8'],
        ['activity', 'Suspicious Account Monitoring', 'fa-shield-halved', '2'],
        ['audit', 'Manager Action History', 'fa-clipboard-list'],
    ];
    $adminNav = [
        ['dashboard', 'Administrator Dashboard', 'fa-gauge-high'],
        ['managers', 'Manager Account & Permission Management', 'fa-user-shield'],
        ['users', 'All Registered Accounts', 'fa-users'],
        ['enforcement', 'Account Suspensions & Bans', 'fa-ban', '2'],
        ['appeals', 'Account Ban Appeals', 'fa-scale-balanced', '1'],
        ['audit', 'System-Wide Activity Logs', 'fa-clipboard-list'],
        ['settings', 'System Configuration & Security', 'fa-sliders'],
    ];
    $navigation = $actor === 'administrator' ? $adminNav : $managerNav;
    $actorLabel = $actor === 'administrator' ? 'Administrator' : 'Account & Support Manager';
    $baseRoute = $actor === 'administrator' ? 'administrator.preview' : 'account-manager.preview';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bantay Basura | {{ $actorLabel }} · {{ $pageTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/staff-dashboards.css', 'resources/js/staff-dashboards.js'])
</head>
<body class="staff-body">
    <aside class="staff-sidebar" id="staffSidebar">
        <a class="staff-brand" href="{{ route($baseRoute, ['section' => 'dashboard']) }}">
            <span class="staff-brand-mark"><i class="fa-solid fa-leaf"></i></span>
            <span class="staff-brand-name">Bantay<br>Basura</span>
            <button type="button" class="staff-mobile-close" data-sidebar-close aria-label="Close navigation"><i class="fa-solid fa-xmark"></i></button>
        </a>
        <div class="staff-workspace-label">{{ $actorLabel }} workspace</div>
        <nav class="staff-nav" aria-label="Main navigation">
            <span class="staff-nav-caption">WORKSPACE</span>
            @foreach ($navigation as $item)
                <a class="staff-nav-link {{ $section === $item[0] ? 'active' : '' }}" href="{{ route($baseRoute, ['section' => $item[0]]) }}">
                    <i class="fa-solid {{ $item[2] }}"></i><span>{{ $item[1] }}</span>
                    @if (!empty($item[3])) <small>{{ $item[3] }}</small> @endif
                </a>
            @endforeach
        </nav>
        <div class="staff-sidebar-bottom">
            <div class="staff-prototype-note"><i class="fa-solid fa-flask"></i><span><strong>Prototype mode</strong><small>Sample data · Not connected</small></span></div>
            <a href="{{ route('purok-dash') }}" class="staff-back-link"><i class="fa-solid fa-arrow-left"></i> Purok Leader preview</a>
            <a href="{{ route('user-dash') }}" class="staff-back-link"><i class="fa-solid fa-arrow-left"></i> Resident preview</a>
        </div>
    </aside>
    <div class="staff-sidebar-scrim" data-sidebar-close></div>
    <div class="staff-main">
        <header class="staff-topbar">
            <button type="button" class="staff-menu-button" data-sidebar-open aria-label="Open navigation"><i class="fa-solid fa-bars"></i></button>
            <div class="staff-breadcrumb"><span>Workspace</span><i class="fa-solid fa-chevron-right"></i><strong>{{ $pageTitle }}</strong></div>
            <div class="staff-top-actions">
                <span class="staff-demo-pill"><i class="fa-solid fa-circle-info"></i> Preview data</span>
                <button type="button" class="staff-icon-button" aria-label="Notifications" data-demo-toast="Notifications will be connected when the staff module is integrated."><i class="fa-regular fa-bell"></i><b></b></button>
                <div class="staff-user-chip"><span class="staff-avatar">{{ $staffInitials }}</span><span><strong>{{ $staffName }}</strong><small>{{ $actorLabel }}</small></span></div>
            </div>
        </header>
        <main class="staff-content">
            <div class="staff-content-inner">
                @yield('content')
                <div class="staff-prototype-footer"><i class="fa-solid fa-circle-info"></i> Prototype preview only. Sample records and actions are not connected to the database yet.</div>
            </div>
        </main>
    </div>
    <div class="staff-toast" id="staffToast" role="status" aria-live="polite"></div>
</body>
</html>

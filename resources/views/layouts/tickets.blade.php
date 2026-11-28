@php
    $account = auth()->user();
    $displayName = $account?->name ?? 'User';
    $initials = collect(preg_split('/\s+/', trim($displayName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Support Tickets') | Bantay Basura</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/user-tickets.css', 'resources/css/custom.css', 'resources/js/user-tickets.js'])
</head>
<body>
    @include('partials.sidebar')

    <div class="main-wrapper">
        <header class="top-header">
            <div class="user-greeting">
                <div class="ticket-avatar">{{ $initials ?: 'U' }}</div>
                <div class="greeting-text">
                    <h2>{{ $greeting }}, {{ $displayName }}!</h2>
                    <p>Your Bantay Basura support space</p>
                </div>
            </div>
            <div class="top-actions">
                <div class="profile-pill">
                    <div class="avatar-initials">{{ $initials ?: 'U' }}</div>
                    <div class="profile-info">
                        <div class="name">{{ $displayName }}</div>
                        <div class="role">{{ ucfirst($account->role ?? 'User') }}</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="dashboard-content ticket-dashboard-content">
            @yield('content')
        </main>
    </div>
</body>
</html>

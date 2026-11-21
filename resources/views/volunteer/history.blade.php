@php
    $regs = $regs ?? collect();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Participation History</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/volunteer.css'])
</head>

<body>@include('partials.sidebar')<div class="main-wrapper">
        <header class="top-header">
            <div class="user-greeting">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=120"
                    alt="Maria Lopez" class="avatar-img"
                    onerror="this.src='https://placehold.co/80x80/1e3a17/fff9a2?text=ML'">
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
                        <div class="role">{{ auth()->user()->role }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>
            </div>
        </header>

        <main class="dashboard-content volunteer-page">
            <h1>Participation History</h1>
            <p class="page-sub">Completed, verified, and missed activities you've registered for.</p>
            <div class="history-list">
                @forelse($regs as $reg)
                    <article class="history-card">
                        <div><span
                                class="status {{ $reg->attendance_status }}">{{ ucfirst(str_replace('_', ' ', $reg->attendance_status)) }}</span>
                            <h2>{{ $reg->drive->title }}</h2>
                            <p><i class="fa-regular fa-calendar"></i> {{ $reg->drive->start_at->format('M d, Y') }}
                                &nbsp; <i class="fa-solid fa-location-dot"></i> {{ $reg->drive->location }}</p>
                            <p class="muted">Verification:
                                {{ $reg->verification_method ? ucfirst($reg->verification_method) : 'Not verified' }} ·
                                Earned {{ $reg->points_awarded }} pts · Deducted {{ $reg->points_deducted }} pts</p>
                        </div><a class="btn secondary" href="{{ route('volunteer.gallery', $reg->drive) }}"><i
                                class="fa-regular fa-images"></i> Event gallery</a>
                </article>@empty<div class="empty-state"><i class="fa-solid fa-clock-rotate-left"></i>
                        <h3>No past activities</h3>
                        <p>Your completed participation records will be saved here.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</body>

</html>

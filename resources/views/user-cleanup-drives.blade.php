@php
    $user = $user ?? auth()->user();
    $application = $application ?? null;
    $drives = $drives ?? collect();
    $joined = $joined ?? collect();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bantay Basura - Cleanup Drives</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/volunteer.css'])
</head>

<body>
    @include('partials.sidebar')

    <div class="main-wrapper">
        <header class="top-header">
            <div class="user-greeting">
                @if (auth()->user()->profile_photo_path)
                    <img src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="avatar-img">
                @else
                    <div class="avatar-initials">{{ auth()->user()->initials }}</div>
                @endif
                <div class="greeting-text">
                    <h2>Hello, {{ auth()->user()->name }}!</h2>
                </div>
            </div>

            <div class="top-actions">

                <div class="profile-pill">
                    <div class="avatar-initials">{{ auth()->user()->initials }}</div>
                    <div class="profile-info">
                        <div class="name">{{ auth()->user()->name }}</div>
                        <div class="role">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>
            </div>
        </header>

        <main class="dashboard-content volunteer-page">
            <div class="page-heading">
                <div>
                    <h1>Cleanup Drives</h1>
                    <p>Find community cleanups and take part in keeping your barangay clean.</p>
                </div>
            </div>
            @if (session('notice'))
                <div class="notice"><i class="fa-solid fa-circle-info"></i>{{ session('notice') }}</div>
            @endif
            @if ($user->role === 'resident')
                <section class="volunteer-banner">
                    <div class="banner-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                    <div>
                        <h2>Volunteer registration required</h2>
                        <p>All cleanup-drive participants must be approved volunteers. Submit your application to unlock
                            registration.</p>
                        @if ($application && $application->status === 'pending')
                            <span class="status pending">Application pending review</span>
                        @elseif($application && $application->status === 'rejected')
                            <p class="muted">Your previous application was declined. You may submit a new application.
                        </p><a class="btn primary" href="{{ route('volunteer.apply') }}">Apply again</a>@else<a
                                class="btn primary" href="{{ route('volunteer.apply') }}">Register as volunteer</a>
                        @endif
                    </div>
            </section>@else<section class="volunteer-stats">
                    <div class="stat"><span>Current points</span><strong>{{ $user->volunteer_points }}</strong></div>
                    <div class="stat"><span>My registered drives</span><strong>{{ count($joined) }}</strong></div>
                    <div class="stat"><span>Volunteer status</span><strong class="status approved">Approved</strong>
                    </div>
                </section>
            @endif
            <div class="drive-grid">
                @forelse($drives as $drive)
                    <article class="drive-card">
                        <div class="drive-card-top"><span
                                class="status {{ $drive->status }}">{{ ucfirst($drive->status) }}</span><span
                                class="slots"><i class="fa-solid fa-users"></i>
                                {{ $drive->registrations_count }}/{{ $drive->slots }}</span></div>
                        <h2>{{ $drive->title }}</h2>
                        <p class="drive-description">{{ $drive->description }}</p>
                        <ul class="drive-meta">
                            <li><i class="fa-regular fa-calendar"></i>{{ $drive->start_at->format('D, M j, Y') }}</li>
                            <li><i class="fa-regular fa-clock"></i>{{ $drive->start_at->format('g:i A') }} –
                                {{ $drive->end_at->format('g:i A') }}</li>
                            <li><i class="fa-solid fa-location-dot"></i>{{ $drive->location }}</li>
                            <li><i class="fa-solid fa-user-shield"></i>Organized by {{ $drive->organizer->name }}</li>
                        </ul>
                        @if ($user->role === 'volunteer')
                            @if (in_array($drive->id, $joined))
                                <span class="status approved"><i class="fa-solid fa-check"></i> Registered</span>
                            @elseif($drive->status === 'scheduled' && $drive->start_at->isFuture() && $drive->registrations_count < $drive->slots)
                                <form method="POST" action="{{ route('cleanup.join', $drive) }}">@csrf<button
                                    class="btn primary" type="submit">Join cleanup</button></form>@else<span
                                    class="muted">Registration unavailable</span>
                        @endif @else<span class="muted">Become an approved volunteer to
                                register.</span>
                        @endif
                </article>@empty<div class="empty-state"><i class="fa-solid fa-calendar-xmark"></i>
                        <h3>No cleanup drives scheduled</h3>
                        <p>New events will appear here when your Purok Leader posts them.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</body>

</html>

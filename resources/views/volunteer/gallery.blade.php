@php
    $regs = $regs ?? collect();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cleanup Gallery</title>
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

        <main class="dashboard-content volunteer-page"><a class="back-link" href="{{ route('volunteer.history') }}">←
                Participation History</a>
            <h1>{{ $drive->title }} — Gallery</h1>
            <p class="page-sub">Submitted evidence from registered participants. Photos are shown for attendance
                documentation.</p>
            <div class="evidence-gallery">
                @forelse($evidence as $item)
                    <figure><img src="{{ Storage::disk('public')->url($item->photo_path) }}"
                            alt="Volunteer attendance evidence">
                        <figcaption>{{ $item->registration->volunteer->name }} ·
                            {{ $item->uploaded_at->format('M d, Y') }}</figcaption>
                </figure>@empty<div class="empty-state"><i class="fa-regular fa-images"></i>
                        <h3>No photos submitted</h3>
                        <p>No attendance photos have been uploaded for this drive.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</body>

</html>

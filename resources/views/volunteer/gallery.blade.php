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

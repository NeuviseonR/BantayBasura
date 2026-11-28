<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantay Basura - Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/user-profile.css', 'resources/css/custom.css'])
</head>
<body>
    @include('partials.sidebar')
    <div class="main-wrapper">
        <header class="top-header">
            <div class="user-greeting">
                @if ($user->profile_photo_path)
                    <img src="{{ Storage::disk('public')->url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="avatar-img">
                @else
                    <div class="avatar-initials">{{ $user->initials }}</div>
                @endif
                <div class="greeting-text"><h2>Hello, {{ $user->name }}!</h2></div>
            </div>
            <div class="top-actions">
                <div class="profile-pill">
                    <div class="avatar-initials">{{ $user->initials }}</div>
                    <div class="profile-info"><div class="name">{{ $user->name }}</div><div class="role">{{ ucfirst($user->role) }}</div></div>
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            <div class="page-title"><h1>Profile</h1><p>Manage your personal information and account password.</p></div>

            @if (session('success')) <div class="pf-feedback success" role="status">{{ session('success') }}</div> @endif
            @if (session('password_success')) <div class="pf-feedback success" role="status">{{ session('password_success') }}</div> @endif

            <section class="pf-banner">
                <div class="pf-avatar-wrap">
                    @if ($user->profile_photo_path)
                        <img src="{{ Storage::disk('public')->url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="pf-avatar" id="avatarImg">
                    @else
                        <div class="pf-avatar pf-avatar-initials" id="avatarInitials">{{ $user->initials }}</div>
                    @endif
                </div>
                <div class="pf-banner-text">
                    <h2>{{ $user->name }}</h2>
                    <div class="pf-tags">
                        <span class="pf-tag"><i class="fa-regular fa-user"></i> {{ ucfirst($user->role) }}</span>
                        <span class="pf-tag"><i class="fa-solid fa-location-dot"></i> {{ $user->barangay?->name ?? 'Barangay not assigned' }}</span>
                        <span class="pf-tag"><i class="fa-regular fa-calendar"></i> Member since {{ $user->created_at?->format('F Y') }}</span>
                    </div>
                </div>
            </section>

            <section class="pf-layout">
                <div class="pf-main">
                    <form class="pf-card" action="{{ route('user-profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="pf-card-head"><div class="pf-title"><span class="pf-icon"><i class="fa-regular fa-id-card"></i></span><h3>Personal information</h3></div></div>
                        <div class="pf-grid">
                            <div class="pf-field"><label for="first_name">First name</label><input id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>@error('first_name', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="middle_name">Middle name (optional)</label><input id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">@error('middle_name', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="last_name">Last name</label><input id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>@error('last_name', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="email">Email address</label><input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>@error('email', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="mobile">Mobile number</label><input type="tel" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" required>@error('mobile', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="birthdate">Date of birth</label><input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}" required>@error('birthdate', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="sex">Sex</label><select id="sex" name="sex"><option value="">Prefer not to say</option>@foreach (['Female', 'Male', 'Prefer not to say'] as $sex)<option value="{{ $sex }}" @selected(old('sex', $user->sex) === $sex)>{{ $sex }}</option>@endforeach</select>@error('sex', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="barangay_id">Barangay</label><select id="barangay_id" name="barangay_id" required><option value="">Choose your barangay</option>@foreach ($barangays as $barangay)<option value="{{ $barangay->id }}" @selected((string) old('barangay_id', $user->barangay_id) === (string) $barangay->id)>{{ $barangay->name }}</option>@endforeach</select>@error('barangay_id', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field pf-wide"><label for="address">Purok / street address</label><input id="address" name="address" value="{{ old('address', $user->address) }}" required>@error('address', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field pf-wide"><label for="profile_current_password">Current password (required only when changing email or mobile)</label><input type="password" id="profile_current_password" name="current_password" autocomplete="current-password">@error('current_password', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field pf-wide"><label for="profile_photo">Profile photo (optional)</label><input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/webp"><span class="pf-hint">JPG, PNG, or WEBP. Maximum 5 MB.</span>@error('profile_photo', 'profile')<span class="pf-error">{{ $message }}</span>@enderror</div>
                        </div>
                        <div class="pf-actions"><button type="submit" class="btn-report pf-save"><i class="fa-solid fa-check"></i> Save changes</button></div>
                    </form>

                    <form class="pf-card" action="{{ route('user-profile.password') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="pf-card-head"><div class="pf-title"><span class="pf-icon"><i class="fa-solid fa-lock"></i></span><h3>Change password</h3></div></div>
                        <div class="pf-grid">
                            <div class="pf-field pf-wide"><label for="current_password">Current password</label><input type="password" id="current_password" name="current_password" autocomplete="current-password" required>@error('current_password', 'password')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="password">New password</label><input type="password" id="password" name="password" autocomplete="new-password" required><span class="pf-hint">At least 8 characters, including letters and numbers.</span>@error('password', 'password')<span class="pf-error">{{ $message }}</span>@enderror</div>
                            <div class="pf-field"><label for="password_confirmation">Confirm new password</label><input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required></div>
                        </div>
                        <div class="pf-actions"><button type="submit" class="btn-secondary"><i class="fa-solid fa-key"></i> Update password</button></div>
                    </form>
                </div>

                <aside class="pf-side">
                    <div class="pf-card">
                        <div class="pf-title pf-title-sm"><span class="pf-icon"><i class="fa-regular fa-clipboard"></i></span><h3>My activity</h3></div>
                        <div class="pf-stats"><div><strong>{{ $reportCounts['total'] }}</strong><span>Reports</span></div><div><strong>{{ $reportCounts['pending'] }}</strong><span>Pending / Under review</span></div><div><strong>{{ $reportCounts['resolved'] }}</strong><span>Resolved</span></div></div>
                        <a href="{{ route('user-my-reports') }}" class="view-all">View my reports</a>
                    </div>
                    <div class="pf-card">
                        <div class="pf-title pf-title-sm"><span class="pf-icon"><i class="fa-solid fa-shield-halved"></i></span><h3>Account</h3></div>
                        <p class="pf-note">You are signed in as a {{ $user->role }}. Your reports and support tickets are private to your account.</p>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</button></form>
                    </div>
                </aside>
            </section>
        </main>
    </div>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const navList = document.getElementById('navList');
        if (menuToggle && navList) menuToggle.addEventListener('click', () => navList.classList.toggle('active'));

        const photoInput = document.getElementById('profile_photo');
        if (photoInput) photoInput.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) return;
            const previewUrl = URL.createObjectURL(file);
            let preview = document.getElementById('avatarImg');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'avatarImg';
                preview.className = 'pf-avatar';
                preview.alt = @json($user->name);
                document.getElementById('avatarInitials')?.replaceWith(preview);
            }
            preview.src = previewUrl;
        });
    </script>
</body>
</html>

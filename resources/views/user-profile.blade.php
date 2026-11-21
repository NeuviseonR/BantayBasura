@php
    // ---------------------------------------------------------------------------
    // SAMPLE DATA for the view-only stage. Later, pass $user from your controller
    // (the logged-in resident only) and delete this block.
    // ---------------------------------------------------------------------------
    $user = $user ?? [
        'name' => 'Maria Lopez',
        'initials' => 'ML',
        'email' => 'maria.lopez@example.com',
        'phone' => '0917 000 0000',
        'zone' => 'Zone 2',
        'address' => 'Purok 3',
        'since' => 'March 2025',
        'photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=300',
        'reports' => 3,
        'pending' => 1,
        'resolved' => 2,
    ];
    $zones = ['Zone 1', 'Zone 2', 'Zone 3', 'Zone 4'];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        {{-- Top Navigation Header --}}
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

        <main class="dashboard-content">

            <div class="page-title">
                <h1>Profile</h1>
                <p>Manage your personal details, password and notification settings.</p>
            </div>

            {{-- ================= Profile banner ================= --}}
            <section class="pf-banner">
                <i class="fa-solid fa-leaf pf-banner-leaf"></i>

                <div class="pf-avatar-wrap">
                    <img src="{{ $user['photo'] }}" alt="{{ $user['name'] }}" class="pf-avatar" id="avatarImg"
                        onerror="this.src='https://placehold.co/200x200/1e3a17/fff9a2?text={{ $user['initials'] }}'">
                    <label class="pf-avatar-edit" for="avatarInput" title="Change photo">
                        <i class="fa-solid fa-camera"></i>
                        <span class="sr-only">Change photo</span>
                    </label>
                    <input type="file" id="avatarInput" accept="image/*" hidden>
                </div>

                <div class="pf-banner-text">
                    <h2>{{ $user['name'] }}</h2>
                    <div class="pf-tags">
                        <span class="pf-tag"><i class="fa-regular fa-user"></i> Resident</span>
                        <span class="pf-tag"><i class="fa-solid fa-location-dot"></i> {{ $user['zone'] }}</span>
                        <span class="pf-tag"><i class="fa-regular fa-calendar"></i> Member since
                            {{ $user['since'] }}</span>
                    </div>
                </div>
            </section>

            <section class="pf-layout">

                {{-- ================= Left column ================= --}}
                <div class="pf-main">

                    {{-- Personal information --}}
                    <form class="pf-card" id="infoForm" action="#" method="POST" novalidate>
                        @csrf
                        <div class="pf-card-head">
                            <div class="pf-title">
                                <span class="pf-icon"><i class="fa-regular fa-id-card"></i></span>
                                <h3>Personal information</h3>
                            </div>
                            <button type="button" class="btn-outline" id="editBtn"><i class="fa-solid fa-pen"></i>
                                Edit</button>
                        </div>

                        <div class="pf-grid">
                            <div class="pf-field">
                                <label for="name">Full name</label>
                                <input type="text" id="name" name="name" value="{{ $user['name'] }}"
                                    disabled>
                            </div>
                            <div class="pf-field">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="{{ $user['email'] }}"
                                    disabled>
                            </div>
                            <div class="pf-field">
                                <label for="phone">Mobile number</label>
                                <input type="tel" id="phone" name="phone" value="{{ $user['phone'] }}"
                                    disabled>
                            </div>
                            <div class="pf-field">
                                <label for="zone">Zone</label>
                                <div class="select-wrap">
                                    <select id="zone" name="zone" disabled>
                                        @foreach ($zones as $z)
                                            <option value="{{ $z }}" @selected($z === $user['zone'])>
                                                {{ $z }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                            </div>
                            <div class="pf-field pf-wide">
                                <label for="address">Purok / street address</label>
                                <input type="text" id="address" name="address" value="{{ $user['address'] }}"
                                    disabled>
                                <span class="pf-hint">Your zone decides which collection schedule you see.</span>
                            </div>
                        </div>

                        <div class="pf-actions" id="infoActions" hidden>
                            <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
                            <button type="submit" class="btn-report pf-save"><i class="fa-solid fa-check"></i> Save
                                changes</button>
                        </div>
                    </form>

                    {{-- Password --}}
                    <form class="pf-card" id="passForm" action="#" method="POST" novalidate>
                        @csrf
                        <div class="pf-card-head">
                            <div class="pf-title">
                                <span class="pf-icon"><i class="fa-solid fa-lock"></i></span>
                                <h3>Change password</h3>
                            </div>
                        </div>

                        <div class="pf-grid">
                            <div class="pf-field pf-wide">
                                <label for="currentPass">Current password</label>
                                <div class="pass-wrap">
                                    <input type="password" id="currentPass" name="current_password"
                                        autocomplete="current-password">
                                    <button type="button" class="pass-toggle" data-target="currentPass"
                                        aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
                                </div>
                            </div>
                            <div class="pf-field">
                                <label for="newPass">New password</label>
                                <div class="pass-wrap">
                                    <input type="password" id="newPass" name="password"
                                        autocomplete="new-password" minlength="8">
                                    <button type="button" class="pass-toggle" data-target="newPass"
                                        aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
                                </div>
                                <span class="pf-hint">At least 8 characters.</span>
                            </div>
                            <div class="pf-field">
                                <label for="confirmPass">Confirm new password</label>
                                <div class="pass-wrap">
                                    <input type="password" id="confirmPass" name="password_confirmation"
                                        autocomplete="new-password">
                                    <button type="button" class="pass-toggle" data-target="confirmPass"
                                        aria-label="Show password"><i class="fa-regular fa-eye"></i></button>
                                </div>
                                <span class="pf-hint pf-error" id="passMsg" hidden></span>
                            </div>
                        </div>

                        <div class="pf-actions">
                            <button type="submit" class="btn-secondary"><i class="fa-solid fa-key"></i> Update
                                password</button>
                        </div>
                    </form>
                </div>

                {{-- ================= Right column ================= --}}
                <div class="pf-side">

                    <div class="pf-card">
                        <div class="pf-title pf-title-sm">
                            <span class="pf-icon"><i class="fa-regular fa-clipboard"></i></span>
                            <h3>My activity</h3>
                        </div>
                        <div class="pf-stats">
                            <div><strong>{{ $user['reports'] }}</strong><span>Reports</span></div>
                            <div><strong>{{ $user['pending'] }}</strong><span>Pending</span></div>
                            <div><strong>{{ $user['resolved'] }}</strong><span>Resolved</span></div>
                        </div>
                        <a href="#" class="view-all">View my reports</a>
                    </div>

                    <div class="pf-card">
                        <div class="pf-title pf-title-sm">
                            <span class="pf-icon"><i class="fa-regular fa-bell"></i></span>
                            <h3>Notifications</h3>
                        </div>
                        <div class="pref-list">
                            @foreach ([['report_status', 'Report updates', 'When a report is verified or resolved', true], ['collection', 'Collection reminders', 'Before your zone\'s pickup', true], ['drives', 'Cleanup drives', 'New events in your area', false], ['messages', 'Messages', 'Replies from the barangay team', true]] as [$key, $label, $desc, $on])
                                <div class="pref-row">
                                    <div class="pref-text">
                                        <div class="item-title">{{ $label }}</div>
                                        <div class="item-subtext">{{ $desc }}</div>
                                    </div>
                                    <button type="button" role="switch" class="switch"
                                        aria-checked="{{ $on ? 'true' : 'false' }}" aria-label="{{ $label }}"
                                        data-pref="{{ $key }}"><span></span></button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pf-card">
                        <div class="pf-title pf-title-sm">
                            <span class="pf-icon"><i class="fa-solid fa-shield-halved"></i></span>
                            <h3>Account</h3>
                        </div>
                        <p class="pf-note">You're signed in as a resident. You can only see and manage your own
                            reports.</p>
                        <a href="#" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log
                            out</a>
                    </div>

                </div>
            </section>

        </main>
    </div>

    <script>
        // Mobile menu
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('navList').classList.toggle('active');
        });

        // ---------- Edit personal info ----------
        const infoForm = document.getElementById('infoForm');
        const fields = infoForm.querySelectorAll('input, select');
        const editBtn = document.getElementById('editBtn');
        const actions = document.getElementById('infoActions');
        const original = {};

        function setEditing(on) {
            fields.forEach((f) => {
                f.disabled = !on;
            });
            actions.hidden = !on;
            editBtn.hidden = on;
            infoForm.classList.toggle('is-editing', on);
            if (on) {
                fields[0].focus();
            }
        }

        editBtn.addEventListener('click', () => {
            fields.forEach((f) => {
                original[f.id] = f.value;
            });
            setEditing(true);
        });

        document.getElementById('cancelBtn').addEventListener('click', () => {
            fields.forEach((f) => {
                f.value = original[f.id];
            });
            setEditing(false);
        });

        // View-only for now: don't submit
        infoForm.addEventListener('submit', (e) => {
            e.preventDefault();
            setEditing(false);
        });

        // ---------- Photo preview ----------
        document.getElementById('avatarInput').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) document.getElementById('avatarImg').src = URL.createObjectURL(file);
        });

        // ---------- Password ----------
        document.querySelectorAll('.pass-toggle').forEach((btn) => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.innerHTML = '<i class="fa-regular ' + (show ? 'fa-eye-slash' : 'fa-eye') + '"></i>';
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        });

        const newPass = document.getElementById('newPass');
        const confirmPass = document.getElementById('confirmPass');
        const passMsg = document.getElementById('passMsg');

        function checkMatch() {
            const bad = confirmPass.value && newPass.value !== confirmPass.value;
            passMsg.hidden = !bad;
            passMsg.textContent = bad ? 'Passwords don\'t match.' : '';
        }
        newPass.addEventListener('input', checkMatch);
        confirmPass.addEventListener('input', checkMatch);
        document.getElementById('passForm').addEventListener('submit', (e) => e.preventDefault());

        // ---------- Notification switches ----------
        document.querySelectorAll('[data-pref]').forEach((sw) => {
            sw.addEventListener('click', () => {
                sw.setAttribute('aria-checked', sw.getAttribute('aria-checked') !== 'true');
            });
        });
    </script>
</body>

</html>

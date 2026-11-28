@php
    $regs = $regs ?? collect();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>My Activities</title>
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


        <main class="dashboard-content volunteer-page">
            <div class="page-heading">
                <div>
                    <h1>My Activities</h1>
                    <p>Track your registered cleanup drives and submit attendance proof.</p>
                </div>
                <div class="page-actions"><button class="btn secondary" id="calendarToggle"><i
                            class="fa-regular fa-calendar"></i> Calendar view</button><button class="btn secondary"
                        id="sortToggle"><i class="fa-solid fa-arrow-down-wide-short"></i> Sort: Newest</button></div>
            </div>
            @if (session('notice'))
                <div class="notice">{{ session('notice') }}</div>
            @endif

            @if ($errors->any())
                    <div class="notice error">{{ $errors->first() }}</div>
            @endif
                <div class="activity-filters"><button class="filter active" data-filter="all">All</button><button
                        class="filter" data-filter="upcoming">Upcoming</button><button class="filter"
                        data-filter="ongoing">Ongoing</button><button class="filter" data-filter="verify">Awaiting
                        verification</button><button class="filter" data-filter="completed">Completed</button></div>
                <div class="activities-list" id="activitiesList">
                    @forelse($regs as $reg)
                        @php($drive = $reg->drive)
                        <article class="activity-card" data-activity data-status="{{ $reg->display_status }}"
                            data-start="{{ $drive->start_at->timestamp }}">
                            <div class="activity-date">
                                <span>{{ $drive->start_at->format('M') }}</span><strong>{{ $drive->start_at->format('d') }}</strong>
                            </div>
                            <div class="activity-main">
                                <h2>{{ $drive->title }}</h2>
                                <p class="drive-meta-text"><i class="fa-regular fa-clock"></i>
                                    {{ $drive->start_at->format('M d, Y · g:i A') }} –
                                    {{ $drive->end_at->format('g:i A') }} <i class="fa-solid fa-location-dot"></i>
                                    {{ $drive->location }}</p><span
                                    class="status {{ $reg->display_status }}">{{ str_replace('_', ' ', ucfirst($reg->display_status)) }}</span>
                                @if (
                                    !$reg->verified_at &&
                                        !$reg->evidence_submitted_at &&
                                        $drive->status !== 'cancelled' &&
                                        !in_array($reg->attendance_status, ['cancelled', 'no_show'], true) &&
                                        now()->between($drive->start_at, $drive->end_at->copy()->addDays(3)))
                                    <div class="proof-actions">
                                        <form method="POST" action="{{ route('cleanup.evidence', $reg) }}"
                                            enctype="multipart/form-data" class="proof-form">@csrf<label>Upload photo
                                                proof <input type="file" name="photos[]" accept="image/*" multiple
                                                    required></label><button class="btn primary small"
                                                type="submit">Submit photos</button></form>
                                        <form method="POST" action="{{ route('cleanup.verify-code', $reg) }}"
                                            class="code-form">@csrf<label>Attendance code<input name="code"
                                                    placeholder="Enter event code" maxlength="24"
                                                    required></label><button class="btn secondary small"
                                                type="submit">Verify code</button><button class="btn secondary small"
                                                type="button" data-scan>Scan QR</button></form>
                                    </div>
                                    <p class="muted">Submit photo evidence or the attendance code by
                                        {{ $drive->end_at->copy()->addDays(3)->format('M d, Y g:i A') }}. Only one
                                        method is required.</p>
                                @elseif($reg->evidence_submitted_at && !$reg->verified_at)
                                    <p class="muted">Photo evidence submitted; awaiting organizer review.</p>
                                @endif
                                @if ($reg->attendance_status === 'registered' && $drive->status !== 'cancelled' && $drive->start_at->isFuture())
                                    <form method="POST" action="{{ route('cleanup.cancel', $reg) }}">@csrf<button
                                            class="btn secondary small" type="submit">Cancel registration</button>
                                    </form>
                                @endif
                            </div>
                        </article>@empty<div class="empty-state"><i class="fa-solid fa-calendar-check"></i>
                                <h3>No activities yet</h3>
                                <p>Join a cleanup drive and it will show up here.</p><a class="btn primary"
                                    href="{{ route('user-cleanup-drives') }}">Browse cleanup drives</a>
                            </div>
                        @endforelse
                    </div>
                    <div id="calendar" class="calendar-panel" hidden>
                        <div class="calendar-head"><button class="btn secondary small" id="prevMonth"><i
                                    class="fa-solid fa-chevron-left"></i></button><strong
                                id="calendarMonth"></strong><button class="btn secondary small" id="nextMonth"><i
                                    class="fa-solid fa-chevron-right"></i></button></div>
                        <div class="calendar-grid" id="calendarGrid"></div>
                    </div>
            </main>
        </div>
        <div class="scan-modal" id="scanModal" hidden>
            <div class="scan-inner"><button class="close-scan" id="closeScan" type="button"><i
                        class="fa-solid fa-xmark"></i></button>
                <h2>Scan attendance QR</h2>
                <p>Allow camera access and point it at the event QR code.</p><video id="scanVideo" playsinline></video>
                <p id="scanMessage" class="muted"></p>
            </div>
        </div>
        <script>
            const cards = [...document.querySelectorAll('[data-activity]')];
            let newest = true;
            document.querySelectorAll('[data-filter]').forEach(btn => btn.addEventListener('click', () => {
                document.querySelectorAll('[data-filter]').forEach(x => x.classList.remove('active'));
                btn.classList.add('active');
                cards.forEach(c => {
                    const s = c.dataset.status;
                    const ok = btn.dataset.filter === 'all' || (btn.dataset.filter === 'upcoming' && s === 'registered') || (btn.dataset.filter === 'ongoing' && s === 'ongoing') || (btn.dataset.filter === 'verify' && s === 'awaiting_verification') || (btn.dataset.filter === 'completed' && ['verified', 'no_show']
                        .includes(s));
                    c.hidden = !ok;
                });
            }));
            document.getElementById('sortToggle').addEventListener('click', () => {
                newest = !newest;
                document.getElementById('sortToggle').innerHTML =
                    '<i class="fa-solid fa-arrow-down-wide-short"></i> Sort: ' + (newest ? 'Newest' : 'Oldest');
                cards.sort((a, b) => (Number(a.dataset.start) - Number(b.dataset.start)) * (newest ? -1 : 1)).forEach(
                    c => document.getElementById('activitiesList').append(c));
            });
            const cal = document.getElementById('calendar'),
                list = document.getElementById('activitiesList');
            document.getElementById('calendarToggle').onclick = () => {
                cal.hidden = !cal.hidden;
                list.hidden = cal.hidden;
                document.getElementById('calendarToggle').innerHTML = cal.hidden ? 'Calendar view' : 'List view';
                if (!cal.hidden) renderCal();
            };
            let m = new Date();

            function escCal(s) {
                return String(s).replace(/[&<>\"']/g, c => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '\"': '&quot;',
                    "'": '&#39;'
                } [c]));
            }

            function renderCal() {
                const first = new Date(m.getFullYear(), m.getMonth(), 1),
                    last = new Date(m.getFullYear(), m.getMonth() + 1, 0);
                document.getElementById('calendarMonth').textContent = m.toLocaleDateString(undefined, {
                    month: 'long',
                    year: 'numeric'
                });
                let cells = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].map(w => '<div class="calendar-cell weekday">' +
                    w + '</div>').join('');
                for (let i = 0; i < first.getDay(); i++) cells += '<div class="calendar-cell muted"></div>';
                for (let d = 1; d <= last.getDate(); d++) {
                    let date = new Date(m.getFullYear(), m.getMonth(), d),
                        events = cards.filter(c => {
                            let x = new Date(Number(c.dataset.start) * 1000);
                            return x.toDateString() === date.toDateString()
                        });
                    cells += '<div class="calendar-cell"><b>' + d + '</b>' + events.map(c => '<span class="calendar-event">' +
                        escCal(c.querySelector('h2').textContent) + '</span>').join('') + '</div>';
                }
                document.getElementById('calendarGrid').innerHTML = cells;
            }
            document.getElementById('prevMonth').onclick = () => {
                m.setMonth(m.getMonth() - 1);
                renderCal()
            };
            document.getElementById('nextMonth').onclick = () => {
                m.setMonth(m.getMonth() + 1);
                renderCal()
            };
            let stream, scanner;
            document.querySelectorAll('[data-scan]').forEach(b => b.onclick = async () => {
                const form = b.closest('form'),
                    video = document.getElementById('scanVideo'),
                    modal = document.getElementById('scanModal');
                modal.hidden = false;
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: 'environment'
                        }
                    });
                    video.srcObject = stream;
                    await video.play();
                    if (!('BarcodeDetector' in window)) {
                        document.getElementById('scanMessage').textContent =
                            'QR scanning is not supported on this browser. Please enter the code manually.';
                        return;
                    }
                    scanner = new BarcodeDetector({
                        formats: ['qr_code']
                    });
                    const scan = async () => {
                        if (modal.hidden) return;
                        const codes = await scanner.detect(video);
                        if (codes.length) {
                            form.querySelector('[name=code]').value = codes[0].rawValue.trim();
                            form.requestSubmit();
                            closeScanner();
                        } else requestAnimationFrame(scan);
                    };
                    scan();
                } catch (e) {
                    document.getElementById('scanMessage').textContent =
                        'Camera unavailable. Please enter the code manually.';
                }
            });

            function closeScanner() {
                if (stream) stream.getTracks().forEach(t => t.stop());
                document.getElementById('scanVideo').srcObject = null;
                document.getElementById('scanModal').hidden = true;
            }
            document.getElementById('closeScan').onclick = closeScanner;
        </script>
    </body>

    </html>

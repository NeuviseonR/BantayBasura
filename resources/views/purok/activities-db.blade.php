@extends('layouts.purok')
@section('title', 'Cleanup Activities')
@section('content')
    <div class="purok-activities-page">
        <header class="pa-heading">
            <div><span class="pa-eyebrow">COMMUNITY PROGRAMS</span>
                <h1>Cleanup Activities</h1>
                <p>Plan community cleanups, manage registered participants, and monitor each drive.</p>
            </div>
            <div class="pa-count"><strong>{{ $drives->count() }}</strong><span>total drives</span></div>
        </header>
        @if (session('notice'))
            <div class="pa-alert pa-success"><i class="fa-solid fa-circle-check"></i>{{ session('notice') }}</div>
        @endif
        @if ($errors->any())
            <div class="pa-alert pa-error"><i class="fa-solid fa-triangle-exclamation"></i>{{ $errors->first() }}</div>
        @endif
        @if (session('generated_code'))
            <div class="pa-code-alert">
                <div><span class="pa-eyebrow">ATTENDANCE CODE {{ session('code_drive_id') ? 'UPDATED' : 'GENERATED' }}</span>
                    <p>Save and share this code with volunteers. It is only shown here once.</p><strong
                        id="generatedCode">{{ session('generated_code') }}</strong><button type="button"
                        class="pa-btn pa-outline"
                        onclick="navigator.clipboard.writeText(document.getElementById('generatedCode').textContent.trim())"><i
                            class="fa-regular fa-copy"></i> Copy code</button>
                    <div id="generatedQr" class="pa-qr"></div>
                </div>
            </div>
        @endif
        <div class="pa-layout">
            <aside class="pa-create-card">
                <div class="pa-card-heading"><span class="pa-icon"><i class="fa-solid fa-calendar-plus"></i></span>
                    <div>
                        <h2>Create a cleanup drive</h2>
                        <p>Fill in the details and choose a map location.</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('purok-activities-db.store') }}" id="createDriveForm">@csrf
                    <label class="pa-field">Event title<input name="title" value="{{ old('title') }}" maxlength="180"
                            required placeholder="e.g. Riverside cleanup"></label>
                    <label class="pa-field">Meeting point / address<input name="location" id="driveLocation"
                            value="{{ old('location') }}" maxlength="255" required
                            placeholder="Enter location description"></label>
                    <div class="pa-map-wrap">
                        <div id="driveLocationMap" class="pa-map" aria-label="Select cleanup drive location on map"></div>
                        <div class="pa-map-unavailable" id="paMapUnavailable" hidden><i
                                class="fa-solid fa-map-location-dot"></i>
                            <p>Map unavailable. Enter the location description and try again later.</p>
                        </div>
                    </div>
                    <div class="pa-map-actions"><button type="button" id="paUseGps" class="pa-btn pa-outline"><i
                                class="fa-solid fa-location-crosshairs"></i> Use my location</button><span
                            id="paMapStatus">Tap the map or drag the pin to set the meeting point.</span></div>
                    <input type="hidden" name="latitude" id="driveLat" value="{{ old('latitude') }}"><input type="hidden"
                        name="longitude" id="driveLng" value="{{ old('longitude') }}">
                    <div class="pa-two-fields"><label class="pa-field">Starts<input type="datetime-local" name="start_at"
                                value="{{ old('start_at') }}" required></label><label class="pa-field">Ends<input
                                type="datetime-local" name="end_at" value="{{ old('end_at') }}" required></label></div>
                    <label class="pa-field">Volunteer slots<input type="number" name="slots"
                            value="{{ old('slots', 30) }}" min="1" max="1000" required></label>
                    <label class="pa-field">Description
                        <textarea name="description" rows="3" required
                            placeholder="Purpose, instructions, what volunteers should bring...">{{ old('description') }}</textarea>
                    </label>
                    <div class="pa-status-note"><i class="fa-solid fa-clock"></i><span>Status is automatic: Scheduled before
                            start, Ongoing during the event, Completed after the end time. You can cancel a drive anytime
                            before it finishes.</span></div>
                    <button class="pa-btn pa-primary pa-full"><i class="fa-solid fa-plus"></i> Create cleanup drive</button>
                </form>
            </aside>
            <section class="pa-drive-list">
                <div class="pa-list-heading">
                    <div>
                        <h2>Existing cleanup drives</h2>
                        <p>Click a card to expand its details and actions.</p>
                    </div><span class="pa-total">{{ $drives->count() }} drives</span>
                </div>
                @forelse($drives as $drive)
                    @php
                        $participants = $drive->participant_list;
                        $canEdit = $drive->display_status === 'scheduled';
                    @endphp
                    <article class="pa-drive-item {{ $drive->display_status }}">
                        <button class="pa-drive-summary" type="button" aria-expanded="false"
                            aria-controls="drive-details-{{ $drive->id }}"><span
                                class="pa-status-dot {{ $drive->display_status }}"></span><span
                                class="pa-summary-main"><strong>{{ $drive->title }}</strong><small><i
                                        class="fa-regular fa-calendar"></i>
                                    {{ $drive->start_at->format('M d, Y · g:i A') }} <span class="pa-separator">•</span><i
                                        class="fa-solid fa-location-dot"></i> {{ $drive->location }}</small></span><span
                                class="pa-summary-stats"><span
                                    class="pa-status-pill {{ $drive->display_status }}">{{ ucfirst($drive->display_status) }}</span><span
                                    class="pa-registered"><i class="fa-solid fa-users"></i>
                                    {{ $drive->registrations_count }}/{{ $drive->slots }}</span></span><i
                                class="fa-solid fa-chevron-down pa-chevron"></i></button>
                        <div class="pa-drive-details" id="drive-details-{{ $drive->id }}" hidden>
                            <div class="pa-details-grid">
                                <div><span class="pa-detail-label">Event
                                        schedule</span><strong>{{ $drive->start_at->format('M d, Y · g:i A') }}</strong><small>Until
                                        {{ $drive->end_at->format('M d, Y · g:i A') }}</small></div>
                                <div><span class="pa-detail-label">Location</span><strong>{{ $drive->location }}</strong>
                                    @if ($drive->latitude && $drive->longitude)
                                        <a class="pa-map-link" target="_blank" rel="noopener"
                                            href="https://www.google.com/maps?q={{ $drive->latitude }},{{ $drive->longitude }}">Open
                                            map <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                    @endif
                                </div>
                                <div><span
                                        class="pa-detail-label">Volunteers</span><strong>{{ $drive->registrations_count }}
                                        of {{ $drive->slots }} slots</strong><small>{{ $drive->description }}</small>
                                </div>
                            </div>
                            <div class="pa-actions"><button type="button" class="pa-btn pa-outline pa-participants"
                                    data-participants='@json($participants)'
                                    data-drive-title="{{ $drive->title }}" data-drive-id="{{ $drive->id }}"
                                    data-can-edit="{{ $canEdit ? '1' : '0' }}"><i class="fa-solid fa-users"></i> Manage
                                    participants ({{ $participants->count() }})</button>
                                <form method="POST" action="{{ route('purok-activities-db.code', $drive) }}">@csrf<button
                                        class="pa-btn pa-outline" {{ !$canEdit ? 'disabled' : '' }}
                                        title="Code can only be changed before start"><i class="fa-solid fa-rotate"></i>
                                        Regenerate attendance code</button></form>
                                @if ($canEdit)
                                    <button type="button" class="pa-btn pa-outline pa-edit-drive"
                                        data-drive-id="{{ $drive->id }}" data-drive-title="{{ $drive->title }}"
                                        data-drive-description="{{ $drive->description }}"
                                        data-drive-location="{{ $drive->location }}"
                                        data-drive-latitude="{{ $drive->latitude }}"
                                        data-drive-longitude="{{ $drive->longitude }}"
                                        data-drive-start="{{ $drive->start_at->format('Y-m-d\\TH:i') }}"
                                        data-drive-end="{{ $drive->end_at->format('Y-m-d\\TH:i') }}"
                                        data-drive-slots="{{ $drive->slots }}"><i class="fa-solid fa-pen"></i> Edit
                                        drive</button>
                                @endif
                                @if (!in_array($drive->display_status, ['cancelled', 'completed']))
                                    <form method="POST" action="{{ route('purok-activities-db.cancel', $drive) }}"
                                        onsubmit="return confirm('Cancel this cleanup drive? Participants will see it as cancelled.')">
                                        @csrf<button class="pa-btn pa-danger"><i class="fa-solid fa-ban"></i> Cancel
                                            drive</button></form>
                                @endif
                            </div>
                            @if (session('generated_code') && session('code_drive_id') == $drive->id)
                                <div class="pa-inline-code"><strong>Current one-time code:</strong>
                                    {{ session('generated_code') }}</div>
                            @endif
                        </div>
                    </article>
                @empty<div class="pa-empty"><i class="fa-regular fa-calendar-plus"></i><strong>No cleanup drives
                            yet</strong>
                        <p>Create your first drive using the form.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </div>
    <div class="pa-modal" id="paParticipantsModal" hidden>
        <div class="pa-modal-backdrop" data-pa-close></div>
        <section class="pa-modal-box" role="dialog" aria-modal="true" aria-labelledby="paParticipantsTitle">
            <header>
                <div><span class="pa-eyebrow">REGISTRATION LIST</span>
                    <h2 id="paParticipantsTitle">Participants</h2>
                </div><button class="pa-modal-close" type="button" data-pa-close aria-label="Close"><i
                        class="fa-solid fa-xmark"></i></button>
            </header>
            <div class="pa-modal-content" id="paParticipantsContent"></div>
        </section>
    </div>
    <div class="pa-modal" id="paEditModal" hidden>
        <div class="pa-modal-backdrop" data-pa-close></div>
        <section class="pa-modal-box pa-edit-box" role="dialog" aria-modal="true">
            <header>
                <div><span class="pa-eyebrow">UPDATE DETAILS</span>
                    <h2>Edit cleanup drive</h2>
                </div><button class="pa-modal-close" type="button" data-pa-close><i
                        class="fa-solid fa-xmark"></i></button>
            </header>
            <div class="pa-modal-content">
                <form id="paEditForm" method="POST">@csrf @method('PUT')<label class="pa-field">Title<input
                            name="title" required maxlength="180"></label><label class="pa-field">Meeting point<input
                            name="location" id="paEditLocation" required maxlength="255"></label>
                    <div class="pa-map-wrap">
                        <div id="paEditMap" class="pa-map"></div>
                        <div class="pa-map-unavailable" id="paEditMapUnavailable" hidden>Map unavailable</div>
                    </div><input type="hidden" name="latitude" id="paEditLat"><input type="hidden" name="longitude"
                        id="paEditLng"><label class="pa-field">Description
                        <textarea name="description" rows="3" required></textarea>
                    </label>
                    <div class="pa-two-fields"><label class="pa-field">Starts<input name="start_at"
                                type="datetime-local" required></label><label class="pa-field">Ends<input name="end_at"
                                type="datetime-local" required></label></div><label class="pa-field">Slots<input
                            name="slots" type="number" min="1" max="1000" required></label><button
                        class="pa-btn pa-primary pa-full">Save changes</button>
                </form>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @if (session('generated_code'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const el = document.getElementById('generatedQr');
                if (el && window.QRCode) new QRCode(el, {
                    text: @json(session('generated_code')),
                    width: 148,
                    height: 148
                });
            });
        </script>
    @endif
    <script>
        (() => {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            let map = null,
                marker = null,
                editMap = null,
                editMarker = null,
                editTarget = null;
            const setPin = (m, mk, lat, lng, fields) => {
                const pos = {
                    lat: Number(lat),
                    lng: Number(lng)
                };
                if (!mk) {
                    mk = new google.maps.Marker({
                        map: m,
                        position: pos,
                        draggable: true,
                        title: 'Cleanup meeting point'
                    });
                    mk.addListener('dragend', () => fields(posOf(mk)));
                } else mk.setPosition(pos);
                m.setCenter(pos);
                m.setZoom(16);
                fields(pos);
                return mk;
            };
            const posOf = m => ({
                lat: m.getPosition().lat(),
                lng: m.getPosition().lng()
            });
            const updateCreate = (p) => {
                document.getElementById('driveLat').value = p.lat.toFixed(7);
                document.getElementById('driveLng').value = p.lng.toFixed(7);
                document.getElementById('paMapStatus').textContent =
                    'Map pin selected. Drag the pin to adjust the location.';
            };
            window.initCleanupDriveMaps = () => {
                const center = {
                    lat: 12.8797,
                    lng: 121.774
                };
                map = new google.maps.Map(document.getElementById('driveLocationMap'), {
                    center,
                    zoom: 6,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: true,
                    clickableIcons: false,
                    gestureHandling: 'greedy'
                });
                map.addListener('click', e => {
                    if (e.latLng) marker = setPin(map, marker, e.latLng.lat(), e.latLng.lng(),
                    updateCreate);
                });
                const oldLat = document.getElementById('driveLat').value,
                    oldLng = document.getElementById('driveLng').value;
                if (oldLat && oldLng) marker = setPin(map, marker, oldLat, oldLng, updateCreate);
                document.getElementById('paUseGps').addEventListener('click', () => {
                    const status = document.getElementById('paMapStatus');
                    if (!navigator.geolocation) {
                        status.textContent = 'Location is not supported in this browser.';
                        return;
                    }
                    status.textContent = 'Getting GPS location…';
                    navigator.geolocation.getCurrentPosition(pos => {
                            marker = setPin(map, marker, pos.coords.latitude, pos.coords.longitude,
                                updateCreate);
                            status.textContent = 'GPS pin set (accuracy approx. ' + Math.round(pos
                                .coords.accuracy) + 'm). Adjust pin if needed.';
                        }, () => status.textContent =
                        'Could not get GPS. Please tap the map to set a pin.', {
                            enableHighAccuracy: true,
                            timeout: 15000
                        });
                });
            };
            const modalShow = id => {
                const el = document.getElementById(id);
                el.hidden = false;
                document.body.classList.add('no-scroll');
            };
            const modalHide = () => {
                document.querySelectorAll('.pa-modal').forEach(m => m.hidden = true);
                document.body.classList.remove('no-scroll');
            };
            document.addEventListener('click', e => {
                if (e.target.closest('[data-pa-close]')) modalHide();
            });
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') modalHide();
            });
            const tierClass = t => t.toLowerCase().replace(/[^a-z]+/g, '-');
            document.querySelectorAll('.pa-participants').forEach(btn => btn.addEventListener('click', () => {
                const people = JSON.parse(btn.dataset.participants || '[]'),
                    title = btn.dataset.driveTitle,
                    canEdit = btn.dataset.canEdit === '1';
                document.getElementById('paParticipantsTitle').textContent = title + ' — Participants';
                const content = document.getElementById('paParticipantsContent');
                if (!people.length) {
                    content.innerHTML =
                        '<div class="pa-empty"><i class="fa-solid fa-user-group"></i><strong>No registrations yet</strong></div>';
                } else {
                    content.innerHTML = people.map(p =>
                        `<article class="pa-person"><div class="pa-person-head"><div class="pa-person-avatar">${escInitial(p.name)}</div><div class="pa-person-info"><strong>${escapeHtml(p.name)}</strong><small>${escapeHtml(p.email)} · ${p.points} pts</small></div><span class="pa-tier ${tierClass(p.tier)}">${escapeHtml(p.tier)}</span></div><div class="pa-person-meta"><span>Registered: ${escapeHtml(p.registered_at||'Not recorded')}</span><span>Status: ${escapeHtml(p.attendance_status)}</span>${p.verified_at?`<span>Verified: ${escapeHtml(p.verified_at)}</span>`:''}<span>Evidence: ${p.evidence_count}</span></div>${canEdit?`<button class="pa-link-button" type="button" data-edit-person='${JSON.stringify(p).replaceAll("'",'&#39;')}' data-registration-id="${p.id}"><i class="fa-regular fa-pen-to-square"></i> Edit registration</button>`:'<small class="pa-locked"><i class="fa-solid fa-lock"></i> Editing disabled after the drive starts</small>'}</article>`
                        ).join('');
                }
                modalShow('paParticipantsModal');
            }));

            function escapeHtml(s) {
                return String(s ?? '').replace(/[&<>"']/g, c => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [c]));
            }

            function escInitial(s) {
                return escapeHtml(String(s || '?').trim().split(/\s+/).slice(0, 2).map(x => x[0]).join('')
                .toUpperCase());
            }
            document.addEventListener('click', e => {
                const summary = e.target.closest('.pa-drive-summary');
                if (summary) {
                    const item = summary.closest('.pa-drive-item'),
                        detail = item.querySelector('.pa-drive-details'),
                        open = !detail.hidden;
                    detail.hidden = open;
                    summary.setAttribute('aria-expanded', String(!open));
                    item.classList.toggle('expanded', !open);
                    return;
                }
                const editPerson = e.target.closest('[data-edit-person]');
                if (editPerson) {
                    const p = JSON.parse(editPerson.dataset.editPerson),
                        form = document.getElementById('paEditParticipantForm');
                    if (!form) {
                        document.getElementById('paParticipantsContent').insertAdjacentHTML('beforeend',
                            `<form id="paEditParticipantForm" class="pa-person-edit" method="POST">@csrf @method('PUT')<label class="pa-field">Registration date/time<input name="registered_at" type="datetime-local" required></label><label class="pa-field">Registration status<select name="attendance_status"><option value="registered">Registered</option><option value="cancelled">Cancelled</option></select></label><button class="pa-btn pa-primary">Save participant</button><button type="button" class="pa-btn pa-outline" id="paEditPersonCancel">Cancel</button></form>`
                            );
                    }
                    const f = document.getElementById('paEditParticipantForm');
                    f.action =
                        `{{ url('/purok-activities/participants') }}/${editPerson.dataset.registrationId}`;
                    f.elements.namedItem('registered_at').value = p.registered_at;
                    f.elements.namedItem('attendance_status').value = p.attendance_status;
                    f.hidden = false;
                    document.getElementById('paEditPersonCancel').onclick = () => f.hidden = true;
                    return;
                }
                const driveEdit = e.target.closest('.pa-edit-drive');
                if (driveEdit) {
                    const d = {
                            id: driveEdit.dataset.driveId,
                            title: driveEdit.dataset.driveTitle,
                            description: driveEdit.dataset.driveDescription,
                            location: driveEdit.dataset.driveLocation,
                            latitude: driveEdit.dataset.driveLatitude,
                            longitude: driveEdit.dataset.driveLongitude,
                            start_at: driveEdit.dataset.driveStart,
                            end_at: driveEdit.dataset.driveEnd,
                            slots: driveEdit.dataset.driveSlots
                        },
                        f = document.getElementById('paEditForm');
                    f.action = `{{ url('/purok-activities') }}/${d.id}`;
                    f.elements.namedItem('title').value = d.title;
                    f.elements.namedItem('location').value = d.location;
                    f.elements.namedItem('description').value = d.description;
                    f.elements.namedItem('start_at').value = d.start_at;
                    f.elements.namedItem('end_at').value = d.end_at;
                    f.elements.namedItem('slots').value = d.slots;
                    document.getElementById('paEditLat').value = d.latitude || '';
                    document.getElementById('paEditLng').value = d.longitude || '';
                    modalShow('paEditModal');
                    setTimeout(() => {
                        if (window.google && google.maps) {
                            const center = d.latitude && d.longitude ? {
                                lat: Number(d.latitude),
                                lng: Number(d.longitude)
                            } : {
                                lat: 12.8797,
                                lng: 121.774
                            };
                            if (!editMap) {
                                editMap = new google.maps.Map(document.getElementById('paEditMap'), {
                                    center,
                                    zoom: d.latitude ? 16 : 6,
                                    mapTypeControl: false,
                                    streetViewControl: false,
                                    fullscreenControl: true,
                                    gestureHandling: 'greedy'
                                });
                                editMap.addListener('click', ev => {
                                    if (ev.latLng) editMarker = setPin(editMap, editMarker, ev
                                        .latLng.lat(), ev.latLng.lng(), p => {
                                            document.getElementById('paEditLat').value = p
                                                .lat;
                                            document.getElementById('paEditLng').value = p
                                                .lng;
                                        });
                                });
                            }
                            editMap.setCenter(center);
                            if (d.latitude && d.longitude) editMarker = setPin(editMap, editMarker, d
                                .latitude, d.longitude, p => {
                                    document.getElementById('paEditLat').value = p.lat;
                                    document.getElementById('paEditLng').value = p.lng;
                                });
                            google.maps.event.trigger(editMap, 'resize');
                        }
                    }, 120);
                    return;
                }
            });
            const mapKey = @json(config('services.google_maps.key') ?: env('GOOGLE_MAPS_API_KEY'));
            if (mapKey) {
                const s = document.createElement('script');
                s.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(mapKey) +
                    '&callback=initCleanupDriveMaps&loading=async';
                s.async = true;
                s.defer = true;
                s.onerror = () => {
                    document.getElementById('paMapUnavailable').hidden = false;
                    document.getElementById('paEditMapUnavailable').hidden = false;
                };
                document.head.appendChild(s);
            } else {
                document.getElementById('paMapUnavailable').hidden = false;
                document.getElementById('paEditMapUnavailable').hidden = false;
            }
        })();
    </script>
@endpush

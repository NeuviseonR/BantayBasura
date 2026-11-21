@php
    $u = auth()->user();
    $initials = strtoupper(mb_substr($u->first_name ?? $u->name, 0, 1) . mb_substr($u->last_name ?? '', 0, 1));
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bantay Basura - Report a Waste Issue</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/user-dash.css', 'resources/css/user-report.css', 'resources/css/custom.css'])
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
                        <div class="role">{{ auth()->user()->name }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>
            </div>
        </header>

        <main class="dashboard-content">

            <div class="page-title">
                <h1>Report a Waste Issue</h1>
                <p>Tell us what you found and where. Your report goes to your barangay's cleanup team.</p>
            </div>

            @if (session('success'))
                <div class="form-alert success" role="status">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="form-alert error" role="alert">
                    <span style="color: rgb(221, 68, 22)"><i class="fa-solid fa-triangle-exclamation"></i> Your report
                        wasn't sent yet. Please fix the items marked below.
                        @if ($errors->has('photo'))
                            Photos need to be chosen again after an error.
                        @endif
                    </span>
                </div>
            @endif

            <form class="report-layout" action="{{ route('user-report.store') }}" method="POST"
                enctype="multipart/form-data" id="reportForm" novalidate>
                @csrf

                {{-- Left: report details --}}
                <div class="form-card">
                    <div class="form-section-title">
                        <span class="form-section-icon"><i class="fa-regular fa-file-lines"></i></span>
                        <h3>Report details</h3>
                    </div>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" maxlength="100" value="{{ old('title') }}"
                            placeholder="e.g. Plastic waste on the sidewalk" required>
                        <span class="field-hint">A short summary of the problem.</span>
                        @error('title')
                            <span class="field-error" style="color: rgb(221, 68, 22)"><i
                                    class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <div class="select-wrap">
                            <select id="category" name="category" required>
                                <option value="" disabled @selected(!old('category'))>Choose a category</option>
                                @foreach ($categories as $value => $label)
                                    <option value="{{ $value }}" @selected(old('category') === $value)>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        @error('category')
                            <span class="field-error" style="color: rgb(221, 68, 22)"><i
                                    class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="5" maxlength="500"
                            placeholder="What did you see? How big is the problem? Is it blocking a road or path?" required>{{ old('description') }}</textarea>
                        <span class="field-hint char-count"><span
                                id="charCount">{{ strlen(old('description', '')) }}</span>/500</span>
                        @error('description')
                            <span class="field-error" style="color: rgb(221, 68, 22)"><i
                                    class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Right: photo + location --}}
                <div class="form-side">

                    <div class="form-card">
                        <div class="form-section-title">
                            <span class="form-section-icon"><i class="fa-solid fa-camera"></i></span>
                            <h3>Photo</h3>
                        </div>

                        <label class="photo-drop" for="photo" id="photoDrop">
                            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp"
                                required>
                            <div class="photo-empty" id="photoEmpty">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <strong>Add a photo</strong>
                                <span>Tap to take or choose a picture (JPG, PNG or WEBP, up to 5 MB)</span>
                            </div>
                            <img id="photoPreview" class="photo-preview" alt="Selected photo preview" hidden>
                        </label>
                        <button type="button" class="btn-text" id="photoRemove" hidden>
                            <i class="fa-regular fa-trash-can"></i> Remove photo
                        </button>
                        <span class="field-error" id="photoSizeError" hidden>That photo is over 5 MB. Please choose a
                            smaller one.</span>
                        @error('photo')
                            <span class="field-error" style="color: rgb(221, 68, 22)"><i
                                    class="fa-solid fa-triangle-exclamation"></i>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-card">
                        <div class="form-section-title">
                            <span class="form-section-icon"><i class="fa-solid fa-location-dot"></i></span>
                            <h3>Location</h3>
                        </div>

                        <div class="location-intro">
                            <p>Use your device GPS to find the area, then check the marker on the map. You can drag the
                                pin or tap the map to correct its position.</p>
                        </div>

                        <div class="location-box" id="locationBox" data-state="idle">
                            <div class="location-status" id="locationStatus" role="status" aria-live="polite">
                                <i class="fa-solid fa-location-crosshairs"></i>
                                <span>Location not selected yet</span>
                            </div>
                            <div class="location-coords" id="locationCoords" hidden></div>
                        </div>

                        <div class="map-frame">
                            <div id="locationMap" class="location-map" role="application"
                                aria-label="Map for selecting the waste location"></div>
                            <div class="map-unavailable" id="mapUnavailable" hidden>
                                <i class="fa-solid fa-map-location-dot"></i>
                                <strong>Map unavailable</strong>
                                <span>Check the Google Maps API key configuration, then reload this page. You can still
                                    try GPS positioning.</span>
                            </div>
                        </div>
                        <p class="map-help"><i class="fa-solid fa-hand-pointer"></i> Drag the marker or tap the map to
                            set the exact waste location.</p>

                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

                        <button type="button" class="btn-secondary" id="locateBtn">
                            <i class="fa-solid fa-location-crosshairs"></i> Use my current GPS
                        </button>
                        <span class="field-hint">For the best result, enable your phone's location services and allow
                            location access in your browser. A GPS reading over 100 meters must be corrected using the
                            map pin.</span>
                        <span class="field-error" id="locationRequired" hidden>Please select a location. If GPS
                            accuracy is poor, adjust the map pin before submitting.</span>
                        @if ($errors->has('latitude') || $errors->has('longitude'))
                            <span
                                class="field-error">{{ $errors->first('latitude') ?: $errors->first('longitude') }}</span>
                        @endif
                    </div>

                </div>

                {{-- Actions --}}
                <div class="form-actions">
                    <a href="{{ route('user-dash') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-report btn-submit" id="submitBtn">
                        <i class="fa-solid fa-paper-plane"></i> Submit report
                    </button>
                </div>
            </form>

        </main>
    </div>

    <script>
        // Mobile menu
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('navList').classList.toggle('active');
        });

        // Description counter
        const desc = document.getElementById('description');
        const count = document.getElementById('charCount');
        desc.addEventListener('input', () => count.textContent = desc.value.length);

        // Photo preview + size check
        const MAX_PHOTO = 5 * 1024 * 1024;
        const photo = document.getElementById('photo');
        const preview = document.getElementById('photoPreview');
        const empty = document.getElementById('photoEmpty');
        const removeBtn = document.getElementById('photoRemove');
        const drop = document.getElementById('photoDrop');
        const sizeError = document.getElementById('photoSizeError');

        photo.addEventListener('change', () => {
            const file = photo.files[0];
            if (!file) return resetPhoto();
            if (file.size > MAX_PHOTO) {
                resetPhoto();
                sizeError.hidden = false;
                return;
            }
            sizeError.hidden = true;
            preview.src = URL.createObjectURL(file);
            preview.hidden = false;
            empty.hidden = true;
            removeBtn.hidden = false;
            drop.classList.add('has-photo');
        });

        function resetPhoto() {
            photo.value = '';
            preview.hidden = true;
            preview.removeAttribute('src');
            empty.hidden = false;
            removeBtn.hidden = true;
            drop.classList.remove('has-photo');
        }
        removeBtn.addEventListener('click', () => {
            resetPhoto();
            sizeError.hidden = true;
        });

        // GPS + Google Maps location picker
        const locateBtn = document.getElementById('locateBtn');
        const statusEl = document.getElementById('locationStatus');
        const coordsEl = document.getElementById('locationCoords');
        const box = document.getElementById('locationBox');
        const latEl = document.getElementById('latitude');
        const lngEl = document.getElementById('longitude');
        const locReq = document.getElementById('locationRequired');
        const mapUnavailable = document.getElementById('mapUnavailable');
        const GOOGLE_MAPS_KEY = @json(config('services.google_maps.key') ?: 'AIzaSyA0PGv7CApwQ0mU-rOactvS098TEZxCLpg');
        const MAX_ACCEPTED_ACCURACY = 100; // meters; worse readings need manual pin correction

        let map = null;
        let marker = null;
        let accuracyCircle = null;
        let currentAccuracy = null;
        let manuallyAdjusted = false;
        let locationChosen = false;

        function setStatus(icon, message, state) {
            statusEl.innerHTML = '<i class="' + icon + '"></i><span>' + message + '</span>';
            box.dataset.state = state || 'idle';
        }

        function updateCoordinateFields(lat, lng) {
            latEl.value = Number(lat).toFixed(7);
            lngEl.value = Number(lng).toFixed(7);
            coordsEl.textContent = latEl.value + ', ' + lngEl.value;
            coordsEl.hidden = false;
            locationChosen = true;
            locReq.hidden = true;
        }

        function placeMarker(lat, lng, source) {
            if (!map || !window.google || !google.maps) return;
            const position = {
                lat: Number(lat),
                lng: Number(lng)
            };
            if (!marker) {
                marker = new google.maps.Marker({
                    position,
                    map,
                    draggable: true,
                    title: 'Waste report location'
                });
                marker.addListener('dragend', () => {
                    manuallyAdjusted = true;
                    currentAccuracy = null;
                    if (accuracyCircle) accuracyCircle.setMap(null);
                    updateCoordinateFields(marker.getPosition().lat(), marker.getPosition().lng());
                    setStatus('fa-solid fa-circle-check', 'Pin adjusted manually — location selected', 'success');
                });
            } else {
                marker.setPosition(position);
            }
            map.setCenter(position);
            updateCoordinateFields(position.lat, position.lng);

            if (source === 'gps') {
                if (currentAccuracy !== null && currentAccuracy <= MAX_ACCEPTED_ACCURACY) {
                    setStatus('fa-solid fa-circle-check', 'GPS location acquired — accuracy about ' + Math.round(
                        currentAccuracy) + ' m', 'success');
                } else {
                    setStatus('fa-solid fa-triangle-exclamation', 'GPS is approximate (about ' + Math.round(
                        currentAccuracy || 0) + ' m). Drag the pin or tap the map to correct it.', 'warning');
                }
            } else if (source === 'manual') {
                manuallyAdjusted = true;
                currentAccuracy = null;
                if (accuracyCircle) accuracyCircle.setMap(null);
                setStatus('fa-solid fa-circle-check', 'Map pin selected manually', 'success');
            }
            locateBtn.innerHTML = '<i class="fa-solid fa-rotate"></i> Retry GPS';
        }

        function initWasteReportMap() {
            const mapElement = document.getElementById('locationMap');
            map = new google.maps.Map(mapElement, {
                center: {
                    lat: 12.8797,
                    lng: 121.7740
                },
                zoom: 6,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                clickableIcons: false,
                gestureHandling: 'greedy'
            });

            map.addListener('click', (event) => {
                if (event.latLng) placeMarker(event.latLng.lat(), event.latLng.lng(), 'manual');
            });

            // Restore a previously submitted coordinate after a validation error.
            if (latEl.value && lngEl.value) {
                manuallyAdjusted = true;
                placeMarker(Number(latEl.value), Number(lngEl.value), 'manual');
            }
        }
        window.initWasteReportMap = initWasteReportMap;

        if (GOOGLE_MAPS_KEY) {
            const mapsScript = document.createElement('script');
            mapsScript.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(GOOGLE_MAPS_KEY) +
                '&callback=initWasteReportMap&loading=async';
            mapsScript.async = true;
            mapsScript.defer = true;
            mapsScript.onerror = () => {
                mapUnavailable.hidden = false;
            };
            document.head.appendChild(mapsScript);
        } else {
            mapUnavailable.hidden = false;
        }

        locateBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                return setStatus('fa-solid fa-triangle-exclamation',
                    'This browser does not support location sharing.', 'error');
            }
            setStatus('fa-solid fa-spinner fa-spin', 'Finding your location… Keep the page open while GPS updates.',
                'loading');
            locateBtn.disabled = true;
            manuallyAdjusted = false;
            currentAccuracy = null;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const {
                        latitude,
                        longitude,
                        accuracy
                    } = position.coords;
                    currentAccuracy = accuracy;
                    if (map) {
                        placeMarker(latitude, longitude, 'gps');
                        map.setZoom(accuracy <= 100 ? 17 : accuracy <= 1000 ? 14 : 11);
                        if (accuracyCircle) accuracyCircle.setMap(null);
                        accuracyCircle = new google.maps.Circle({
                            map,
                            center: {
                                lat: latitude,
                                lng: longitude
                            },
                            radius: accuracy,
                            strokeColor: '#D87727',
                            strokeOpacity: 0.9,
                            strokeWeight: 2,
                            fillColor: '#D87727',
                            fillOpacity: 0.12,
                            clickable: false
                        });
                        // Re-apply GPS warning after placeMarker has updated the status.
                        if (accuracy > MAX_ACCEPTED_ACCURACY) {
                            setStatus('fa-solid fa-triangle-exclamation', 'GPS accuracy is about ' + Math.round(
                                    accuracy) + ' m. Please adjust the pin to confirm the exact spot.',
                                'warning');
                        }
                    } else {
                        updateCoordinateFields(latitude, longitude);
                        setStatus(accuracy <= MAX_ACCEPTED_ACCURACY ? 'fa-solid fa-circle-check' :
                            'fa-solid fa-triangle-exclamation',
                            'GPS accuracy about ' + Math.round(accuracy) + ' m. ' + (accuracy >
                                MAX_ACCEPTED_ACCURACY ?
                                'A map is required to correct this approximate position.' :
                                'Location acquired.'),
                            accuracy <= MAX_ACCEPTED_ACCURACY ? 'success' : 'warning');
                    }
                    locateBtn.disabled = false;
                },
                (error) => {
                    const message = error.code === 1 ?
                        'Location permission was denied. Allow it in your browser settings and try again.' :
                        error.code === 3 ?
                        'Location request timed out. Move outdoors or retry with a clearer GPS signal.' :
                        'Could not determine your location. Check your device location settings and retry.';
                    setStatus('fa-solid fa-triangle-exclamation', message, 'error');
                    locateBtn.disabled = false;
                }, {
                    enableHighAccuracy: true,
                    timeout: 30000,
                    maximumAge: 0
                }
            );
        });

        // Before sending: make sure a location was sent, and stop double-clicks
        document.getElementById('reportForm').addEventListener('submit', (e) => {
            const poorGpsNeedsCorrection = currentAccuracy !== null && currentAccuracy > MAX_ACCEPTED_ACCURACY && !
                manuallyAdjusted;
            if (!latEl.value || !lngEl.value || poorGpsNeedsCorrection) {
                e.preventDefault();
                locReq.hidden = false;
                locateBtn.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return;
            }
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending…';
        });
    </script>
</body>

</html>

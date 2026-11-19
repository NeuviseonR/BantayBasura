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
        <div class="avatar-initials">{{ $initials }}</div>
        <div class="greeting-text">
          <h2>Hello, {{ $u->name }}!</h2>
          <p>Resident</p>
        </div>
      </div>

      <div class="top-actions">
        <button class="notification-btn" aria-label="Notifications">
          <i class="fa-regular fa-bell"></i>
          <span class="notification-dot"></span>
        </button>

        <div class="profile-pill">
          <div class="avatar-initials">{{ $initials }}</div>
          <div class="profile-info">
            <div class="name">{{ $u->name }}</div>
            <div class="role">Resident</div>
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
          <i class="fa-solid fa-triangle-exclamation"></i>
          <span>Your report wasn't sent yet. Please fix the items marked below.
            @if ($errors->has('photo')) Photos need to be chosen again after an error. @endif
          </span>
        </div>
      @endif

      <form class="report-layout" action="{{ route('user-report.store') }}" method="POST" enctype="multipart/form-data" id="reportForm" novalidate>
        @csrf

        {{-- Left: report details --}}
        <div class="form-card">
          <div class="form-section-title">
            <span class="form-section-icon"><i class="fa-regular fa-file-lines"></i></span>
            <h3>Report details</h3>
          </div>

          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" maxlength="100" value="{{ old('title') }}" placeholder="e.g. Plastic waste on the sidewalk" required>
            <span class="field-hint">A short summary of the problem.</span>
            @error('title') <span class="field-error">{{ $message }}</span> @enderror
          </div>

          <div class="form-group">
            <label for="category">Category</label>
            <div class="select-wrap">
              <select id="category" name="category" required>
                <option value="" disabled @selected(! old('category'))>Choose a category</option>
                @foreach ($categories as $value => $label)
                  <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                @endforeach
              </select>
              <i class="fa-solid fa-chevron-down"></i>
            </div>
            @error('category') <span class="field-error">{{ $message }}</span> @enderror
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" maxlength="500" placeholder="What did you see? How big is the problem? Is it blocking a road or path?" required>{{ old('description') }}</textarea>
            <span class="field-hint char-count"><span id="charCount">{{ strlen(old('description', '')) }}</span>/500</span>
            @error('description') <span class="field-error">{{ $message }}</span> @enderror
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
              <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" required>
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
            <span class="field-error" id="photoSizeError" hidden>That photo is over 5 MB. Please choose a smaller one.</span>
            @error('photo') <span class="field-error">{{ $message }}</span> @enderror
          </div>

          <div class="form-card">
            <div class="form-section-title">
              <span class="form-section-icon"><i class="fa-solid fa-location-dot"></i></span>
              <h3>Location</h3>
            </div>

            <div class="location-box" id="locationBox">
              <div class="location-status" id="locationStatus">
                <i class="fa-solid fa-location-crosshairs"></i>
                <span>No location sent yet</span>
              </div>
              <div class="location-coords" id="locationCoords" hidden></div>
            </div>

            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">

            <button type="button" class="btn-secondary" id="locateBtn">
              <i class="fa-solid fa-location-arrow"></i> Send my GPS location
            </button>
            <span class="field-hint">Stand near the waste when you send it so the team can find it.</span>
            <span class="field-error" id="locationRequired" hidden>Please send your GPS location first.</span>
            @if ($errors->has('latitude') || $errors->has('longitude'))
              <span class="field-error">{{ $errors->first('latitude') ?: $errors->first('longitude') }}</span>
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
    document.getElementById('menuToggle').addEventListener('click', function () {
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
    removeBtn.addEventListener('click', () => { resetPhoto(); sizeError.hidden = true; });

    // GPS location
    const locateBtn = document.getElementById('locateBtn');
    const statusEl = document.getElementById('locationStatus');
    const coordsEl = document.getElementById('locationCoords');
    const box = document.getElementById('locationBox');
    const latEl = document.getElementById('latitude');
    const lngEl = document.getElementById('longitude');
    const locReq = document.getElementById('locationRequired');

    function setStatus(icon, text, state) {
      statusEl.innerHTML = '<i class="' + icon + '"></i><span>' + text + '</span>';
      box.dataset.state = state || '';
    }

    function showSent(lat, lng, accuracy) {
      setStatus('fa-solid fa-circle-check', 'Location sent', 'success');
      coordsEl.textContent = lat + ', ' + lng + (accuracy ? ' (accuracy about ' + Math.round(accuracy) + ' m)' : '');
      coordsEl.hidden = false;
      locateBtn.innerHTML = '<i class="fa-solid fa-rotate"></i> Update location';
      locReq.hidden = true;
    }

    // If the page reloaded after a validation error, keep the location that was already sent
    if (latEl.value && lngEl.value) showSent(latEl.value, lngEl.value);

    locateBtn.addEventListener('click', () => {
      if (!navigator.geolocation) {
        return setStatus('fa-solid fa-triangle-exclamation', 'This browser can\'t share a location. Try another browser.', 'error');
      }
      setStatus('fa-solid fa-spinner fa-spin', 'Finding your location…', 'loading');
      locateBtn.disabled = true;

      navigator.geolocation.getCurrentPosition(
        (pos) => {
          latEl.value = pos.coords.latitude.toFixed(7);
          lngEl.value = pos.coords.longitude.toFixed(7);
          showSent(latEl.value, lngEl.value, pos.coords.accuracy);
          locateBtn.disabled = false;
        },
        (err) => {
          const msg = err.code === 1
            ? 'Location access was blocked. Allow it in your browser settings and try again.'
            : 'Couldn\'t get your location. Check your signal and try again.';
          setStatus('fa-solid fa-triangle-exclamation', msg, 'error');
          locateBtn.disabled = false;
        },
        { enableHighAccuracy: true, timeout: 15000 }
      );
    });

    // Before sending: make sure a location was sent, and stop double-clicks
    document.getElementById('reportForm').addEventListener('submit', (e) => {
      if (!latEl.value || !lngEl.value) {
        e.preventDefault();
        locReq.hidden = false;
        locateBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
      }
      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending…';
    });
  </script>
</body>
</html>

@extends('layouts.purok')

@section('title', 'Profile')

@section('content')
@php
  // Sample leader. Later, build this from the logged-in user.
  $leader = $leader ?? ['name' => 'Ana Santos', 'initials' => 'AS', 'purok' => 'Purok 3'];
  // SAMPLE DATA for the view-only prototype. Later, pass $leaderProfile from the logged-in user.
  $p = $leaderProfile ?? [
    'name' => 'Ana Santos', 'initials' => 'AS', 'email' => 'ana.santos@example.com', 'mobile' => '0917 000 0000',
    'barangay' => 'Barangay sample', 'purok' => 'Purok 3', 'since' => 'January 2024', 'term' => 'Jan 2024 - Dec 2026',
    'handled' => 58, 'led' => 14, 'volunteers' => 12,
  ];
  $duties = [
    'Check and update waste reports from your purok',
    'Plan and lead cleanup activities',
    'Endorse volunteer applications from residents',
    'Send announcements about schedules and drives',
  ];
@endphp

<div class="page-title">
  <h1>Profile</h1>
  <p>Manage your details, password and notification settings.</p>
</div>

<section class="pf-banner">
  <i class="fa-solid fa-leaf pf-banner-leaf"></i>
  <div class="pf-avatar">{{ $p['initials'] }}</div>
  <div class="pf-banner-text">
    <h2>{{ $p['name'] }}</h2>
    <div class="pf-tags">
      <span class="pf-tag"><i class="fa-solid fa-user-shield"></i> Purok Leader</span>
      <span class="pf-tag"><i class="fa-solid fa-location-dot"></i> {{ $p['purok'] }}</span>
      <span class="pf-tag"><i class="fa-regular fa-calendar"></i> Leader since {{ $p['since'] }}</span>
    </div>
  </div>
</section>

<section class="pf-layout">
  <div class="pf-main">

    <form class="pg-card" id="infoForm" action="#" method="POST" novalidate>
      @csrf
      <div class="pg-card-head">
        <h3><i class="fa-regular fa-id-card"></i> Personal information</h3>
        <button type="button" class="btn-ghost btn-sm" id="editBtn"><i class="fa-solid fa-pen"></i> Edit</button>
      </div>

      <div class="form-grid">
        <div class="field"><label for="pName">Full name</label><input type="text" id="pName" value="{{ $p['name'] }}" disabled></div>
        <div class="field"><label for="pEmail">Email</label><input type="email" id="pEmail" value="{{ $p['email'] }}" disabled></div>
        <div class="field"><label for="pMobile">Mobile number</label><input type="tel" id="pMobile" value="{{ $p['mobile'] }}" disabled></div>
        <div class="field"><label for="pBarangay">Barangay</label><input type="text" id="pBarangay" value="{{ $p['barangay'] }}" disabled></div>
      </div>

      <p class="field-hint">Your assigned purok and term are set by the barangay and can't be edited here.</p>

      <div class="form-actions" id="infoActions" hidden>
        <button type="button" class="btn-ghost" id="cancelBtn">Cancel</button>
        <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Save changes</button>
      </div>
    </form>

    <form class="pg-card" id="passForm" action="#" method="POST" novalidate>
      @csrf
      <div class="pg-card-head"><h3><i class="fa-solid fa-lock"></i> Change password</h3></div>
      <div class="form-grid">
        <div class="field wide"><label for="curPass">Current password</label><input type="password" id="curPass" autocomplete="current-password"></div>
        <div class="field"><label for="newPass">New password</label><input type="password" id="newPass" autocomplete="new-password"><span class="field-hint">At least 8 characters, with letters and numbers.</span></div>
        <div class="field"><label for="confPass">Confirm new password</label><input type="password" id="confPass" autocomplete="new-password"><span class="field-error" id="passMsg" hidden></span></div>
      </div>
      <div class="form-actions"><button type="submit" class="btn-primary"><i class="fa-solid fa-key"></i> Update password</button></div>
    </form>

  </div>

  <div class="pf-side">
    <div class="pg-card">
      <div class="pg-card-head"><h3><i class="fa-solid fa-ranking-star"></i> My activity</h3></div>
      <div class="mini-stats">
        <div><strong>{{ $p['handled'] }}</strong><span>Reports handled</span></div>
        <div><strong>{{ $p['led'] }}</strong><span>Drives led</span></div>
        <div><strong>{{ $p['volunteers'] }}</strong><span>Volunteers</span></div>
      </div>
    </div>

    <div class="pg-card">
      <div class="pg-card-head"><h3><i class="fa-solid fa-user-shield"></i> My role</h3></div>
      <div class="status-row"><span>Assigned purok</span><strong>{{ $p['purok'] }}</strong></div>
      <div class="status-row"><span>Term</span><strong>{{ $p['term'] }}</strong></div>
      <ul class="duty-list">
        @foreach ($duties as $d)
          <li><i class="fa-solid fa-check"></i>{{ $d }}</li>
        @endforeach
      </ul>
    </div>

    <div class="pg-card">
      <div class="pg-card-head"><h3><i class="fa-regular fa-bell"></i> Notifications</h3></div>
      <div class="pref-list">
        @foreach ([
          ['New reports in my purok', 'As soon as a resident sends one', true],
          ['Volunteer applications', 'When someone applies to volunteer', true],
          ['Activity reminders', 'The day before a cleanup drive', true],
          ['Messages', 'Replies from residents and officials', false],
        ] as [$label, $desc, $on])
          <div class="pref-row">
            <div><div class="item-title">{{ $label }}</div><div class="item-subtext">{{ $desc }}</div></div>
            <button type="button" role="switch" class="switch" aria-checked="{{ $on ? 'true' : 'false' }}" aria-label="{{ $label }}"><span></span></button>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // ---------- Edit personal info ----------
  const fields = document.querySelectorAll('#infoForm input:not([type=hidden])');
  const original = {};
  function setEditing(on) {
    fields.forEach((f) => { f.disabled = !on; });
    $('infoActions').hidden = !on;
    $('editBtn').hidden = on;
    if (on) fields[0].focus();
  }
  $('editBtn').addEventListener('click', () => { fields.forEach((f) => { original[f.id] = f.value; }); setEditing(true); });
  $('cancelBtn').addEventListener('click', () => { fields.forEach((f) => { f.value = original[f.id]; }); setEditing(false); });
  $('infoForm').addEventListener('submit', (e) => { e.preventDefault(); setEditing(false); PL.toast('Saved (prototype only)'); });

  // ---------- Password ----------
  function checkMatch() {
    const bad = $('confPass').value && $('newPass').value !== $('confPass').value;
    $('passMsg').hidden = !bad;
    $('passMsg').textContent = bad ? 'Passwords don\'t match.' : '';
  }
  $('newPass').addEventListener('input', checkMatch);
  $('confPass').addEventListener('input', checkMatch);
  $('passForm').addEventListener('submit', (e) => { e.preventDefault(); });

  // ---------- Switches ----------
  document.querySelectorAll('.pref-row .switch').forEach((sw) => {
    sw.addEventListener('click', () => sw.setAttribute('aria-checked', sw.getAttribute('aria-checked') !== 'true'));
  });
</script>
@endpush

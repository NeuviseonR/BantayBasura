@extends('layouts.site')

@section('title', 'Log In')

@push('styles')
    @vite(['resources/css/login.css'])
@endpush

@section('content')
@php
    // If the sign-up form failed validation, reopen the sign-up card.
    $showSignup = $errors->hasBag('register');
@endphp

<div class="auth-wrapper">

    <!-- ================= LOGIN ================= -->
    <div class="auth-card" id="loginCard" @if ($showSignup) style="display: none;" @endif>
        <div class="auth-header">
            <div class="auth-logo">&#x267B; Community Clean</div>
            <h2>Welcome Back</h2>
            <p>Log in to report waste issues, track your reports and join community clean-up drives.</p>
        </div>

        @if (session('status'))
            <div class="auth-alert success" role="status">{{ session('status') }}</div>
        @endif

        @if (! $showSignup && $errors->any())
            <div class="auth-alert error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form class="auth-form" action="{{ route('login.attempt') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="loginEmail">Email Address</label>
                <input type="email" id="loginEmail" name="email" placeholder="Enter your email"
                       value="{{ $showSignup ? '' : old('email') }}" autocomplete="email" required>
            </div>

            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="password" placeholder="Enter your password"
                       autocomplete="current-password" required>
            </div>

            <label class="check-row">
                <input type="checkbox" name="remember" value="1">
                <span>Keep me logged in</span>
            </label>

            <button type="submit" class="auth-btn">Log In &#x21AA;</button>
        </form>

        <div class="auth-switch">
            <p>Don't have an account? <a href="#" onclick="toggleAuth('signup'); event.preventDefault();">Sign Up</a></p>
            <p><a href="{{ route('password.request') }}">Forgot password?</a></p>
        </div>
    </div>

    <!-- ================= SIGN UP ================= -->
    <div class="auth-card wide" id="signupCard" @if (! $showSignup) style="display: none;" @endif>
        <div class="auth-header">
            <div class="auth-logo">&#x267B; Community Clean</div>
            <h2>Create an Account</h2>
            <p>Sign up as a resident. You can apply to become a volunteer later, from the Cleanup Drives page.</p>
        </div>

        @if ($errors->hasBag('register'))
            <div class="auth-alert error" role="alert">Please fix the highlighted fields and try again.</div>
        @endif

        <form class="auth-form" action="{{ route('register') }}" method="POST" novalidate>
            @csrf

            <fieldset class="form-section">
                <legend>About you</legend>

                <div class="form-row three">
                    <div class="form-group">
                        <label for="firstName">First name</label>
                        <input type="text" id="firstName" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name" required>
                        @error('first_name', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="middleName">Middle name <small>(optional)</small></label>
                        <input type="text" id="middleName" name="middle_name" value="{{ old('middle_name') }}" autocomplete="additional-name">
                        @error('middle_name', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last name</label>
                        <input type="text" id="lastName" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name" required>
                        @error('last_name', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row three">
                    <div class="form-group">
                        <label for="birthdate">Date of birth</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}"
                               max="{{ now()->subYears($minAge)->toDateString() }}" autocomplete="bday" required>
                        @error('birthdate', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex <small>(optional)</small></label>
                        <select id="sex" name="sex">
                            <option value="">Select</option>
                            @foreach ($sexes as $s)
                                <option value="{{ $s }}" @selected(old('sex') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('sex', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile number</label>
                        <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="0917 123 4567" autocomplete="tel" required>
                        @error('mobile', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Where you live</legend>

                <div class="form-row">
                    <div class="form-group">
                        <label for="barangay">Barangay</label>
                        <select id="barangay" name="barangay_id" required>
                            <option value="">Choose your barangay</option>
                            @foreach ($barangays as $b)
                                <option value="{{ $b->id }}" @selected((string) old('barangay_id') === (string) $b->id)>{{ $b->name }}</option>
                            @endforeach
                        </select>
                        @error('barangay_id', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="zone">Zone</label>
                        <select id="zone" name="zone" required>
                            <option value="">Choose your zone</option>
                            @foreach ($zones as $z)
                                <option value="{{ $z }}" @selected(old('zone') === $z)>{{ $z }}</option>
                            @endforeach
                        </select>
                        @error('zone', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Purok / street address</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="e.g. Purok 3, Mabini Street" autocomplete="street-address" required>
                    @error('address', 'register') <span class="field-error">{{ $message }}</span> @enderror
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Your account</legend>

                <div class="form-group">
                    <label for="signupEmail">Email address</label>
                    <input type="email" id="signupEmail" name="email" value="{{ $showSignup ? old('email') : '' }}" placeholder="Enter your email" autocomplete="email" required>
                    @error('email', 'register') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="signupPassword">Password</label>
                        <input type="password" id="signupPassword" name="password" placeholder="At least 8 characters" autocomplete="new-password" required>
                        <span class="field-hint">Use letters and numbers.</span>
                        @error('password', 'register') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="signupPasswordConfirm">Confirm password</label>
                        <input type="password" id="signupPasswordConfirm" name="password_confirmation" placeholder="Type it again" autocomplete="new-password" required>
                    </div>
                </div>
            </fieldset>

            <div class="form-group">
                <label class="check-row">
                    <input type="checkbox" name="terms" value="1" @checked(old('terms')) required>
                    <span>I agree to the Terms &amp; Conditions and allow the barangay to store my details for reports and clean-up drives, as described in the Privacy Policy.</span>
                </label>
                @error('terms', 'register') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="auth-btn">Register Account &#x21AA;</button>
        </form>

        <div class="auth-switch">
            <p>Already have an account? <a href="#" onclick="toggleAuth('login'); event.preventDefault();">Log In</a></p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function toggleAuth(view) {
        document.getElementById('loginCard').style.display = view === 'signup' ? 'none' : 'block';
        document.getElementById('signupCard').style.display = view === 'signup' ? 'block' : 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endpush

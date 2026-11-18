@extends('layouts.site')

@section('title', 'Reset Password')

@push('styles')
    @vite(['resources/css/login.css'])
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">&#x267B; Community Clean</div>
            <h2>Choose a New Password</h2>
            <p>Pick a password you haven't used before. Use at least 8 characters, with letters and numbers.</p>
        </div>

        <form class="auth-form" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" autocomplete="email" required>
                @error('email') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">New password</label>
                <input type="password" id="password" name="password" autocomplete="new-password" required>
                @error('password') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="passwordConfirm">Confirm new password</label>
                <input type="password" id="passwordConfirm" name="password_confirmation" autocomplete="new-password" required>
            </div>

            <button type="submit" class="auth-btn">Update Password &#x21AA;</button>
        </form>

        <div class="auth-switch">
            <p><a href="{{ route('login') }}">&larr; Back to log in</a></p>
        </div>
    </div>
</div>
@endsection

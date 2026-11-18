@extends('layouts.site')

@section('title', 'Forgot Password')

@push('styles')
    @vite(['resources/css/login.css'])
@endpush

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo">&#x267B; Community Clean</div>
            <h2>Forgot Password?</h2>
            <p>Enter the email you signed up with and we'll send you a link to choose a new password.</p>
        </div>

        @if (session('status'))
            <div class="auth-alert success" role="status">{{ session('status') }}</div>
        @endif

        <form class="auth-form" action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" autocomplete="email" required>
                @error('email') <span class="field-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="auth-btn">Send Reset Link &#x21AA;</button>
        </form>

        <div class="auth-switch">
            <p><a href="{{ route('login') }}">&larr; Back to log in</a></p>
        </div>
    </div>
</div>
@endsection

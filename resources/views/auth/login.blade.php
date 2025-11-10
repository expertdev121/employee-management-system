@extends('layouts.app')

@section('content')
<!-- Tailwind CSS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<style>
    /* Reset body and html for full viewport centering */
    html, body {
        height: 100%;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden;
    }

    /* Reset any parent container margins/padding */
    #app, .container, .container-fluid, main {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Custom styles to match the design */
    .auth-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        overflow: hidden;
        margin: 0;
    }

    .dark .auth-container {
        background: rgb(17, 24, 39);
    }

    .content-wrapper {
        max-width: 28rem;
        width: 100%;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    @media (min-width: 640px) {
        .page-title {
            font-size: 2.25rem;
        }
    }

    .dark .page-title {
        color: rgba(255, 255, 255, 0.9);
    }

    .page-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .dark .page-subtitle {
        color: rgba(156, 163, 175, 1);
    }

    .form-label {
        display: block;
        margin-bottom: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    .dark .form-label {
        color: rgba(156, 163, 175, 1);
    }

    .required {
        color: #ef4444;
    }

    .form-input {
        height: 2.75rem;
        width: 100%;
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        background-color: transparent;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        color: #1f2937;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #93c5fd;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-input::placeholder {
        color: #9ca3af;
    }

    .dark .form-input {
        border-color: #374151;
        background-color: rgb(17, 24, 39);
        color: rgba(255, 255, 255, 0.9);
    }

    .dark .form-input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }

    .dark .form-input:focus {
        border-color: #1e3a8a;
    }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 30;
        color: #6b7280;
        cursor: pointer;
    }

    .dark .password-toggle {
        color: rgba(156, 163, 175, 1);
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        cursor: pointer;
        user-select: none;
        font-size: 0.875rem;
        font-weight: 400;
        color: #374151;
    }

    .dark .custom-checkbox {
        color: rgba(156, 163, 175, 1);
    }

    .checkbox-wrapper {
        position: relative;
        margin-right: 0.75rem;
    }

    .checkbox-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkbox-box {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 1.25rem;
        width: 1.25rem;
        border: 1.25px solid #d1d5db;
        border-radius: 0.375rem;
        background-color: transparent;
        transition: all 0.2s;
    }

    .dark .checkbox-box {
        border-color: #374151;
    }

    .checkbox-input:checked + .checkbox-box {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .checkbox-check {
        opacity: 0;
        transition: opacity 0.2s;
    }

    .checkbox-input:checked + .checkbox-box .checkbox-check {
        opacity: 1;
    }

    .link-primary {
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.2s;
    }

    .link-primary:hover {
        color: #2563eb;
    }

    .dark .link-primary {
        color: #60a5fa;
    }

    .btn-primary-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: white;
        background-color: #3b82f6;
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: background-color 0.2s;
    }

    .btn-primary-custom:hover {
        background-color: #2563eb;
    }
</style>

<div class="auth-container">
    <div class="content-wrapper">
        <div>
            <div style="margin-bottom: 2rem;">
                <h1 class="page-title">Sign In</h1>
                <p class="page-subtitle">Enter your email and password to sign in!</p>
            </div>

            <div>
                @if ($errors->has('login'))
                    <div class="alert alert-danger" style="color: #ef4444; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; padding: 0.75rem; margin-bottom: 1rem;">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf

                    <div>
                        <!-- Email Field -->
                        <div style="margin-bottom: 1.25rem;">
                            <label for="email" class="form-label">
                                Email<span class="required">*</span>
                            </label>
                            <input
                                id="email"
                                type="email"
                                class="form-input @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                                placeholder="info@gmail.com"
                            >
                            @error('email')
                                <div class="invalid-feedback d-block" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div style="margin-bottom: 1.25rem;">
                            <label for="password" class="form-label">
                                Password<span class="required">*</span>
                            </label>
                            <div class="password-wrapper">
                                <input
                                    id="password"
                                    type="password"
                                    class="form-input @error('password') is-invalid @enderror"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    style="padding-right: 2.75rem;"
                                >
                                <span class="password-toggle" onclick="togglePassword()">
                                    <svg id="eye-off" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="fill-current">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0002 13.8619C7.23361 13.8619 4.86803 12.1372 3.92328 9.70241C4.86804 7.26761 7.23361 5.54297 10.0002 5.54297C12.7667 5.54297 15.1323 7.26762 16.0771 9.70243C15.1323 12.1372 12.7667 13.8619 10.0002 13.8619ZM10.0002 4.04297C6.48191 4.04297 3.49489 6.30917 2.4155 9.4593C2.3615 9.61687 2.3615 9.78794 2.41549 9.94552C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C13.5184 15.3619 16.5055 13.0957 17.5849 9.94555C17.6389 9.78797 17.6389 9.6169 17.5849 9.45932C16.5055 6.30919 13.5184 4.04297 10.0002 4.04297ZM9.99151 7.84413C8.96527 7.84413 8.13333 8.67606 8.13333 9.70231C8.13333 10.7286 8.96527 11.5605 9.99151 11.5605H10.0064C11.0326 11.5605 11.8646 10.7286 11.8646 9.70231C11.8646 8.67606 11.0326 7.84413 10.0064 7.84413H9.99151Z" fill="#98A2B3"/>
                                    </svg>
                                    <svg id="eye-on" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="fill-current" style="display: none;">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.63803 3.57709C4.34513 3.2842 3.87026 3.2842 3.57737 3.57709C3.28447 3.86999 3.28447 4.34486 3.57737 4.63775L4.85323 5.91362C3.74609 6.84199 2.89363 8.06395 2.4155 9.45936C2.3615 9.61694 2.3615 9.78801 2.41549 9.94558C3.49488 13.0957 6.48191 15.3619 10.0002 15.3619C11.255 15.3619 12.4422 15.0737 13.4994 14.5598L15.3625 16.4229C15.6554 16.7158 16.1302 16.7158 16.4231 16.4229C16.716 16.13 16.716 15.6551 16.4231 15.3622L4.63803 3.57709ZM12.3608 13.4212L10.4475 11.5079C10.3061 11.5423 10.1584 11.5606 10.0064 11.5606H9.99151C8.96527 11.5606 8.13333 10.7286 8.13333 9.70237C8.13333 9.5461 8.15262 9.39434 8.18895 9.24933L5.91885 6.97923C5.03505 7.69015 4.34057 8.62704 3.92328 9.70247C4.86803 12.1373 7.23361 13.8619 10.0002 13.8619C10.8326 13.8619 11.6287 13.7058 12.3608 13.4212ZM16.0771 9.70249C15.7843 10.4569 15.3552 11.1432 14.8199 11.7311L15.8813 12.7925C16.6329 11.9813 17.2187 11.0143 17.5849 9.94561C17.6389 9.78803 17.6389 9.61696 17.5849 9.45938C16.5055 6.30925 13.5184 4.04303 10.0002 4.04303C9.13525 4.04303 8.30244 4.17999 7.52218 4.43338L8.75139 5.66259C9.1556 5.58413 9.57311 5.54303 10.0002 5.54303C12.7667 5.54303 15.1323 7.26768 16.0771 9.70249Z" fill="#98A2B3"/>
                                    </svg>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block" style="color: #ef4444; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex align-items-center justify-content-between" style="margin-bottom: 1.25rem;">
                            <label for="remember" class="custom-checkbox">
                                <div class="checkbox-wrapper">
                                    <input
                                        class="checkbox-input"
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                        {{ old('remember') ? 'checked' : '' }}
                                    >
                                    <div class="checkbox-box">
                                        <span class="checkbox-check">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.6666 3.5L5.24992 9.91667L2.33325 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                Keep me logged in
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="link-primary" style="font-size: 0.875rem;">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="btn-primary-custom">
                                Sign In
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOff = document.getElementById('eye-off');
        const eyeOn = document.getElementById('eye-on');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOff.style.display = 'none';
            eyeOn.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            eyeOff.style.display = 'block';
            eyeOn.style.display = 'none';
        }
    }

    // Prevent multiple login submissions
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('login-form');
        const loginBtn = loginForm.querySelector('button[type="submit"]');

        if (loginForm && loginBtn) {
            loginForm.addEventListener('submit', function(e) {
                if (loginBtn.disabled) {
                    e.preventDefault();
                    return false;
                }

                loginBtn.disabled = true;
            });
        }
    });
</script>
@endsection

@extends('layouts.template')
@section('title', 'CateringApp')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection
@section('content')



    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="login">Login</button>
                    <button class="auth-tab" data-tab="register">Register</button>
                </div>

                <!-- Login Form -->
                <div class="auth-form-container active" id="login-form-container">
                    <h2>Welcome Back</h2>
                    <p class="auth-subtitle">Sign in to access your account</p>

                    <form class="auth-form" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="login-email">Email</label>
                            <input id="login-email" type="email" name="email" value="{{ old('email') }}" required
                                autocomplete="email" autofocus>
                        </div>

                        <div class="form-group">
                            <label for="login-password">Password</label>
                            <div class="password-input">
                                <input id="login-password" type="password" name="password" required
                                    autocomplete="current-password">
                                <button type="button" class="toggle-password">👁️</button>
                            </div>
                        </div>

                        <div class="form-options">
                            <div class="remember-me">
                                <input type="checkbox" name="remember" id="remember-me"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember-me">Remember me</label>
                            </div>
                            <a href="#" class="forgot-password">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn-primary">Sign In</button>
                    </form>
                </div>

                <!-- Register Form -->
                <div class="auth-form-container" id="register-form-container">
                    <h2>Create an Account</h2>
                    <p class="auth-subtitle">Join SEA Catering for a healthier lifestyle</p>

                    <form class="auth-form" method="POST" action="/register">
                        @csrf

                        <div class="form-group">
                            <label for="register-name">Full Name</label>
                            <input type="text" id="register-name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="register-email">Email</label>
                            <input type="email" id="register-email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="register-password">Password</label>
                            <div class="password-input">
                                <input type="password" id="register-password" name="password" required>
                                <button type="button" class="toggle-password">👁️</button>
                            </div>
                            <small class="password-requirements">Password must be at least 8 characters and include
                                uppercase, lowercase, number, and special character</small>
                        </div>

                        <div class="form-group">
                            <label for="register-confirm-password">Confirm Password</label>
                            <div class="password-input">
                                <input type="password" id="register-confirm-password" name="password_confirmation" required>
                                <button type="button" class="toggle-password">👁️</button>
                            </div>
                        </div>

                        <div class="form-group terms">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">I agree to the <a href="/terms-of-service">Terms of Service</a> and <a
                                    href="/privacy-policy">Privacy Policy</a></label>
                        </div>

                        <button type="submit" class="btn-primary">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('javascript')
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection

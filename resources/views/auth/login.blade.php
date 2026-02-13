@extends('layouts.app')

@section('title', 'Login — QR Code Generator')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="{{ route('qr-codes.index') }}" class="inline-block mb-4">
                    <img src="{{ asset('logo-integrating-me.webp') }}" alt="Logo" class="h-10 w-auto mx-auto">
                </a>
                <h1 class="text-2xl font-bold text-dark-500">Welcome back</h1>
                <p class="text-dark-300 mt-1">Sign in to your account</p>
            </div>

            <!-- Error Display -->
            <div id="auth-error" class="hidden mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm"></div>

            <!-- Loading Overlay -->
            <div id="auth-loading" class="hidden mb-4 px-4 py-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-sm text-center">
                <svg class="animate-spin inline-block w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Signing in...
            </div>

            <!-- Email/Password Form -->
            <form id="login-form" class="space-y-4" onsubmit="return false;">
                <div>
                    <label for="email" class="block text-sm font-medium text-dark-500 mb-1">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-colors text-dark-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-dark-500 mb-1">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password" minlength="6"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-colors text-dark-500">
                </div>
                <button type="submit" id="login-btn"
                    class="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary-300">
                    Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-white text-dark-300">or continue with</span>
                </div>
            </div>

            <!-- Social Login Buttons -->
            <div class="space-y-3">
                <!-- Google -->
                <button id="google-login-btn" type="button"
                    class="w-full flex items-center justify-center gap-3 py-2.5 px-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium text-dark-500">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </button>
            </div>

            <!-- Footer Links -->
            <div class="mt-6 text-center space-y-2">
                <p class="text-sm text-dark-300">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">Register</a>
                </p>
                <p class="text-sm">
                    <a href="{{ route('qr-codes.index') }}" class="text-dark-400 hover:text-dark-500 transition-colors">Continue as Guest</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('login-form');
    const errorEl = document.getElementById('auth-error');
    const loadingEl = document.getElementById('auth-loading');
    const googleBtn = document.getElementById('google-login-btn');

    function showError(msg) {
        errorEl.textContent = msg;
        errorEl.classList.remove('hidden');
        loadingEl.classList.add('hidden');
    }

    function showLoading(msg = 'Signing in...') {
        loadingEl.innerHTML = `<svg class="animate-spin inline-block w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> ${msg}`;
        loadingEl.classList.remove('hidden');
        errorEl.classList.add('hidden');
    }

    function hideMessages() {
        errorEl.classList.add('hidden');
        loadingEl.classList.add('hidden');
    }

    function handleSuccess(data) {
        loadingEl.classList.remove('hidden');
        loadingEl.textContent = 'Success! Redirecting...';
        window.location.href = data.redirect || '/dashboard';
    }

    // Email/Password Login
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        hideMessages();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!email || !password) {
            showError('Please fill in all fields.');
            return;
        }

        showLoading('Signing in...');

        try {
            const data = await window.FirebaseAuth.signInWithEmail(email, password);
            handleSuccess(data);
        } catch (error) {
            showError(window.FirebaseAuth.getErrorMessage(error));
        }
    });

    // Google Login
    googleBtn.addEventListener('click', async function() {
        hideMessages();
        showLoading('Connecting to Google...');

        try {
            const data = await window.FirebaseAuth.signInWithGoogle();
            handleSuccess(data);
        } catch (error) {
            showError(window.FirebaseAuth.getErrorMessage(error));
        }
    });
});
</script>
@endpush

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QR Code Generator')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white border-b border-dark-50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('qr-codes.index') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('logo-integrating-me.webp') }}" alt="Logo" class="h-8 w-auto">
                        <span class="text-xl font-bold text-dark-500">QR Generator</span>
                    </a>
                </div>
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('qr-codes.index') }}" class="text-dark-500 hover:text-primary-600 font-medium transition-colors">
                        Create QR
                    </a>
                    <a href="{{ route('qr-codes.history') }}" class="text-dark-500 hover:text-primary-600 font-medium transition-colors">
                        History
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-dark-50 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Top Section: Logo, Email & Social Icons -->
            <div class="flex flex-wrap items-center justify-center mb-6 gap-4">
                <!-- Logo -->
                <a href="{{ route('qr-codes.index') }}" class="flex items-center">
                    <img src="{{ asset('logo-integrating-me.webp') }}" alt="IntegratingMe Logo" class="h-8 w-auto">
                </a>
                
                <!-- Email -->
                <a href="mailto:info@integrating.me" class="text-dark-500 hover:text-primary-600 text-sm transition-colors">
                    info@integrating.me
                </a>
                
                <!-- Social Icons -->
                <div class="flex items-center space-x-3">
                    <a href="https://www.linkedin.com/company/integratingme" target="_blank" rel="noopener noreferrer" class="text-dark-500 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/integratingme" target="_blank" rel="noopener noreferrer" class="text-dark-500 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Bottom Section: Links & Copyright -->
            <div class="flex flex-col items-center space-y-3">
                <!-- Links -->
                <div class="flex flex-wrap gap-x-4 gap-y-2 justify-center">
                    <a href="{{ route('pages.privacy-policy') }}" class="text-dark-500 hover:text-primary-600 text-sm transition-colors">
                        Privacy Policy
                    </a>
                    <a href="{{ route('pages.terms-and-conditions') }}" class="text-dark-500 hover:text-primary-600 text-sm transition-colors">
                        Terms and Conditions
                    </a>
                    <a href="{{ route('pages.aup') }}" class="text-dark-500 hover:text-primary-600 text-sm transition-colors">
                        AUP
                    </a>
                    <a href="{{ route('pages.cookie-policy') }}" class="text-dark-500 hover:text-primary-600 text-sm transition-colors">
                        Cookie Policy
                    </a>
                    <a href="{{ route('pages.disclaimer') }}" class="text-dark-500 hover:text-primary-600 text-sm transition-colors">
                        Disclaimer
                    </a>
                </div>
                
                <!-- Copyright -->
                <p class="text-dark-500 text-sm">
                    © {{ date('Y') }} QR Code Generator. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

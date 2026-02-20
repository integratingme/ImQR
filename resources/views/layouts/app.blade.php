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
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('qr-codes.index') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('logo-integrating-me.webp') }}" alt="Logo" class="h-8 w-auto">
                        <span class="text-xl font-bold text-dark-500">QR Generator</span>
                    </a>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('qr-codes.index') }}" class="text-dark-500 hover:text-primary-600 font-medium transition-colors">
                        Create QR
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-dark-500 hover:text-primary-600 font-medium transition-colors">
                            Dashboard
                        </a>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="text-dark-400 max-w-[150px] truncate">{{ auth()->user()->email ?? auth()->user()->phone }}</span>
                                @if(auth()->user()->isPremium())
                                    <span class="px-2 py-0.5 rounded bg-primary-100 text-primary-700 font-medium text-xs">Premium</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-medium text-xs">Free</span>
                                @endif
                                <svg class="w-4 h-4 text-dark-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-dark-500 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-dark-300 truncate">{{ auth()->user()->email ?? auth()->user()->phone }}</p>
                                </div>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-dark-500 hover:bg-gray-50 transition-colors">Dashboard</a>
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <button onclick="handleLogout()" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        Sign Out
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('qr-codes.history') }}" class="text-dark-500 hover:text-primary-600 font-medium transition-colors">
                            History
                        </a>
                        {{-- Guest: show Login / Register --}}
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-500 font-medium text-xs">Guest</span>
                            <a href="{{ route('login') }}" class="text-dark-500 hover:text-primary-600 font-medium text-sm transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm rounded-lg transition-colors">
                                Register
                            </a>
                        </div>
                    @endauth
                </nav>

                <!-- Mobile Hamburger -->
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <svg class="w-6 h-6 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('qr-codes.index') }}" class="block py-2 text-dark-500 hover:text-primary-600 font-medium transition-colors">Create QR</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block py-2 text-dark-500 hover:text-primary-600 font-medium transition-colors">Dashboard</a>
                    <div class="pt-2 border-t border-gray-100">
                        <div class="flex items-center gap-2 py-2">
                            <span class="text-sm text-dark-400 truncate">{{ auth()->user()->email ?? auth()->user()->phone }}</span>
                            @if(auth()->user()->isPremium())
                                <span class="px-2 py-0.5 rounded bg-primary-100 text-primary-700 font-medium text-xs">Premium</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 font-medium text-xs">Free</span>
                            @endif
                        </div>
                        <button onclick="handleLogout()" class="block w-full text-left py-2 text-red-600 font-medium">Sign Out</button>
                    </div>
                @else
                    <a href="{{ route('qr-codes.history') }}" class="block py-2 text-dark-500 hover:text-primary-600 font-medium transition-colors">History</a>
                    <div class="pt-2 border-t border-gray-100 flex gap-2">
                        <a href="{{ route('login') }}" class="flex-1 text-center py-2 border border-gray-300 text-dark-500 font-medium rounded-lg hover:bg-gray-50 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="flex-1 text-center py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">Register</a>
                    </div>
                @endauth
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

    <script>
    async function handleLogout() {
        // Sign out from Firebase first (non-blocking)
        if (window.FirebaseAuth && typeof window.FirebaseAuth.signOut === 'function') {
            window.FirebaseAuth.signOut().catch(error => {
                console.error('Firebase logout error:', error);
            });
        }
        
        // Redirect to logout page which will handle Laravel logout
        window.location.href = '{{ route("logout") }}';
    }
    </script>

    <!-- Alpine.js for dropdowns (lightweight) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>

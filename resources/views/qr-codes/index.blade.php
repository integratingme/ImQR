@extends('layouts.app')

@section('title', 'QR Code Generator - Select Type')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-dark-500 mb-4">
            Create Your <span class="text-gradient">QR Code</span>
        </h1>
        <p class="text-xl text-dark-300 max-w-2xl mx-auto">
            Choose from 12 different QR code types to create professional, customizable QR codes in seconds
        </p>
    </div>

    <!-- QR Code Type Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- URL QR Code -->
        <a href="{{ route('qr-codes.create', 'url') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-200 transition-colors">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">Website URL</h3>
                <p class="text-sm text-dark-300">Link to any website</p>
            </div>
        </a>

        <!-- Email QR Code -->
        <a href="{{ route('qr-codes.create', 'email') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-dark-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-dark-100 transition-colors">
                    <svg class="w-8 h-8 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">Email</h3>
                <p class="text-sm text-dark-300">Send pre-filled emails</p>
            </div>
        </a>

        <!-- Text QR Code -->
        <a href="{{ route('qr-codes.create', 'text') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">Text</h3>
                <p class="text-sm text-dark-300">Display plain text</p>
            </div>
        </a>

        <!-- PDF QR Code -->
        <a href="{{ route('qr-codes.create', 'pdf') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition-colors">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">PDF</h3>
                <p class="text-sm text-dark-300">Share PDF documents</p>
            </div>
        </a>

        <!-- Menu QR Code -->
        <a href="{{ route('qr-codes.create', 'menu') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">Menu</h3>
                <p class="text-sm text-dark-300">Restaurant menu</p>
            </div>
        </a>

        <!-- Coupon QR Code -->
        <a href="{{ route('qr-codes.create', 'coupon') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">Coupon</h3>
                <p class="text-sm text-dark-300">Discount coupons</p>
            </div>
        </a>

        <!-- Event QR Code -->
        <a href="{{ route('qr-codes.create', 'event') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-pink-200 transition-colors">
                    <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">Event</h3>
                <p class="text-sm text-dark-300">Event information</p>
            </div>
        </a>

        <!-- App QR Code -->
        <a href="{{ route('qr-codes.create', 'app') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-200 transition-colors">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">App</h3>
                <p class="text-sm text-dark-300">Mobile app links</p>
            </div>
        </a>

        <!-- Location QR Code -->
        <a href="{{ route('qr-codes.create', 'location') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-teal-200 transition-colors">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">Location</h3>
                <p class="text-sm text-dark-300">GPS coordinates</p>
            </div>
        </a>

        <!-- WiFi QR Code -->
        <a href="{{ route('qr-codes.create', 'wifi') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition-colors">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">WiFi</h3>
                <p class="text-sm text-dark-300">WiFi credentials</p>
            </div>
        </a>

        <!-- Phone QR Code -->
        <a href="{{ route('qr-codes.create', 'phone') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">Phone</h3>
                <p class="text-sm text-dark-300">Phone number</p>
            </div>
        </a>

        <!-- MP3 QR Code -->
        <a href="{{ route('qr-codes.create', 'mp3') }}" class="card card-hover group">
            <div class="text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-200 transition-colors">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-dark-500 mb-2">MP3</h3>
                <p class="text-sm text-dark-300">Audio file</p>
            </div>
        </a>
    </div>

    <!-- Features Section -->
    <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-dark-500 mb-2">Customizable Design</h3>
            <p class="text-dark-300">Choose colors and styles to match your brand</p>
        </div>
        <div class="text-center">
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-dark-500 mb-2">Multiple Formats</h3>
            <p class="text-dark-300">Download as PNG or SVG for any use case</p>
        </div>
        <div class="text-center">
            <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-dark-500 mb-2">Instant Generation</h3>
            <p class="text-dark-300">Create professional QR codes in seconds</p>
        </div>
    </div>
</div>
@endsection

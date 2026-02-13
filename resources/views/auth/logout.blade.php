@extends('layouts.app')

@section('title', 'Logged Out — QR Code Generator')

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
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-dark-500">You've been logged out</h1>
                <p class="text-dark-300 mt-1">Thank you for using our service</p>
            </div>

            <!-- Success Message -->
            @if(session('message'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm text-center">
                {{ session('message') }}
            </div>
            @endif

            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ route('qr-codes.index') }}"
                    class="block w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors text-center focus:outline-none focus:ring-2 focus:ring-primary-300">
                    Continue as Guest
                </a>
                <a href="{{ route('login') }}"
                    class="block w-full py-2.5 px-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium text-dark-500 text-center">
                    Sign In Again
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

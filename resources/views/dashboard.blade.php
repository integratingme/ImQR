@extends('layouts.app')

@section('title', 'Dashboard — QR Code Generator')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome & Plan Status -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-dark-500">Welcome, {{ $user->name ?? 'User' }}</h1>
                <p class="text-dark-300 mt-1">{{ $user->email ?? $user->phone }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if($user->isPremium())
                    <span class="px-3 py-1.5 rounded-full bg-primary-100 text-primary-700 font-medium text-sm">
                        Premium Plan
                    </span>
                @else
                    <span class="px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 font-medium text-sm">
                        Free Plan
                    </span>
                    <a href="#" class="px-3 py-1.5 rounded-full bg-primary-600 text-white font-medium text-sm hover:bg-primary-700 transition-colors">
                        Upgrade to Premium
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-dark-500">{{ $stats['total_qr_codes'] }}</p>
                    <p class="text-sm text-dark-300">QR Codes</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-dark-500">{{ $stats['total_scans'] }}</p>
                    <p class="text-sm text-dark-300">Total Scans</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-dark-500">{{ $stats['dynamic_codes'] }}</p>
                    <p class="text-sm text-dark-300">Dynamic QR Codes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Codes List -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-dark-500">Your QR Codes</h2>
            <a href="{{ route('qr-codes.index') }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                Create New
            </a>
        </div>

        @if($qrCodes->isEmpty())
            <div class="px-6 py-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <h3 class="text-lg font-medium text-dark-500 mb-1">No QR codes yet</h3>
                <p class="text-dark-300 mb-4">Create your first QR code to get started.</p>
                <a href="{{ route('qr-codes.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors">
                    Create QR Code
                </a>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($qrCodes as $qrCode)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            @if($qrCode->qr_image_path)
                                <img src="{{ asset('storage/' . $qrCode->qr_image_path) }}" alt="QR Code" class="w-12 h-12 rounded border border-gray-200 object-contain">
                            @else
                                <div class="w-12 h-12 rounded border border-gray-200 bg-gray-50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-dark-500">{{ $qrCode->name ?? 'Untitled QR Code' }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 uppercase font-medium">{{ $qrCode->type }}</span>
                                    @if($qrCode->is_dynamic)
                                        <span class="text-xs px-1.5 py-0.5 rounded bg-purple-100 text-purple-600 font-medium">Dynamic</span>
                                    @endif
                                    <span class="text-xs text-dark-300">{{ $qrCode->scan_count }} scans</span>
                                    <span class="text-xs text-dark-300">{{ $qrCode->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($qrCode->qr_image_path)
                                <a href="{{ route('qr-codes.download', [$qrCode->id, 'png']) }}" class="px-3 py-1.5 text-sm text-dark-500 hover:text-primary-600 border border-gray-200 rounded-lg hover:border-primary-300 transition-colors">
                                    Download
                                </a>
                            @endif
                            @if($user->isPremium())
                                <a href="{{ route('qr-codes.edit', $qrCode->id) }}" class="px-3 py-1.5 text-sm text-dark-500 hover:text-primary-600 border border-gray-200 rounded-lg hover:border-primary-300 transition-colors">
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

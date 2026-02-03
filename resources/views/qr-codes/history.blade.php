@extends('layouts.app')

@section('title', 'QR Code History')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-dark-500 mb-2">QR Code History</h1>
        <p class="text-dark-300">View and download your previously generated QR codes</p>
    </div>

    @if($qrCodes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($qrCodes as $qrCode)
                <div class="card">
                    <div class="mb-4">
                        @if($qrCode->qr_image_path)
                            <img src="{{ asset('storage/' . $qrCode->qr_image_path) }}" alt="{{ $qrCode->name }}" class="w-full h-48 object-contain rounded-lg p-4">
                        @else
                            <div class="w-full h-48 bg-dark-200 rounded-lg flex items-center justify-center">
                                <svg class="w-16 h-16 text-dark-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-dark-500 mb-1">{{ $qrCode->name }}</h3>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                {{ $qrCode->type_name }}
                            </span>
                            <span class="text-sm text-dark-200">
                                {{ $qrCode->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-2">
                        @if(in_array($qrCode->type, ['text', 'coupon', 'pdf', 'app', 'phone', 'menu']))
                            @php
                                $pageRoutes = [
                                    'text' => ['route' => 'qr-codes.text-page', 'label' => 'View Text Page'],
                                    'coupon' => ['route' => 'qr-codes.coupon-page', 'label' => 'View Coupon Page'],
                                    'pdf' => ['route' => 'qr-codes.pdf-page', 'label' => 'View PDF Page'],
                                    'app' => ['route' => 'qr-codes.app-page', 'label' => 'View App Page'],
                                    'phone' => ['route' => 'qr-codes.phone-page', 'label' => 'View Phone Page'],
                                    'menu' => ['route' => 'qr-codes.menu-page', 'label' => 'View Menu Page'],
                                ];
                                $page = $pageRoutes[$qrCode->type];
                            @endphp
                            <a href="{{ route($page['route'], $qrCode->id) }}" target="_blank" class="btn btn-secondary text-sm py-2 w-full">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                {{ $page['label'] }}
                            </a>
                        @endif
                        <div class="flex space-x-2">
                            <a href="{{ route('qr-codes.download', ['id' => $qrCode->id, 'format' => 'png']) }}" class="flex-1 btn btn-primary text-sm py-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                PNG
                            </a>
                            <a href="{{ route('qr-codes.download', ['id' => $qrCode->id, 'format' => 'svg']) }}" class="flex-1 btn btn-outline text-sm py-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                SVG
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $qrCodes->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-24 h-24 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-dark-500 mb-2">No QR Codes Yet</h3>
            <p class="text-dark-300 mb-6">You haven't created any QR codes yet. Start by creating your first one!</p>
            <a href="{{ route('qr-codes.index') }}" class="btn btn-primary inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create QR Code
            </a>
        </div>
    @endif
</div>
@endsection

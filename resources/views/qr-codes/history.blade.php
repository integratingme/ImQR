@extends('layouts.app')

@section('title', 'QR Code History')

@section('content')
<div class="min-h-[60vh] bg-gradient-to-b from-dark-50/50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
        {{-- Header --}}
        <header class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-dark-600 tracking-tight">Your QR codes</h1>
                    <p class="mt-1.5 text-dark-300 text-lg">View, download or remove your generated QR codes</p>
                </div>
                <a href="{{ route('qr-codes.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-medium text-white bg-primary-500 hover:bg-primary-600 shadow-md hover:shadow-lg transition-all duration-200 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create new
                </a>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-8 flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @php
            $typeNames = [
                'text' => 'Text',
                'coupon' => 'Coupon',
                'pdf' => 'PDF',
                'app' => 'App',
                'phone' => 'Phone',
                'menu' => 'Menu',
                'location' => 'Location',
            ];
        @endphp

        {{-- Type filter --}}
        <div class="mb-8">
            <p class="text-sm font-medium text-dark-400 mb-3">Filter by type</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('qr-codes.history') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !$currentType ? 'bg-primary-500 text-white shadow-sm' : 'bg-white border border-dark-200 text-dark-600 hover:bg-dark-50' }}">
                    All
                </a>
                @foreach($historyTypes as $t)
                    <a href="{{ route('qr-codes.history', ['type' => $t]) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ $currentType === $t ? 'bg-primary-500 text-white shadow-sm' : 'bg-white border border-dark-200 text-dark-600 hover:bg-dark-50' }}">
                        {{ $typeNames[$t] ?? ucfirst($t) }}
                    </a>
                @endforeach
            </div>
        </div>

        @if($qrCodes->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @foreach($qrCodes as $qrCode)
                    <article class="group relative bg-white rounded-2xl border border-dark-100 shadow-sm hover:shadow-xl hover:border-dark-200/60 transition-all duration-300 overflow-hidden">
                        {{-- QR preview area --}}
                        <div class="p-6 pb-4">
                            <div class="aspect-square max-w-[200px] mx-auto rounded-2xl bg-dark-50/80 p-4 flex items-center justify-center ring-1 ring-dark-100/50">
                                @if($qrCode->qr_image_path)
                                    <img src="{{ asset('storage/' . $qrCode->qr_image_path) }}" alt="{{ $qrCode->name }}" class="w-full h-full object-contain rounded-lg">
                                @else
                                    <div class="w-full h-full rounded-lg bg-dark-100 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-dark-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Meta --}}
                        <div class="px-6 pb-4">
                            <h3 class="font-semibold text-dark-600 text-lg truncate pr-8" title="{{ $qrCode->name }}">{{ $qrCode->name }}</h3>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-primary-100 text-primary-700">
                                    {{ $qrCode->type_name }}
                                </span>
                                <span class="text-xs text-dark-300">{{ $qrCode->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="px-6 pb-6 pt-0 space-y-2">
                            @if(in_array($qrCode->type, ['text', 'coupon', 'pdf', 'app', 'phone', 'menu']))
                                @php
                                    $pageRoutes = [
                                        'text' => ['route' => 'qr-codes.text-page', 'label' => 'View page'],
                                        'coupon' => ['route' => 'qr-codes.coupon-page', 'label' => 'View page'],
                                        'pdf' => ['route' => 'qr-codes.pdf-page', 'label' => 'View page'],
                                        'app' => ['route' => 'qr-codes.app-page', 'label' => 'View page'],
                                        'phone' => ['route' => 'qr-codes.phone-page', 'label' => 'View page'],
                                        'menu' => ['route' => 'qr-codes.menu-page', 'label' => 'View page'],
                                    ];
                                    $page = $pageRoutes[$qrCode->type];
                                @endphp
                                <a href="{{ route($page['route'], $qrCode->id) }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-medium text-dark-500 bg-dark-50 hover:bg-dark-100 border border-dark-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    {{ $page['label'] }}
                                </a>
                            @else
                                {{-- Placeholder so PNG/SVG row stays at same position when there is no View page --}}
                                <div class="w-full py-2.5 rounded-xl invisible pointer-events-none select-none" aria-hidden="true">View page</div>
                            @endif
                            <div class="flex gap-2">
                                <a href="{{ route('qr-codes.download', ['id' => $qrCode->id, 'format' => 'png']) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 shadow-sm transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    PNG
                                </a>
                                <a href="{{ route('qr-codes.download', ['id' => $qrCode->id, 'format' => 'svg']) }}" class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-xl text-sm font-medium text-dark-600 bg-white border border-dark-200 hover:bg-dark-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    SVG
                                </a>
                            </div>
                            <form action="{{ route('qr-codes.destroy', $qrCode->id) }}" method="POST" onsubmit="return confirm('Delete this QR code? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($qrCodes->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $qrCodes->links() }}
                </div>
            @endif
        @else
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center max-w-md mx-auto">
                <div class="w-24 h-24 rounded-2xl bg-dark-100 flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-dark-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                @if($currentType)
                    <h2 class="text-xl font-semibold text-dark-600 mb-2">No {{ $typeNames[$currentType] ?? $currentType }} QR codes</h2>
                    <p class="text-dark-300 mb-8">You don't have any QR codes of this type yet. Try another filter or create one.</p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('qr-codes.history') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-medium text-dark-600 bg-white border border-dark-200 hover:bg-dark-50">Show all</a>
                        <a href="{{ route('qr-codes.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-medium text-white bg-primary-500 hover:bg-primary-600 shadow-md hover:shadow-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create QR code
                        </a>
                    </div>
                @else
                    <h2 class="text-xl font-semibold text-dark-600 mb-2">No QR codes yet</h2>
                    <p class="text-dark-300 mb-8">Create your first QR code and it will show up here. You can then download or manage it from this page.</p>
                    <a href="{{ route('qr-codes.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-medium text-white bg-primary-500 hover:bg-primary-600 shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create QR code
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

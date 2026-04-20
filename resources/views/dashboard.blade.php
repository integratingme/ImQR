@extends('layouts.app')

@section('title', 'Dashboard — QR Code Generator')

@section('content')
<div class="min-h-[60vh] bg-gradient-to-b from-dark-50/50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">

        {{-- Welcome & Plan Status --}}
        <header class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-dark-600 tracking-tight">Welcome, {{ $user->name ?? 'User' }}</h1>
                    <p class="mt-1.5 text-dark-300 text-lg">{{ $user->email }}</p>
                </div>
                <div class="flex items-center gap-3">
                    @if($user->isPremium())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary-100 text-primary-700 font-medium text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l3.057-3L12 3l3.943-3L19 3l2 7-4 4v7l-5-2-5 2v-7l-4-4 2-7z"/>
                            </svg>
                            Premium
                        </span>
                        <form action="{{ route('dashboard.update-plan') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="plan" value="free">
                            <button type="submit" class="px-3 py-1.5 rounded-full bg-gray-100 text-gray-500 font-medium text-sm hover:bg-gray-200 transition-colors">
                                Downgrade to Free
                            </button>
                        </form>
                    @else
                        <span class="px-3 py-1.5 rounded-full bg-gray-100 text-gray-600 font-medium text-sm">
                            Free Plan
                        </span>
                        <form action="{{ route('dashboard.update-plan') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="plan" value="premium">
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-gradient-to-r from-primary-600 to-primary-500 text-white font-medium text-sm hover:from-primary-700 hover:to-primary-600 shadow-sm hover:shadow transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l3.057-3L12 3l3.943-3L19 3l2 7-4 4v7l-5-2-5 2v-7l-4-4 2-7z"/>
                                </svg>
                                Upgrade to Premium
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </header>

        {{-- Stats Cards --}}
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
                'url' => 'Website URL',
                'email' => 'Email',
                'text' => 'Text',
                'coupon' => 'Coupon',
                'pdf' => 'PDF',
                'app' => 'App',
                'phone' => 'Phone',
                'menu' => 'Menu',
                'location' => 'Location',
                'wifi' => 'WiFi',
                'event' => 'Event',
                'business_card' => 'Business Card',
                'personal_vcard' => 'Personal vCard',
            ];
        @endphp

        {{-- Type filter --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                <p class="text-sm font-medium text-dark-400">Filter by type</p>
                <a href="{{ route('qr-codes.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-medium text-white bg-primary-500 hover:bg-primary-600 shadow-md hover:shadow-lg transition-all duration-200 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create new
                </a>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ !$currentType ? 'bg-primary-500 text-white shadow-sm' : 'bg-white border border-dark-200 text-dark-600 hover:bg-dark-50' }}">
                    All
                </a>
                @foreach($historyTypes as $t)
                    <a href="{{ route('dashboard', ['type' => $t]) }}" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium transition-colors {{ $currentType === $t ? 'bg-primary-500 text-white shadow-sm' : 'bg-white border border-dark-200 text-dark-600 hover:bg-dark-50' }}">
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
                        <div class="p-5 pb-4">
                            <div class="min-h-[320px] h-[440px] max-w-[360px] w-full mx-auto rounded-2xl bg-dark-50/80 p-3 flex items-center justify-center ring-1 ring-dark-100/50 overflow-hidden"
                                 id="qr-preview-{{ $qrCode->id }}"
                                 data-qr-id="{{ $qrCode->id }}"
                                 data-qr-type="{{ $qrCode->type }}"
                                 data-qr-data="{{ json_encode($qrCode->data) }}"
                                 data-qr-colors="{{ json_encode($qrCode->colors) }}"
                                 data-qr-customization="{{ json_encode($qrCode->customization) }}">
                                {{-- QR code will be rendered here by JavaScript --}}
                                <div class="w-10 h-10 text-dark-300 animate-spin" aria-hidden="true">
                                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
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
                            @if(in_array($qrCode->type, ['text', 'coupon', 'pdf', 'app', 'phone', 'menu', 'business_card', 'personal_vcard', 'event']))
                                @php
                                    $pageRoutes = [
                                        'text' => ['route' => 'qr-codes.text-page', 'label' => 'View page'],
                                        'coupon' => ['route' => 'qr-codes.coupon-page', 'label' => 'View page'],
                                        'pdf' => ['route' => 'qr-codes.pdf-page', 'label' => 'View page'],
                                        'app' => ['route' => 'qr-codes.app-page', 'label' => 'View page'],
                                        'phone' => ['route' => 'qr-codes.phone-page', 'label' => 'View page'],
                                        'menu' => ['route' => 'qr-codes.menu-page', 'label' => 'View page'],
                                        'business_card' => ['route' => 'qr-codes.business-card-page', 'label' => 'View page'],
                                        'personal_vcard' => ['route' => 'qr-codes.personal-vcard-page', 'label' => 'View page'],
                                        'event' => ['route' => 'qr-codes.event-page', 'label' => 'View page'],
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
                                <div class="w-full py-2.5 rounded-xl invisible pointer-events-none select-none" aria-hidden="true">View page</div>
                            @endif
                            @if($user->isPremium() && $qrCode->user_id == auth()->id())
                                <a href="{{ route('qr-codes.edit', $qrCode->id) }}" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-medium text-primary-600 bg-primary-50 hover:bg-primary-100 border border-primary-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
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
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-medium text-dark-600 bg-white border border-dark-200 hover:bg-dark-50">Show all</a>
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

@push('scripts')
<script>
(function() {
// Base URL for frame SVGs (ensure trailing slash)
var BASE_URL = '{{ rtrim(url("/"), "/") }}/';

// Frame configuration
var FRAME_CONFIG = @json($frameConfig);
var CUSTOM_FRAME_DESIGNS = @json($customFrameDesigns ?? []);

// Wait for QRCodeStyling
function waitForQRCodeStyling(callback, maxWaitMs) {
    maxWaitMs = maxWaitMs || 4000;
    var start = Date.now();
    function check() {
        if (window.QRCodeStyling) {
            callback(window.QRCodeStyling);
            return;
        }
        if (Date.now() - start >= maxWaitMs) {
            document.querySelectorAll('[data-qr-id]').forEach(function(el) {
                el.innerHTML = '<div class="text-xs text-dark-400 text-center px-2">Preview unavailable</div>';
            });
            return;
        }
        setTimeout(check, 80);
    }
    check();
}

function normalizeHexColor(val) {
    if (!val || typeof val !== 'string') return '#000000';
    val = val.trim().replace(/^#/, '');
    if (/^[0-9A-Fa-f]{6}$/.test(val)) return '#' + val;
    if (/^[0-9A-Fa-f]{3}$/.test(val)) return '#' + val[0] + val[0] + val[1] + val[1] + val[2] + val[2];
    return '#000000';
}

function escapeSvgText(s) {
    if (s == null) return '';
    return String(s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

async function getThemedFrameUrl(svgPath, primaryHex, secondaryHex) {
    var primary = normalizeHexColor(primaryHex);
    var secondary = normalizeHexColor(secondaryHex);
    var res = await fetch(BASE_URL + svgPath);
    let text = await res.text();
    text = text.replace(/#PRIMARY#/gi, primary).replace(/#SECONDARY#/gi, secondary);
    const blob = new Blob([text], { type: 'image/svg+xml' });
    return URL.createObjectURL(blob);
}

async function getReviewUsFrameUrl(config) {
    var svgPath = FRAME_CONFIG['review-us'] && FRAME_CONFIG['review-us'].url;
    if (!svgPath) return '';
    var res = await fetch(BASE_URL + svgPath);
    let svg = await res.text();

    const frameColor = normalizeHexColor(config.color || '#84BD00');
    const textColor = normalizeHexColor(config.text_color || '#000000');

    svg = svg.replace(/fill="#84BD00"/, 'fill="' + frameColor + '"');
    svg = svg.replace(/(<text[^>]*?)fill="#000000"([^>]*>)/g, '$1fill="' + textColor + '"$2');

    const line1 = config.line1 || 'your';
    const line2 = config.line2 || 'text';
    const line3 = config.line3 || 'here';

    svg = svg.replace(/>your<\/text>/, '>' + escapeSvgText(line1) + '</text>');
    svg = svg.replace(/>text<\/text>/, '>' + escapeSvgText(line2) + '</text>');
    svg = svg.replace(/>here<\/text>/, '>' + escapeSvgText(line3) + '</text>');

    const iconValue = config.icon || 'default';
    const iconGroupRegex = /<g transform="translate\(100 480\)">[\s\S]*?<\/g>/;

    if (iconValue === 'custom' && config.logo_url) {
        const iconReplacement = '<image x="100" y="480" width="200" height="80" href="' + config.logo_url.replace(/"/g, '&quot;') + '" preserveAspectRatio="xMidYMid meet"/>';
        svg = svg.replace(iconGroupRegex, iconReplacement);
    } else if (iconValue !== 'default' && iconValue !== 'custom') {
        try {
            const iconRes = await fetch(iconValue);
            const iconSvgText = await iconRes.text();
            const iconDataUrl = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(iconSvgText)));
            const iconReplacement = '<image x="100" y="480" width="200" height="80" href="' + iconDataUrl.replace(/"/g, '&quot;') + '" preserveAspectRatio="xMidYMid meet"/>';
            svg = svg.replace(iconGroupRegex, iconReplacement);
        } catch (e) {
            console.warn('Could not load icon', e);
        }
    }

    const blob = new Blob([svg], { type: 'image/svg+xml' });
    return URL.createObjectURL(blob);
}

function getCustomFrameDesign(customization) {
    if (!customization || customization.frame !== 'custom') return null;
    const frameDesignId = String(customization.frame_design_id || '');
    if (!frameDesignId) return null;
    return CUSTOM_FRAME_DESIGNS[frameDesignId] || null;
}

function buildCustomFrameSvgUrl(svgContent, primaryColor, secondaryColor) {
    if (!svgContent) return null;
    const themed = String(svgContent)
        .replaceAll('#PRIMARY#', primaryColor)
        .replaceAll('#SECONDARY#', secondaryColor);
    return URL.createObjectURL(new Blob([themed], { type: 'image/svg+xml' }));
}

async function buildCustomFrameWrapper(container, customization, primaryColor, secondaryColor) {
    const customFrame = getCustomFrameDesign(customization);
    if (!customFrame || (!customFrame.svg_content && !customFrame.design_json)) {
        return null;
    }

    const designJson = customFrame.design_json || {
        canvas_width: 400,
        canvas_height: 500,
        qr_zone: { x_pct: 5, y_pct: 4, w_pct: 90, h_pct: 72 }
    };

    const wrapper = document.createElement('div');
    wrapper.className = 'frame-wrapper relative mx-auto inline-block';
    wrapper.style.width = (designJson.canvas_width || 400) + 'px';
    wrapper.style.height = (designJson.canvas_height || 500) + 'px';

    if (window.renderFrameDesign && customFrame.design_json) {
        const frameCanvas = document.createElement('canvas');
        frameCanvas.className = 'w-full h-full block';
        await window.renderFrameDesign(frameCanvas, designJson, primaryColor, secondaryColor, null);
        wrapper.appendChild(frameCanvas);
    } else if (customFrame.svg_content) {
        const frameImg = document.createElement('img');
        frameImg.className = 'w-full h-full block object-contain';
        frameImg.alt = 'Custom frame';
        frameImg.src = buildCustomFrameSvgUrl(customFrame.svg_content, primaryColor, secondaryColor);
        wrapper.appendChild(frameImg);
    }

    const zone = designJson.qr_zone || { x_pct: 5, y_pct: 4, w_pct: 90, h_pct: 72 };
    const qrInFrame = document.createElement('div');
    qrInFrame.className = 'qr-in-frame absolute flex items-center justify-center';
    qrInFrame.style.left = zone.x_pct + '%';
    qrInFrame.style.top = zone.y_pct + '%';
    qrInFrame.style.width = zone.w_pct + '%';
    qrInFrame.style.height = zone.h_pct + '%';

    wrapper.appendChild(qrInFrame);
    container.appendChild(wrapper);

    setTimeout(function() {
        var cw = container.clientWidth;
        var ch = container.clientHeight;
        var ww = designJson.canvas_width || 400;
        var wh = designJson.canvas_height || 500;
        if (cw > 0 && ch > 0 && (ww > cw || wh > ch)) {
            var scale = Math.min(cw / ww, ch / wh, 1);
            wrapper.style.transform = 'scale(' + scale + ')';
            wrapper.style.transformOrigin = 'center center';
        }
    }, 80);

    return { appendTarget: qrInFrame, designJson: designJson };
}

async function renderQRCode(container, QRCodeStylingClass) {
    var QRS = QRCodeStylingClass || window.QRCodeStyling;
    var qrId = container.dataset.qrId;
    var qrType = container.dataset.qrType;
    const qrData = JSON.parse(container.dataset.qrData || '{}');
    const colors = JSON.parse(container.dataset.qrColors || '{}');
    const customization = JSON.parse(container.dataset.qrCustomization || '{}');

    let primaryColor = normalizeHexColor(colors.primary || '#000000');
    let secondaryColor = normalizeHexColor(colors.secondary || '#FFFFFF');
    // Ensure contrast: if pattern and background are same (e.g. both black), QR would be invisible
    if (primaryColor === secondaryColor || primaryColor.toLowerCase() === secondaryColor.toLowerCase()) {
        secondaryColor = '#FFFFFF';
        primaryColor = primaryColor === '#FFFFFF' ? '#000000' : primaryColor;
    }
    const pattern = customization.pattern || 'square';
    const cornerStyle = customization.corner_style || 'square';
    const cornerDotStyle = customization.corner_dot_style || 'square';
    const frameId = customization.frame || 'none';
    const logoUrl = customization.logo_url || '';

    let qrContent = '';
    switch (qrType) {
        case 'text':
            qrContent = qrData.text_page_url || '';
            break;
        case 'pdf':
            qrContent = qrData.pdf_page_url || '';
            break;
        case 'coupon':
            qrContent = qrData.coupon_page_url || '';
            break;
        case 'app':
            qrContent = qrData.app_page_url || '';
            break;
        case 'phone':
            qrContent = qrData.phone_page_url || '';
            break;
        case 'menu':
            qrContent = qrData.menu_page_url || '';
            break;
        case 'business_card':
            qrContent = qrData.business_card_page_url || '';
            break;
        case 'personal_vcard':
            qrContent = qrData.personal_vcard_page_url || '';
            break;
        case 'event':
            qrContent = qrData.event_page_url || '';
            break;
        case 'location': {
            qrContent = qrData.location_url || '';
            if (!qrContent && qrData.latitude != null && qrData.longitude != null) {
                qrContent = 'https://www.google.com/maps?q=' + qrData.latitude + ',' + qrData.longitude;
            }
            if (!qrContent && (qrData.address || '').trim()) {
                qrContent = 'https://www.google.com/maps?q=' + encodeURIComponent((qrData.address || '').trim());
            }
            break;
        }
        case 'email': {
            const email = qrData.email || '';
            if (!email) { qrContent = ''; break; }
            let mailtoUrl = 'mailto:' + email;
            const subject = qrData.subject || '';
            const message = qrData.message || '';
            if (subject || message) {
                mailtoUrl += '?';
                if (subject) mailtoUrl += 'subject=' + encodeURIComponent(subject);
                if (message) {
                    if (subject) mailtoUrl += '&';
                    mailtoUrl += 'body=' + encodeURIComponent(message);
                }
            }
            qrContent = mailtoUrl;
            break;
        }
        case 'wifi': {
            const ssid = qrData.ssid || '';
            if (!ssid) { qrContent = ''; break; }
            const password = qrData.password || '';
            const encryption = qrData.encryption || 'WPA2';
            function escapeWifiString(str) {
                return str.replace(/\\/g, '\\\\').replace(/;/g, '\\;').replace(/:/g, '\\:').replace(/,/g, '\\,');
            }
            let wifiString = 'WIFI:';
            if (encryption !== 'nopass') wifiString += 'T:' + encryption + ';';
            wifiString += 'S:' + escapeWifiString(ssid) + ';';
            if (encryption !== 'nopass' && password) wifiString += 'P:' + escapeWifiString(password) + ';';
            wifiString += ';';
            qrContent = wifiString;
            break;
        }
        default:
            qrContent = qrData.url || '';
    }

    if (!qrContent) {
        container.innerHTML = '<div class="text-xs text-dark-400">No QR data</div>';
        return;
    }

    const dotsTypeMap = { square: 'square', circle: 'dots', rounded: 'rounded' };
    const cornersSquareTypeMap = { square: 'square', rounded: 'rounded', 'extra-rounded': 'extra-rounded' };
    const cornersDotTypeMap = { square: 'square', circle: 'dot', rounded: 'rounded' };

    const dotsType = dotsTypeMap[pattern] || 'square';
    const cornersSquareType = cornersSquareTypeMap[cornerStyle] || 'square';
    const cornersDotType = cornersDotTypeMap[cornerDotStyle] || 'square';

    var HISTORY_QR_SIZE = 200;
    var qrSize = (frameId && frameId !== 'none') ? 165 : HISTORY_QR_SIZE;

    const options = {
        width: qrSize,
        height: qrSize,
        type: 'canvas',
        data: qrContent,
        margin: 0,
        qrOptions: { errorCorrectionLevel: 'H' },
        dotsOptions: { color: primaryColor, type: dotsType },
        backgroundOptions: { color: secondaryColor },
        cornersSquareOptions: { type: cornersSquareType, color: primaryColor },
        cornersDotOptions: { type: cornersDotType, color: primaryColor },
        image: logoUrl || undefined,
        imageOptions: {
            hideBackgroundDots: true,
            imageSize: 0.4,
            margin: 4,
            crossOrigin: 'anonymous',
        },
    };

    container.innerHTML = '';

    if (frameId === 'custom') {
        const customResult = await buildCustomFrameWrapper(container, customization, primaryColor, secondaryColor);
        if (customResult && customResult.appendTarget) {
            const zone = customResult.designJson?.qr_zone || { x_pct: 5, y_pct: 4, w_pct: 90, h_pct: 72 };
            const zonePixelW = Math.max(80, ((zone.w_pct || 90) / 100) * (customResult.designJson.canvas_width || 400));
            const zonePixelH = Math.max(80, ((zone.h_pct || 72) / 100) * (customResult.designJson.canvas_height || 500));
            const customQrSize = Math.max(80, Math.min(220, Math.round(Math.min(zonePixelW, zonePixelH) * 0.85)));
            options.width = customQrSize;
            options.height = customQrSize;
            var customQrCodeStyling = new QRS(options);
            customQrCodeStyling.append(customResult.appendTarget);
            return;
        }
    }

    if (frameId && frameId !== 'none' && FRAME_CONFIG[frameId]) {
        const cfg = FRAME_CONFIG[frameId];
        if (cfg && cfg.url) {
            const wrapper = document.createElement('div');
            wrapper.className = 'frame-wrapper relative mx-auto inline-block';

            const holePx = HISTORY_QR_SIZE;
            const totalW = holePx / (cfg.qrWidth / 100);
            const totalH = totalW * (cfg.frameHeight / cfg.frameWidth);
            wrapper.style.width = totalW + 'px';
            wrapper.style.height = totalH + 'px';

            const img = document.createElement('img');
            if (frameId === 'review-us') {
                img.src = await getReviewUsFrameUrl(customization.review_us_config || {});
            } else {
                img.src = cfg.themable
                    ? await getThemedFrameUrl(cfg.url, primaryColor, secondaryColor)
                    : BASE_URL + cfg.url;
            }
            img.alt = 'Frame';
            img.className = 'frame-img w-full h-full object-contain block';

            const qrInFrame = document.createElement('div');
            qrInFrame.className = 'qr-in-frame absolute flex items-center justify-center';
            qrInFrame.style.left = cfg.qrLeft + '%';
            qrInFrame.style.top = cfg.qrTop + '%';
            qrInFrame.style.width = cfg.qrWidth + '%';
            qrInFrame.style.height = cfg.qrHeight + '%';

            wrapper.appendChild(img);
            wrapper.appendChild(qrInFrame);
            container.appendChild(wrapper);

            var qrCodeStyling = new QRS(options);
            qrCodeStyling.append(qrInFrame);

            setTimeout(function() {
                var cw = container.clientWidth;
                var ch = container.clientHeight;
                var ww = totalW;
                var wh = totalH;
                if (cw > 0 && ch > 0 && (ww > cw || wh > ch)) {
                    var scale = Math.min(cw / ww, ch / wh, 1);
                    wrapper.style.transform = 'scale(' + scale + ')';
                    wrapper.style.transformOrigin = 'center center';
                }
            }, 80);
        }
    } else {
        var qrCodeStyling = new QRS(options);
        qrCodeStyling.append(container);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    waitForQRCodeStyling(function(QRCodeStylingClass) {
        var containers = document.querySelectorAll('[data-qr-id]');
        var i = 0;
        function renderNext() {
            if (i >= containers.length) return;
            var container = containers[i];
            i++;
            renderQRCode(container, QRCodeStylingClass).then(function() {
                renderNext();
            }).catch(function(error) {
                console.error('Error rendering QR code:', error);
                container.innerHTML = '<div class="text-xs text-red-500 text-center px-2">Error loading</div>';
                renderNext();
            });
        }
        renderNext();
    });
});
})();
</script>
@endpush
@endsection

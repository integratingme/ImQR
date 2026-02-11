@extends('layouts.app')

@section('title', 'Create ' . ucfirst($type) . ' QR Code')

@section('content')
@if(!empty($recaptchaSiteKey))
<script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}" async></script>
<script>
window.recaptchaSiteKey = @json($recaptchaSiteKey);
</script>
@endif
<style>
/* When a frame is selected, show full frame without clipping by rounded phone overlay */
#phone-mockup-overlay-step2.frame-selected {
    border-radius: 0;
}
/* Coupon mockup card – interior uses secondary color, left/right semicircles use primary */
.coupon-card {
    background: var(--coupon-card-bg, white);
    border-radius: 15px;
    max-width: 400px;
    width: 100%;
    min-height: 520px;
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 0;
    overflow: hidden;
}
.coupon-card-promo {
    height: 40%;
    min-height: 40%;
    flex-shrink: 0;
    background: var(--coupon-card-bg, white);
}
.coupon-card-promo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 15px 15px 0 0;
}
.coupon-card-content {
    flex: 1;
    padding: 40px 30px;
    display: flex;
    flex-direction: column;
}
.coupon-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 6px;
    line-height: 1.3;
}
.coupon-card-description {
    font-size: 0.8125rem;
    color: #4b5563;
    line-height: 1.4;
}
.coupon-card-button-wrap {
    margin-top: auto;
    padding-top: 2%;
}
.coupon-card-button {
    display: block;
    width: 100%;
    padding: 14px 24px;
    font-size: 1rem;
    font-weight: 600;
    color: var(--coupon-button-text-color, #1f2937);
    background: var(--coupon-button-bg, #d1d5db);
    border: none;
    border-radius: 12px;
    cursor: default;
    text-align: center;
}
.coupon-card-barcode-wrap {
    margin-top: auto;
    padding-top: 24px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.coupon-card > .coupon-card-barcode-wrap {
    position: absolute;
    bottom: 28px;
    left: 0;
    right: 0;
    margin-top: 0;
    padding: 16px 30px 0;
    display: flex;
    justify-content: center;
    align-items: center;
}
.coupon-card-valid-until {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 6px 8px;
    margin: 0;
    font-size: 0.75rem;
    color: #6b7280;
    text-align: center;
    line-height: 1.2;
}
.coupon-card-barcode-wrap img {
    max-width: 100%;
    width: auto;
    height: auto;
    max-height: 90px;
    object-fit: contain;
    display: block;
    border: 1px solid #e5e7eb;
}
.coupon-sales-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--coupon-sales-badge-bg, #9FE2BF);
    color: var(--coupon-sales-badge-text-color, #1f2937);
    padding: 8px 16px;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.875rem;
}
.coupon-sales-badge svg {
    flex-shrink: 0;
    width: 20px;
    height: 15px;
}
.coupon-card-dashed-line {
    position: absolute;
    left: 25px;
    right: 25px;
    top: 70%;
    transform: translateY(-50%);
    height: 0;
    border-top: 2px dashed var(--coupon-circle-color, #5B7DBE);
    z-index: 1;
}
.coupon-sales-badge-wrap {
    position: absolute;
    left: 50%;
    top: 70%;
    transform: translate(-50%, -50%);
    z-index: 2;
}
.coupon-card::before {
    content: '';
    position: absolute;
    width: 50px;
    height: 50px;
    background: var(--coupon-circle-color, #5B7DBE);
    border-radius: 50%;
    left: -25px;
    top: 70%;
    transform: translateY(-50%);
}
.coupon-mockup-company {
    height: 25%;
    min-height: 32px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--coupon-card-bg, #FFFFFF);
}
.coupon-mockup-company-logo {
    width: 28px;
    height: 28px;
    object-fit: contain;
    flex-shrink: 0;
}
.coupon-mockup-company-text {
    flex-shrink: 0;
}
.coupon-mockup-view-more {
    margin-top: 12px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: underline;
    color: var(--coupon-card-bg, #FFFFFF);
}
.coupon-mockup-view-more a {
    color: inherit;
    text-decoration: inherit;
    cursor: default;
}
.coupon-mockup-view-more a:hover {
    opacity: 0.9;
}
.coupon-card::after {
    content: '';
    position: absolute;
    width: 50px;
    height: 50px;
    background: var(--coupon-circle-color, #5B7DBE);
    border-radius: 50%;
    right: -25px;
    top: 70%;
    transform: translateY(-50%);
}
</style>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Progress Steps -->
    <div class="mb-12">
        <div class="flex items-center justify-center">
            <div class="flex items-center space-x-2 md:space-x-4">
                <!-- Step 1 -->
                <div class="flex items-center">
                    <div class="step-indicator step-active" id="step-1-indicator">
                        1
                    </div>
                    <span class="ml-1 md:ml-2 text-xs md:text-sm font-medium text-dark-500">Setup Info</span>
                </div>
                
                <div class="w-4 md:w-16 h-0.5 bg-dark-200"></div>
                
                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="step-indicator step-inactive" id="step-2-indicator">
                        2
                    </div>
                    <span class="ml-1 md:ml-2 text-xs md:text-sm font-medium text-dark-300">Customize</span>
                </div>
                
                <div class="w-4 md:w-16 h-0.5 bg-dark-200"></div>
                
                <!-- Step 3 -->
                <div class="flex items-center">
                    <div class="step-indicator step-inactive" id="step-3-indicator">
                        3
                    </div>
                    <span class="ml-1 md:ml-2 text-xs md:text-sm font-medium text-dark-300">Design QR Code</span>
                </div>
            </div>
        </div>
    </div>

    <form id="qr-form" enctype="multipart/form-data" @if($type === 'coupon') data-coupon-default-promo-url="{{ asset('coupon-icons/coupon-promo-image.webp') }}" @endif>
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">

        {{-- Honeypot fields – invisible to users, bots often fill these --}}
        <div style="display: none; position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden;">
            <input type="text" name="hp_url" value="" autocomplete="off" tabindex="-1" disabled>
            <input type="text" name="hp_comment" value="" autocomplete="off" tabindex="-1" disabled>
        </div>

        <!-- Step 1: Setup Info -->
        <div id="step-1" class="step-content">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-[60%_40%] gap-8 items-start">
                    <!-- Column 1: Form (60% width) -->
                    <div class="card">
                <h2 class="text-2xl font-bold text-dark-500 mb-6">Setup Information</h2>
                
                <!-- QR Name -->
                <div class="mb-6">
                    <label for="name" class="label">QR Code Name *</label>
                    <input type="text" id="name" name="name" class="input" placeholder="My {{ ucfirst($type) }} QR Code" value="{{ isset($qrCode) ? old('name', $qrCode->name) : old('name') }}" required>
                </div>
                
                @include('qr-codes.forms.' . $type)

                <div class="flex justify-end mt-6">
                    <button type="button" onclick="nextStep(2)" class="btn btn-primary">
                        Next Step →
                    </button>
                        </div>
                    </div>

                    <!-- Column 2: Preview Mockup with phone.png (40% width) -->
                    <div class="flex items-center justify-center sticky top-8">
                        <div id="phone-mockup-container-step1" class="relative w-full max-w-sm mx-auto ">
                            <img src="{{ asset('phone.png') }}" alt="Phone mockup" id="phone-mockup-step1" class="w-full h-auto object-contain relative z-10">
                            <div id="phone-mockup-overlay-step1" class="absolute pointer-events-none overflow-hidden" style="background-color: #FFFFFF; border-radius: 4rem; border: 2px solid #E5E7EB;">
                                <div id="phone-mockup-content" class="w-full h-full flex flex-col overflow-hidden rounded-[4rem]">
                                    <!-- Type-specific mockup preview will be shown here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Customize QR Code Appearance -->
        <div id="step-2" class="step-content hidden">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-[60%_40%] gap-8 items-start">
                    <!-- Column 1: QR Code Customization Options -->
                    <div class="card self-start">
                        <h2 class="text-2xl font-bold text-dark-500 mb-6">Customize QR Code Design</h2>
                        
                        <!-- Color Selection -->
                        <div class="mb-6">
                            <label class="label">Select Colors</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="primary_color" class="text-sm text-dark-300 mb-2 block">Primary Color (QR Code)</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" id="primary_color" name="primary_color" value="{{ isset($qrCode) ? ($qrCode->colors['primary'] ?? '#000000') : '#000000' }}" class="w-16 h-12 rounded border-2 border-dark-200 cursor-pointer">
                                        <input type="text" id="primary_color_hex" value="{{ isset($qrCode) ? ($qrCode->colors['primary'] ?? '#000000') : '#000000' }}" class="input flex-1" placeholder="#000000">
                                    </div>
                                </div>
                                <div>
                                    <label for="secondary_color" class="text-sm text-dark-300 mb-2 block">Background Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" id="secondary_color" name="secondary_color" value="{{ isset($qrCode) ? ($qrCode->colors['secondary'] ?? '#FFFFFF') : '#FFFFFF' }}" class="w-16 h-12 rounded border-2 border-dark-200 cursor-pointer">
                                        <input type="text" id="secondary_color_hex" value="{{ isset($qrCode) ? ($qrCode->colors['secondary'] ?? '#FFFFFF') : '#FFFFFF' }}" class="input flex-1" placeholder="#FFFFFF">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Color Presets -->
                            <div class="mt-4">
                                <p class="text-sm text-dark-300 mb-2">Quick Presets:</p>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="color-preset" data-primary="#000000" data-secondary="#FFFFFF">
                                        <div class="w-10 h-10 rounded border-2 border-dark-200 bg-black"></div>
                                    </button>
                                    <button type="button" class="color-preset" data-primary="#FF6a00" data-secondary="#FFFFFF">
                                        <div class="w-10 h-10 rounded border-2 border-dark-200" style="background-color: #FF6a00;"></div>
                                    </button>
                                    <button type="button" class="color-preset" data-primary="#10B981" data-secondary="#FFFFFF">
                                        <div class="w-10 h-10 rounded border-2 border-dark-200 bg-green-500"></div>
                                    </button>
                                    <button type="button" class="color-preset" data-primary="#8B5CF6" data-secondary="#FFFFFF">
                                        <div class="w-10 h-10 rounded border-2 border-dark-200 bg-purple-500"></div>
                                    </button>
                                    <button type="button" class="color-preset" data-primary="#EF4444" data-secondary="#FFFFFF">
                                        <div class="w-10 h-10 rounded border-2 border-dark-200 bg-red-500"></div>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Logo (optional) -->
                        <div class="mb-6">
                            <label class="label">Logo (optional)</label>
                            <p class="text-sm text-dark-300 mb-3">
                                Add a logo or image in the center of the QR code. This affects only the visual preview for now.
                            </p>
                            <div id="logo-limit-warning" class="mb-3 hidden flex items-center gap-2 px-4 py-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800">
                                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <p class="text-sm font-medium">You have already created a QR code with a custom logo. Free plan allows only one QR code with a custom logo.</p>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex-1">
                                    <label for="qr_logo" class="sr-only">Upload logo</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="qr_logo" class="w-full flex flex-col items-center justify-center px-4 py-3 border-2 border-dashed border-dark-200 rounded-lg cursor-pointer bg-white hover:border-primary-400 transition-colors">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-6 h-6 text-dark-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V8a2 2 0 00-2-2h-3.172a2 2 0 01-1.414-.586l-1.828-1.828A2 2 0 0010.172 3H6a2 2 0 00-2 2v13a2 2 0 002 2z"></path>
                                                </svg>
                                                <div class="text-left">
                                                    <p class="text-sm font-medium text-dark-500">Upload logo image</p>
                                                    <p class="text-xs text-dark-300">PNG, JPEG or SVG, max ~2 MB</p>
                                                </div>
                                            </div>
                                            <input id="qr_logo" name="qr_logo" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden">
                                        </label>
                                    </div>
                                </div>
                                <div class="flex flex-col items-start gap-2">
                                    <button type="button" id="qr_logo_remove_btn" class="btn btn-secondary btn-xs" style="display:none;">
                                        Remove logo
                                    </button>
                                    <div id="qr_logo_filename" class="text-xs text-dark-300 line-clamp-2 max-w-[180px]"></div>
                                </div>
                            </div>
                            <input type="hidden" id="qr_logo_data_url" name="qr_logo_data_url" value="">
                        </div>

                        <!-- Pattern Selection -->
                        <div class="mb-6">
                            <label class="label">Pattern Style</label>
                            <p class="text-sm text-dark-300 mb-4">Choose the pattern style for QR code modules.</p>
                            <div class="grid grid-cols-3 gap-3">
                                <button type="button" class="pattern-option border-2 border-primary-500 rounded-lg p-4 hover:border-primary-600 transition-colors" data-pattern="square" onclick="selectPattern(this, 'square')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="grid grid-cols-3 gap-1">
                                            <div class="w-4 h-4 bg-black rounded-sm"></div>
                                            <div class="w-4 h-4 bg-black rounded-sm"></div>
                                            <div class="w-4 h-4 bg-black rounded-sm"></div>
                                            <div class="w-4 h-4 bg-black rounded-sm"></div>
                                            <div class="w-4 h-4 bg-black rounded-sm"></div>
                                            <div class="w-4 h-4 bg-black rounded-sm"></div>
                                    </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Square</p>
                                </button>
                                <button type="button" class="pattern-option border-2 border-dark-200 rounded-lg p-4 hover:border-primary-400 transition-colors" data-pattern="circle" onclick="selectPattern(this, 'circle')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="grid grid-cols-3 gap-1">
                                            <div class="w-4 h-4 bg-black rounded-full"></div>
                                            <div class="w-4 h-4 bg-black rounded-full"></div>
                                            <div class="w-4 h-4 bg-black rounded-full"></div>
                                            <div class="w-4 h-4 bg-black rounded-full"></div>
                                            <div class="w-4 h-4 bg-black rounded-full"></div>
                                            <div class="w-4 h-4 bg-black rounded-full"></div>
                                    </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Circle</p>
                                </button>
                                <button type="button" class="pattern-option border-2 border-dark-200 rounded-lg p-4 hover:border-primary-400 transition-colors" data-pattern="rounded" onclick="selectPattern(this, 'rounded')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="grid grid-cols-3 gap-1">
                                            <div class="w-4 h-4 bg-black rounded"></div>
                                            <div class="w-4 h-4 bg-black rounded"></div>
                                            <div class="w-4 h-4 bg-black rounded"></div>
                                            <div class="w-4 h-4 bg-black rounded"></div>
                                            <div class="w-4 h-4 bg-black rounded"></div>
                                            <div class="w-4 h-4 bg-black rounded"></div>
                                    </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Rounded</p>
                                </button>
                            </div>
                            <input type="hidden" id="selected_pattern" name="pattern" value="{{ isset($qrCode) ? ($qrCode->customization['pattern'] ?? 'square') : 'square' }}">
                        </div>

                        <!-- Corner Style -->
                        <div class="mb-6">
                            <label class="label">Corner Style</label>
                            <p class="text-sm text-dark-300 mb-4">Customize the corner squares of your QR code.</p>
                            <div class="grid grid-cols-3 gap-3">
                                <button type="button" class="corner-option border-2 border-primary-500 rounded-lg p-4 hover:border-primary-600 transition-colors" data-corner="square" onclick="selectCorner(this, 'square')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="relative w-16 h-16">
                                            <div class="absolute top-0 left-0 w-6 h-6 border-4 border-black"></div>
                                            <div class="absolute top-0 right-0 w-6 h-6 border-4 border-black"></div>
                                            <div class="absolute bottom-0 left-0 w-6 h-6 border-4 border-black"></div>
                                    </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Square</p>
                                </button>
                                <button type="button" class="corner-option border-2 border-dark-200 rounded-lg p-4 hover:border-primary-400 transition-colors" data-corner="rounded" onclick="selectCorner(this, 'rounded')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="relative w-16 h-16">
                                            <div class="absolute top-0 left-0 w-6 h-6 border-4 border-black rounded"></div>
                                            <div class="absolute top-0 right-0 w-6 h-6 border-4 border-black rounded"></div>
                                            <div class="absolute bottom-0 left-0 w-6 h-6 border-4 border-black rounded"></div>
                                    </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Rounded</p>
                                </button>
                                <button type="button" class="corner-option border-2 border-dark-200 rounded-lg p-4 hover:border-primary-400 transition-colors" data-corner="extra-rounded" onclick="selectCorner(this, 'extra-rounded')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="relative w-16 h-16">
                                            <div class="absolute top-0 left-0 w-6 h-6 border-4 border-black rounded-lg"></div>
                                            <div class="absolute top-0 right-0 w-6 h-6 border-4 border-black rounded-lg"></div>
                                            <div class="absolute bottom-0 left-0 w-6 h-6 border-4 border-black rounded-lg"></div>
                                    </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Extra Rounded</p>
                                </button>
                            </div>
                            <input type="hidden" id="selected_corner" name="corner_style" value="{{ isset($qrCode) ? ($qrCode->customization['corner_style'] ?? 'square') : 'square' }}">
                        </div>

                        <!-- Corner Dot Style -->
                        <div class="mb-6">
                            <label class="label">Corner Dot Style</label>
                            <p class="text-sm text-dark-300 mb-4">Choose the style for the center dot in corner squares.</p>
                            <div class="grid grid-cols-3 gap-3">
                                <button type="button" class="corner-dot-option border-2 border-primary-500 rounded-lg p-4 hover:border-primary-600 transition-colors" data-corner-dot="square" onclick="selectCornerDot(this, 'square')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="w-12 h-12 border-4 border-black">
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-black"></div>
                                </div>
                                </div>
                            </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Square</p>
                                </button>
                                <button type="button" class="corner-dot-option border-2 border-dark-200 rounded-lg p-4 hover:border-primary-400 transition-colors" data-corner-dot="circle" onclick="selectCornerDot(this, 'circle')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="w-12 h-12 border-4 border-black rounded">
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-black rounded-full"></div>
                        </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Circle</p>
                                </button>
                                <button type="button" class="corner-dot-option border-2 border-dark-200 rounded-lg p-4 hover:border-primary-400 transition-colors" data-corner-dot="rounded" onclick="selectCornerDot(this, 'rounded')">
                                    <div class="w-full h-20 bg-white rounded border-2 border-dark-200 flex items-center justify-center">
                                        <div class="w-12 h-12 border-4 border-black rounded">
                                            <div class="w-full h-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-black rounded"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-center mt-2 text-dark-400">Rounded</p>
                                </button>
                            </div>
                            <input type="hidden" id="selected_corner_dot" name="corner_dot_style" value="{{ isset($qrCode) ? ($qrCode->customization['corner_dot_style'] ?? 'square') : 'square' }}">
                        </div>

                        <!-- Frame (around QR) -->
                        <div class="mb-6">
                            <label class="label">Frame</label>
                            <p class="text-sm text-dark-300 mb-4">Add a frame around your QR code (e.g. border + "Scan me!").</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                                <button type="button" class="frame-option border-2 border-primary-500 p-3 hover:border-primary-600 transition-colors flex flex-col items-center" data-frame="none" onclick="selectFrame(this, 'none')">
                                    <div class="w-full h-16 bg-dark-100 border border-dark-200 flex items-center justify-center text-dark-400 text-xs">No frame</div>
                                    <p class="text-xs text-center mt-2 text-dark-400">No frame</p>
                                </button>
                                <button type="button" class="frame-option border-2 border-dark-200 p-3 hover:border-primary-400 transition-colors flex flex-col items-center" data-frame="standard-border" onclick="selectFrame(this, 'standard-border')">
                                    <img src="{{ asset('frames/standard-border.svg') }}" alt="Standard" class="w-full h-16 object-contain object-center border border-dark-200">
                                    <p class="text-xs text-center mt-2 text-dark-400">Standard</p>
                                </button>
                                <button type="button" class="frame-option border-2 border-dark-200 p-3 hover:border-primary-400 transition-colors flex flex-col items-center" data-frame="thick-border" onclick="selectFrame(this, 'thick-border')">
                                    <img src="{{ asset('frames/thick-border.svg') }}" alt="Thick" class="w-full h-16 object-contain object-center border border-dark-200">
                                    <p class="text-xs text-center mt-2 text-dark-400">Thick</p>
                                </button>
                                <button type="button" class="frame-option border-2 border-dark-200 p-3 hover:border-primary-400 transition-colors flex flex-col items-center" data-frame="speech-bubble" onclick="selectFrame(this, 'speech-bubble')">
                                    <img src="{{ asset('frames/speech-bubble.svg') }}" alt="Speech bubble" class="w-full h-16 object-contain object-center border border-dark-200">
                                    <p class="text-xs text-center mt-2 text-dark-400">Speech bubble</p>
                                </button>
                                <button type="button" class="frame-option border-2 border-dark-200 p-3 hover:border-primary-400 transition-colors flex flex-col items-center" data-frame="menu-qr" onclick="selectFrame(this, 'menu-qr')">
                                    <img src="{{ asset('frames/menu-qr.svg') }}" alt="Menu" class="w-full h-16 object-contain object-center border border-dark-200">
                                    <p class="text-xs text-center mt-2 text-dark-400">Menu</p>
                                </button>
                                <button type="button" class="frame-option border-2 border-dark-200 p-3 hover:border-primary-400 transition-colors flex flex-col items-center" data-frame="location" onclick="selectFrame(this, 'location')">
                                    <img src="{{ asset('frames/location.svg') }}" alt="Location" class="w-full h-16 object-contain object-center border border-dark-200">
                                    <p class="text-xs text-center mt-2 text-dark-400">Location</p>
                                </button>
                                <button type="button" class="frame-option border-2 border-dark-200 p-3 hover:border-primary-400 transition-colors flex flex-col items-center" data-frame="wifi" onclick="selectFrame(this, 'wifi')">
                                    <img src="{{ asset('frames/wifi.svg') }}" alt="Wi‑Fi" class="w-full h-16 object-contain object-center border border-dark-200">
                                    <p class="text-xs text-center mt-2 text-dark-400">Wi‑Fi</p>
                                </button>
                                <button type="button" class="frame-option border-2 border-dark-200 p-3 hover:border-primary-400 transition-colors flex flex-col items-center" data-frame="chat" onclick="selectFrame(this, 'chat')">
                                    <img src="{{ asset('frames/chat.svg') }}" alt="Chat" class="w-full h-16 object-contain object-center border border-dark-200">
                                    <p class="text-xs text-center mt-2 text-dark-400">Chat</p>
                                </button>
                                <button type="button" class="frame-option border-2 border-dark-200 p-3 hover:border-primary-400 transition-colors flex flex-col items-center" data-frame="review-us" onclick="selectFrame(this, 'review-us')">
                                    <img src="{{ asset('frames/review-us.svg') }}" alt="Review us" class="w-full h-16 object-contain object-center border border-dark-200">
                                    <p class="text-xs text-center mt-2 text-dark-400">Review us</p>
                                </button>
                            </div>
                            <input type="hidden" id="selected_frame" name="frame" value="{{ isset($qrCode) ? ($qrCode->customization['frame'] ?? 'none') : 'none' }}">

                            <!-- Review-us frame options (visible only when this frame is selected) -->
                            <div id="review-us-frame-options" class="hidden mt-4 p-4 border border-dark-200 rounded-xl bg-dark-50 space-y-4">
                                <p class="text-sm font-medium text-dark-600">Customize Review us frame</p>
                                <div class="flex flex-wrap items-center gap-6">
                                    <div>
                                        <label for="review_frame_color" class="block text-xs font-medium text-dark-500 mb-1">Frame color</label>
                                        <div class="flex items-center gap-2">
                                            <input type="color" id="review_frame_color" name="review_frame_color" value="{{ isset($qrCode) && isset($qrCode->customization['review_us_config']['color']) ? $qrCode->customization['review_us_config']['color'] : '#84BD00' }}" class="h-10 w-14 cursor-pointer rounded border border-dark-200 bg-white p-0.5">
                                            <input type="text" id="review_frame_color_hex" maxlength="7" value="{{ isset($qrCode) && isset($qrCode->customization['review_us_config']['color']) ? $qrCode->customization['review_us_config']['color'] : '#84BD00' }}" class="w-24 rounded-lg border border-dark-200 px-2 py-1.5 text-sm font-mono" placeholder="#84BD00">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="review_frame_text_color" class="block text-xs font-medium text-dark-500 mb-1">Text color</label>
                                        <div class="flex items-center gap-2">
                                            <input type="color" id="review_frame_text_color" name="review_frame_text_color" value="{{ isset($qrCode) && isset($qrCode->customization['review_us_config']['text_color']) ? $qrCode->customization['review_us_config']['text_color'] : '#000000' }}" class="h-10 w-14 cursor-pointer rounded border border-dark-200 bg-white p-0.5">
                                            <input type="text" id="review_frame_text_color_hex" maxlength="7" value="{{ isset($qrCode) && isset($qrCode->customization['review_us_config']['text_color']) ? $qrCode->customization['review_us_config']['text_color'] : '#000000' }}" class="w-24 rounded-lg border border-dark-200 px-2 py-1.5 text-sm font-mono" placeholder="#000000">
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-3">
                                    <div>
                                        <label for="review_frame_line1" class="block text-xs font-medium text-dark-500 mb-1">Line 1</label>
                                        <input type="text" id="review_frame_line1" name="review_frame_line1" value="{{ isset($qrCode) && isset($qrCode->customization['review_us_config']['line1']) ? $qrCode->customization['review_us_config']['line1'] : 'your' }}" maxlength="100" class="w-full rounded-lg border border-dark-200 px-3 py-2 text-sm" placeholder="your">
                                    </div>
                                    <div>
                                        <label for="review_frame_line2" class="block text-xs font-medium text-dark-500 mb-1">Line 2</label>
                                        <input type="text" id="review_frame_line2" name="review_frame_line2" value="{{ isset($qrCode) && isset($qrCode->customization['review_us_config']['line2']) ? $qrCode->customization['review_us_config']['line2'] : 'text' }}" maxlength="100" class="w-full rounded-lg border border-dark-200 px-3 py-2 text-sm" placeholder="text">
                                    </div>
                                    <div>
                                        <label for="review_frame_line3" class="block text-xs font-medium text-dark-500 mb-1">Line 3</label>
                                        <input type="text" id="review_frame_line3" name="review_frame_line3" value="{{ isset($qrCode) && isset($qrCode->customization['review_us_config']['line3']) ? $qrCode->customization['review_us_config']['line3'] : 'here' }}" maxlength="100" class="w-full rounded-lg border border-dark-200 px-3 py-2 text-sm" placeholder="here">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-dark-500 mb-1">Icon</label>
                                    <p class="text-xs text-dark-400 mb-2">Choose a predefined icon or upload your own (JPG/PNG, max 2MB).</p>
                                    <input type="hidden" id="review_frame_icon" value="default">
                                    <div id="review_frame_selected_preview" class="mb-3 p-3 rounded-lg border border-dark-200 bg-white flex items-center gap-2" data-default-icon-url="{{ asset('frames/review-us-icons/default.svg') }}">
                                        <span class="text-xs text-dark-500 whitespace-nowrap">Selected:</span>
                                        <img id="review_frame_selected_icon_img" src="{{ asset('frames/review-us-icons/default.svg') }}" alt="Selected icon" class="h-12 w-auto object-contain max-w-[200px]">
                                    </div>
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <button type="button" class="review-frame-icon-option border-2 border-primary-500 px-3 py-2 rounded-lg text-xs font-medium flex flex-col items-center gap-1 min-w-[72px]" data-review-icon="default" onclick="selectReviewFrameIcon(this, 'default', 'here')" title="Default">
                                            <img src="{{ asset('frames/review-us-icons/default.svg') }}" alt="Default" class="w-10 h-8 object-contain">
                                            <span>Default</span>
                                        </button>
                                        @foreach($reviewUsIcons ?? [] as $icon)
                                        <button type="button" class="review-frame-icon-option border-2 border-dark-200 px-3 py-2 rounded-lg text-xs font-medium flex flex-col items-center gap-1 min-w-[72px] hover:border-primary-400" data-review-icon-url="{{ $icon['url'] }}" data-review-icon-name="{{ $icon['name'] }}" onclick="selectReviewFrameIcon(this, this.getAttribute('data-review-icon-url'), this.getAttribute('data-review-icon-name'))" title="{{ $icon['name'] }}">
                                            <img src="{{ $icon['url'] }}" alt="{{ $icon['name'] }}" class="w-10 h-8 object-contain">
                                            <span class="truncate max-w-full">{{ $icon['name'] }}</span>
                                        </button>
                                        @endforeach
                                        <button type="button" class="review-frame-icon-option border-2 border-dark-200 px-3 py-2 rounded-lg text-xs font-medium flex flex-col items-center gap-1 min-w-[72px] hover:border-primary-400" data-review-icon="custom" onclick="selectReviewFrameIcon(this, 'custom', null)" title="Upload your own">
                                            <svg class="w-10 h-8 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            <span>Custom</span>
                                        </button>
                                    </div>
                                    <div id="review_frame_custom_upload" class="hidden border border-dark-200 rounded-lg p-3 bg-white">
                                        <p class="text-xs text-dark-500 mb-2">Upload your own icon (JPG or PNG, max 2MB)</p>
                                        <div class="flex items-center gap-3">
                                            <input type="file" id="review_frame_logo" name="review_frame_logo" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden">
                                            <input type="hidden" id="review_frame_logo_data_url" value="">
                                            <button type="button" onclick="document.getElementById('review_frame_logo').click()" class="btn btn-secondary btn-sm">Choose image</button>
                                            <span id="review_frame_logo_filename" class="text-xs text-dark-400 truncate max-w-[140px]"></span>
                                            <button type="button" id="review_frame_logo_remove" class="hidden btn btn-outline btn-xs" onclick="clearReviewFrameLogo()">Remove</button>
                                        </div>
                                        <div id="review_frame_logo_preview" class="mt-2 hidden">
                                            <img id="review_frame_logo_preview_img" src="" alt="Logo preview" class="h-14 w-auto object-contain border border-dark-200 rounded-lg">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" onclick="prevStep(1)" class="btn btn-secondary" id="step2-back-btn">
                                ← Back
                            </button>
                            <button type="button" onclick="nextStep(3)" class="btn btn-primary" id="step2-next-btn">
                                <span id="step2-next-text">Next Step →</span>
                                <span id="step2-next-loading" class="hidden">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Generating...
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Column 2: QR Code Preview with phone.png -->
                    <div class="flex items-center justify-center sticky top-8">
                        <div class="relative w-full max-w-sm mx-auto">
                            <img src="{{ asset('phone.png') }}" alt="Phone mockup" id="phone-mockup-step2" class="w-full h-auto object-contain relative z-10">
                            <div id="phone-mockup-overlay-step2" class="absolute pointer-events-none flex items-center justify-center" style="background-color: #FFFFFF; border-radius: 4rem; border: 2px solid #E5E7EB;">
                                <div id="phone-mockup-qr-step2" class="w-full h-full flex items-center justify-center p-6">
                                    <!-- QR code preview will be inserted here -->
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Preview QR Code -->
        <div id="step-3" class="step-content hidden">
            <div class="max-w-4xl mx-auto">
                <div class="card">
                    <h2 class="text-2xl font-bold text-dark-500 mb-6 text-center">Your QR Code</h2>
                
                <div class="text-center mb-8">
                    <div id="qr-preview" class="inline-block">
                        <!-- Loading State -->
                        <div id="qr-loading" class="w-64 h-64 flex flex-col items-center justify-center">
                            <svg class="animate-spin h-16 w-16 text-primary-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-dark-300 font-medium">Generating your QR code...</p>
                            <p class="text-sm text-dark-200 mt-2">Please wait</p>
                        </div>
                        <!-- QR Code will be inserted here -->
                    </div>
                    <!-- Error Message -->
                    <div id="qr-error" class="hidden mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-red-800">Failed to generate QR code</p>
                                <p class="text-xs text-red-600 mt-1" id="qr-error-message"></p>
                            </div>
                        </div>
                        <button type="button" onclick="retryGeneration()" class="mt-3 btn btn-secondary text-sm">
                            Try Again
                        </button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                    <button type="button" id="download-png-btn" onclick="downloadQR('png')" class="btn btn-primary" disabled>
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download PNG
                    </button>
                    <button type="button" id="download-svg-btn" onclick="downloadQR('svg')" class="btn btn-outline" disabled>
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download SVG
                    </button>
                </div>

                <div class="flex justify-between">
                    <button type="button" onclick="prevStep(2)" class="btn btn-secondary">
                        ← Back
                    </button>
                    <a href="{{ route('qr-codes.index') }}" class="btn btn-primary">
                        Create Another QR Code
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let currentStep = 1;
let qrCodeId = @json(isset($qrCode) ? $qrCode->id : null);
let lastSubmittedFormFingerprint = null;
let qrStylingInstance = null;

// QR code data for editing
@if(isset($qrCode))
const qrCodeData = @json($qrCode->data ?? []);
const qrCodeColors = @json($qrCode->colors ?? []);
const qrCodeCustomization = @json($qrCode->customization ?? []);
const qrCodeFiles = @json($qrCode->files->map(function($file) {
    return [
        'file_type' => $file->file_type,
        'file_path' => asset('storage/' . $file->file_path),
    ];
})->toArray() ?? []);
@else
const qrCodeData = {};
const qrCodeColors = {};
const qrCodeCustomization = {};
const qrCodeFiles = [];
@endif

function getFormFingerprint() {
    const form = document.getElementById('qr-form');
    if (!form) return '';
    const fd = new FormData(form);
    const parts = [];
    for (const [k, v] of fd.entries()) {
        if (v instanceof File) {
            parts.push(k + '=' + (v.name || '') + '|' + (v.size || 0));
        } else {
            parts.push(k + '=' + String(v));
        }
    }
    parts.sort();
    return parts.join('&');
}

// Real-time validation - remove error styling when field is filled
function setupRealTimeValidation() {
    const type = document.querySelector('input[name="type"]').value;
    
    // Name field (always required)
    const name = document.getElementById('name');
    if (name) {
        name.addEventListener('input', () => {
            if (name.value.trim()) {
                name.classList.remove('border-red-500');
            } else {
                name.classList.add('border-red-500');
            }
        });
        name.addEventListener('blur', () => {
            if (!name.value.trim()) {
                name.classList.add('border-red-500');
            }
        });
    }
    
    // Type-specific fields
    const fieldsToWatch = {
        'url': ['url'],
        'email': ['email', 'message'],
        'text': ['text'],
        'event': ['event_name'],
        'location': ['address', 'latitude', 'longitude', 'location_url'],
        'wifi': ['ssid', 'encryption', 'password'],
        'phone': ['full_name', 'phone_number', 'phone_background_color_hex', 'phone_font_family'],
        'menu': ['menu_file', 'menu_url']
    };
    
    if (fieldsToWatch[type]) {
        fieldsToWatch[type].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                const isRequired = field.hasAttribute('required');
                if (field.type === 'file') {
                    field.addEventListener('change', () => {
                        if (field.files && field.files.length > 0) {
                            field.closest('.border-dashed')?.classList.remove('border-red-500');
                        } else if (isRequired) {
                            field.closest('.border-dashed')?.classList.add('border-red-500');
                        }
                    });
                } else {
                    field.addEventListener('input', () => {
                        if (field.value.trim()) {
                            field.classList.remove('border-red-500');
                        } else if (isRequired) {
                            field.classList.add('border-red-500');
                        }
                    });
                    // Check on blur for required fields
                    if (isRequired) {
                        field.addEventListener('blur', () => {
                            if (!field.value.trim()) {
                                field.classList.add('border-red-500');
                            }
                        });
                    }
                }
            }
        });
    }
    
    // Special handling for WiFi encryption change
    if (type === 'wifi') {
        const encryption = document.getElementById('encryption');
        if (encryption) {
            encryption.addEventListener('change', () => {
                encryption.classList.remove('border-red-500');
            });
        }
    }
    
    // Special handling for Menu - at least one of (sections, PDF, URL) must be filled
    if (type === 'menu') {
        const menuFile = document.getElementById('menu_file');
        const menuUrl = document.getElementById('menu_url');
        const menuSectionsContainer = document.getElementById('menu-sections-container');
        const clearMenuErrors = () => {
            if (menuFile) menuFile.closest('.border-dashed')?.classList.remove('border-red-500');
            if (menuUrl) menuUrl.classList.remove('border-red-500');
            if (menuSectionsContainer) menuSectionsContainer.classList.remove('border-red-500');
        };
        if (menuFile) {
            menuFile.addEventListener('change', () => {
                if (menuFile.files && menuFile.files.length > 0) clearMenuErrors();
            });
        }
        if (menuUrl) {
            menuUrl.addEventListener('input', () => {
                if (menuUrl.value.trim()) {
                    if (isValidUrl(menuUrl.value.trim())) clearMenuErrors();
                    else menuUrl.classList.add('border-red-500');
                } else menuUrl.classList.remove('border-red-500');
            });
        }
        if (menuSectionsContainer) {
            const observer = new MutationObserver(() => {
                if (menuSectionsContainer.querySelectorAll('.menu-section-block').length > 0) clearMenuErrors();
            });
            observer.observe(menuSectionsContainer, { childList: true, subtree: true });
        }
    }
    
    // Real-time validation for URL fields
    const urlFields = ['url', 'menu_url', 'website_url'];
    urlFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            const isRequired = field.hasAttribute('required');
            field.addEventListener('input', () => {
                if (field.value.trim()) {
                    if (isValidUrl(field.value.trim())) {
                        field.classList.remove('border-red-500');
                    } else {
                        field.classList.add('border-red-500');
                    }
                } else {
                    // If required and empty, show error
                    if (isRequired) {
                        field.classList.add('border-red-500');
                    } else {
                        field.classList.remove('border-red-500');
                    }
                }
            });
            // Check on blur for required fields
            if (isRequired) {
                field.addEventListener('blur', () => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                    }
                });
            }
        }
    });
    
    // Real-time validation for App Store link
    const appStoreLink = document.getElementById('app_store_link');
    if (appStoreLink) {
        appStoreLink.addEventListener('input', () => {
            if (appStoreLink.value.trim()) {
                if (isValidUrl(appStoreLink.value.trim()) && isValidAppStoreLink(appStoreLink.value.trim())) {
                    appStoreLink.classList.remove('border-red-500');
                } else {
                    appStoreLink.classList.add('border-red-500');
                }
            } else {
                appStoreLink.classList.remove('border-red-500');
            }
        });
    }
    
    // Real-time validation for Play Store link
    const playStoreLink = document.getElementById('play_store_link');
    if (playStoreLink) {
        playStoreLink.addEventListener('input', () => {
            if (playStoreLink.value.trim()) {
                if (isValidUrl(playStoreLink.value.trim()) && isValidPlayStoreLink(playStoreLink.value.trim())) {
                    playStoreLink.classList.remove('border-red-500');
                } else {
                    playStoreLink.classList.add('border-red-500');
                }
            } else {
                playStoreLink.classList.remove('border-red-500');
            }
        });
    }
    
    // Real-time validation for email field
    const emailField = document.getElementById('email');
    if (emailField) {
        emailField.addEventListener('input', () => {
            if (emailField.value.trim()) {
                if (isValidEmail(emailField.value.trim())) {
                    emailField.classList.remove('border-red-500');
                } else {
                    emailField.classList.add('border-red-500');
                }
            } else {
                // If required and empty, show error
                if (emailField.hasAttribute('required')) {
                    emailField.classList.add('border-red-500');
                } else {
                    emailField.classList.remove('border-red-500');
                }
            }
        });
        // Check on blur for required email
        if (emailField.hasAttribute('required')) {
            emailField.addEventListener('blur', () => {
                if (!emailField.value.trim()) {
                    emailField.classList.add('border-red-500');
                }
            });
        }
    }
    
    // Real-time validation for all required text fields
    const requiredFields = document.querySelectorAll('input[required], textarea[required]');
    requiredFields.forEach(field => {
        field.addEventListener('input', () => {
            if (field.value.trim()) {
                field.classList.remove('border-red-500');
            } else {
                field.classList.add('border-red-500');
            }
        });
        field.addEventListener('blur', () => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
            }
        });
    });
}

// Color picker sync
// Normalize hex to #rrggbb for backend (regex expects # + 6 hex digits)
function normalizeHexColor(val) {
    if (!val || typeof val !== 'string') return '#000000';
    let hex = val.trim().replace(/^#/, '');
    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    if (!/^[0-9A-Fa-f]{6}$/.test(hex)) return '#000000';
    return '#' + hex;
}

// Color picker sync for Step 2 (only named inputs primary_color, secondary_color are submitted)
const primaryColorInput = document.getElementById('primary_color');
const primaryColorHex = document.getElementById('primary_color_hex');
if (primaryColorInput && primaryColorHex) {
    primaryColorInput.addEventListener('input', (e) => {
        primaryColorHex.value = e.target.value;
        updateStep2QRPreview();
    });
    primaryColorHex.addEventListener('input', (e) => {
        const normalized = normalizeHexColor(e.target.value);
        primaryColorInput.value = normalized;
        primaryColorHex.value = normalized;
        updateStep2QRPreview();
    });
}

function updatePhoneMockupBackground() {
    const secondaryColor = document.getElementById('secondary_color').value;
    const overlay = document.getElementById('phone-mockup-overlay');
    if (overlay) {
        overlay.style.backgroundColor = secondaryColor;
    }
    // Update QR code preview when color changes
    updatePhoneMockupQR();
}

function updateStep2TypeSpecific() {
    const typeSpecificContainer = document.getElementById('step2-type-specific');
    if (!typeSpecificContainer) return;
    
    const type = document.querySelector('input[name="type"]').value;
    let html = '';
    
    // Type-specific customization options
    switch(type) {
        case 'url':
            html = `
                <div class="mb-4">
                    <label class="label">Display Options</label>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="show_url_text" name="show_url_text" checked class="rounded text-primary-600 focus:ring-primary-500" onchange="updatePhoneMockupText()">
                            <label for="show_url_text" class="text-sm text-dark-400">Show URL text below QR code</label>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'email':
            html = `
                <div class="mb-4">
                    <label class="label">Display Options</label>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="show_email_text" name="show_email_text" checked class="rounded text-primary-600 focus:ring-primary-500" onchange="updatePhoneMockupText()">
                            <label for="show_email_text" class="text-sm text-dark-400">Show email address below QR code</label>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'text':
            html = `
                <div class="mb-4">
                    <label class="label">Display Options</label>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="show_text_preview" name="show_text_preview" checked class="rounded text-primary-600 focus:ring-primary-500" onchange="updatePhoneMockupText()">
                            <label for="show_text_preview" class="text-sm text-dark-400">Show text preview below QR code</label>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'app':
            html = `
                <div class="mb-4">
                    <label class="label">Display Options</label>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="show_app_name" name="show_app_name" checked class="rounded text-primary-600 focus:ring-primary-500" onchange="updatePhoneMockupText()">
                            <label for="show_app_name" class="text-sm text-dark-400">Show app name below QR code</label>
                        </div>
                    </div>
                </div>
            `;
            break;
        // Add more types as needed
    }
    
    typeSpecificContainer.innerHTML = html;
}

function updatePhoneMockupText() {
    const textContainer = document.getElementById('phone-mockup-text');
    if (!textContainer) return;
    
    const type = document.querySelector('input[name="type"]').value;
    let text = '';
    let showText = false;
    
    switch(type) {
        case 'url':
            const url = document.getElementById('url')?.value || '';
            const showUrlText = document.getElementById('show_url_text')?.checked ?? true;
            if (showUrlText && url) {
                text = url;
                showText = true;
            }
            break;
        case 'email':
            const email = document.getElementById('email')?.value || '';
            const showEmailText = document.getElementById('show_email_text')?.checked ?? true;
            if (showEmailText && email) {
                text = email;
                showText = true;
            }
            break;
        case 'text':
            const textValue = document.getElementById('text')?.value || '';
            const showTextPreview = document.getElementById('show_text_preview')?.checked ?? true;
            if (showTextPreview && textValue) {
                // Truncate long text
                text = textValue.length > 50 ? textValue.substring(0, 50) + '...' : textValue;
                showText = true;
            }
            break;
        case 'app':
            const appName = document.getElementById('app_name')?.value || '';
            const showAppName = document.getElementById('show_app_name')?.checked ?? true;
            if (showAppName && appName) {
                text = appName;
                showText = true;
            }
            break;
    }
    
    if (showText && text) {
        // Get primary color for text (use darker shade for better contrast)
        const primaryColor = document.getElementById('primary_color')?.value || '#000000';
        textContainer.innerHTML = `<p class="text-xs font-medium break-words" style="color: ${primaryColor};">${text}</p>`;
        textContainer.classList.remove('hidden');
    } else {
        textContainer.innerHTML = '';
        textContainer.classList.add('hidden');
    }
}

// Pattern selection
function selectPattern(button, patternValue) {
    // Remove active state from all pattern options
    document.querySelectorAll('.pattern-option').forEach(btn => {
        btn.classList.remove('border-primary-500');
        btn.classList.add('border-dark-200');
    });
    
    // Add active state to selected pattern
    button.classList.remove('border-dark-200');
    button.classList.add('border-primary-500');
    
    // Update hidden input
    document.getElementById('selected_pattern').value = patternValue;
    
    // Update QR code preview
    updateStep2QRPreview();
}

// Corner style selection
function selectCorner(button, cornerValue) {
    // Remove active state from all corner options
    document.querySelectorAll('.corner-option').forEach(btn => {
        btn.classList.remove('border-primary-500');
        btn.classList.add('border-dark-200');
    });
    
    // Add active state to selected corner
    button.classList.remove('border-dark-200');
    button.classList.add('border-primary-500');
    
    // Update hidden input
    document.getElementById('selected_corner').value = cornerValue;
    
    // Update QR code preview
    updateStep2QRPreview();
}

var REVIEW_FRAME_PREDEFINED_TEXTS = {
    'Tripadvisor': ['Review us', 'on', 'Tripadvisor'],
    'Booking': ['Find us', 'on', 'Booking.com'],
    'Airbnb': ['Book', 'via', 'Airbnb']
};
function selectReviewFrameIcon(button, value, line3Label) {
    document.querySelectorAll('.review-frame-icon-option').forEach(btn => {
        btn.classList.remove('border-primary-500');
        btn.classList.add('border-dark-200');
    });
    button.classList.remove('border-dark-200');
    button.classList.add('border-primary-500');
    const hidden = document.getElementById('review_frame_icon');
    if (hidden) hidden.value = value || '';
    const customUpload = document.getElementById('review_frame_custom_upload');
    if (customUpload) customUpload.classList.toggle('hidden', value !== 'custom');
    var line1El = document.getElementById('review_frame_line1');
    var line2El = document.getElementById('review_frame_line2');
    var line3El = document.getElementById('review_frame_line3');
    if (value === 'default' || value === 'custom') {
        if (line1El) line1El.value = 'your';
        if (line2El) line2El.value = 'text';
        if (line3El) line3El.value = 'here';
    } else if (value && (value.startsWith('http') || value.startsWith('/')) && line3Label) {
        var texts = REVIEW_FRAME_PREDEFINED_TEXTS[line3Label];
        if (texts) {
            if (line1El) line1El.value = texts[0];
            if (line2El) line2El.value = texts[1];
            if (line3El) line3El.value = texts[2];
        } else {
            if (line1El) line1El.value = 'Review us';
            if (line2El) line2El.value = 'on';
            if (line3El) line3El.value = line3Label;
        }
    }
    var previewImg = document.getElementById('review_frame_selected_icon_img');
    var previewContainer = document.getElementById('review_frame_selected_preview');
    if (previewImg && previewContainer) {
        var defaultUrl = previewContainer.getAttribute('data-default-icon-url') || '';
        if (value === 'default') {
            previewImg.src = defaultUrl;
            previewImg.alt = 'Default';
        } else if (value === 'custom') {
            var dataUrl = document.getElementById('review_frame_logo_data_url')?.value?.trim() || '';
            previewImg.src = dataUrl || defaultUrl;
            previewImg.alt = dataUrl ? 'Custom' : 'Default';
        } else if (value && value !== 'custom') {
            var btnImg = button.querySelector('img');
            if (btnImg && btnImg.src) {
                previewImg.src = btnImg.src;
            } else {
                previewImg.src = value;
            }
            previewImg.alt = line3Label || 'Icon';
        }
    }
    if (currentStep === 2) updateStep2QRPreview();
}

function clearReviewFrameLogo() {
    const input = document.getElementById('review_frame_logo');
    const dataUrl = document.getElementById('review_frame_logo_data_url');
    const filename = document.getElementById('review_frame_logo_filename');
    const removeBtn = document.getElementById('review_frame_logo_remove');
    const preview = document.getElementById('review_frame_logo_preview');
    const previewImg = document.getElementById('review_frame_logo_preview_img');
    if (input) input.value = '';
    if (dataUrl) dataUrl.value = '';
    if (filename) filename.textContent = '';
    if (removeBtn) removeBtn.classList.add('hidden');
    if (preview) preview.classList.add('hidden');
    if (previewImg) previewImg.src = '';
    var selectedIconImg = document.getElementById('review_frame_selected_icon_img');
    var previewContainer = document.getElementById('review_frame_selected_preview');
    if (selectedIconImg && previewContainer) {
        selectedIconImg.src = previewContainer.getAttribute('data-default-icon-url') || '';
    }
    if (currentStep === 2) updateStep2QRPreview();
}

// Frame selection
function selectFrame(button, frameValue) {
    document.querySelectorAll('.frame-option').forEach(btn => {
        btn.classList.remove('border-primary-500', 'border-primary-600');
        btn.classList.add('border-dark-200');
    });
    button.classList.remove('border-dark-200');
    button.classList.add('border-primary-500');
    document.getElementById('selected_frame').value = frameValue;
    const reviewUsOpts = document.getElementById('review-us-frame-options');
    if (reviewUsOpts) {
        reviewUsOpts.classList.toggle('hidden', frameValue !== 'review-us');
    }
    updateStep2QRPreview();
}

// Corner dot style selection
function selectCornerDot(button, cornerDotValue) {
    // Remove active state from all corner dot options
    document.querySelectorAll('.corner-dot-option').forEach(btn => {
        btn.classList.remove('border-primary-500');
        btn.classList.add('border-dark-200');
    });
    
    // Add active state to selected corner dot
    button.classList.remove('border-dark-200');
    button.classList.add('border-primary-500');
    
    // Update hidden input
    document.getElementById('selected_corner_dot').value = cornerDotValue;
    
    // Update QR code preview
    updateStep2QRPreview();
}

function updateQRSize(value) {
    // Update size value display
    const sizeValue = document.getElementById('qr_size_value');
    if (sizeValue) {
        sizeValue.textContent = value + '%';
    }
    
    // Update QR code size in preview
    const qrContainer = document.getElementById('phone-mockup-qr');
    if (qrContainer) {
        const qrImage = qrContainer.querySelector('img[alt="QR Code Preview"]');
        if (qrImage) {
            qrImage.style.width = value + '%';
            qrImage.style.height = value + '%';
        }
        
        // Also update if QR code is inside a relative container
        const qrWrapper = qrContainer.querySelector('.relative');
        if (qrWrapper) {
            const wrapperQrImage = qrWrapper.querySelector('img[alt="QR Code Preview"]');
            if (wrapperQrImage) {
                wrapperQrImage.style.width = value + '%';
                wrapperQrImage.style.height = value + '%';
            }
        }
    }
}

// Update Step 1 preview (mockup layout based on type)
function updateStep1Preview() {
    if (currentStep !== 1) return;
    
    const type = document.querySelector('input[name="type"]').value;
    const previewContainer = document.getElementById('phone-mockup-content');
    if (!previewContainer) return;
    
    let mockupHtml = '';
    
    // Get overlay element for background color
    const overlay = document.getElementById('phone-mockup-overlay-step1');
    if (overlay) overlay.style.minHeight = '';

    switch(type) {
        case 'url':
            if (overlay) overlay.style.backgroundColor = '#F9FAFB'; // gray-50
            const url = document.getElementById('url')?.value || '';
            let urlDomain = 'example.com';
            if (url) {
                try {
                    urlDomain = new URL(url).hostname.replace('www.', '') || urlDomain;
                } catch (e) {
                    // Incomplete or invalid URL while typing – keep default
                }
            }
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col">
                    <!-- Browser Header -->
                    <div class="bg-white border-b border-gray-200 px-3 py-2 flex items-center gap-2 mt-15">
                        <div class="flex gap-1">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="flex-1 bg-gray-100 rounded px-3 py-1 text-xs text-gray-600 truncate">
                            ${url || 'https://example.com'}
                        </div>
                    </div>
                    <!-- Browser Content -->
                    <div class="p-4 flex-1 overflow-y-auto">
                        <div class="bg-white rounded-lg shadow-sm p-4">
                            <div class="h-4 bg-gray-200 rounded w-3/4 mb-3"></div>
                            <div class="h-3 bg-gray-200 rounded w-full mb-2"></div>
                            <div class="h-3 bg-gray-200 rounded w-5/6 mb-4"></div>
                            <div class="h-2 bg-gray-100 rounded w-full mb-1"></div>
                            <div class="h-2 bg-gray-100 rounded w-full mb-1"></div>
                            <div class="h-2 bg-gray-100 rounded w-4/5"></div>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'email':
            if (overlay) overlay.style.backgroundColor = '#FFFFFF'; // white
            const email = document.getElementById('email')?.value || '';
            const subject = document.getElementById('subject')?.value || '';
            const message = document.getElementById('message')?.value || '';
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col">
                    <!-- Email Header -->
                    <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex-shrink-0 mt-10">
                        <div class="space-y-2">
                            <div class="text-xs text-gray-600">
                                <span class="font-medium">From:</span> <span class="text-gray-900">you</span>
                            </div>
                            <div class="text-xs text-gray-600">
                                <span class="font-medium">To:</span> <span class="text-gray-900">${email || 'recipient@example.com'}</span>
                            </div>
                            <div class="text-xs text-gray-600">
                                <span class="font-medium">Subject:</span> <span class="text-gray-900">${subject || 'Email Subject'}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Email Body -->
                    <div class="p-4 flex-1 overflow-y-auto">
                        <div class="text-xs text-gray-600 mb-2">
                            <span class="font-medium">Message:</span>
                            <br>
                            <br>
                            <span class="text-gray-900">${message || 'Your message will appear here...'}</span>
                        </div>
                        
                    </div>
                </div>
            `;
            break;
            
        case 'text':
            const text = document.getElementById('text')?.value || 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam efficitur turpis ut massa semper, et venenatis ipsum vulputate. Curabitur ac sem accumsan, accumsan tortor eu, consectetur purus. Proin dignissim eu dui in vehicula. Morbi rhoncus, leo et tristique condimentum, dolor libero porttitor mauris, id dapibus urna erat a purus. Donec porta, augue quis pellentesque mollis, lectus purus laoreet turpis, vel consectetur nisi nibh vitae dolor. Ut in metus ut nulla congue gravida ut a quam. Quisque a lacus non orci malesuada ornare. Curabitur eu tristique ex. Phasellus ultrices non justo vitae fringilla. In consequat mollis nulla, id ullamcorper eros sollicitudin porta. In laoreet ultrices facilisis. Cras auctor nulla eu est facilisis ullamcorper. Maecenas vehicula sem quis ipsum posuere, ut dictum diam dictum.';
            const textBackgroundColor = document.getElementById('text_background_color_hex')?.value || '#FFFFFF';
            const textTextColor = document.getElementById('text_text_color_hex')?.value || '#000000';
            const textFontFamily = document.getElementById('text_font_family')?.value || 'Maven Pro';
            
            // Set background color on overlay div
            if (overlay) overlay.style.backgroundColor = textBackgroundColor;
            
            // Load Google Font if needed
            if (textFontFamily !== 'Maven Pro') {
                const fontId = textFontFamily.replace(/\s+/g, '+');
                const linkId = 'google-font-text-' + fontId;
                if (!document.getElementById(linkId)) {
                    const link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = `https://fonts.googleapis.com/css2?family=${fontId}:wght@400;500;600;700&display=swap`;
                    document.head.appendChild(link);
                }
            }
            
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex items-center justify-center p-4" style="font-family: '${textFontFamily}', sans-serif;">
                    <div class="w-full max-w-2xl h-[80%] flex flex-col min-h-0">
                        <div class="bg-white rounded-lg shadow-2xl p-6 md:p-8 flex-1 min-h-0 flex flex-col overflow-hidden">
                            <div class="prose max-w-none flex-1 min-h-0 overflow-y-auto" style="color: ${textTextColor}; font-family: '${textFontFamily}', sans-serif;">
                                <p class="text-xs md:text-sm leading-relaxed whitespace-pre-wrap">${text}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'phone': {
            const phoneBgColor = document.getElementById('phone_background_color_hex')?.value || '#2d3748';
            const phoneFontFamily = document.getElementById('phone_font_family')?.value || 'Maven Pro';
            if (overlay) overlay.style.backgroundColor = phoneBgColor;
            const phoneFullName = document.getElementById('full_name')?.value || 'John Doe';
            const phoneNumber = document.getElementById('phone_number')?.value || '+123456789';
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col items-center justify-center p-4" style="background-color: ${phoneBgColor}; font-family: '${phoneFontFamily}', sans-serif;">
                    <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center mb-2" style="background-color: rgba(255,255,255,0.2);">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2a4 4 0 100 8 4 4 0 000-8zm0 10c-3.314 0-6 2.686-6 6v2h12v-2c0-3.314-2.686-6-6-6z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="text-white text-sm font-semibold mb-0.5">${phoneFullName}</div>
                    <div class="text-white text-xs mb-4">${phoneNumber}</div>
                    <div class="w-full max-w-[85%] space-y-0 border-t border-white/20">
                        <div class="flex items-center gap-2 py-2 border-b border-white/20">
                            <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center" style="background-color: #22c55e;">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            </div>
                            <span class="text-white text-xs">Tap for cellular call</span>
                        </div>
                        <div class="flex items-center gap-2 py-2 border-b border-white/20">
                            <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center" style="background-color: #25D366;">
                                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                            <span class="text-white text-xs">Tap for WhatsApp call</span>
                        </div>
                        <div class="flex items-center gap-2 py-2">
                            <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center" style="background-color: #7360f2;">
                                <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M11.4 0C9.473-.028 5.333.553 2.846 2.666 1.357 3.737.02 5.597 0 8.233c-.014 1.645.303 3.255.93 4.756L0 24l5.301-1.398c1.467.795 3.093 1.211 4.742 1.21h.006c.022 0 .044-.002.066-.003.021.001.042.003.064.003 2.9 0 5.652-1.13 7.702-3.182C23.087 17.092 24 14.315 24 11.417 24 5.11 18.515.014 11.4 0zm.082 2.454c6.2.028 11.064 5.055 11.064 10.963 0 2.753-1.073 5.35-3.022 7.3-1.95 1.949-4.547 3.022-7.3 3.022-.02 0-.04-.002-.06-.003-.02.001-.04.003-.06.003-1.398 0-2.795-.354-4.026-1.038l-.284-.146-2.877.759.769-2.808-.181-.282a10.77 10.77 0 01-1.614-5.595c0-6.016 4.898-10.964 10.97-10.964zM6.345 4.383c-.26 0-.52.006-.778.02-.41.02-.41.32-.423.74-.02.54-.04 1.318.058 2.084.165 1.28.5 2.503 1.017 3.646.26.57.567 1.123.92 1.644.26.38.472.69.61.92.14.23.23.38.26.44.03.06.05.12.06.18.01.06.02.18-.01.3-.03.12-.09.27-.19.44-.1.17-.22.36-.35.55-.13.19-.27.38-.4.57-.1.14-.2.28-.28.4-.08.12-.14.21-.17.24-.03.03-.06.05-.08.06-.02.01-.05.02-.09.02-.04 0-.1-.01-.18-.02-.08-.01-.2-.05-.34-.1-.14-.05-.33-.12-.55-.22-.22-.1-.48-.22-.77-.36-1.7-.82-3.14-2.09-4.15-3.62-.5-.75-.88-1.55-1.13-2.36-.13-.42-.23-.84-.3-1.24-.07-.4-.12-.78-.15-1.13-.03-.35-.05-.66-.06-.92 0-.26-.01-.47-.01-.61 0-.42-.27-.74-.68-.74-.08 0-.16.01-.24.03-.08.02-.17.05-.26.09-.09.04-.2.09-.31.15-.11.06-.24.13-.38.2-.55.27-1.32.65-2.15 1.1-.42.23-.84.47-1.24.7-.2.12-.39.23-.56.34-.17.11-.32.2-.44.28-.12.08-.21.14-.27.18-.06.04-.1.07-.12.09-.02.02-.03.03-.03.03 0 0 .01.02.03.05.02.03.05.08.09.14.04.06.09.13.15.2.06.07.13.15.2.23.07.08.15.16.24.24.09.08.18.16.28.24.1.08.2.16.31.23.11.07.22.14.33.2.11.06.22.11.32.16.1.05.2.09.28.13.08.04.15.07.2.09.06.02.1.03.13.04.03.01.05.01.06.01.04 0 .07-.01.1-.02.03-.01.07-.03.12-.06.05-.03.12-.07.2-.12.08-.05.18-.11.29-.18.11-.07.24-.15.38-.24.14-.09.3-.19.46-.3.16-.11.34-.23.52-.36.36-.26.76-.54 1.18-.84.42-.3.84-.6 1.24-.9.4-.3.76-.58 1.08-.84.32-.26.58-.48.76-.66.18-.18.28-.31.31-.38.03-.07.04-.12.04-.16 0-.04-.01-.07-.02-.09-.01-.02-.03-.04-.06-.05-.03-.02-.07-.03-.12-.04-.05 0-.1-.01-.16-.01z"/></svg>
                            </div>
                            <span class="text-white text-xs">Tap for Viber call</span>
                        </div>
                    </div>
                </div>
            `;
            break;
        }
            
        case 'wifi':
            if (overlay) overlay.style.backgroundColor = '#FFFFFF'; // white
            const ssid = document.getElementById('ssid')?.value || '';
            const encryption = document.getElementById('encryption')?.value || 'WPA2';
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">${ssid || 'WiFi Network'}</div>
                                <div class="text-xs text-gray-500">${encryption === 'nopass' ? 'Open Network' : encryption + ' Protected'}</div>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="text-xs text-gray-600">Tap to connect</div>
                        </div>
                    </div>
                </div>
            `;
            break;
            
        case 'location':
            if (overlay) {
                overlay.style.backgroundColor = 'transparent';
                overlay.style.minHeight = '66.67vh';
            }
            const address = document.getElementById('address')?.value || '';
            const locationText = (address || '').trim().replace(/</g, '&lt;').replace(/>/g, '&gt;');
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden relative">
                    <img src="{{ asset('locationpin.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover">
                    ${locationText ? `<div class="absolute left-0 right-0 top-[60%] flex justify-center -translate-y-1/2 p-3"><div class="bg-white/60 px-4 py-2 rounded-lg max-w-full"><div class="text-sm font-medium text-gray text-center line-clamp-2">${locationText}</div></div></div>` : ''}
                </div>
            `;
            break;
            
        case 'event':
            const eventPrimaryColor = document.getElementById('event_primary_color_hex')?.value || document.getElementById('event_primary_color_picker')?.value || '#6594FF';
            const eventSecondaryColor = document.getElementById('event_secondary_color_hex')?.value || document.getElementById('event_secondary_color_picker')?.value || '#FFFFFF';
            const eventFontFamily = document.getElementById('event_font_family')?.value || 'Maven Pro';
            
            if (overlay) overlay.style.backgroundColor = eventSecondaryColor;
            const eventName = document.getElementById('event_name')?.value || '';
            const companyName = document.getElementById('company_name')?.value || '';
            const description = document.getElementById('description')?.value || '';
            const eventDate = document.getElementById('date')?.value || '';
            const eventTime = document.getElementById('time')?.value || '';
            const eventLocation = document.getElementById('location')?.value || '';
            const dressCodeColorPicker = document.getElementById('dress_code_color');
            const dressCodeHexInput = document.getElementById('dress_code_color_hex');
            const dressCodeColor = dressCodeColorPicker?.value || dressCodeHexInput?.value || '#000000';
            const contact = document.getElementById('contact')?.value || '';
            
            // Get event image from preview
            const eventImagePreview = document.getElementById('event-img-preview');
            const eventImageSrc = eventImagePreview && !eventImagePreview.classList.contains('hidden') 
                ? eventImagePreview.querySelector('img')?.src || '' 
                : '';
            
            // Get selected amenities
            const amenitiesCheckboxes = document.querySelectorAll('input[name="amenities[]"]:checked');
            const amenities = Array.from(amenitiesCheckboxes).map(cb => cb.value);
            
            // Format date (dd.mm.yyyy)
            let formattedDate = '';
            if (eventDate) {
                const date = new Date(eventDate + 'T00:00:00');
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                formattedDate = `${day}.${month}.${year}`;
            }
            
            // Format time (HH:mm)
            let formattedTime = '';
            if (eventTime) {
                const [hours, minutes] = eventTime.split(':');
                formattedTime = `${hours}:${minutes}h`;
            }
            
            // Use dress code color (always hex from color picker)
            const dressCodeHex = dressCodeColor.startsWith('#') ? dressCodeColor : '#000000';
            
            // Load Google Font if needed
            if (eventFontFamily !== 'Maven Pro') {
                const fontId = eventFontFamily.replace(/\s+/g, '+');
                const linkId = 'google-font-event-' + fontId;
                if (!document.getElementById(linkId)) {
                    const link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = `https://fonts.googleapis.com/css2?family=${fontId}:wght@400;500;600;700&display=swap`;
                    document.head.appendChild(link);
                }
            }
            
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col shadow-lg" style="background-color: ${eventSecondaryColor}; font-family: '${eventFontFamily}', sans-serif;">
                    <!-- Hero Image Section -->
                    <div class="relative h-64 w-full shrink-0">
                        ${eventImageSrc ? `
                            <img src="${eventImageSrc}" 
                                 class="w-full h-full object-cover" 
                                 alt="${eventName || 'Event'}">
                        ` : `
                            <div class="w-full h-full" style="background: linear-gradient(to bottom right, ${eventPrimaryColor}, ${eventPrimaryColor}dd);"></div>
                        `}
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        
                        <div class="absolute bottom-4 left-6">
                            ${companyName ? `
                                <span class="inline-block px-2 py-1 rounded text-[10px] font-bold text-white uppercase tracking-wider mb-2" style="background-color: ${eventPrimaryColor};">
                                    ${companyName}
                                </span>
                            ` : ''}
                            <h1 class="text-2xl font-bold text-white leading-tight" style="font-family: '${eventFontFamily}', sans-serif;">
                                ${eventName || 'Event Name'}
                            </h1>
                        </div>
                    </div>

                    <!-- Quick Info Bar (Date/Time/DressCode) -->
                    <div class="flex border-b border-gray-100 text-center" style="background-color: ${eventSecondaryColor};">
                        <div class="flex-1 p-4 border-r border-gray-100">
                            <p class="text-[10px] text-gray-400 uppercase font-bold" style="font-family: '${eventFontFamily}', sans-serif;">Date</p>
                            <p class="text-sm font-bold text-gray-800" style="font-family: '${eventFontFamily}', sans-serif;">${formattedDate || '-'}</p>
                        </div>
                        <div class="flex-1 p-4 border-r border-gray-100">
                            <p class="text-[10px] text-gray-400 uppercase font-bold" style="font-family: '${eventFontFamily}', sans-serif;">Time</p>
                            <p class="text-sm font-bold text-gray-800" style="font-family: '${eventFontFamily}', sans-serif;">${formattedTime || '-'}</p>
                        </div>
                        <div class="flex-1 p-4 flex flex-col items-center justify-center">
                            <div class="w-4 h-4 rounded-full border border-gray-200 mb-1 shadow-sm"
                                 style="background-color: ${dressCodeHex}"></div>
                            <p class="text-[9px] text-gray-400 uppercase font-bold" style="font-family: '${eventFontFamily}', sans-serif;">Dress Code</p>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="p-6 space-y-6 flex-grow overflow-y-auto" style="background-color: ${eventSecondaryColor};">
                        ${eventLocation ? `
                            <!-- Location -->
                            <div class="flex gap-3">
                                <div style="color: ${eventPrimaryColor};">📍</div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800" style="font-family: '${eventFontFamily}', sans-serif;">Location</h4>
                                    <p class="text-xs text-gray-500" style="font-family: '${eventFontFamily}', sans-serif;">${eventLocation}</p>
                                </div>
                            </div>
                        ` : ''}

                        ${description ? `
                            <!-- Description -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-bold text-gray-800" style="font-family: '${eventFontFamily}', sans-serif;">About the Event</h4>
                                <p class="text-xs text-gray-600 leading-relaxed italic" style="font-family: '${eventFontFamily}', sans-serif;">
                                    ${description}
                                </p>
                            </div>
                        ` : ''}

                        ${amenities.length > 0 ? `
                            <!-- Amenities -->
                            <div class="space-y-3">
                                <h4 class="text-sm font-bold text-gray-800" style="font-family: '${eventFontFamily}', sans-serif;">Amenities</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    ${amenities.map(amenity => `
                                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg border border-gray-100">
                                            <span class="text-xs uppercase font-bold" style="color: ${eventPrimaryColor}; font-family: '${eventFontFamily}', sans-serif;">${amenity}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}

                        ${contact ? `
                            <!-- Contact -->
                            <div class="rounded-2xl p-4 text-white flex items-center justify-between" style="background-color: #1e293b;">
                                <div>
                                    <p class="text-[9px] text-slate-400 uppercase font-bold" style="font-family: '${eventFontFamily}', sans-serif;">Contact Info</p>
                                    <p class="text-sm font-bold" style="font-family: '${eventFontFamily}', sans-serif;">${contact}</p>
                                </div>
                                <a href="tel:${contact}" class="p-2 rounded-lg" style="background-color: ${eventPrimaryColor};">📞</a>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
            break;
            
        case 'app':
            const appName = document.getElementById('app_name')?.value || '';
            const appDescription = document.getElementById('app_description')?.value || '';
            const appSecondaryColor = document.getElementById('app_secondary_color_hex')?.value || '#FFFFFF';
            const appPrimaryColor = document.getElementById('app_primary_color_hex')?.value || '#6594FF';
            const appFontFamily = document.getElementById('app_font_family')?.value || 'Maven Pro';
            const appTextColor = document.getElementById('app_text_color_hex')?.value || '#000000';
            const appTextFontSize = parseInt(document.getElementById('app_text_font_size')?.value || '16', 10);
            const appIconSize = parseInt(document.getElementById('app_icon_size')?.value || '96', 10);
            const appStoreLink = document.getElementById('app_store_link')?.value || '';
            const playStoreLink = document.getElementById('play_store_link')?.value || '';
            const appStoreButtonColor = document.getElementById('app_store_button_color_hex')?.value || appPrimaryColor;
            const appStoreButtonTextColor = document.getElementById('app_store_button_text_color_hex')?.value || appSecondaryColor;
            const appImagePreview = document.getElementById('app-img-preview');
            const appImageSrc = appImagePreview && !appImagePreview.classList.contains('hidden') 
                ? appImagePreview.querySelector('img')?.src || '' 
                : '';
            
            // Button texts - always show both buttons
            const appStoreButtonText = 'Download on the App Store';
            const playStoreButtonText = 'Get it on Google Play';
            
            // Load Google Font if needed
            if (appFontFamily !== 'Maven Pro') {
                const fontId = appFontFamily.replace(/\s+/g, '+');
                const linkId = 'google-font-app-' + fontId;
                if (!document.getElementById(linkId)) {
                    const link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = `https://fonts.googleapis.com/css2?family=${fontId}:wght@400;500;600;700&display=swap`;
                    document.head.appendChild(link);
                }
            }
            
            if (overlay) overlay.style.background = '';
            const appIconHalf = Math.floor(appIconSize / 2);
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col relative" style="font-family: '${appFontFamily}', sans-serif;">
                    <div class="flex-shrink-0 flex flex-col justify-end items-center" style="height: 25vh; background-color: ${appPrimaryColor};">
                        <div class="rounded-3xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg border-4 border-white" style="width: ${appIconSize}px; height: ${appIconSize}px; background-color: ${appPrimaryColor}; margin-bottom: -5vh; position: relative; z-index: 10;">
                            ${appImageSrc 
                                ? `<img src="${appImageSrc}" alt="App Logo" class="w-full h-full object-contain rounded-xl">`
                                : (appName ? appName.charAt(0).toUpperCase() : 'A')
                            }
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col min-h-0" style="background-color: ${appSecondaryColor}; padding-top: calc(3vh + ${appIconHalf}px);">
                        <div class="flex flex-col items-center px-4 pt-0">
                            <div class="font-bold mb-2 text-center" style="color: ${appTextColor}; font-size: ${appTextFontSize + 16}px;">
                                ${appName || 'Your app name here'}
                            </div>
                            <div class="px-2 text-center max-w-md mb-6" style="color: ${appTextColor}; font-size: ${appTextFontSize}px;">
                                ${appDescription || 'Your app description here'}
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-center gap-3 px-4 pb-4 min-h-0">
                            <button class="w-full py-3 rounded-lg font-medium transition-colors shadow-lg" style="background-color: ${appStoreButtonColor}; color: ${appStoreButtonTextColor};">
                                ${appStoreButtonText}
                            </button>
                            <button class="w-full py-3 rounded-lg font-medium transition-colors shadow-lg" style="background-color: ${appStoreButtonColor}; color: ${appStoreButtonTextColor};">
                                ${playStoreButtonText}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            break;

        case 'coupon': {
            const couponPrimaryColor = document.getElementById('coupon_primary_color_hex')?.value || '#6594FF';
            const couponSecondaryColor = document.getElementById('coupon_secondary_color_hex')?.value || '#FFFFFF';
            const couponSalesBadge = document.getElementById('coupon_sales_badge')?.value?.trim() || '25% OFF*';
            const couponSalesBadgeColor = document.getElementById('coupon_sales_badge_color_hex')?.value || '#9FE2BF';
            const couponSalesBadgeTextColor = document.getElementById('coupon_sales_badge_text_color_hex')?.value || '#1f2937';
            const couponCompany = document.getElementById('coupon_company')?.value?.trim() || '';
            const couponLogoPreview = document.getElementById('logo-img-preview');
            const couponLogoSrc = couponLogoPreview && !couponLogoPreview.classList.contains('hidden')
                ? (couponLogoPreview.querySelector('img')?.src || '')
                : '';
            const couponValidUntil = document.getElementById('coupon_valid_until')?.value || '';
            const couponViewMoreWebsite = document.getElementById('coupon_view_more_website')?.value?.trim() || '';
            const couponTitle = document.getElementById('coupon_title')?.value?.trim() || '';
            const couponDescription = document.getElementById('coupon_description')?.value?.trim() || '';
            const couponCodeButtonText = document.getElementById('coupon_code_button_text')?.value?.trim() || 'Get code';
            const couponButtonColor = document.getElementById('coupon_button_color_hex')?.value || '#D6D6D6';
            const couponButtonTextColor = document.getElementById('coupon_button_text_color_hex')?.value || '#1f2937';
            const couponUseBarcode = document.getElementById('coupon_use_barcode')?.checked || false;
            const couponBarcodePreview = document.getElementById('coupon-barcode-preview');
            const couponBarcodeSrc = couponUseBarcode && couponBarcodePreview && !couponBarcodePreview.classList.contains('hidden')
                ? (couponBarcodePreview.querySelector('img')?.src || '')
                : '';
            const formEl = document.getElementById('qr-form');
            const defaultPromoUrl = formEl?.dataset?.couponDefaultPromoUrl || '/coupon-icons/coupon-promo-image.webp';
            const couponImgPreview = document.getElementById('coupon-img-preview');
            const userPromoSrc = couponImgPreview && !couponImgPreview.classList.contains('hidden')
                ? (couponImgPreview.querySelector('img')?.src || '')
                : '';
            const promoImgSrc = userPromoSrc || defaultPromoUrl;

            if (overlay) overlay.style.backgroundColor = couponPrimaryColor;

            const salesBadgeHtml = `
                <div class="coupon-sales-badge-wrap">
                    <span class="coupon-sales-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        <span>${couponSalesBadge}</span>
                    </span>
                </div>
            `;

            const buttonHtml = `<div class="coupon-card-button-wrap"><button type="button" class="coupon-card-button">${couponCodeButtonText}</button></div>`;
            const barcodeHtml = couponBarcodeSrc
                ? `<div class="coupon-card-barcode-wrap"><img src="${couponBarcodeSrc}" alt="Barcode"></div>`
                : '';

            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-visible flex flex-col" style="--coupon-circle-color: ${couponPrimaryColor}; --coupon-card-bg: ${couponSecondaryColor}; --coupon-button-bg: ${couponButtonColor}; --coupon-button-text-color: ${couponButtonTextColor}; --coupon-sales-badge-bg: ${couponSalesBadgeColor}; --coupon-sales-badge-text-color: ${couponSalesBadgeTextColor}">
                    <div class="coupon-mockup-company">
                        ${couponLogoSrc ? `<img src="${couponLogoSrc}" alt="Logo" class="coupon-mockup-company-logo">` : ''}
                        <span class="coupon-mockup-company-text">${couponCompany || 'Your company name'}</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center justify-center p-4 min-h-0">
                        <div class="coupon-card">
                            <div class="coupon-card-promo">
                                ${promoImgSrc ? `<img src="${promoImgSrc}" alt="Promo">` : ''}
                            </div>
                            <div class="coupon-card-content">
                                <div class="coupon-card-title">${couponTitle || 'Your coupon title'}</div>
                                <div class="coupon-card-description">${couponDescription || 'Description'}</div>
                                ${couponBarcodeSrc ? '' : buttonHtml}
                            </div>
                            <div class="coupon-card-dashed-line"></div>
                            ${salesBadgeHtml}
                            ${barcodeHtml}
                            ${couponValidUntil ? `<div class="coupon-card-valid-until">Valid until: ${couponValidUntil}</div>` : ''}
                        </div>
                    </div>
                </div>
            `;
            break;
        }
            
        case 'menu': {
            const menuPrimaryColor = document.getElementById('menu_primary_color_hex')?.value || '#6594FF';
            const menuSecondaryColor = document.getElementById('menu_secondary_color_hex')?.value || '#FFFFFF';
            const menuFontFamily = document.getElementById('menu_font_family')?.value || 'Maven Pro';
            const restaurantName = document.getElementById('restaurant_name')?.value || '';
            const restaurantDescription = document.getElementById('restaurant_description')?.value || '';
            const menuRestaurantImg = document.getElementById('menu-restaurant-image-thumb');
            const hasMenuImage = menuRestaurantImg && menuRestaurantImg.src && menuRestaurantImg.src.startsWith('data:');
            const menuRestaurantNameFontSize = parseInt(document.getElementById('menu_restaurant_name_font_size')?.value || '18', 10);
            const menuRestaurantDescFontSize = parseInt(document.getElementById('menu_restaurant_description_font_size')?.value || '14', 10);
            const menuRestaurantNameColor = document.getElementById('menu_restaurant_name_color_hex')?.value || document.getElementById('menu_restaurant_name_color')?.value || '#FFFFFF';
            const menuRestaurantDescColor = document.getElementById('menu_restaurant_description_color_hex')?.value || document.getElementById('menu_restaurant_description_color')?.value || '#FFFFFF';

            const menuFileInput = document.getElementById('menu_file');
            const menuUrlInput = document.getElementById('menu_url');
            const menuSectionsContainer = document.getElementById('menu-sections-container');
            const hasMenuPdf = menuFileInput && menuFileInput.files && menuFileInput.files.length > 0;
            const hasMenuUrl = menuUrlInput && menuUrlInput.value && menuUrlInput.value.trim() !== '';
            const menuUrlValue = (menuUrlInput && menuUrlInput.value && menuUrlInput.value.trim()) || '';
            const hasMenuSections = menuSectionsContainer && menuSectionsContainer.querySelectorAll('.menu-section-block').length > 0;
            const menuMode = hasMenuSections ? 'sections' : (hasMenuUrl ? 'url' : (hasMenuPdf ? 'pdf' : 'sections'));

            const escapeMenuHtml = (s) => {
                if (!s) return '';
                const div = document.createElement('div');
                div.textContent = s;
                return div.innerHTML;
            };
            let sectionNames = [];
            let firstSectionProducts = [];
            if (menuSectionsContainer) {
                const sectionBlocks = menuSectionsContainer.querySelectorAll('.menu-section-block');
                sectionBlocks.forEach((block, idx) => {
                    const sectionNameInput = block.querySelector('input[name*="[section_name]"]');
                    const sectionName = sectionNameInput ? sectionNameInput.value.trim() : '';
                    if (sectionName) sectionNames.push({ name: sectionName, index: idx });
                    if (idx === 0) {
                        const productsContainer = block.querySelector('[id^="menu-section-products-"]');
                        if (productsContainer) {
                            const productBlocks = productsContainer.querySelectorAll('.menu-product-block');
                            productBlocks.forEach((pb) => {
                                const nameInput = pb.querySelector('input[name*="[product_name]"]');
                                const priceInput = pb.querySelector('input[name*="[price]"]');
                                const descInput = pb.querySelector('input[name*="[product_description]"]');
                                const allergensInput = pb.querySelector('input[name*="[allergens]"]');
                                const thumbImg = pb.querySelector('.menu-product-image-thumb');
                                const imgSrc = thumbImg && thumbImg.src && thumbImg.src.startsWith('data:') ? thumbImg.src : '';
                                firstSectionProducts.push({
                                    name: nameInput ? nameInput.value.trim() : '',
                                    price: priceInput ? priceInput.value.trim() : '',
                                    description: descInput ? descInput.value.trim() : '',
                                    allergens: allergensInput ? allergensInput.value.trim() : '',
                                    imgSrc: imgSrc
                                });
                            });
                        }
                    }
                });
            }

            let categoryPillsHtml = '';
            sectionNames.forEach((s, i) => {
                const isSelected = i === 0;
                categoryPillsHtml += `<span class="flex-shrink-0 py-1.5 px-3 rounded-full text-xs font-semibold whitespace-nowrap ${isSelected ? 'text-white' : 'text-gray-700 bg-gray-100'}" style="${isSelected ? 'background-color: ' + menuPrimaryColor + ';' : ''}">${escapeMenuHtml(s.name)}</span>`;
            });
            if (!categoryPillsHtml) {
                categoryPillsHtml = `<span class="flex-shrink-0 py-1.5 px-3 rounded-full text-xs font-semibold whitespace-nowrap text-white inline-flex items-center gap-1.5" style="background-color: ${menuPrimaryColor};">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add section
                </span>`;
            }

            let productCardsHtml = '';
            firstSectionProducts.forEach((p) => {
                if (!p.name && !p.price) return;
                const imgPlaceholder = `<div class="w-full h-full rounded-full flex items-center justify-center bg-gray-200"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>`;
                const productImgHtml = p.imgSrc ? `<img src="${p.imgSrc}" alt="" class="w-full h-full object-cover rounded-full">` : imgPlaceholder;
                productCardsHtml += `
                    <div class="rounded-xl bg-white shadow-sm border border-gray-100 p-2.5 flex flex-col gap-1">
                        <div class="flex gap-2">
                            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-gray-100">${productImgHtml}</div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold text-gray-800 truncate">${escapeMenuHtml(p.name) || '—'}</div>
                                ${p.description ? `<div class="text-[10px] text-gray-600 line-clamp-2 mt-0.5">${escapeMenuHtml(p.description)}</div>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            ${p.price ? `<span class="text-xs font-bold text-gray-800">${escapeMenuHtml(p.price)}</span>` : ''}
                            ${p.allergens ? `<span class="text-[10px] text-gray-400 truncate max-w-[50%]">${escapeMenuHtml(p.allergens)}</span>` : ''}
                        </div>
                    </div>`;
            });
            if (!productCardsHtml) {
                productCardsHtml = `
                    <div class="rounded-xl bg-white shadow-sm border border-gray-100 p-2.5 flex flex-col gap-1 border-2 border-dashed border-gray-200">
                        <div class="flex gap-2">
                            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 bg-gray-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <div class="min-w-0 flex-1 flex items-center">
                                <div class="text-xs font-semibold text-gray-500">Add product</div>
                            </div>
                        </div>
                    </div>`;
            }

            let menuBottomContentHtml = '';
            if (menuMode === 'pdf') {
                const pdfFileName = menuFileInput && menuFileInput.files[0] ? escapeMenuHtml(menuFileInput.files[0].name) : 'menu.pdf';
                menuBottomContentHtml = `
                    <div class="flex-1 min-h-0 rounded-t-2xl overflow-hidden flex flex-col items-center justify-center p-4" style="background-color: ${menuSecondaryColor}; color: #1f2937;">
                        <div class="w-20 h-20 rounded-xl bg-white shadow-sm border border-gray-100 flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-xs font-medium text-gray-600 truncate max-w-full px-2 mb-4">${pdfFileName}</p>
                        <button type="button" class="py-2.5 px-5 rounded-xl text-sm font-semibold text-white shadow-md" style="background-color: ${menuPrimaryColor};">View menu</button>
                    </div>`;
            } else if (menuMode === 'url') {
                const urlDisplay = menuUrlValue.length > 35 ? menuUrlValue.substring(0, 32) + '...' : menuUrlValue;
                menuBottomContentHtml = `
                    <div class="flex-1 min-h-0 rounded-t-2xl overflow-hidden flex flex-col p-4 pt-4" style="background-color: ${menuSecondaryColor}; color: #1f2937;">
                        <div class="w-full max-w-full rounded-lg bg-gray-100 px-3 py-2.5 mb-3">
                            <p class="text-xs text-gray-600 truncate">${escapeMenuHtml(urlDisplay)}</p>
                        </div>
                        <button type="button" class="w-full py-3 rounded-xl text-sm font-semibold text-white shadow-md flex-shrink-0" style="background-color: ${menuPrimaryColor};">Open menu</button>
                    </div>`;
            } else {
                menuBottomContentHtml = `
                    <div class="flex-shrink-0 px-3 py-2 overflow-x-auto" style="background-color: ${menuSecondaryColor};">
                        <div class="flex gap-2 items-center">
                            ${categoryPillsHtml}
                        </div>
                    </div>
                    <div class="flex-1 min-h-0 rounded-t-2xl overflow-hidden overflow-y-auto" style="background-color: ${menuSecondaryColor}; color: #1f2937;">
                        <div class="grid grid-cols-2 gap-2 p-3 pb-4">
                            ${productCardsHtml}
                        </div>
                    </div>`;
            }

            if (overlay) overlay.style.backgroundColor = menuSecondaryColor;

            if (menuFontFamily !== 'Maven Pro') {
                const fontId = menuFontFamily.replace(/\s+/g, '+');
                const linkId = 'google-font-menu-' + fontId;
                if (!document.getElementById(linkId)) {
                    const link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = `https://fonts.googleapis.com/css2?family=${fontId}:wght@400;500;600;700&display=swap`;
                    document.head.appendChild(link);
                }
            }

            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col" style="font-family: '${menuFontFamily}', sans-serif;">
                    <!-- Top section: title (mt-12) → description → image at bottom -->
                    <div class="flex-shrink-0 flex flex-col rounded-t-lg overflow-hidden" style="height: 32%; min-height: 32%; background-color: ${menuPrimaryColor};">
                        <div class="flex-shrink-0 px-4 pt-8 pb-2 text-center">
                            <div class="font-bold truncate" style="font-size: ${menuRestaurantNameFontSize}px; margin-top: 32px; color: ${menuRestaurantNameColor};">${escapeMenuHtml(restaurantName) || 'Restaurant name'}</div>
                            <div class="mt-1.5 line-clamp-2" style="font-size: ${menuRestaurantDescFontSize}px; color: ${menuRestaurantDescColor};">${escapeMenuHtml(restaurantDescription) || 'Short description of your restaurant'}</div>
                        </div>
                        <div class="flex-1 min-h-0"></div>
                        <div class="flex-shrink-0 w-full overflow-hidden rounded-b-2xl" style="height: 55%; max-height: 55%;">
                            ${hasMenuImage
                                ? `<div class="w-full h-full flex items-center justify-center overflow-hidden"><img src="${menuRestaurantImg.src}" alt="" class="h-full w-full object-cover object-bottom"></div>`
                                : `<div class="w-full h-full flex items-center justify-center bg-white/5">
                                    <svg class="w-10 h-10 opacity-50" style="color: rgba(255,255,255,0.6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                   </div>`
                            }
                        </div>
                    </div>
                    <!-- Bottom: PDF / URL / sections (changes by mode) -->
                    ${menuBottomContentHtml}
                </div>
            `;
            break;
        }

        case 'pdf':
            const pdfPrimaryColor = document.getElementById('pdf_primary_color_hex')?.value || '#6594FF';
            const pdfSecondaryColor = document.getElementById('pdf_secondary_color_hex')?.value || '#FFFFFF';
            const pdfTitle = document.getElementById('pdf_title')?.value || 'Title';
            const pdfWebsite = document.getElementById('pdf_website')?.value || '';
            const pdfFile = document.getElementById('pdf_file')?.files?.[0];
            const pdfFileDescription = document.getElementById('file_description')?.value || '';
            const pdfButtonText = document.getElementById('pdf_button_text')?.value || 'Download PDF';
            const pdfButtonColor = document.getElementById('pdf_button_color_hex')?.value || '#D6D6D6';
            const pdfFontFamily = document.getElementById('pdf_font_family')?.value || 'Maven Pro';
            
            // Button text color defaults to secondary color
            const buttonTextColor = pdfSecondaryColor;
            
            // Set split background on overlay div (top half primary, bottom half secondary)
            if (overlay) {
                overlay.style.background = `linear-gradient(to bottom, ${pdfPrimaryColor} 0%, ${pdfPrimaryColor} 50%, ${pdfSecondaryColor} 50%, ${pdfSecondaryColor} 100%)`;
            }
            
            // Load Google Font if needed
            if (pdfFontFamily !== 'Maven Pro') {
                const fontId = pdfFontFamily.replace(/\s+/g, '+');
                const linkId = 'google-font-' + fontId;
                if (!document.getElementById(linkId)) {
                    const link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = `https://fonts.googleapis.com/css2?family=${fontId}:wght@400;500;600;700&display=swap`;
                    document.head.appendChild(link);
                }
            }
            
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col relative" style="font-family: '${pdfFontFamily}', sans-serif;">
                    
                    <div class="relative z-10 flex-1 flex flex-col items-center justify-center p-6">
                        ${pdfTitle ? `
                            <h2 class="text-2xl font-bold mb-6 text-center" style="color: ${pdfPrimaryColor === '#FFFFFF' ? '#000000' : '#FFFFFF'}; font-family: '${pdfFontFamily}', sans-serif;">
                                ${pdfTitle}
                            </h2>
                        ` : ''}
                        
                        <!-- PDF Square -->
                        <div class="w-48 h-48 bg-white rounded-lg shadow-lg flex items-center justify-center mb-6">
                            ${pdfFile ? `
                                <div class="text-center p-4">
                                    <svg class="w-16 h-16 text-red-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-xs text-gray-600 font-medium truncate max-w-[180px]" style="font-family: '${pdfFontFamily}', sans-serif;">${pdfFile.name}</p>
                                </div>
                            ` : `
                                <div class="text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-xs" style="font-family: '${pdfFontFamily}', sans-serif;">PDF Preview</p>
                                </div>
                            `}
                        </div>
                        
                        ${pdfFileDescription ? `
                        <div class="w-[70%] mx-auto mb-6 text-center text-xs" style="color: ${pdfPrimaryColor === '#FFFFFF' ? '#000000' : '#FFFFFF'}; font-family: '${pdfFontFamily}', sans-serif;">
                            ${pdfFileDescription}
                        </div>
                        ` : ''}
                        
                        <!-- Download Button -->
                        <button type="button" class="px-8 py-3.5 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 mb-4" style="background-color: ${pdfButtonColor}; color: ${buttonTextColor}; font-family: '${pdfFontFamily}', sans-serif;">
                            ${pdfButtonText}
                        </button>
                        
                        ${pdfWebsite ? `
                            <a href="${pdfWebsite.startsWith('http') ? pdfWebsite : 'https://' + pdfWebsite}" target="_blank" class="text-sm underline hover:no-underline" style="color: ${pdfSecondaryColor === '#FFFFFF' ? '#000000' : '#FFFFFF'}; font-family: '${pdfFontFamily}', sans-serif;">
                                ${pdfWebsite}
                            </a>
                        ` : ''}
                    </div>
                </div>
            `;
            break;

        case 'business_card': {
            const escapeHtml = (s) => { if (!s) return ''; const d = document.createElement('div'); d.textContent = s; return d.innerHTML; };
            const bcPrimary = document.getElementById('business_card_primary_color_hex')?.value || '#e54e1a';
            const bcSecondary = document.getElementById('business_card_secondary_color_hex')?.value || '#FFFFFF';
            const bcFont = document.getElementById('business_card_font_family')?.value || 'Maven Pro';
            const companyName = document.getElementById('business_card_company_name')?.value || 'Your Company Name';
            const subtitleEl = document.getElementById('business_card_subtitle');
            const subtitle = subtitleEl?.value?.trim() || subtitleEl?.placeholder || '';
            const logoPreviewImg = document.getElementById('business-card-logo-preview-img');
            const logoSrc = logoPreviewImg && logoPreviewImg.src && logoPreviewImg.src.startsWith('data:') ? logoPreviewImg.src : '';
            const aboutEl = document.getElementById('business_card_about');
            const about = aboutEl?.value?.trim() || aboutEl?.placeholder || 'We are leaders in providing innovative solutions for small and medium enterprises. Our team of experts is here to help you in every step of your growth.';
            const contactNameEl = document.getElementById('business_card_contact_name');
            const contactName = contactNameEl?.value?.trim() || contactNameEl?.placeholder || '';
            const phoneEl = document.getElementById('business_card_phone');
            const phone = phoneEl?.value?.trim() || phoneEl?.placeholder || '';
            const emailEl = document.getElementById('business_card_email');
            const email = emailEl?.value?.trim() || emailEl?.placeholder || '';
            const addressEl = document.getElementById('business_card_address');
            const address = addressEl?.value?.trim() || addressEl?.placeholder || '';
            const workingHoursRaw = document.getElementById('business_card_working_hours')?.value || '';

            if (overlay) overlay.style.backgroundColor = bcSecondary;
            if (bcFont !== 'Maven Pro') {
                const fontId = bcFont.replace(/\s+/g, '+');
                const linkId = 'google-font-bc-' + fontId;
                if (!document.getElementById(linkId)) {
                    const link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = `https://fonts.googleapis.com/css2?family=${fontId}:wght@400;500;600;700&display=swap`;
                    document.head.appendChild(link);
                }
            }

            let buttonsHtml = '';
            const buttonRows = document.querySelectorAll('#business-card-buttons-container .business-card-button-row');
            buttonRows.forEach((row) => {
                const labelInput = row.querySelector('input[name*="[label]"]');
                const label = (labelInput?.value || '').trim() || (labelInput?.placeholder || 'Link');
                buttonsHtml += `<a href="#" class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <span class="font-semibold text-sm" style="color: ${bcPrimary}">${escapeHtml(label)}</span>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>`;
            });
            if (!buttonsHtml) {
                const firstButtonRow = document.querySelector('#business-card-buttons-container .business-card-button-row');
                const firstLabelInput = firstButtonRow?.querySelector('input[name*="[label]"]');
                const defaultLabel = firstLabelInput?.placeholder || 'Link';
                buttonsHtml = `<a href="#" class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 shadow-sm"><span class="font-semibold text-sm" style="color: ${bcPrimary}">${escapeHtml(defaultLabel)}</span><svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></a>`;
            }

            let workingHoursHtml = '';
            const workingHoursDisplay = workingHoursRaw.trim() || 'Monday - Friday: 07:00 AM - 05:00 PM';
            const lines = workingHoursDisplay.split('\n').filter(l => l.trim());
            if (lines.length) {
                workingHoursHtml = `<section class="bg-white/50 p-3 rounded-2xl">
                    <h3 class="font-bold text-gray-800 text-sm border-b border-gray-200 pb-2 mb-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full" style="background-color: ${bcPrimary}"></span>
                        Working Hours
                    </h3>
                    <div class="space-y-1 text-xs text-gray-600">${lines.map(l => escapeHtml(l.trim())).join('<br>')}</div>
                </section>`;
            }

            let socialsHtml = '';
            const socialRows = document.querySelectorAll('#business-card-socials-container .business-card-social-row');
            socialRows.forEach((row) => {
                const urlInput = row.querySelector('input[name*="[url]"]');
                if (urlInput?.value?.trim()) socialsHtml += `<a href="#" class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-sm font-bold transition-transform hover:scale-110" style="color: ${bcPrimary}">${escapeHtml((row.querySelector('select')?.selectedOptions?.[0]?.text || '?').charAt(0))}</a>`;
            });
            if (!socialsHtml) {
                socialsHtml = `<a href="#" class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-xs font-bold" style="color: ${bcPrimary}">f</a><a href="#" class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-xs font-bold" style="color: ${bcPrimary}">in</a>`;
            }

            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col min-h-0" style="font-family: '${bcFont}', sans-serif; background-color: ${bcSecondary};">
                    <div class="w-full h-full flex flex-col min-h-0" style="transform: scale(0.95); transform-origin: top center;">
                        <div class="pt-12 pb-10 px-4 text-center flex-shrink-0" style="background-color: ${bcPrimary}">
                            ${logoSrc ? `<img src="${logoSrc}" alt="" class="w-14 h-14 object-contain mx-auto mb-2 rounded-lg bg-white/10">` : ''}
                            <h1 class="text-xl font-bold text-white mb-0.5">${escapeHtml(companyName)}</h1>
                            <p class="text-white/90 font-light text-xs">${escapeHtml(subtitle)}</p>
                        </div>
                        <div class="p-4 -mt-5 rounded-t-[24px] flex-1 min-h-0 overflow-y-auto flex flex-col gap-4" style="background-color: ${bcSecondary}">
                            <div class="space-y-2">${buttonsHtml}</div>
                            <section class="bg-white/60 p-3 rounded-2xl flex-shrink-0">
                                <h3 class="font-bold text-gray-800 text-sm border-b border-gray-200 pb-2 mb-2 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" style="background-color: ${bcPrimary}"></span>
                                    About Us
                                </h3>
                                <p class="text-gray-600 text-xs leading-relaxed line-clamp-3">${escapeHtml(about)}</p>
                            </section>
                            <section class="bg-white/60 p-3 rounded-2xl flex-shrink-0">
                                <h3 class="font-bold text-gray-800 text-sm border-b border-gray-200 pb-2 mb-2 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" style="background-color: ${bcPrimary}"></span>
                                    Contact
                                </h3>
                                <div class="space-y-1.5 text-xs">
                                    ${contactName ? `<div class="flex items-center gap-2 text-gray-700"><span class="text-gray-400">👤</span>${escapeHtml(contactName)}</div>` : ''}
                                    ${phone ? `<a href="tel:${phone.replace(/ /g, '')}" class="flex items-center gap-2 font-medium" style="color: ${bcPrimary}"><span class="text-gray-400">📞</span>${escapeHtml(phone)}</a>` : ''}
                                    ${email ? `<a href="mailto:${email}" class="flex items-center gap-2 font-medium" style="color: ${bcPrimary}"><span class="text-gray-400">📧</span>${escapeHtml(email)}</a>` : ''}
                                </div>
                            </section>
                            <section class="bg-white/60 p-3 rounded-2xl flex-shrink-0">
                                <h3 class="font-bold text-gray-800 text-sm border-b border-gray-200 pb-2 mb-2 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full" style="background-color: ${bcPrimary}"></span>
                                    Location
                                </h3>
                                ${address ? `<p class="text-xs text-gray-600 mb-2">${escapeHtml(address)}</p>` : ''}
                                <span class="inline-block px-4 py-1.5 text-white text-xs font-bold rounded-full" style="background-color: ${bcPrimary}">Open in Google Maps</span>
                            </section>
                            ${workingHoursHtml}
                            <div class="pt-2 pb-2 flex justify-center gap-2 flex-wrap flex-shrink-0">${socialsHtml}</div>
                        </div>
                    </div>
                </div>
            `;
            break;
        }

        case 'personal_vcard': {
            const escapeHtml = (s) => { if (!s) return ''; const d = document.createElement('div'); d.textContent = s; return d.innerHTML; };
            const vcPrimary = document.getElementById('personal_vcard_primary_color_hex')?.value || '#b45341';
            const vcSecondary = document.getElementById('personal_vcard_secondary_color_hex')?.value || '#ffffff';
            const vcFont = document.getElementById('personal_vcard_font_family')?.value || 'Maven Pro';
            const vcName = document.getElementById('personal_vcard_name')?.value || 'Your Name';
            const vcTitle = document.getElementById('personal_vcard_title')?.value || 'Your Title';
            const profilePreviewImg = document.getElementById('personal-vcard-profile-preview-img');
            const profileSrc = profilePreviewImg && profilePreviewImg.src && profilePreviewImg.src.startsWith('data:') ? profilePreviewImg.src : '';
            const vcAbout = (document.getElementById('personal_vcard_about')?.value || '').trim() || 'A short bio...';
            const vcPhone = document.getElementById('personal_vcard_phone')?.value || '';
            const vcEmail = document.getElementById('personal_vcard_email')?.value || '';
            const vcMaps = document.getElementById('personal_vcard_maps_link')?.value || '';

            if (overlay) overlay.style.backgroundColor = vcSecondary;
            if (vcFont !== 'Maven Pro') {
                const fontId = vcFont.replace(/\s+/g, '+');
                const linkId = 'google-font-vc-' + fontId;
                if (!document.getElementById(linkId)) {
                    const link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = `https://fonts.googleapis.com/css2?family=${fontId}:wght@400;500;700&display=swap`;
                    document.head.appendChild(link);
                }
            }

            let socialsHtml = '';
            const socialRows = document.querySelectorAll('#personal-vcard-socials-container .personal-vcard-social-row');
            socialRows.forEach((row) => {
                const urlInput = row.querySelector('input[name*="[url]"]');
                const platformSelect = row.querySelector('select[name*="[platform]"]');
                const platform = platformSelect?.value || 'facebook';
                if (urlInput?.value?.trim()) {
                    const platformIcon = platform === 'facebook' ? 'f' : platform === 'instagram' ? 'in' : platform === 'twitter' ? 't' : platform === 'linkedin' ? 'in' : platform === 'whatsapp' ? 'wa' : '?';
                    socialsHtml += `<span class="w-7 h-7 rounded-full border border-gray-200 inline-flex items-center justify-center text-xs font-bold text-gray-400" style="color: ${vcPrimary}">${platformIcon}</span>`;
                }
            });
            if (!socialsHtml) {
                socialsHtml = `<span class="w-7 h-7 rounded-full border border-gray-200 inline-flex items-center justify-center text-xs font-bold text-gray-400">f</span><span class="w-7 h-7 rounded-full border border-gray-200 inline-flex items-center justify-center text-xs font-bold text-gray-400">in</span>`;
            }

            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden flex flex-col min-h-0" style="font-family: '${vcFont}', sans-serif;">
                    <div class="relative pt-16 pb-16 text-center flex-shrink-0" style="background-color: ${vcPrimary}">
                        <div class="relative z-10 mb-4 flex justify-center">
                            <div class="w-24 h-24 rounded-full border-[4px] border-white shadow-xl overflow-hidden bg-white" style="width: 6rem; height: 6rem;">
                                <img src="${profileSrc || 'https://placehold.co/200'}" alt="" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <h1 class="text-xl font-bold text-white mb-1">${escapeHtml(vcName)}</h1>
                        <p class="text-white/90 text-sm font-medium italic opacity-90">${escapeHtml(vcTitle)}</p>
                        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0]">
                            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="relative block w-full" style="height: 2rem;">
                                <path d="M0.00,49.98 C149.99,150.00 349.89,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="fill: ${vcSecondary}"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex justify-center gap-4 -mt-6 relative z-20 px-4 flex-shrink-0">
                        <span class="w-11 h-11 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-50" style="color: ${vcPrimary}; font-size: 1.1rem;">📞</span>
                        <span class="w-11 h-11 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-50" style="color: ${vcPrimary}; font-size: 1.1rem;">📧</span>
                        ${vcMaps ? `<span class="w-11 h-11 bg-white rounded-full shadow-lg flex items-center justify-center border border-gray-50" style="color: ${vcPrimary}; font-size: 1.1rem;">📍</span>` : ''}
                    </div>
                    <div class="px-5 pt-6 pb-4 flex-1 min-h-0 overflow-y-auto text-center">
                        ${vcAbout && vcAbout !== 'A short bio...' ? `<p class="text-gray-600 text-sm leading-relaxed italic">"${escapeHtml(vcAbout)}"</p>` : '<p class="text-gray-400 text-xs italic">Add your bio...</p>'}
                    </div>
                    <div class="px-5 pb-3 flex justify-center gap-3 flex-wrap flex-shrink-0">${socialsHtml}</div>
                    <div class="px-5 pb-4 flex-shrink-0">
                        <div class="w-full py-3 rounded-2xl text-white text-xs font-bold text-center uppercase tracking-wider shadow-lg" style="background-color: ${vcPrimary}">Save contact</div>
                    </div>
                </div>
            `;
            break;
        }
            
        default:
            if (overlay) overlay.style.backgroundColor = '#FFFFFF'; // white
            mockupHtml = `
                <div class="w-full h-full rounded-lg flex items-center justify-center p-4">
                    <div class="text-center">
                        <div class="text-sm text-gray-500">Fill in the form to see preview</div>
                    </div>
                </div>
            `;
    }
    
    previewContainer.innerHTML = mockupHtml;
}

// Build QR content string from Step 1 form values (mirrors backend logic)
function buildQrContentFromForm() {
    const type = document.querySelector('input[name="type"]').value;
    const getValue = (id) => document.getElementById(id)?.value || '';

    switch (type) {
        case 'url':
            return getValue('url');
        case 'email': {
            const email = getValue('email');
            const subject = getValue('subject');
            const message = getValue('message');
            const subjectPart = subject ? '?subject=' + encodeURIComponent(subject) : '';
            const bodyPart = message ? '&body=' + encodeURIComponent(message) : '';
            return `mailto:${email}${subjectPart}${bodyPart}`;
        }
        case 'text':
            // For text type, use text page URL (similar to PDF)
            return '/text/preview';
        case 'pdf':
            return '/pdf/preview';
        case 'menu':
            // Placeholder URL so QR renders on step 2; real URL is set when saved
            return (typeof window !== 'undefined' && window.location && window.location.origin ? window.location.origin + '/menu/preview' : '/menu/preview');
        case 'coupon':
            return '/coupon/preview';
        case 'event': {
            const amenities = []; 
            const checkedAmenities = document.querySelectorAll('input[name="amenities[]"]:checked');
            checkedAmenities.forEach(cb => amenities.push(cb.value));
            
            return JSON.stringify({
                type: 'event',
                event_name: getValue('event_name'),
                company_name: getValue('company_name'),
                description: getValue('description'),
                date: getValue('date'),
                time: getValue('time'),
                location: getValue('location'),
                amenities,
                dress_code_color: getValue('dress_code_color'),
                contact: getValue('contact'),
                event_primary_color: getValue('event_primary_color') || getValue('event_primary_color_hex') || '#6594FF',
                event_secondary_color: getValue('event_secondary_color') || getValue('event_secondary_color_hex') || '#FFFFFF',
                event_font_family: getValue('event_font_family') || 'Maven Pro',
            });
        }
        case 'app':
            // For app type, use app page URL (similar to PDF and text)
            return '/app/preview';
        case 'location': {
            const locationUrl = getValue('location_url');
            if (locationUrl && locationUrl.trim().startsWith('https://')) {
                return locationUrl.trim();
            }
            const lat = getValue('latitude');
            const lng = getValue('longitude');
            if (lat && lng) {
                return 'https://www.google.com/maps?q=' + lat + ',' + lng;
            }
            const address = getValue('address');
            return 'https://www.google.com/maps?q=' + encodeURIComponent(address || '');
        }
        case 'wifi': {
            const escapeWifi = (val) =>
                val.replace(/\\/g, '\\\\')
                    .replace(/;/g, '\\;')
                    .replace(/:/g, '\\:')
                    .replace(/,/g, '\\,');

            const ssidRaw = getValue('ssid');
            const passwordRaw = getValue('password');
            const encryption = getValue('encryption') || 'WPA2';

            const ssid = escapeWifi(ssidRaw);
            const password = escapeWifi(passwordRaw);

            let wifiString = 'WIFI:';
            if (encryption !== 'nopass') {
                wifiString += 'T:' + encryption + ';';
            }
            wifiString += 'S:' + ssid + ';';
            if (encryption !== 'nopass' && password) {
                wifiString += 'P:' + password + ';';
            }
            wifiString += ';';
            return wifiString;
        }
        case 'phone': {
            const phone = getValue('phone_number').replace(/[^\d+]/g, '');
            return 'tel:' + phone;
        }
        case 'business_card':
            return (typeof window !== 'undefined' && window.location && window.location.origin ? window.location.origin + '/business-card/preview' : '/business-card/preview');
        case 'personal_vcard':
            return (typeof window !== 'undefined' && window.location && window.location.origin ? window.location.origin + '/vcard/preview' : '/vcard/preview');
        default:
            return '';
    }
}

// Frame config: SVG path and QR area as % of frame size (left, top, width, height)
// themable: true = SVG may use #PRIMARY# and #SECONDARY# placeholders, replaced by palette colors
const FRAME_CONFIG = {
    'none': null,
    'standard-border': {
        url: '{{ asset("frames/standard-border.svg") }}',
        qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
        frameWidth: 400, frameHeight: 500,
        themable: true
    },
    'thick-border': {
        url: '{{ asset("frames/thick-border.svg") }}',
        qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
        frameWidth: 400, frameHeight: 500,
        themable: true
    },
    'speech-bubble': {
        url: '{{ asset("frames/speech-bubble.svg") }}',
        qrLeft: 5, qrTop: 3.85, qrWidth: 90, qrHeight: 69.2,
        frameWidth: 400, frameHeight: 520,
        themable: true
    },
    'menu-qr': {
        url: '{{ asset("frames/menu-qr.svg") }}',
        qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
        frameWidth: 400, frameHeight: 500,
        themable: true
    },
    'location': {
        url: '{{ asset("frames/location.svg") }}',
        qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
        frameWidth: 400, frameHeight: 500,
        themable: true
    },
    'wifi': {
        url: '{{ asset("frames/wifi.svg") }}',
        qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
        frameWidth: 400, frameHeight: 500,
        themable: true
    },
    'chat': {
        url: '{{ asset("frames/chat.svg") }}',
        qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
        frameWidth: 400, frameHeight: 500,
        themable: true
    },
    'review-us': {
        url: '{{ asset("frames/review-us.svg") }}',
        qrLeft: 15, qrTop: 6.15, qrWidth: 70, qrHeight: 43.08,
        frameWidth: 400, frameHeight: 650
    }
};

// Fetch SVG, replace #PRIMARY# and #SECONDARY# with palette colors, return blob URL for use in img/canvas
async function getThemedFrameUrl(svgUrl, primaryHex, secondaryHex) {
    const primary = (primaryHex || '#000000').trim();
    const secondary = (secondaryHex || '#FFFFFF').trim();
    const res = await fetch(svgUrl);
    let text = await res.text();
    text = text.replace(/#PRIMARY#/gi, primary).replace(/#SECONDARY#/gi, secondary);
    const blob = new Blob([text], { type: 'image/svg+xml' });
    return URL.createObjectURL(blob);
}

// Escape for SVG text content
function escapeSvgText(s) {
    if (s == null) return '';
    return String(s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

// Normalize hex color to #rrggbb
function normalizeHexColor(val) {
    if (!val || typeof val !== 'string') return '#84BD00';
    val = val.trim().replace(/^#/, '');
    if (/^[0-9A-Fa-f]{6}$/.test(val)) return '#' + val;
    if (/^[0-9A-Fa-f]{3}$/.test(val)) return '#' + val[0] + val[0] + val[1] + val[1] + val[2] + val[2];
    return '#84BD00';
}

// Build review-us frame SVG with custom text, color, and icon (default, predefined SVG, or custom upload); return blob URL
async function getReviewUsFrameUrl() {
    const url = FRAME_CONFIG['review-us']?.url;
    if (!url) return '';
    const res = await fetch(url);
    let svg = await res.text();
    const frameColor = normalizeHexColor(document.getElementById('review_frame_color')?.value || document.getElementById('review_frame_color_hex')?.value || '#84BD00');
    const textColor = normalizeHexColor(document.getElementById('review_frame_text_color')?.value || document.getElementById('review_frame_text_color_hex')?.value || '#000000');
    svg = svg.replace(/fill="#84BD00"/, 'fill="' + frameColor + '"');
    svg = svg.replace(/(<text[^>]*?)fill="#000000"([^>]*>)/g, '$1fill="' + textColor + '"$2');
    const line1 = document.getElementById('review_frame_line1')?.value ?? 'your';
    const line2 = document.getElementById('review_frame_line2')?.value ?? 'text';
    const line3 = document.getElementById('review_frame_line3')?.value ?? 'here';
    svg = svg.replace(/>your<\/text>/, '>' + escapeSvgText(line1) + '</text>');
    svg = svg.replace(/>text<\/text>/, '>' + escapeSvgText(line2) + '</text>');
    svg = svg.replace(/>here<\/text>/, '>' + escapeSvgText(line3) + '</text>');
    const iconValue = document.getElementById('review_frame_icon')?.value ?? 'default';
    const iconGroupRegex = /<g transform="translate\(100 480\)">[\s\S]*?<\/g>/;
    let iconReplacement = null;
    var defaultIconUrl = document.getElementById('review_frame_selected_preview')?.getAttribute('data-default-icon-url') || '';
    if (iconValue === 'custom') {
        const logoDataUrl = document.getElementById('review_frame_logo_data_url')?.value?.trim() || '';
        if (logoDataUrl) {
            iconReplacement = '<image x="100" y="480" width="200" height="80" href="' + logoDataUrl.replace(/"/g, '&quot;') + '" preserveAspectRatio="xMidYMid meet"/>';
        } else if (defaultIconUrl) {
            try {
                const iconRes = await fetch(defaultIconUrl);
                const iconSvgText = await iconRes.text();
                const iconDataUrl = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(iconSvgText)));
                iconReplacement = '<image x="100" y="480" width="200" height="80" href="' + iconDataUrl.replace(/"/g, '&quot;') + '" preserveAspectRatio="xMidYMid meet"/>';
            } catch (e) {
                console.warn('Could not load default icon for custom fallback', e);
            }
        }
    } else if (iconValue === 'default' && defaultIconUrl) {
        try {
            const iconRes = await fetch(defaultIconUrl);
            const iconSvgText = await iconRes.text();
            const iconDataUrl = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(iconSvgText)));
            iconReplacement = '<image x="100" y="480" width="200" height="80" href="' + iconDataUrl.replace(/"/g, '&quot;') + '" preserveAspectRatio="xMidYMid meet"/>';
        } catch (e) {
            console.warn('Could not load default icon for preview', e);
        }
    } else if (iconValue && iconValue !== 'default' && (iconValue.startsWith('http') || iconValue.startsWith('/'))) {
        try {
            const iconRes = await fetch(iconValue);
            const iconSvgText = await iconRes.text();
            const iconDataUrl = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(iconSvgText)));
            iconReplacement = '<image x="100" y="480" width="200" height="80" href="' + iconDataUrl.replace(/"/g, '&quot;') + '" preserveAspectRatio="xMidYMid meet"/>';
        } catch (e) {
            console.warn('Could not load predefined icon for preview', e);
        }
    }
    if (iconReplacement) {
        svg = svg.replace(iconGroupRegex, iconReplacement);
    }
    const blob = new Blob([svg], { type: 'image/svg+xml' });
    return URL.createObjectURL(blob);
}

// Update Step 2 QR code preview with customization using qr-code-styling
async function updateStep2QRPreview() {
    if (currentStep !== 2) return;

    const qrContainer = document.getElementById('phone-mockup-qr-step2');
    const overlay = document.getElementById('phone-mockup-overlay-step2');
    if (!qrContainer) return;

    const frameId = document.getElementById('selected_frame')?.value || 'none';
    const type = document.querySelector('input[name="type"]').value;
    const primaryColor = document.getElementById('primary_color')?.value || '#000000';
    const secondaryColor = document.getElementById('secondary_color')?.value || '#FFFFFF';
    const pattern = document.getElementById('selected_pattern')?.value || 'square';
    const cornerStyle = document.getElementById('selected_corner')?.value || 'square';
    const cornerDotStyle = document.getElementById('selected_corner_dot')?.value || 'square';
    const logoDataUrl = document.getElementById('qr_logo_data_url')?.value || '';

    // Update overlay background color (Step 2 background)
    if (overlay) {
        overlay.style.backgroundColor = secondaryColor;
        if (frameId && frameId !== 'none') {
            overlay.classList.add('frame-selected');
        } else {
            overlay.classList.remove('frame-selected');
        }
    }

    const data = buildQrContentFromForm();

    if (!window.QRCodeStyling) {
        console.warn('QR Code Styling library is not loaded. Make sure you ran `npm install qr-code-styling` and the Vite bundle is loaded.');
        qrContainer.innerHTML = '';
        return;
    }

    // Build frame wrapper when a frame is selected; get the element to which we append the QR
    let appendTarget = qrContainer;
    const QR_HOLE_SIZE = 260;
    const qrDisplaySize = (frameId && frameId !== 'none') ? 220 : QR_HOLE_SIZE;
    if (frameId && frameId !== 'none' && FRAME_CONFIG[frameId]) {
        const cfg = FRAME_CONFIG[frameId];
        if (cfg.url && cfg.qrLeft !== undefined) {
            // Full-frame layout: frame image + QR over the "hole" (QR drawn smaller for gap)
            const wrapper = document.createElement('div');
            wrapper.className = 'frame-wrapper relative mx-auto';
            const holePx = QR_HOLE_SIZE;
            const totalW = holePx / (cfg.qrWidth / 100);
            const totalH = totalW * (cfg.frameHeight / cfg.frameWidth);
            wrapper.style.width = totalW + 'px';
            wrapper.style.height = totalH + 'px';
            const img = document.createElement('img');
            if (frameId === 'review-us') {
                img.src = await getReviewUsFrameUrl();
            } else {
                img.src = cfg.themable
                    ? await getThemedFrameUrl(cfg.url, primaryColor, secondaryColor)
                    : cfg.url;
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
            qrContainer.innerHTML = '';
            qrContainer.appendChild(wrapper);
            appendTarget = qrInFrame;
            qrStylingInstance = null;
        }
    } else {
        // No frame: clear any previous wrapper so QR appends directly
        if (qrContainer.querySelector('.frame-wrapper')) {
            qrContainer.innerHTML = '';
            qrStylingInstance = null;
        }
        appendTarget = qrContainer;
    }

    const dotsTypeMap = {
        square: 'square',
        circle: 'dots',
        rounded: 'rounded',
    };

    const cornersSquareTypeMap = {
        square: 'square',
        rounded: 'rounded',
        'extra-rounded': 'extra-rounded',
    };

    const cornersDotTypeMap = {
        square: 'square',
        circle: 'dot',
        rounded: 'rounded',
    };

    const dotsType = dotsTypeMap[pattern] || 'square';
    const cornersSquareType = cornersSquareTypeMap[cornerStyle] || 'square';
    const cornersDotType = cornersDotTypeMap[cornerDotStyle] || 'dot';

    const options = {
        width: qrDisplaySize,
        height: qrDisplaySize,
        type: 'canvas',
        data,
        margin: 0,
        qrOptions: {
            errorCorrectionLevel: 'H',
        },
        dotsOptions: {
            color: primaryColor,
            type: dotsType,
        },
        backgroundOptions: {
            color: secondaryColor,
        },
        cornersSquareOptions: {
            type: cornersSquareType,
            color: primaryColor,
        },
        cornersDotOptions: {
            type: cornersDotType,
            color: primaryColor,
        },
        image: logoDataUrl || undefined,
        imageOptions: {
            hideBackgroundDots: true,
            imageSize: 0.4,
            margin: 4,
            crossOrigin: 'anonymous',
        },
    };

    // Create or update QR code instance (append to appendTarget: either qrContainer or qr-in-frame)
    if (!qrStylingInstance) {
        appendTarget.innerHTML = '';
        qrStylingInstance = new window.QRCodeStyling(options);
        qrStylingInstance.append(appendTarget);
    } else {
        qrStylingInstance.update(options);
    }
}

function updatePhoneMockupOverlaySize() {
    const phoneMockup = document.getElementById('phone-mockup');
    const phoneOverlay = document.getElementById('phone-mockup-overlay');
    
    if (!phoneMockup || !phoneOverlay) return;
    
    const img = phoneMockup;
    const parentRect = img.parentElement.getBoundingClientRect();
    
    const imgAspect = img.naturalWidth / img.naturalHeight;
    const containerAspect = parentRect.width / parentRect.height;
    
    let displayWidth, displayHeight, offsetX, offsetY;
    
    if (imgAspect > containerAspect) {
        // Image is wider - fit to width
        displayWidth = parentRect.width;
        displayHeight = parentRect.width / imgAspect;
        offsetX = 0;
        offsetY = (parentRect.height - displayHeight) / 2;
    } else {
        // Image is taller - fit to height
        displayHeight = parentRect.height;
        displayWidth = parentRect.height * imgAspect;
        offsetX = (parentRect.width - displayWidth) / 2;
        offsetY = 0;
    }
    
    // Add margin from all sides (1rem = 16px)
    const margin = 16;
    const overlayWidth = displayWidth - (margin * 2);
    const overlayHeight = displayHeight - (margin * 2);
    
    phoneOverlay.style.width = overlayWidth + 'px';
    phoneOverlay.style.height = overlayHeight + 'px';
    phoneOverlay.style.left = (offsetX + margin) + 'px';
    phoneOverlay.style.top = (offsetY + margin) + 'px';
}

// Secondary color picker sync for Step 2
const secondaryColorInput = document.getElementById('secondary_color');
const secondaryColorHex = document.getElementById('secondary_color_hex');
if (secondaryColorInput && secondaryColorHex) {
    secondaryColorInput.addEventListener('input', (e) => {
        secondaryColorHex.value = e.target.value;
        updateStep2QRPreview();
    });

    secondaryColorHex.addEventListener('input', (e) => {
        const normalized = normalizeHexColor(e.target.value);
        secondaryColorInput.value = normalized;
        secondaryColorHex.value = normalized;
        updateStep2QRPreview();
    });
}

// Populate form fields with existing QR code data when editing
function populateFormFields() {
    if (!qrCodeId || Object.keys(qrCodeData).length === 0) {
        return;
    }
    
    const type = document.querySelector('input[name="type"]')?.value;
    if (!type) return;
    
    // Populate Step 2 fields (colors, pattern, corner, frame, logo)
    if (qrCodeColors.primary) {
        const primaryColorInput = document.getElementById('primary_color');
        const primaryColorHex = document.getElementById('primary_color_hex');
        if (primaryColorInput) primaryColorInput.value = qrCodeColors.primary;
        if (primaryColorHex) primaryColorHex.value = qrCodeColors.primary;
    }
    
    if (qrCodeColors.secondary) {
        const secondaryColorInput = document.getElementById('secondary_color');
        const secondaryColorHex = document.getElementById('secondary_color_hex');
        if (secondaryColorInput) secondaryColorInput.value = qrCodeColors.secondary;
        if (secondaryColorHex) secondaryColorHex.value = qrCodeColors.secondary;
    }
    
    // Populate pattern
    if (qrCodeCustomization.pattern) {
        const patternInput = document.getElementById('selected_pattern');
        if (patternInput) {
            patternInput.value = qrCodeCustomization.pattern;
            // Update visual selection
            const patternButton = document.querySelector(`[data-pattern="${qrCodeCustomization.pattern}"]`);
            if (patternButton) {
                selectPattern(patternButton, qrCodeCustomization.pattern);
            }
        }
    }
    
    // Populate corner style
    if (qrCodeCustomization.corner_style) {
        const cornerInput = document.getElementById('selected_corner');
        if (cornerInput) {
            cornerInput.value = qrCodeCustomization.corner_style;
            // Update visual selection
            const cornerButton = document.querySelector(`[data-corner="${qrCodeCustomization.corner_style}"]`);
            if (cornerButton) {
                selectCorner(cornerButton, qrCodeCustomization.corner_style);
            }
        }
    }
    
    // Populate corner dot style
    if (qrCodeCustomization.corner_dot_style) {
        const cornerDotInput = document.getElementById('selected_corner_dot');
        if (cornerDotInput) {
            cornerDotInput.value = qrCodeCustomization.corner_dot_style;
            // Update visual selection
            const cornerDotButton = document.querySelector(`[data-corner-dot="${qrCodeCustomization.corner_dot_style}"]`);
            if (cornerDotButton) {
                selectCornerDot(cornerDotButton, qrCodeCustomization.corner_dot_style);
            }
        }
    }
    
    // Populate frame
    if (qrCodeCustomization.frame) {
        const frameInput = document.getElementById('selected_frame');
        if (frameInput) {
            frameInput.value = qrCodeCustomization.frame;
            // Update visual selection
            const frameButton = document.querySelector(`[data-frame="${qrCodeCustomization.frame}"]`);
            if (frameButton) {
                selectFrame(frameButton, qrCodeCustomization.frame);
            }
        }
    }
    
    // Populate logo
    if (qrCodeCustomization.logo_url) {
        const logoDataUrlInput = document.getElementById('qr_logo_data_url');
        const logoRemoveBtn = document.getElementById('qr_logo_remove_btn');
        const logoFilename = document.getElementById('qr_logo_filename');
        
        if (logoDataUrlInput && qrCodeCustomization.logo_url) {
            // Check if it's a data URL or a regular URL
            if (qrCodeCustomization.logo_url.startsWith('data:')) {
                logoDataUrlInput.value = qrCodeCustomization.logo_url;
            } else {
                // Convert image URL to data URL
                fetch(qrCodeCustomization.logo_url)
                    .then(res => res.blob())
                    .then(blob => {
                        const reader = new FileReader();
                        reader.onloadend = () => {
                            logoDataUrlInput.value = reader.result;
                            if (logoRemoveBtn) logoRemoveBtn.style.display = 'inline-flex';
                            if (logoFilename) logoFilename.textContent = 'Logo';
                        };
                        reader.readAsDataURL(blob);
                    })
                    .catch(() => {
                        // If fetch fails, try to use URL directly
                        logoDataUrlInput.value = qrCodeCustomization.logo_url;
                    });
            }
            if (logoRemoveBtn) logoRemoveBtn.style.display = 'inline-flex';
            if (logoFilename) logoFilename.textContent = 'Logo';
        }
    }
    
    // Populate review-us frame options if frame is review-us
    if (qrCodeCustomization.frame === 'review-us' && qrCodeCustomization.review_us_config) {
        const config = qrCodeCustomization.review_us_config;
        
        if (config.color) {
            const colorInput = document.getElementById('review_frame_color');
            const colorHexInput = document.getElementById('review_frame_color_hex');
            if (colorInput) colorInput.value = config.color;
            if (colorHexInput) colorHexInput.value = config.color;
        }
        
        if (config.text_color) {
            const textColorInput = document.getElementById('review_frame_text_color');
            const textColorHexInput = document.getElementById('review_frame_text_color_hex');
            if (textColorInput) textColorInput.value = config.text_color;
            if (textColorHexInput) textColorHexInput.value = config.text_color;
        }
        
        if (config.line1) {
            const line1Input = document.getElementById('review_frame_line1');
            if (line1Input) line1Input.value = config.line1;
        }
        
        if (config.line2) {
            const line2Input = document.getElementById('review_frame_line2');
            if (line2Input) line2Input.value = config.line2;
        }
        
        if (config.line3) {
            const line3Input = document.getElementById('review_frame_line3');
            if (line3Input) line3Input.value = config.line3;
        }
        
        if (config.icon) {
            const iconInput = document.getElementById('review_frame_icon');
            if (iconInput) iconInput.value = config.icon;
            // Update visual selection
            const iconButton = document.querySelector(`[data-icon-value="${config.icon}"]`);
            if (iconButton) {
                selectReviewFrameIcon(iconButton, config.icon, config.line3 || 'here');
            }
        }
        
        if (config.logo_url) {
            const reviewFrameLogoDataUrl = document.getElementById('review_frame_logo_data_url');
            const reviewFrameLogoPreview = document.getElementById('review_frame_logo_preview');
            const reviewFrameLogoPreviewImg = document.getElementById('review_frame_logo_preview_img');
            const reviewFrameLogoRemove = document.getElementById('review_frame_logo_remove');
            
            if (reviewFrameLogoDataUrl && config.logo_url) {
                if (config.logo_url.startsWith('data:')) {
                    reviewFrameLogoDataUrl.value = config.logo_url;
                } else {
                    fetch(config.logo_url)
                        .then(res => res.blob())
                        .then(blob => {
                            const reader = new FileReader();
                            reader.onloadend = () => {
                                reviewFrameLogoDataUrl.value = reader.result;
                                if (reviewFrameLogoPreview) reviewFrameLogoPreview.classList.remove('hidden');
                                if (reviewFrameLogoPreviewImg) reviewFrameLogoPreviewImg.src = reader.result;
                                if (reviewFrameLogoRemove) reviewFrameLogoRemove.classList.remove('hidden');
                            };
                            reader.readAsDataURL(blob);
                        })
                        .catch(() => {
                            reviewFrameLogoDataUrl.value = config.logo_url;
                        });
                }
            }
        }
    }
    
    // Populate Step 1 fields based on QR code type
    // This will be handled by type-specific form includes
    populateStep1Fields(type, qrCodeData, qrCodeFiles);
}

// Populate Step 1 fields based on type
function populateStep1Fields(type, data, files) {
    switch(type) {
        case 'url':
            if (data.url) {
                const urlInput = document.getElementById('url');
                if (urlInput) urlInput.value = data.url;
            }
            break;
            
        case 'email':
            if (data.email) {
                const emailInput = document.getElementById('email');
                if (emailInput) emailInput.value = data.email;
            }
            if (data.subject) {
                const subjectInput = document.getElementById('subject');
                if (subjectInput) subjectInput.value = data.subject;
            }
            if (data.message) {
                const messageInput = document.getElementById('message');
                if (messageInput) messageInput.value = data.message;
            }
            break;
            
        case 'text':
            if (data.text) {
                const textInput = document.getElementById('text');
                if (textInput) textInput.value = data.text;
            }
            break;
            
        case 'event':
            if (data.event_name) {
                const eventNameInput = document.getElementById('event_name');
                if (eventNameInput) eventNameInput.value = data.event_name;
            }
            if (data.company_name) {
                const companyNameInput = document.getElementById('company_name');
                if (companyNameInput) companyNameInput.value = data.company_name;
            }
            if (data.description) {
                const descriptionInput = document.getElementById('description');
                if (descriptionInput) descriptionInput.value = data.description;
            }
            if (data.date) {
                const dateInput = document.getElementById('date');
                if (dateInput) dateInput.value = data.date;
            }
            if (data.time) {
                const timeInput = document.getElementById('time');
                if (timeInput) timeInput.value = data.time;
            }
            if (data.location) {
                const locationInput = document.getElementById('location');
                if (locationInput) locationInput.value = data.location;
            }
            if (data.dress_code_color) {
                const dressCodeInput = document.getElementById('dress_code_color');
                const dressCodeHexInput = document.getElementById('dress_code_color_hex');
                const dressCodeValue = data.dress_code_color.startsWith('#') ? data.dress_code_color : '#000000';
                if (dressCodeInput) {
                    dressCodeInput.value = dressCodeValue;
                }
                if (dressCodeHexInput) {
                    dressCodeHexInput.value = dressCodeValue.toUpperCase();
                }
            }
            if (data.contact) {
                const contactInput = document.getElementById('contact');
                if (contactInput) contactInput.value = data.contact;
            }
            if (data.amenities && Array.isArray(data.amenities)) {
                data.amenities.forEach(amenity => {
                    const checkbox = document.querySelector(`input[name="amenities[]"][value="${amenity}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            // Handle event design colors
            if (data.event_primary_color) {
                const primaryHex = document.getElementById('event_primary_color_hex');
                const primaryPicker = document.getElementById('event_primary_color_picker');
                if (primaryHex) primaryHex.value = data.event_primary_color;
                if (primaryPicker) primaryPicker.value = data.event_primary_color;
            }
            if (data.event_secondary_color) {
                const secondaryHex = document.getElementById('event_secondary_color_hex');
                const secondaryPicker = document.getElementById('event_secondary_color_picker');
                if (secondaryHex) secondaryHex.value = data.event_secondary_color;
                if (secondaryPicker) secondaryPicker.value = data.event_secondary_color;
            }
            if (data.event_font_family) {
                const fontFamilySelect = document.getElementById('event_font_family');
                if (fontFamilySelect) fontFamilySelect.value = data.event_font_family;
            }
            // Handle event image
            const eventImageFile = files.find(f => f.file_type === 'image');
            if (eventImageFile && eventImageFile.file_path) {
                const eventImagePreview = document.getElementById('event-img-preview');
                const eventImagePreviewImg = eventImagePreview?.querySelector('img');
                const eventUploadArea = document.getElementById('event-upload-area');
                if (eventImagePreviewImg && eventImagePreview) {
                    eventImagePreviewImg.src = eventImageFile.file_path;
                    eventImagePreview.classList.remove('hidden');
                    if (eventUploadArea) eventUploadArea.classList.add('hidden');
                }
            }
            break;
            
        case 'location':
            if (data.address) {
                const addressInput = document.getElementById('address');
                if (addressInput) addressInput.value = data.address;
            }
            if (data.latitude) {
                const latInput = document.getElementById('latitude');
                if (latInput) latInput.value = data.latitude;
            }
            if (data.longitude) {
                const lngInput = document.getElementById('longitude');
                if (lngInput) lngInput.value = data.longitude;
            }
            if (data.location_url) {
                const locationUrlInput = document.getElementById('location_url');
                if (locationUrlInput) locationUrlInput.value = data.location_url;
            }
            break;
            
        case 'wifi':
            if (data.ssid) {
                const ssidInput = document.getElementById('ssid');
                if (ssidInput) ssidInput.value = data.ssid;
            }
            if (data.encryption) {
                const encryptionSelect = document.getElementById('encryption');
                if (encryptionSelect) encryptionSelect.value = data.encryption;
            }
            if (data.password) {
                const passwordInput = document.getElementById('password');
                if (passwordInput) passwordInput.value = data.password;
            }
            if (data.hidden !== undefined) {
                const hiddenCheckbox = document.getElementById('hidden');
                if (hiddenCheckbox) hiddenCheckbox.checked = data.hidden;
            }
            break;
            
        case 'phone':
            if (data.full_name) {
                const fullNameInput = document.getElementById('full_name');
                if (fullNameInput) fullNameInput.value = data.full_name;
            }
            if (data.phone_number) {
                const phoneNumberInput = document.getElementById('phone_number');
                if (phoneNumberInput) phoneNumberInput.value = data.phone_number;
            }
            if (data.phone_background_color_hex) {
                const phoneBgColorInput = document.getElementById('phone_background_color_hex');
                if (phoneBgColorInput) phoneBgColorInput.value = data.phone_background_color_hex;
            }
            if (data.phone_font_family) {
                const phoneFontInput = document.getElementById('phone_font_family');
                if (phoneFontInput) phoneFontInput.value = data.phone_font_family;
            }
            break;
            
            
        case 'app':
            if (data.app_name) {
                const appNameInput = document.getElementById('app_name');
                if (appNameInput) appNameInput.value = data.app_name;
            }
            if (data.app_description) {
                const appDescriptionInput = document.getElementById('app_description');
                if (appDescriptionInput) appDescriptionInput.value = data.app_description;
            }
            if (data.app_store_link) {
                const appStoreInput = document.getElementById('app_store_link');
                if (appStoreInput) appStoreInput.value = data.app_store_link;
            }
            if (data.play_store_link) {
                const playStoreInput = document.getElementById('play_store_link');
                if (playStoreInput) playStoreInput.value = data.play_store_link;
            }
            if (data.app_font_family) {
                const appFontInput = document.getElementById('app_font_family');
                if (appFontInput) appFontInput.value = data.app_font_family;
            }
            if (data.app_text_color) {
                const appTextColorInput = document.getElementById('app_text_color');
                if (appTextColorInput) appTextColorInput.value = data.app_text_color;
            }
            if (data.app_text_font_size) {
                const appTextFontSizeInput = document.getElementById('app_text_font_size');
                if (appTextFontSizeInput) appTextFontSizeInput.value = data.app_text_font_size;
            }
            if (data.app_icon_size) {
                const appIconSizeInput = document.getElementById('app_icon_size');
                if (appIconSizeInput) appIconSizeInput.value = data.app_icon_size;
            }
            if (data.app_store_button_color) {
                const appStoreButtonColorInput = document.getElementById('app_store_button_color');
                if (appStoreButtonColorInput) appStoreButtonColorInput.value = data.app_store_button_color;
            }
            if (data.app_store_button_text_color) {
                const appStoreButtonTextColorInput = document.getElementById('app_store_button_text_color');
                if (appStoreButtonTextColorInput) appStoreButtonTextColorInput.value = data.app_store_button_text_color;
            }
            // Handle app image
            const appImageFile = files.find(f => f.file_type === 'image');
            if (appImageFile && appImageFile.file_path) {
                const appImagePreview = document.getElementById('app-img-preview');
                const appImagePreviewImg = appImagePreview?.querySelector('img');
                const appUploadArea = document.getElementById('app-upload-area');
                if (appImagePreviewImg && appImagePreview) {
                    appImagePreviewImg.src = appImageFile.file_path;
                    appImagePreview.classList.remove('hidden');
                    if (appUploadArea) appUploadArea.classList.add('hidden');
                }
            }
            break;
            
        case 'business_card':
            if (data.company_name) {
                const companyNameInput = document.getElementById('business_card_company_name');
                if (companyNameInput) companyNameInput.value = data.company_name;
            }
            if (data.subtitle) {
                const subtitleInput = document.getElementById('business_card_subtitle');
                if (subtitleInput) subtitleInput.value = data.subtitle;
            }
            if (data.about) {
                const aboutInput = document.getElementById('business_card_about');
                if (aboutInput) aboutInput.value = data.about;
            }
            if (data.contact_name) {
                const contactNameInput = document.getElementById('business_card_contact_name');
                if (contactNameInput) contactNameInput.value = data.contact_name;
            }
            if (data.phone) {
                const phoneInput = document.getElementById('business_card_phone');
                if (phoneInput) phoneInput.value = data.phone;
            }
            if (data.email) {
                const emailInput = document.getElementById('business_card_email');
                if (emailInput) emailInput.value = data.email;
            }
            if (data.address) {
                const addressInput = document.getElementById('business_card_address');
                if (addressInput) addressInput.value = data.address;
            }
            if (data.maps_link) {
                const mapsLinkInput = document.getElementById('business_card_maps_link');
                if (mapsLinkInput) mapsLinkInput.value = data.maps_link;
            }
            if (data.working_hours) {
                const workingHoursInput = document.getElementById('business_card_working_hours');
                if (workingHoursInput) workingHoursInput.value = data.working_hours;
            }
            if (data.primary_color) {
                const primaryColorInput = document.getElementById('business_card_primary_color_hex');
                if (primaryColorInput) primaryColorInput.value = data.primary_color;
            }
            if (data.secondary_color) {
                const secondaryColorInput = document.getElementById('business_card_secondary_color_hex');
                if (secondaryColorInput) secondaryColorInput.value = data.secondary_color;
            }
            if (data.font_family) {
                const fontFamilyInput = document.getElementById('business_card_font_family');
                if (fontFamilyInput) fontFamilyInput.value = data.font_family;
            }
            // Handle buttons
            if (data.buttons && Array.isArray(data.buttons)) {
                const buttonsContainer = document.getElementById('business-card-buttons-container');
                if (buttonsContainer) {
                    buttonsContainer.innerHTML = '';
                    data.buttons.forEach((button, index) => {
                        const n = buttonsContainer.querySelectorAll('.business-card-button-row').length;
                        const row = document.createElement('div');
                        row.className = 'business-card-button-row flex gap-2 items-start';
                        row.innerHTML = '<input type="text" name="business_card_buttons[' + n + '][label]" class="input flex-1" placeholder="Label" value="' + (button.label || '').replace(/"/g, '&quot;') + '">' +
                            '<input type="url" name="business_card_buttons[' + n + '][url]" class="input flex-1" placeholder="https://..." value="' + (button.url || '').replace(/"/g, '&quot;') + '">' +
                            '<button type="button" class="btn btn-secondary btn-xs remove-business-card-button" aria-label="Remove">✕</button>';
                        buttonsContainer.appendChild(row);
                        row.querySelector('.remove-business-card-button').addEventListener('click', function() { 
                            row.remove(); 
                            if (typeof updateStep1Preview === 'function') updateStep1Preview(); 
                        });
                    });
                    // Show remove buttons if more than one row
                    const removeButtons = buttonsContainer.querySelectorAll('.remove-business-card-button');
                    removeButtons.forEach(btn => btn.classList.toggle('hidden', removeButtons.length <= 1));
                }
            }
            // Handle socials
            if (data.socials && Array.isArray(data.socials)) {
                const socialsContainer = document.getElementById('business-card-socials-container');
                if (socialsContainer) {
                    socialsContainer.innerHTML = '';
                    data.socials.forEach((social, index) => {
                        const n = socialsContainer.querySelectorAll('.business-card-social-row').length;
                        const row = document.createElement('div');
                        row.className = 'business-card-social-row flex gap-2 items-center';
                        row.innerHTML = '<select name="business_card_socials[' + n + '][platform]" class="input flex-1 max-w-[140px] h-10">' +
                            '<option value="facebook"' + (social.platform === 'facebook' ? ' selected' : '') + '>Facebook</option>' +
                            '<option value="instagram"' + (social.platform === 'instagram' ? ' selected' : '') + '>Instagram</option>' +
                            '<option value="twitter"' + (social.platform === 'twitter' ? ' selected' : '') + '>Twitter/X</option>' +
                            '<option value="linkedin"' + (social.platform === 'linkedin' ? ' selected' : '') + '>LinkedIn</option>' +
                            '<option value="whatsapp"' + (social.platform === 'whatsapp' ? ' selected' : '') + '>WhatsApp</option>' +
                            '</select>' +
                            '<input type="url" name="business_card_socials[' + n + '][url]" class="input flex-1 h-10" placeholder="https://..." value="' + (social.url || '').replace(/"/g, '&quot;') + '">' +
                            '<button type="button" class="btn btn-secondary btn-xs remove-business-card-social" aria-label="Remove">✕</button>';
                        socialsContainer.appendChild(row);
                        row.querySelector('.remove-business-card-social').addEventListener('click', function() { 
                            row.remove(); 
                            if (typeof updateStep1Preview === 'function') updateStep1Preview(); 
                        });
                    });
                    // Show remove buttons if more than one row
                    const removeButtons = socialsContainer.querySelectorAll('.remove-business-card-social');
                    removeButtons.forEach(btn => btn.classList.toggle('hidden', removeButtons.length <= 1));
                }
            }
            // Handle logo
            const businessCardLogoFile = files.find(f => f.file_type === 'business_card_logo');
            if (businessCardLogoFile && businessCardLogoFile.file_path) {
                const businessCardLogoPreview = document.getElementById('business-card-logo-preview-img');
                const businessCardLogoPlaceholder = document.getElementById('business-card-logo-placeholder');
                if (businessCardLogoPreview) {
                    businessCardLogoPreview.src = businessCardLogoFile.file_path;
                    businessCardLogoPreview.classList.remove('hidden');
                    if (businessCardLogoPlaceholder) businessCardLogoPlaceholder.classList.add('hidden');
                }
            }
            break;
            
        case 'personal_vcard':
            if (data.name) {
                const nameInput = document.getElementById('personal_vcard_name');
                if (nameInput) nameInput.value = data.name;
            }
            if (data.title) {
                const titleInput = document.getElementById('personal_vcard_title');
                if (titleInput) titleInput.value = data.title;
            }
            if (data.about) {
                const aboutInput = document.getElementById('personal_vcard_about');
                if (aboutInput) aboutInput.value = data.about;
            }
            if (data.phone) {
                const phoneInput = document.getElementById('personal_vcard_phone');
                if (phoneInput) phoneInput.value = data.phone;
            }
            if (data.email) {
                const emailInput = document.getElementById('personal_vcard_email');
                if (emailInput) emailInput.value = data.email;
            }
            if (data.address) {
                const addressInput = document.getElementById('personal_vcard_address');
                if (addressInput) addressInput.value = data.address;
            }
            if (data.maps_link) {
                const mapsLinkInput = document.getElementById('personal_vcard_maps_link');
                if (mapsLinkInput) mapsLinkInput.value = data.maps_link;
            }
            if (data.primary_color) {
                const primaryColorInput = document.getElementById('personal_vcard_primary_color_hex');
                if (primaryColorInput) primaryColorInput.value = data.primary_color;
            }
            if (data.secondary_color) {
                const secondaryColorInput = document.getElementById('personal_vcard_secondary_color_hex');
                if (secondaryColorInput) secondaryColorInput.value = data.secondary_color;
            }
            if (data.font_family) {
                const fontFamilyInput = document.getElementById('personal_vcard_font_family');
                if (fontFamilyInput) fontFamilyInput.value = data.font_family;
            }
            // Handle socials
            if (data.socials && Array.isArray(data.socials)) {
                const socialsContainer = document.getElementById('personal-vcard-socials-container');
                if (socialsContainer) {
                    socialsContainer.innerHTML = '';
                    data.socials.forEach((social, index) => {
                        const n = socialsContainer.querySelectorAll('.personal-vcard-social-row').length;
                        const row = document.createElement('div');
                        row.className = 'personal-vcard-social-row flex gap-2 items-center';
                        row.innerHTML = '<select name="personal_vcard_socials[' + n + '][platform]" class="input flex-1 max-w-[140px] h-10">' +
                            '<option value="facebook"' + (social.platform === 'facebook' ? ' selected' : '') + '>Facebook</option>' +
                            '<option value="instagram"' + (social.platform === 'instagram' ? ' selected' : '') + '>Instagram</option>' +
                            '<option value="twitter"' + (social.platform === 'twitter' ? ' selected' : '') + '>Twitter/X</option>' +
                            '<option value="linkedin"' + (social.platform === 'linkedin' ? ' selected' : '') + '>LinkedIn</option>' +
                            '<option value="whatsapp"' + (social.platform === 'whatsapp' ? ' selected' : '') + '>WhatsApp</option>' +
                            '</select>' +
                            '<input type="url" name="personal_vcard_socials[' + n + '][url]" class="input flex-1 h-10" placeholder="https://..." value="' + (social.url || '').replace(/"/g, '&quot;') + '">' +
                            '<button type="button" class="btn btn-secondary btn-xs remove-personal-vcard-social" aria-label="Remove">✕</button>';
                        socialsContainer.appendChild(row);
                        row.querySelector('.remove-personal-vcard-social').addEventListener('click', function() { 
                            row.remove(); 
                            if (typeof updateStep1Preview === 'function') updateStep1Preview(); 
                        });
                    });
                    // Show remove buttons if more than one row
                    const removeButtons = socialsContainer.querySelectorAll('.remove-personal-vcard-social');
                    removeButtons.forEach(btn => btn.classList.toggle('hidden', removeButtons.length <= 1));
                }
            }
            // Handle profile image
            const personalVCardProfileFile = files.find(f => f.file_type === 'personal_vcard_profile');
            if (personalVCardProfileFile && personalVCardProfileFile.file_path) {
                const personalVCardProfilePreview = document.getElementById('personal-vcard-profile-preview-img');
                const personalVCardProfilePlaceholder = document.getElementById('personal-vcard-profile-placeholder');
                if (personalVCardProfilePreview) {
                    personalVCardProfilePreview.src = personalVCardProfileFile.file_path;
                    personalVCardProfilePreview.classList.remove('hidden');
                    if (personalVCardProfilePlaceholder) personalVCardProfilePlaceholder.classList.add('hidden');
                }
            }
            break;
            
        case 'coupon':
            if (data.coupon_company) {
                const companyInput = document.getElementById('coupon_company');
                if (companyInput) companyInput.value = data.coupon_company;
            }
            if (data.coupon_title) {
                const titleInput = document.getElementById('coupon_title');
                if (titleInput) titleInput.value = data.coupon_title;
            }
            if (data.coupon_description) {
                const descriptionInput = document.getElementById('coupon_description');
                if (descriptionInput) descriptionInput.value = data.coupon_description;
            }
            if (data.coupon_sales_badge) {
                const salesBadgeInput = document.getElementById('coupon_sales_badge');
                if (salesBadgeInput) salesBadgeInput.value = data.coupon_sales_badge;
            }
            if (data.coupon_sales_badge_color) {
                const salesBadgeColorInput = document.getElementById('coupon_sales_badge_color');
                if (salesBadgeColorInput) salesBadgeColorInput.value = data.coupon_sales_badge_color;
            }
            if (data.coupon_sales_badge_text_color) {
                const salesBadgeTextColorInput = document.getElementById('coupon_sales_badge_text_color');
                if (salesBadgeTextColorInput) salesBadgeTextColorInput.value = data.coupon_sales_badge_text_color;
            }
            if (data.coupon_code_button_text) {
                const codeButtonTextInput = document.getElementById('coupon_code_button_text');
                if (codeButtonTextInput) codeButtonTextInput.value = data.coupon_code_button_text;
            }
            if (data.coupon_button_color) {
                const buttonColorInput = document.getElementById('coupon_button_color');
                if (buttonColorInput) buttonColorInput.value = data.coupon_button_color;
            }
            if (data.coupon_button_text_color) {
                const buttonTextColorInput = document.getElementById('coupon_button_text_color');
                if (buttonTextColorInput) buttonTextColorInput.value = data.coupon_button_text_color;
            }
            if (data.coupon_valid_until) {
                const validUntilInput = document.getElementById('coupon_valid_until');
                if (validUntilInput) validUntilInput.value = data.coupon_valid_until;
            }
            if (data.coupon_view_more_text) {
                const viewMoreTextInput = document.getElementById('coupon_view_more_text');
                if (viewMoreTextInput) viewMoreTextInput.value = data.coupon_view_more_text;
            }
            if (data.coupon_view_more_website) {
                const viewMoreWebsiteInput = document.getElementById('coupon_view_more_website');
                if (viewMoreWebsiteInput) viewMoreWebsiteInput.value = data.coupon_view_more_website;
            }
            if (data.coupon_font_family) {
                const fontFamilyInput = document.getElementById('coupon_font_family');
                if (fontFamilyInput) fontFamilyInput.value = data.coupon_font_family;
            }
            if (data.coupon_primary_color) {
                const primaryColorInput = document.getElementById('coupon_primary_color_hex');
                if (primaryColorInput) primaryColorInput.value = data.coupon_primary_color;
            }
            if (data.coupon_secondary_color) {
                const secondaryColorInput = document.getElementById('coupon_secondary_color_hex');
                if (secondaryColorInput) secondaryColorInput.value = data.coupon_secondary_color;
            }
            if (data.coupon_use_barcode !== undefined) {
                const useBarcodeCheckbox = document.getElementById('coupon_use_barcode');
                if (useBarcodeCheckbox) useBarcodeCheckbox.checked = data.coupon_use_barcode;
            }
            // Handle coupon image
            const couponImageFile = files.find(f => f.file_type === 'image');
            if (couponImageFile && couponImageFile.file_path) {
                const couponImagePreview = document.getElementById('logo-img-preview');
                const couponImagePreviewImg = couponImagePreview?.querySelector('img');
                const couponUploadArea = document.getElementById('logo-upload-area');
                if (couponImagePreviewImg && couponImagePreview) {
                    couponImagePreviewImg.src = couponImageFile.file_path;
                    couponImagePreview.classList.remove('hidden');
                    if (couponUploadArea) couponUploadArea.classList.add('hidden');
                }
            }
            // Handle coupon logo
            const couponLogoFile = files.find(f => f.file_type === 'logo');
            if (couponLogoFile && couponLogoFile.file_path) {
                // Similar handling for logo
            }
            // Handle coupon barcode
            const couponBarcodeFile = files.find(f => f.file_type === 'barcode');
            if (couponBarcodeFile && couponBarcodeFile.file_path) {
                // Similar handling for barcode
            }
            break;
            
        case 'pdf':
            if (data.pdf_title) {
                const pdfTitleInput = document.getElementById('pdf_title');
                if (pdfTitleInput) pdfTitleInput.value = data.pdf_title;
            }
            if (data.pdf_website) {
                const pdfWebsiteInput = document.getElementById('pdf_website');
                if (pdfWebsiteInput) pdfWebsiteInput.value = data.pdf_website;
            }
            if (data.company_name) {
                const companyNameInput = document.getElementById('company_name');
                if (companyNameInput) companyNameInput.value = data.company_name;
            }
            if (data.file_description) {
                const fileDescriptionInput = document.getElementById('file_description');
                if (fileDescriptionInput) fileDescriptionInput.value = data.file_description;
            }
            if (data.pdf_primary_color) {
                const pdfPrimaryColorInput = document.getElementById('pdf_primary_color_hex');
                if (pdfPrimaryColorInput) pdfPrimaryColorInput.value = data.pdf_primary_color;
            }
            if (data.pdf_secondary_color) {
                const pdfSecondaryColorInput = document.getElementById('pdf_secondary_color_hex');
                if (pdfSecondaryColorInput) pdfSecondaryColorInput.value = data.pdf_secondary_color;
            }
            if (data.pdf_button_text) {
                const pdfButtonTextInput = document.getElementById('pdf_button_text');
                if (pdfButtonTextInput) pdfButtonTextInput.value = data.pdf_button_text;
            }
            if (data.pdf_button_color) {
                const pdfButtonColorInput = document.getElementById('pdf_button_color');
                if (pdfButtonColorInput) pdfButtonColorInput.value = data.pdf_button_color;
            }
            if (data.pdf_font_family) {
                const pdfFontFamilyInput = document.getElementById('pdf_font_family');
                if (pdfFontFamilyInput) pdfFontFamilyInput.value = data.pdf_font_family;
            }
            break;
            
        case 'menu':
            // Menu is complex - handle menu_sections if present
            if (data.menu_sections && Array.isArray(data.menu_sections)) {
                // This would require more complex handling
                // For now, just log that sections exist
                console.log('Menu sections found:', data.menu_sections.length);
            }
            if (data.menu_url) {
                const menuUrlInput = document.getElementById('menu_url');
                if (menuUrlInput) menuUrlInput.value = data.menu_url;
            }
            // Handle menu file
            const menuFile = files.find(f => f.file_type === 'menu');
            if (menuFile && menuFile.file_path) {
                // Handle menu file display
            }
            // Handle restaurant image
            const restaurantImageFile = files.find(f => f.file_type === 'restaurant_image');
            if (restaurantImageFile && restaurantImageFile.file_path) {
                // Handle restaurant image display
            }
            break;
    }
}

// Setup real-time validation and preview updates when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupRealTimeValidation();
    
    // Populate form fields if editing existing QR code
    if (qrCodeId) {
        populateFormFields();
        
        // Update Step 2 visual selections after populating
        setTimeout(() => {
            // Update pattern visual selection
            const patternValue = document.getElementById('selected_pattern')?.value;
            if (patternValue) {
                const patternButton = document.querySelector(`[data-pattern="${patternValue}"]`);
                if (patternButton) {
                    patternButton.classList.add('border-primary-500', 'border-primary-600');
                    patternButton.classList.remove('border-dark-200');
                }
            }
            
            // Update corner visual selection
            const cornerValue = document.getElementById('selected_corner')?.value;
            if (cornerValue) {
                const cornerButton = document.querySelector(`[data-corner="${cornerValue}"]`);
                if (cornerButton) {
                    cornerButton.classList.add('border-primary-500', 'border-primary-600');
                    cornerButton.classList.remove('border-dark-200');
                }
            }
            
            // Update corner dot visual selection
            const cornerDotValue = document.getElementById('selected_corner_dot')?.value;
            if (cornerDotValue) {
                const cornerDotButton = document.querySelector(`[data-corner-dot="${cornerDotValue}"]`);
                if (cornerDotButton) {
                    cornerDotButton.classList.add('border-primary-500', 'border-primary-600');
                    cornerDotButton.classList.remove('border-dark-200');
                }
            }
            
            // Update frame visual selection and show review-us options if needed
            const frameValue = document.getElementById('selected_frame')?.value;
            if (frameValue) {
                const frameButton = document.querySelector(`[data-frame="${frameValue}"]`);
                if (frameButton) {
                    frameButton.classList.add('border-primary-500', 'border-primary-600');
                    frameButton.classList.remove('border-dark-200');
                }
                
                // Show review-us frame options if frame is review-us
                if (frameValue === 'review-us') {
                    const reviewUsOpts = document.getElementById('review-us-frame-options');
                    if (reviewUsOpts) {
                        reviewUsOpts.classList.remove('hidden');
                    }
                }
            }
        }, 100);
    }
    
    // Setup phone mockup overlay for Step 1
    const phoneMockupContainerStep1 = document.getElementById('phone-mockup-container-step1');
    if (phoneMockupContainerStep1) {
        const updateStep1OverlaySize = () => {
            const overlay = document.getElementById('phone-mockup-overlay-step1');
            if (!overlay || !phoneMockupContainerStep1) return;
            
            const containerRect = phoneMockupContainerStep1.getBoundingClientRect();
            
            // Overlay should cover the entire container with 2px margin on all sides for border-radius visibility
            const margin = 2; // 2px margin on all sides
            overlay.style.width = (containerRect.width - (margin * 2)) + 'px';
            overlay.style.height = (containerRect.height - (margin * 2)) + 'px';
            overlay.style.left = margin + 'px';
            overlay.style.top = margin + 'px';
        };
        
        // Update immediately and on resize
        updateStep1OverlaySize();
        window.addEventListener('resize', updateStep1OverlaySize);
        
        // Also update when image loads (in case container size changes)
        const phoneMockupStep1 = document.getElementById('phone-mockup-step1');
        if (phoneMockupStep1) {
            if (phoneMockupStep1.complete) {
                updateStep1OverlaySize();
            } else {
                phoneMockupStep1.addEventListener('load', updateStep1OverlaySize);
            }
        }
    }
    
    // Setup phone mockup overlay for Step 2
    const phoneMockupStep2 = document.getElementById('phone-mockup-step2');
    if (phoneMockupStep2) {
        const updateStep2OverlaySize = () => {
            const overlay = document.getElementById('phone-mockup-overlay-step2');
            if (!overlay || !phoneMockupStep2) return;
            
            const img = phoneMockupStep2;
            const imgRect = img.getBoundingClientRect();
            const parentRect = img.parentElement.getBoundingClientRect();
            
            // Calculate position relative to parent container (where image is positioned)
            const imgLeft = imgRect.left - parentRect.left;
            const imgTop = imgRect.top - parentRect.top;
            
            // Use actual displayed image dimensions
            const displayWidth = imgRect.width;
            const displayHeight = imgRect.height;
            
            // Overlay should have 2px margin on all sides for border-radius visibility
            const margin = 2; // 2px margin on all sides
            overlay.style.width = (displayWidth - (margin * 2)) + 'px';
            overlay.style.height = (displayHeight - (margin * 2)) + 'px';
            overlay.style.left = (imgLeft + margin) + 'px';
            overlay.style.top = (imgTop + margin) + 'px';
        };
        
        if (phoneMockupStep2.complete) {
            updateStep2OverlaySize();
        } else {
            phoneMockupStep2.addEventListener('load', updateStep2OverlaySize);
        }
        
        window.addEventListener('resize', updateStep2OverlaySize);
        
        // Also update when QR code is loaded
        const observer = new MutationObserver(() => {
            updateStep2OverlaySize();
        });
        const qrContainer = document.getElementById('phone-mockup-qr-step2');
        if (qrContainer) {
            observer.observe(qrContainer, { childList: true, subtree: true });
        }
    }
    
    // Add event listeners for Step 1 fields to update preview
    const step1Fields = [
        'name', 'url', 'email', 'subject', 'message', 'text', 'full_name', 'phone_number', 'phone_background_color_hex', 'phone_font_family', 
        'app_name', 'website_url', 'app_store_link', 'play_store_link', 'app_description',
        'app_primary_color_hex', 'app_secondary_color_hex', 'app_button_text', 'app_button_color_hex', 'app_font_family', 'app_text_color_hex',
        'app_text_font_size', 'app_icon_size',
        'app_store_button_color_hex', 'app_store_button_text_color_hex',
        'ssid', 'encryption', 'password', 'address',
        'event_name', 'company_name', 'date', 'time', 'location', 'description', 'contact', 'dress_code_color', 'dress_code_color_hex',
        'event_primary_color', 'event_primary_color_hex', 'event_secondary_color', 'event_secondary_color_hex', 'event_font_family',
        'pdf_primary_color_hex', 'pdf_secondary_color_hex', 'pdf_title', 'pdf_website', 
        'company_name', 'file_description', 'pdf_button_text', 'pdf_button_color_hex', 'pdf_font_family',
        'menu_primary_color_hex', 'menu_secondary_color_hex', 'menu_font_family',
        'restaurant_name', 'restaurant_description',
        'text_background_color_hex', 'text_text_color_hex', 'text_font_family',
        'coupon_primary_color_hex', 'coupon_secondary_color_hex', 'coupon_button_color_hex', 'coupon_button_text_color_hex', 'coupon_font_family',
        'coupon_company', 'coupon_title', 'coupon_description', 'coupon_sales_badge', 'coupon_sales_badge_color_hex', 'coupon_sales_badge_text_color_hex', 'coupon_code_button_text',
        'coupon_valid_until', 'coupon_view_more_website',
        'business_card_company_name', 'business_card_subtitle', 'business_card_about',
        'business_card_primary_color_hex', 'business_card_secondary_color_hex', 'business_card_font_family',
        'business_card_contact_name', 'business_card_phone', 'business_card_email',
        'business_card_address', 'business_card_maps_link', 'business_card_working_hours',
        'personal_vcard_name', 'personal_vcard_title', 'personal_vcard_about',
        'personal_vcard_primary_color_hex', 'personal_vcard_secondary_color_hex', 'personal_vcard_font_family',
        'personal_vcard_phone', 'personal_vcard_email', 'personal_vcard_address', 'personal_vcard_maps_link'
    ];
    
    step1Fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', () => {
                if (currentStep === 1) {
                    updateStep1Preview();
                }
            });
            // Also listen for change events (for select elements)
            field.addEventListener('change', () => {
                if (currentStep === 1) {
                    updateStep1Preview();
                }
            });
        }
    });
    // Business card: dynamic button/social rows
    ['business-card-buttons-container', 'business-card-socials-container', 'personal-vcard-socials-container'].forEach(containerId => {
        const container = document.getElementById(containerId);
        if (container) {
            container.addEventListener('input', () => { if (currentStep === 1) updateStep1Preview(); });
            container.addEventListener('change', () => { if (currentStep === 1) updateStep1Preview(); });
        }
    });
    
    // Event amenities checkboxes - use event delegation for dynamic checkboxes
    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'amenities[]' && currentStep === 1) {
            updateStep1Preview();
        }
    });
    
    // Logo upload handling for Step 2
    const logoInput = document.getElementById('qr_logo');
    const logoHidden = document.getElementById('qr_logo_data_url');
    const logoFilename = document.getElementById('qr_logo_filename');
    const logoRemoveBtn = document.getElementById('qr_logo_remove_btn');
    const logoLimitWarning = document.getElementById('logo-limit-warning');
    let canAddLogo = true; // Will be checked on page load

    // Check logo limit on page load
    async function checkLogoLimit() {
        try {
            const response = await fetch('{{ route("qr-codes.check-logo-limit") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const data = await response.json();
            canAddLogo = data.can_add_logo || false;
            
            if (!canAddLogo && logoLimitWarning) {
                logoLimitWarning.classList.remove('hidden');
            } else if (logoLimitWarning) {
                logoLimitWarning.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error checking logo limit:', error);
            // On error, allow logo upload (fail open)
            canAddLogo = true;
        }
    }

    // Check logo limit when page loads
    checkLogoLimit();

    if (logoInput && logoHidden) {
        logoInput.addEventListener('change', async () => {
            const file = logoInput.files && logoInput.files[0] ? logoInput.files[0] : null;
            if (!file) {
                logoHidden.value = '';
                if (logoFilename) logoFilename.textContent = '';
                if (logoRemoveBtn) logoRemoveBtn.style.display = 'none';
                updateStep2QRPreview();
                return;
            }

            // Check logo limit before processing
            if (!canAddLogo) {
                // Reset input
                logoInput.value = '';
                // Show warning
                if (logoLimitWarning) {
                    logoLimitWarning.classList.remove('hidden');
                }
                // Show error message
                alert('You have already created a QR code with a custom logo. Free plan allows only one QR code with a custom logo.');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                logoHidden.value = e.target.result;
                if (logoFilename) logoFilename.textContent = file.name;
                if (logoRemoveBtn) logoRemoveBtn.style.display = 'inline-flex';
                if (logoLimitWarning) logoLimitWarning.classList.add('hidden');
                updateStep2QRPreview();
            };
            reader.readAsDataURL(file);
        });
    }

    if (logoRemoveBtn && logoInput && logoHidden) {
        logoRemoveBtn.addEventListener('click', () => {
            logoInput.value = '';
            logoHidden.value = '';
            if (logoFilename) logoFilename.textContent = '';
            logoRemoveBtn.style.display = 'none';
            updateStep2QRPreview();
        });
    }

    // Review-us frame: text fields update preview
    ['review_frame_line1', 'review_frame_line2', 'review_frame_line3', 'review_frame_color', 'review_frame_color_hex', 'review_frame_text_color', 'review_frame_text_color_hex'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => { if (currentStep === 2) updateStep2QRPreview(); });
            el.addEventListener('change', () => { if (currentStep === 2) updateStep2QRPreview(); });
        }
    });

    // Review-us frame: sync color picker and hex input
    const reviewFrameColor = document.getElementById('review_frame_color');
    const reviewFrameColorHex = document.getElementById('review_frame_color_hex');
    if (reviewFrameColor && reviewFrameColorHex) {
        reviewFrameColor.addEventListener('input', function() {
            reviewFrameColorHex.value = this.value;
        });
        reviewFrameColor.addEventListener('change', function() {
            reviewFrameColorHex.value = this.value;
        });
        reviewFrameColorHex.addEventListener('input', function() {
            var hex = this.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(hex) || /^[0-9A-Fa-f]{6}$/.test(hex)) {
                reviewFrameColor.value = hex.startsWith('#') ? hex : '#' + hex;
            }
        });
        reviewFrameColorHex.addEventListener('change', function() {
            var hex = normalizeHexColor(this.value);
            this.value = hex;
            reviewFrameColor.value = hex;
        });
    }
    const reviewFrameTextColor = document.getElementById('review_frame_text_color');
    const reviewFrameTextColorHex = document.getElementById('review_frame_text_color_hex');
    if (reviewFrameTextColor && reviewFrameTextColorHex) {
        reviewFrameTextColor.addEventListener('input', function() {
            reviewFrameTextColorHex.value = this.value;
        });
        reviewFrameTextColor.addEventListener('change', function() {
            reviewFrameTextColorHex.value = this.value;
        });
        reviewFrameTextColorHex.addEventListener('input', function() {
            var hex = this.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(hex) || /^[0-9A-Fa-f]{6}$/.test(hex)) {
                reviewFrameTextColor.value = hex.startsWith('#') ? hex : '#' + hex;
            }
        });
        reviewFrameTextColorHex.addEventListener('change', function() {
            var hex = this.value.trim() ? normalizeHexColor(this.value) : '#000000';
            this.value = hex;
            reviewFrameTextColor.value = hex;
        });
    }

    // Review-us frame: custom icon upload
    const reviewFrameLogoInput = document.getElementById('review_frame_logo');
    const reviewFrameLogoDataUrl = document.getElementById('review_frame_logo_data_url');
    const reviewFrameLogoFilename = document.getElementById('review_frame_logo_filename');
    const reviewFrameLogoRemove = document.getElementById('review_frame_logo_remove');
    const reviewFrameLogoPreview = document.getElementById('review_frame_logo_preview');
    const reviewFrameLogoPreviewImg = document.getElementById('review_frame_logo_preview_img');
    if (reviewFrameLogoInput && reviewFrameLogoDataUrl) {
        reviewFrameLogoInput.addEventListener('change', function() {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                reviewFrameLogoDataUrl.value = '';
                if (reviewFrameLogoFilename) reviewFrameLogoFilename.textContent = '';
                if (reviewFrameLogoRemove) reviewFrameLogoRemove.classList.add('hidden');
                if (reviewFrameLogoPreview) reviewFrameLogoPreview.classList.add('hidden');
                if (currentStep === 2) updateStep2QRPreview();
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                reviewFrameLogoDataUrl.value = e.target.result;
                if (reviewFrameLogoFilename) reviewFrameLogoFilename.textContent = file.name;
                if (reviewFrameLogoRemove) reviewFrameLogoRemove.classList.remove('hidden');
                if (reviewFrameLogoPreview && reviewFrameLogoPreviewImg) {
                    reviewFrameLogoPreviewImg.src = e.target.result;
                    reviewFrameLogoPreview.classList.remove('hidden');
                }
                var selectedIconImg = document.getElementById('review_frame_selected_icon_img');
                if (selectedIconImg) selectedIconImg.src = e.target.result;
                if (currentStep === 2) updateStep2QRPreview();
            };
            reader.readAsDataURL(file);
        });
    }
    if (reviewFrameLogoRemove && reviewFrameLogoInput) {
        reviewFrameLogoRemove.addEventListener('click', clearReviewFrameLogo);
    }

    // Initial preview update
    setTimeout(() => {
        updateStep1Preview();
    }, 100);
});

// Color presets for Step 2
document.querySelectorAll('.color-preset').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const primary = btn.dataset.primary;
        const secondary = btn.dataset.secondary;
        const primaryColorInput = document.getElementById('primary_color');
        const primaryColorHex = document.getElementById('primary_color_hex');
        const secondaryColorInput = document.getElementById('secondary_color');
        const secondaryColorHex = document.getElementById('secondary_color_hex');
        
        if (primaryColorInput) primaryColorInput.value = primary;
        if (primaryColorHex) primaryColorHex.value = primary;
        if (secondaryColorInput) secondaryColorInput.value = secondary;
        if (secondaryColorHex) secondaryColorHex.value = secondary;
        
        updateStep2QRPreview();
    });
});

// Validation helper functions
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidUrl(url) {
    try {
        const urlObj = new URL(url);
        return urlObj.protocol === 'https:';
    } catch (e) {
        return false;
    }
}

function validateWebsite(input) {
    if (typeof input === 'string') {
        // If input is a string, treat it as URL value
        const website = input.trim();
        if (website === '') {
            return true;
        }
        // Check if URL starts with https://
        if (!website.startsWith('https://')) {
            return false;
        }
        try {
            const url = new URL(website);
            return url.protocol === 'https:';
        } catch (e) {
            return false;
        }
    } else {
        // If input is an element, validate and update UI
        const website = input.value.trim();
        const errorDiv = document.getElementById('pdf_website_error');
        
        if (website === '') {
            input.classList.remove('border-red-500');
            if (errorDiv) errorDiv.classList.add('hidden');
            return true;
        }
        
        // Check if URL starts with https://
        if (!website.startsWith('https://')) {
            input.classList.add('border-red-500');
            if (errorDiv) {
                errorDiv.classList.remove('hidden');
                errorDiv.textContent = 'Website URL must start with https://';
            }
            return false;
        }
        
        try {
            const url = new URL(website);
            if (url.protocol === 'https:') {
                input.classList.remove('border-red-500');
                if (errorDiv) errorDiv.classList.add('hidden');
                return true;
            }
        } catch (e) {
            // Invalid URL
        }
        
        input.classList.add('border-red-500');
        if (errorDiv) {
            errorDiv.classList.remove('hidden');
            errorDiv.textContent = 'You have entered an invalid link. Please try again.';
        }
        return false;
    }
}

function validateCouponWebsite(input) {
    if (typeof input === 'string') {
        const website = input.trim();
        if (website === '') return true;
        if (!website.startsWith('https://')) return false;
        try {
            const url = new URL(website);
            return url.protocol === 'https:';
        } catch (e) {
            return false;
        }
    }
    const website = input.value.trim();
    const errorDiv = document.getElementById('coupon_view_more_website_error');
    if (website === '') {
        input.classList.remove('border-red-500');
        if (errorDiv) errorDiv.classList.add('hidden');
        return true;
    }
    if (!website.startsWith('https://')) {
        input.classList.add('border-red-500');
        if (errorDiv) {
            errorDiv.classList.remove('hidden');
            errorDiv.textContent = 'Website URL must start with https://';
        }
        return false;
    }
    try {
        const url = new URL(website);
        if (url.protocol === 'https:') {
            input.classList.remove('border-red-500');
            if (errorDiv) errorDiv.classList.add('hidden');
            return true;
        }
    } catch (e) {}
    input.classList.add('border-red-500');
    if (errorDiv) {
        errorDiv.classList.remove('hidden');
        errorDiv.textContent = 'You have entered an invalid link. Please try again.';
    }
    return false;
}

function isValidAppStoreLink(url) {
    return url.startsWith('https://apps.apple.com/');
}

function isValidPlayStoreLink(url) {
    return url.startsWith('https://play.google.com/store/apps/');
}

function validateStep1() {
    const type = document.querySelector('input[name="type"]').value;
    const errors = [];
    
    // Name is always required
    const name = document.getElementById('name');
    if (!name.value.trim()) {
        errors.push('QR Code Name is required');
        name.classList.add('border-red-500');
    } else {
        name.classList.remove('border-red-500');
    }
    
    // Type-specific required fields
    switch(type) {
        case 'url':
            const url = document.getElementById('url');
            if (!url || !url.value.trim()) {
                errors.push('Website URL is required');
                if (url) url.classList.add('border-red-500');
            } else if (!isValidUrl(url.value.trim())) {
                errors.push('Website URL must be a valid URL starting with https://');
                if (url) url.classList.add('border-red-500');
            } else if (url) {
                url.classList.remove('border-red-500');
            }
            break;
            
        case 'email':
            const email = document.getElementById('email');
            const message = document.getElementById('message');
            
            if (!email || !email.value.trim()) {
                errors.push('Email address is required');
                if (email) email.classList.add('border-red-500');
            } else if (!isValidEmail(email.value.trim())) {
                errors.push('Please enter a valid email address');
                if (email) email.classList.add('border-red-500');
            } else if (email) {
                email.classList.remove('border-red-500');
            }
            
            if (!message || !message.value.trim()) {
                errors.push('Message is required');
                if (message) message.classList.add('border-red-500');
            } else if (message) {
                message.classList.remove('border-red-500');
            }
            break;
            
        case 'text':
            const text = document.getElementById('text');
            if (!text || !text.value.trim()) {
                errors.push('Text content is required');
                if (text) text.classList.add('border-red-500');
            } else if (text) {
                text.classList.remove('border-red-500');
            }
            break;
            
        case 'pdf':
            const pdfFile = document.getElementById('pdf_file');
            if (!pdfFile || !pdfFile.files || pdfFile.files.length === 0) {
                errors.push('PDF file is required');
                if (pdfFile) pdfFile.closest('.border-dashed')?.classList.add('border-red-500');
            } else if (pdfFile) {
                pdfFile.closest('.border-dashed')?.classList.remove('border-red-500');
            }
            
            // Validate website if provided
            const pdfWebsite = document.getElementById('pdf_website');
            if (pdfWebsite && pdfWebsite.value.trim()) {
                const websiteValue = pdfWebsite.value.trim();
                if (!validateWebsite(websiteValue)) {
                    if (!websiteValue.startsWith('https://')) {
                        errors.push('Website URL must start with https://');
                    } else {
                        errors.push('You have entered an invalid link. Please try again.');
                    }
                    pdfWebsite.classList.add('border-red-500');
                    const errorDiv = document.getElementById('pdf_website_error');
                    if (errorDiv) {
                        errorDiv.classList.remove('hidden');
                        errorDiv.textContent = !websiteValue.startsWith('https://') 
                            ? 'Website URL must start with https://' 
                            : 'You have entered an invalid link. Please try again.';
                    }
                } else {
                    pdfWebsite.classList.remove('border-red-500');
                    const errorDiv = document.getElementById('pdf_website_error');
                    if (errorDiv) errorDiv.classList.add('hidden');
                }
            }
            break;
            
        case 'menu': {
            const menuFile = document.getElementById('menu_file');
            const menuUrl = document.getElementById('menu_url');
            const menuSectionsContainer = document.getElementById('menu-sections-container');
            const hasMenuFile = menuFile && menuFile.files && menuFile.files.length > 0;
            const hasMenuUrl = menuUrl && menuUrl.value.trim();
            const hasMenuSections = menuSectionsContainer && menuSectionsContainer.querySelectorAll('.menu-section-block').length > 0;
            const hasAny = hasMenuSections || hasMenuFile || hasMenuUrl;
            if (!hasAny) {
                errors.push('Please add at least one menu section, or upload a PDF, or enter a menu URL.');
                if (menuFile) menuFile.closest('.border-dashed')?.classList.add('border-red-500');
                if (menuUrl) menuUrl.classList.add('border-red-500');
                if (menuSectionsContainer) menuSectionsContainer.classList.add('border-red-500');
            } else {
                if (menuFile) menuFile.closest('.border-dashed')?.classList.remove('border-red-500');
                if (menuSectionsContainer) menuSectionsContainer.classList.remove('border-red-500');
                if (menuUrl) {
                    if (hasMenuUrl && !isValidUrl(menuUrl.value.trim())) {
                        errors.push('Menu URL must be a valid URL starting with https://');
                        menuUrl.classList.add('border-red-500');
                    } else {
                        menuUrl.classList.remove('border-red-500');
                    }
                }
            }
            break;
        }
            
        case 'coupon': {
            const couponCompany = document.getElementById('coupon_company');
            if (!couponCompany || !couponCompany.value.trim()) {
                errors.push('Company name is required');
                if (couponCompany) couponCompany.classList.add('border-red-500');
            } else if (couponCompany) couponCompany.classList.remove('border-red-500');
            const couponTitle = document.getElementById('coupon_title');
            if (!couponTitle || !couponTitle.value.trim()) {
                errors.push('Coupon title is required');
                if (couponTitle) couponTitle.classList.add('border-red-500');
            } else if (couponTitle) couponTitle.classList.remove('border-red-500');
            const couponSalesBadge = document.getElementById('coupon_sales_badge');
            if (!couponSalesBadge || !couponSalesBadge.value.trim()) {
                errors.push('Sales badge is required');
                if (couponSalesBadge) couponSalesBadge.classList.add('border-red-500');
            } else if (couponSalesBadge) couponSalesBadge.classList.remove('border-red-500');
            const couponValidUntil = document.getElementById('coupon_valid_until');
            if (!couponValidUntil || !couponValidUntil.value.trim()) {
                errors.push('Valid until date is required');
                if (couponValidUntil) couponValidUntil.classList.add('border-red-500');
            } else if (couponValidUntil) couponValidUntil.classList.remove('border-red-500');
            const useBarcode = document.getElementById('coupon_use_barcode');
            const barcodeImage = document.getElementById('coupon_barcode_image');
            const hasBarcode = useBarcode && useBarcode.checked && barcodeImage && barcodeImage.files && barcodeImage.files.length > 0;
            if (useBarcode && useBarcode.checked && barcodeImage && (!barcodeImage.files || barcodeImage.files.length === 0)) {
                errors.push('Please upload a barcode image when "Use barcode" is enabled');
                barcodeImage.closest('.border-dashed')?.classList.add('border-red-500');
            } else if (barcodeImage) {
                barcodeImage.closest('.border-dashed')?.classList.remove('border-red-500');
            }
            const couponViewMoreWebsite = document.getElementById('coupon_view_more_website');
            const websiteValue = couponViewMoreWebsite ? couponViewMoreWebsite.value.trim() : '';
            if (!hasBarcode && !websiteValue) {
                errors.push('Please enter a website URL or upload a barcode (at least one is required)');
                if (couponViewMoreWebsite) couponViewMoreWebsite.classList.add('border-red-500');
            } else if (couponViewMoreWebsite && websiteValue) {
                if (!validateCouponWebsite(websiteValue)) {
                    errors.push(websiteValue.startsWith('https://') ? 'You have entered an invalid link. Please try again.' : 'Website URL must start with https://');
                    couponViewMoreWebsite.classList.add('border-red-500');
                    const errorDiv = document.getElementById('coupon_view_more_website_error');
                    if (errorDiv) {
                        errorDiv.classList.remove('hidden');
                        errorDiv.textContent = websiteValue.startsWith('https://') ? 'You have entered an invalid link. Please try again.' : 'Website URL must start with https://';
                    }
                } else {
                    couponViewMoreWebsite.classList.remove('border-red-500');
                    const errorDiv = document.getElementById('coupon_view_more_website_error');
                    if (errorDiv) errorDiv.classList.add('hidden');
                }
            } else if (couponViewMoreWebsite) {
                couponViewMoreWebsite.classList.remove('border-red-500');
                const errorDiv = document.getElementById('coupon_view_more_website_error');
                if (errorDiv) errorDiv.classList.add('hidden');
            }
            break;
        }
            
        case 'event':
            const eventName = document.getElementById('event_name');
            if (!eventName || !eventName.value.trim()) {
                errors.push('Event name is required');
                if (eventName) eventName.classList.add('border-red-500');
            } else if (eventName) {
                eventName.classList.remove('border-red-500');
            }
            break;
            
        case 'location': {
            const addressEl = document.getElementById('address');
            const latEl = document.getElementById('latitude');
            const lngEl = document.getElementById('longitude');
            const locationUrlEl = document.getElementById('location_url');
            const hasAddress = addressEl && addressEl.value.trim() !== '';
            const hasCoords = latEl && lngEl && latEl.value.trim() !== '' && lngEl.value.trim() !== '';
            const hasLocationUrl = locationUrlEl && locationUrlEl.value.trim() !== '' && locationUrlEl.value.trim().startsWith('https://');
            if (!hasAddress && !hasCoords && !hasLocationUrl) {
                errors.push('Please enter an address or search for a location, paste a Google Maps link, or use your current location.');
                if (addressEl) addressEl.classList.add('border-red-500');
            } else {
                if (addressEl) addressEl.classList.remove('border-red-500');
            }
            break;
        }
            
        case 'wifi':
            const ssid = document.getElementById('ssid');
            const encryption = document.getElementById('encryption');
            const password = document.getElementById('password');
            
            if (!ssid || !ssid.value.trim()) {
                errors.push('WiFi network name (SSID) is required');
                if (ssid) ssid.classList.add('border-red-500');
            } else if (ssid) {
                ssid.classList.remove('border-red-500');
            }
            
            if (!encryption || !encryption.value) {
                errors.push('Encryption type is required');
                if (encryption) encryption.classList.add('border-red-500');
            } else if (encryption) {
                encryption.classList.remove('border-red-500');
            }
            
            if (encryption && encryption.value !== 'nopass') {
                if (!password || !password.value.trim()) {
                    errors.push('Password is required for encrypted networks');
                    if (password) password.classList.add('border-red-500');
                } else if (password) {
                    password.classList.remove('border-red-500');
                }
            } else if (password) {
                password.classList.remove('border-red-500');
            }
            break;
            
        case 'phone':
            const phoneNumber = document.getElementById('phone_number');
            if (!phoneNumber || !phoneNumber.value.trim()) {
                errors.push('Phone number is required');
                if (phoneNumber) phoneNumber.classList.add('border-red-500');
            } else if (phoneNumber) {
                phoneNumber.classList.remove('border-red-500');
            }
            break;
            
            
        case 'app':
            // Validate URL fields if provided
            const websiteUrl = document.getElementById('website_url');
            const appStoreLink = document.getElementById('app_store_link');
            const playStoreLink = document.getElementById('play_store_link');
            
            if (websiteUrl && websiteUrl.value.trim()) {
                if (!isValidUrl(websiteUrl.value.trim())) {
                    errors.push('Website URL must be a valid URL starting with https://');
                    websiteUrl.classList.add('border-red-500');
                } else {
                    websiteUrl.classList.remove('border-red-500');
                }
            }
            
            if (appStoreLink && appStoreLink.value.trim()) {
                if (!isValidUrl(appStoreLink.value.trim())) {
                    errors.push('App Store Link must be a valid URL starting with https://');
                    appStoreLink.classList.add('border-red-500');
                } else if (!isValidAppStoreLink(appStoreLink.value.trim())) {
                    errors.push('App Store Link must start with https://apps.apple.com/');
                    appStoreLink.classList.add('border-red-500');
                } else {
                    appStoreLink.classList.remove('border-red-500');
                }
            }
            
            if (playStoreLink && playStoreLink.value.trim()) {
                if (!isValidUrl(playStoreLink.value.trim())) {
                    errors.push('Google Play Store Link must be a valid URL starting with https://');
                    playStoreLink.classList.add('border-red-500');
                } else if (!isValidPlayStoreLink(playStoreLink.value.trim())) {
                    errors.push('Google Play Store Link must start with https://play.google.com/store/apps/');
                    playStoreLink.classList.add('border-red-500');
                } else {
                    playStoreLink.classList.remove('border-red-500');
                }
            }
            break;
    }
    
    // Show errors if any
    if (errors.length > 0) {
        // Remove existing error message if any
        const existingError = document.getElementById('step1-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Create error message
        const errorDiv = document.createElement('div');
        errorDiv.id = 'step1-error';
        errorDiv.className = 'mb-6 p-4 bg-red-50 border border-red-200 rounded-lg';
        errorDiv.innerHTML = `
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-800 mb-2">Please fill in all required fields:</p>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        ${errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
        
        // Insert error message before the Next Step button
        const step1Content = document.getElementById('step-1').querySelector('.card');
        const nextButton = step1Content.querySelector('.flex.justify-end');
        nextButton.parentNode.insertBefore(errorDiv, nextButton);
        
        // Scroll to error
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        return false;
    }
    
    // Remove error message if validation passes
    const existingError = document.getElementById('step1-error');
    if (existingError) {
        existingError.remove();
    }
    
    return true;
}

async function nextStep(step) {
    // Validate Step 1 before going to Step 2
    if (step === 2 && currentStep === 1) {
        if (!validateStep1()) {
            return;
        }
    }
    
    // If going to Step 3, create/update QR code when needed, or skip API if nothing changed
    if (step === 3) {
        const nextBtn = document.getElementById('step2-next-btn');
        const nextText = document.getElementById('step2-next-text');
        const nextLoading = document.getElementById('step2-next-loading');
        const backBtn = document.getElementById('step2-back-btn');

        // Sync Step 2 color hex into hidden inputs so fingerprint matches what would be submitted
        const primaryColorInput = document.getElementById('primary_color');
        const primaryColorHex = document.getElementById('primary_color_hex');
        const secondaryColorInput = document.getElementById('secondary_color');
        const secondaryColorHex = document.getElementById('secondary_color_hex');
        if (primaryColorInput && primaryColorHex) primaryColorInput.value = normalizeHexColor(primaryColorHex.value);
        if (secondaryColorInput && secondaryColorHex) secondaryColorInput.value = normalizeHexColor(secondaryColorHex.value);

        const fingerprint = getFormFingerprint();
        const nothingChanged = qrCodeId !== null && fingerprint === lastSubmittedFormFingerprint;

        if (nothingChanged) {
            // No API call: just show Step 3 with existing QR
        } else {
            if (nextBtn && nextText && nextLoading) {
                nextBtn.disabled = true;
                nextText.classList.add('hidden');
                nextLoading.classList.remove('hidden');
            }
            if (backBtn) backBtn.disabled = true;

            const success = qrCodeId === null
                ? await generateQRCode()
                : await updateQRCode(qrCodeId);

            if (nextBtn && nextText && nextLoading) {
                nextBtn.disabled = false;
                nextText.classList.remove('hidden');
                nextLoading.classList.add('hidden');
            }
            if (backBtn) backBtn.disabled = false;

            if (!success) return;
            lastSubmittedFormFingerprint = getFormFingerprint();
        }
    }
    
    document.getElementById(`step-${currentStep}`).classList.add('hidden');
    document.getElementById(`step-${currentStep}-indicator`).classList.remove('step-active');
    document.getElementById(`step-${currentStep}-indicator`).classList.add('step-completed');
    
    currentStep = step;
    
    document.getElementById(`step-${step}`).classList.remove('hidden');
    document.getElementById(`step-${step}-indicator`).classList.remove('step-inactive');
    document.getElementById(`step-${step}-indicator`).classList.add('step-active');
    
    // Update previews when steps are shown
    if (step === 1) {
        setTimeout(() => {
            updateStep1Preview();
        }, 100);
    }
    
    if (step === 2) {
        // Set default selections if not set
        const selectedPattern = document.getElementById('selected_pattern')?.value;
        if (!selectedPattern) {
            const squarePattern = document.querySelector('.pattern-option[data-pattern="square"]');
            if (squarePattern) selectPattern(squarePattern, 'square');
        }
        
        const selectedCorner = document.getElementById('selected_corner')?.value;
        if (!selectedCorner) {
            const squareCorner = document.querySelector('.corner-option[data-corner="square"]');
            if (squareCorner) selectCorner(squareCorner, 'square');
        }
        
        const selectedCornerDot = document.getElementById('selected_corner_dot')?.value;
        if (!selectedCornerDot) {
            const squareCornerDot = document.querySelector('.corner-dot-option[data-corner-dot="square"]');
            if (squareCornerDot) selectCornerDot(squareCornerDot, 'square');
        }
        
        setTimeout(() => {
            updateStep2QRPreview();
        }, 100);
    }
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function prevStep(step) {
    document.getElementById(`step-${currentStep}`).classList.add('hidden');
    document.getElementById(`step-${currentStep}-indicator`).classList.remove('step-active');
    document.getElementById(`step-${currentStep}-indicator`).classList.add('step-inactive');
    
    currentStep = step;
    
    document.getElementById(`step-${step}`).classList.remove('hidden');
    document.getElementById(`step-${step}-indicator`).classList.remove('step-completed');
    document.getElementById(`step-${step}-indicator`).classList.add('step-active');
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function getRecaptchaToken() {
    if (!window.recaptchaSiteKey || typeof grecaptcha === 'undefined') return Promise.resolve(null);
    return new Promise(function(resolve) {
        grecaptcha.ready(function() {
            grecaptcha.execute(window.recaptchaSiteKey, { action: 'submit' }).then(resolve).catch(function() { resolve(null); });
        });
    });
}

async function generateQRCode() {
    // Sync Step 2 color hex fields into named inputs so primary_color/secondary_color are always valid #rrggbb
    const primaryColorInput = document.getElementById('primary_color');
    const primaryColorHex = document.getElementById('primary_color_hex');
    const secondaryColorInput = document.getElementById('secondary_color');
    const secondaryColorHex = document.getElementById('secondary_color_hex');
    if (primaryColorInput && primaryColorHex) {
        primaryColorInput.value = normalizeHexColor(primaryColorHex.value);
    }
    if (secondaryColorInput && secondaryColorHex) {
        secondaryColorInput.value = normalizeHexColor(secondaryColorHex.value);
    }
    const formData = new FormData(document.getElementById('qr-form'));
    const recaptchaToken = await getRecaptchaToken();
    if (recaptchaToken) formData.append('recaptcha_token', recaptchaToken);

    // Show loading state in Step 3
    document.getElementById('qr-loading').classList.remove('hidden');
    document.getElementById('qr-error').classList.add('hidden');
    
    // Disable download buttons
    document.getElementById('download-png-btn').disabled = true;
    document.getElementById('download-svg-btn').disabled = true;
    
    try {
        const response = await fetch('{{ route("qr-codes.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            // Server returned non-JSON (e.g. 500 HTML error page)
            const errMsg = currentStep === 2
                ? 'Server error. Please check your input and try again.'
                : 'Server error. Please try again.';
            if (currentStep === 2) {
                showErrorInStep2(errMsg);
            } else {
                showError(errMsg);
            }
            return false;
        }
        
        if (data.success) {
            qrCodeId = data.qr_code_id;
            window.step3MenuPageUrl = data.menu_page_url || null;
            
            // Hide loading
            document.getElementById('qr-loading').classList.add('hidden');
            
            // Step 3: use real URL (/menu/{id}) from server so QR leads to menu page, not /menu/preview
            await generateStep3CustomizedQR(data.menu_page_url || null);
            
            // Enable download buttons
            document.getElementById('download-png-btn').disabled = false;
            document.getElementById('download-svg-btn').disabled = false;
            
            return true; // Success
        } else {
            const msg = data.message || data.errors ? (typeof data.errors === 'object' ? Object.values(data.errors).flat().join(' ') : String(data.errors)) : 'Failed to generate QR code. Please check your input and try again.';
            if (currentStep === 2) {
                showErrorInStep2(msg);
            } else {
                showError(msg);
            }
            return false;
        }
    } catch (error) {
        console.error('Error:', error);
        const errMsg = currentStep === 2
            ? 'Network error. Please check your connection and try again.'
            : 'Network error. Please check your connection and try again.';
        if (currentStep === 2) {
            showErrorInStep2(errMsg);
        } else {
            showError(errMsg);
        }
        return false;
    }
}

async function updateQRCode(id) {
    const primaryColorInput = document.getElementById('primary_color');
    const primaryColorHex = document.getElementById('primary_color_hex');
    const secondaryColorInput = document.getElementById('secondary_color');
    const secondaryColorHex = document.getElementById('secondary_color_hex');
    if (primaryColorInput && primaryColorHex) primaryColorInput.value = normalizeHexColor(primaryColorHex.value);
    if (secondaryColorInput && secondaryColorHex) secondaryColorInput.value = normalizeHexColor(secondaryColorHex.value);

    const form = document.getElementById('qr-form');
    if (!form) return false;
    const formData = new FormData(form);
    formData.append('_method', 'PUT');
    const recaptchaToken = await getRecaptchaToken();
    if (recaptchaToken) formData.append('recaptcha_token', recaptchaToken);

    const qrLoading = document.getElementById('qr-loading');
    const qrError = document.getElementById('qr-error');
    const downloadPngBtn = document.getElementById('download-png-btn');
    const downloadSvgBtn = document.getElementById('download-svg-btn');
    if (qrLoading) qrLoading.classList.remove('hidden');
    if (qrError) qrError.classList.add('hidden');
    if (downloadPngBtn) downloadPngBtn.disabled = true;
    if (downloadSvgBtn) downloadSvgBtn.disabled = true;

    try {
        const url = `{{ url("/qr-codes") }}/${id}`;
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            showErrorInStep2('Server error. Please try again.');
            return false;
        }
        if (data.success) {
            window.step3MenuPageUrl = data.menu_page_url || null;
            if (qrLoading) qrLoading.classList.add('hidden');
            await generateStep3CustomizedQR(data.menu_page_url || null);
            if (downloadPngBtn) downloadPngBtn.disabled = false;
            if (downloadSvgBtn) downloadSvgBtn.disabled = false;
            return true;
        }
        showErrorInStep2(data.message || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Update failed.'));
        return false;
    } catch (error) {
        console.error('Error:', error);
        showErrorInStep2('Network error. Please try again.');
        if (downloadPngBtn) downloadPngBtn.disabled = false;
        if (downloadSvgBtn) downloadSvgBtn.disabled = false;
        return false;
    }
}

// Generate customized QR code for Step 3 with same styling as Step 2
// menuPageUrl: for type=menu, the actual URL to the menu page (/menu/{id}) from the server so the QR never points to /menu/preview
async function generateStep3CustomizedQR(menuPageUrl) {
    const qrPreviewContainer = document.getElementById('qr-preview');
    if (!qrPreviewContainer) return;
    
    // Get customization parameters from Step 2
    const type = document.querySelector('input[name="type"]').value;
    const primaryColor = document.getElementById('primary_color')?.value || '#000000';
    const secondaryColor = document.getElementById('secondary_color')?.value || '#FFFFFF';
    const pattern = document.getElementById('selected_pattern')?.value || 'square';
    const cornerStyle = document.getElementById('selected_corner')?.value || 'square';
    const cornerDotStyle = document.getElementById('selected_corner_dot')?.value || 'square';
    const logoDataUrl = document.getElementById('qr_logo_data_url')?.value || '';
    
    // Build QR content; for menu type always use real menu page URL (/menu/{id}), never placeholder /menu/preview
    let qrContent;
    if (type === 'menu') {
        qrContent = menuPageUrl || (qrCodeId ? (window.location.origin + '/menu/' + qrCodeId) : buildQrContentFromForm());
    } else {
        qrContent = buildQrContentFromForm();
    }
    
    if (!window.QRCodeStyling) {
        console.warn('QR Code Styling library is not loaded.');
        qrPreviewContainer.innerHTML = '<p class="text-red-500">Failed to load QR code styling library</p>';
        return;
    }
    
    // Map UI values to qr-code-styling types
    const dotsTypeMap = {
        square: 'square',
        circle: 'dots',
        rounded: 'rounded',
    };
    
    const cornersSquareTypeMap = {
        square: 'square',
        rounded: 'rounded',
        'extra-rounded': 'extra-rounded',
    };
    
    const cornersDotTypeMap = {
        square: 'square',
        circle: 'dot',
        rounded: 'rounded',
    };
    
    const dotsType = dotsTypeMap[pattern] || 'square';
    const cornersSquareType = cornersSquareTypeMap[cornerStyle] || 'square';
    const cornersDotType = cornersDotTypeMap[cornerDotStyle] || 'dot';
    
    const frameId = document.getElementById('selected_frame')?.value || 'none';
    const STEP3_HOLE_SIZE = 300;
    const STEP2_QR_IN_FRAME = 220;
    const STEP2_HOLE_PX = 260;
    const step3QrDisplaySize = (frameId && frameId !== 'none')
        ? Math.round(STEP2_QR_IN_FRAME * STEP3_HOLE_SIZE / STEP2_HOLE_PX)
        : STEP3_HOLE_SIZE;
    
    const options = {
        width: step3QrDisplaySize,
        height: step3QrDisplaySize,
        type: 'canvas',
        data: qrContent,
        margin: 0,
        qrOptions: {
            errorCorrectionLevel: 'H',
        },
        dotsOptions: {
            color: primaryColor,
            type: dotsType,
        },
        backgroundOptions: {
            color: secondaryColor,
        },
        cornersSquareOptions: {
            type: cornersSquareType,
            color: primaryColor,
        },
        cornersDotOptions: {
            type: cornersDotType,
            color: primaryColor,
        },
        image: logoDataUrl || undefined,
        imageOptions: {
            hideBackgroundDots: true,
            imageSize: 0.4,
            margin: 4,
            crossOrigin: 'anonymous',
        },
    };

    let step3AppendTarget = qrPreviewContainer;

    if (frameId && frameId !== 'none' && FRAME_CONFIG[frameId]) {
        const cfg = FRAME_CONFIG[frameId];
        if (cfg.url && cfg.qrLeft !== undefined) {
            const wrapper = document.createElement('div');
            wrapper.className = 'frame-wrapper relative mx-auto inline-block';
            const holePx = STEP3_HOLE_SIZE;
            const totalW = holePx / (cfg.qrWidth / 100);
            const totalH = totalW * (cfg.frameHeight / cfg.frameWidth);
            wrapper.style.width = totalW + 'px';
            wrapper.style.height = totalH + 'px';
            const img = document.createElement('img');
            if (frameId === 'review-us') {
                img.src = await getReviewUsFrameUrl();
            } else {
                img.src = cfg.themable
                    ? await getThemedFrameUrl(cfg.url, primaryColor, secondaryColor)
                    : cfg.url;
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
            qrPreviewContainer.innerHTML = '';
            qrPreviewContainer.appendChild(wrapper);
            step3AppendTarget = qrInFrame;
        }
    } else {
        qrPreviewContainer.innerHTML = '';
    }

    const qrCodeStyling = new window.QRCodeStyling(options);
    qrCodeStyling.append(step3AppendTarget);

    window.step3QrInstance = qrCodeStyling;
}

function showErrorInStep2(message) {
    // Remove existing error if any
    const existingError = document.getElementById('step2-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Create error message
    const errorDiv = document.createElement('div');
    errorDiv.id = 'step2-error';
    errorDiv.className = 'mb-6 p-4 bg-red-50 border border-red-200 rounded-lg';
    errorDiv.innerHTML = `
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-medium text-red-800">Failed to generate QR code</p>
                <p class="text-xs text-red-600 mt-1">${message}</p>
            </div>
        </div>
    `;
    
    // Insert error message before the Next Step button
    const step2Content = document.getElementById('step-2').querySelector('.card');
    const nextButton = step2Content.querySelector('.flex.justify-between');
    nextButton.parentNode.insertBefore(errorDiv, nextButton);
    
    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function showError(message) {
    document.getElementById('qr-loading').classList.add('hidden');
    document.getElementById('qr-error').classList.remove('hidden');
    document.getElementById('qr-error-message').textContent = message;
}

function retryGeneration() {
    document.getElementById('qr-error').classList.add('hidden');
    generateQRCode();
}


async function downloadQR(format) {
    if (!qrCodeId) {
        alert('Please generate a QR code first.');
        return;
    }

    const frameId = document.getElementById('selected_frame')?.value || 'none';
    const hasFrame = frameId && frameId !== 'none' && FRAME_CONFIG[frameId] && FRAME_CONFIG[frameId].qrLeft !== undefined;

    // When a frame is selected, download composite (frame + QR) as PNG
    if (hasFrame && window.step3QrInstance) {
        const cfg = FRAME_CONFIG[frameId];
        const primaryColor = document.getElementById('primary_color')?.value || '#000000';
        const secondaryColor = document.getElementById('secondary_color')?.value || '#FFFFFF';
        const frameUrl = frameId === 'review-us'
            ? await getReviewUsFrameUrl()
            : (cfg.themable
                ? await getThemedFrameUrl(cfg.url, primaryColor, secondaryColor)
                : cfg.url);

        const holePx = 300;
        const qrSize = Math.round(220 * holePx / 260);
        const totalW = Math.round(holePx / (cfg.qrWidth / 100));
        const totalH = Math.round(totalW * (cfg.frameHeight / cfg.frameWidth));
        const holeLeft = totalW * (cfg.qrLeft / 100);
        const holeTop = totalH * (cfg.qrTop / 100);
        const holeWidth = totalW * (cfg.qrWidth / 100);
        const holeHeight = totalH * (cfg.qrHeight / 100);
        const qrX = holeLeft + (holeWidth - qrSize) / 2;
        const qrY = holeTop + (holeHeight - qrSize) / 2;

        const canvas = document.createElement('canvas');
        canvas.width = totalW;
        canvas.height = totalH;
        const ctx = canvas.getContext('2d');

        const frameImg = new Image();
        frameImg.crossOrigin = 'anonymous';
        frameImg.onload = function() {
            ctx.drawImage(frameImg, 0, 0, totalW, totalH);
            const qrCanvas = document.querySelector('#qr-preview canvas');
            if (qrCanvas) {
                ctx.drawImage(qrCanvas, qrX, qrY, qrSize, qrSize);
            }
            const link = document.createElement('a');
            link.download = `qr-code-${qrCodeId}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        };
        frameImg.onerror = function() {
            window.step3QrInstance.download({ name: `qr-code-${qrCodeId}`, extension: 'png' });
        };
        frameImg.src = frameUrl;
        return;
    }

    // No frame: download only QR
    if (window.step3QrInstance) {
        const fileName = `qr-code-${qrCodeId}`;
        if (format === 'png') {
            window.step3QrInstance.download({ name: fileName, extension: 'png' });
        } else if (format === 'svg') {
            window.step3QrInstance.download({ name: fileName, extension: 'svg' });
        }
    } else {
        window.location.href = `/qr-codes/${qrCodeId}/download/${format}`;
    }
}
</script>
@endpush
@endsection

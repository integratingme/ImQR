@extends('layouts.app')

@section('title', 'Create ' . ucfirst($type) . ' QR Code')

@section('content')
<style>
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
                    <input type="text" id="name" name="name" class="input" placeholder="My {{ ucfirst($type) }} QR Code" required>
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
                                        <input type="color" id="primary_color" name="primary_color" value="#000000" class="w-16 h-12 rounded border-2 border-dark-200 cursor-pointer">
                                        <input type="text" id="primary_color_hex" value="#000000" class="input flex-1" placeholder="#000000">
                                    </div>
                                </div>
                                <div>
                                    <label for="secondary_color" class="text-sm text-dark-300 mb-2 block">Background Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" id="secondary_color" name="secondary_color" value="#FFFFFF" class="w-16 h-12 rounded border-2 border-dark-200 cursor-pointer">
                                        <input type="text" id="secondary_color_hex" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
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
                                            <input id="qr_logo" name="qr_logo" type="file" accept="image/*" class="hidden">
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
                            <input type="hidden" id="selected_pattern" name="pattern" value="square">
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
                            <input type="hidden" id="selected_corner" name="corner_style" value="square">
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
                            <input type="hidden" id="selected_corner_dot" name="corner_dot_style" value="square">
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
                    <div id="qr-preview" class="inline-block p-8 bg-primary-50 rounded-lg">
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
let qrCodeId = null;
let qrStylingInstance = null;

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
        'location': ['address'],
        'wifi': ['ssid', 'encryption', 'password'],
        'phone': ['full_name', 'phone_number', 'phone_background_color_hex', 'phone_font_family'],
        'mp3': ['song_name', 'artist_name'],
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
    
    // Special handling for Menu - either file or URL must be filled
    if (type === 'menu') {
        const menuFile = document.getElementById('menu_file');
        const menuUrl = document.getElementById('menu_url');
        
        const clearMenuErrors = () => {
            if (menuFile) menuFile.closest('.border-dashed')?.classList.remove('border-red-500');
            if (menuUrl) menuUrl.classList.remove('border-red-500');
        };
        
        if (menuFile) {
            menuFile.addEventListener('change', () => {
                if (menuFile.files && menuFile.files.length > 0) {
                    clearMenuErrors();
                }
            });
        }
        
        if (menuUrl) {
            menuUrl.addEventListener('input', () => {
                if (menuUrl.value.trim()) {
                    if (isValidUrl(menuUrl.value.trim())) {
                        menuUrl.classList.remove('border-red-500');
                    } else {
                        menuUrl.classList.add('border-red-500');
                    }
                } else {
                    menuUrl.classList.remove('border-red-500');
                }
            });
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
// Color picker sync for Step 2
const primaryColorInput = document.getElementById('primary_color');
const primaryColorHex = document.getElementById('primary_color_hex');
if (primaryColorInput && primaryColorHex) {
    primaryColorInput.addEventListener('input', (e) => {
        primaryColorHex.value = e.target.value;
        updateStep2QRPreview();
    });

    primaryColorHex.addEventListener('input', (e) => {
        primaryColorInput.value = e.target.value;
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
    
    switch(type) {
        case 'url':
            if (overlay) overlay.style.backgroundColor = '#F9FAFB'; // gray-50
            const url = document.getElementById('url')?.value || '';
            const urlDomain = url ? new URL(url).hostname.replace('www.', '') : 'example.com';
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
            if (overlay) overlay.style.backgroundColor = '#F3F4F6'; // gray-100
            const address = document.getElementById('address')?.value || '';
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden relative">
                    <!-- Map placeholder -->
                    <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <!-- Address overlay -->
                    <div class="absolute bottom-0 left-0 right-0 bg-white p-3 rounded-t-lg">
                        <div class="text-sm font-medium text-gray-900">${address || 'Enter address'}</div>
                    </div>
                </div>
            `;
            break;
            
        case 'event':
            if (overlay) overlay.style.backgroundColor = '#FFFFFF'; // white
            const eventName = document.getElementById('event_name')?.value || '';
            const companyName = document.getElementById('company_name')?.value || '';
            const eventDate = document.getElementById('date')?.value || '';
            const eventTime = document.getElementById('time')?.value || '';
            const eventLocation = document.getElementById('location')?.value || '';
            mockupHtml = `
                <div class="w-full h-full rounded-lg overflow-hidden">
                    <div class="p-4">
                        ${companyName ? `<div class="text-xs text-gray-500 mb-1">${companyName}</div>` : ''}
                        <div class="text-lg font-bold text-gray-900 mb-3">${eventName || 'Event Name'}</div>
                        ${eventDate || eventTime ? `
                            <div class="flex items-center gap-2 mb-2 text-sm text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>${eventDate || ''} ${eventTime || ''}</span>
                            </div>
                        ` : ''}
                        ${eventLocation ? `
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>${eventLocation}</span>
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
            // Kao na backend preview-u: placeholder URL, pravi URL se postavlja pri snimanju
            return '/pdf/preview';
        case 'menu': {
            const menuUrl = getValue('menu_url');
            return menuUrl;
        }
        case 'coupon':
            // Za preview nemamo URL fajla, koristimo placeholder
            return '/coupon/preview';
        case 'event': {
            const amenities = []; // za preview možemo ignorisati detaljne amenity stavke
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
            });
        }
        case 'app':
            // For app type, use app page URL (similar to PDF and text)
            return '/app/preview';
        case 'location': {
            const address = getValue('address');
            return 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(address);
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
        case 'mp3':
            // Za preview nema realnog URL-a fajla, koristimo placeholder
            return '/mp3/preview';
        default:
            return '';
    }
}

// Update Step 2 QR code preview with customization using qr-code-styling
async function updateStep2QRPreview() {
    if (currentStep !== 2) return;

    const qrContainer = document.getElementById('phone-mockup-qr-step2');
    const overlay = document.getElementById('phone-mockup-overlay-step2');
    if (!qrContainer) return;

    const type = document.querySelector('input[name="type"]').value;
    const primaryColor = document.getElementById('primary_color')?.value || '#000000';
    const secondaryColor = document.getElementById('secondary_color')?.value || '#FFFFFF';
    const pattern = document.getElementById('selected_pattern')?.value || 'square';
    const cornerStyle = document.getElementById('selected_corner')?.value || 'square';
    const cornerDotStyle = document.getElementById('selected_corner_dot')?.value || 'square';
    const logoDataUrl = document.getElementById('qr_logo_data_url')?.value || '';

    // Update overlay background color (Step 2 pozadina)
    if (overlay) {
        overlay.style.backgroundColor = secondaryColor;
    }

    const data = buildQrContentFromForm();

    if (!window.QRCodeStyling) {
        console.warn('QR Code Styling library is not loaded. Make sure you ran `npm install qr-code-styling` and Vite bundle je učitan.');
        qrContainer.innerHTML = '';
        return;
    }

    // Mapiramo naše UI vrijednosti na tipove koje koristi qr-code-styling
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
        width: 260,
        height: 260,
        type: 'svg',
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

    // Inicijalizacija ili update postojeće QR instance
    if (!qrStylingInstance) {
        qrContainer.innerHTML = '';
        qrStylingInstance = new window.QRCodeStyling(options);
        qrStylingInstance.append(qrContainer);
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
        secondaryColorInput.value = e.target.value;
        updateStep2QRPreview();
    });
}

// Setup real-time validation and preview updates when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupRealTimeValidation();
    
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
        'event_name', 'company_name', 'date', 'time', 'location', 'description',
        'pdf_primary_color_hex', 'pdf_secondary_color_hex', 'pdf_title', 'pdf_website', 
        'company_name', 'file_description', 'pdf_button_text', 'pdf_button_color_hex', 'pdf_font_family',
        'text_background_color_hex', 'text_text_color_hex', 'text_font_family',
        'coupon_primary_color_hex', 'coupon_secondary_color_hex', 'coupon_button_color_hex', 'coupon_button_text_color_hex', 'coupon_font_family',
        'coupon_company', 'coupon_title', 'coupon_description', 'coupon_sales_badge', 'coupon_sales_badge_color_hex', 'coupon_sales_badge_text_color_hex', 'coupon_code_button_text',
        'coupon_valid_until', 'coupon_view_more_website'
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
    
    // Logo upload handling for Step 2
    const logoInput = document.getElementById('qr_logo');
    const logoHidden = document.getElementById('qr_logo_data_url');
    const logoFilename = document.getElementById('qr_logo_filename');
    const logoRemoveBtn = document.getElementById('qr_logo_remove_btn');

    if (logoInput && logoHidden) {
        logoInput.addEventListener('change', () => {
            const file = logoInput.files && logoInput.files[0] ? logoInput.files[0] : null;
            if (!file) {
                logoHidden.value = '';
                if (logoFilename) logoFilename.textContent = '';
                if (logoRemoveBtn) logoRemoveBtn.style.display = 'none';
                updateStep2QRPreview();
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                logoHidden.value = e.target.result;
                if (logoFilename) logoFilename.textContent = file.name;
                if (logoRemoveBtn) logoRemoveBtn.style.display = 'inline-flex';
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
            
        case 'menu':
            const menuFile = document.getElementById('menu_file');
            const menuUrl = document.getElementById('menu_url');
            const hasMenuFile = menuFile && menuFile.files && menuFile.files.length > 0;
            const hasMenuUrl = menuUrl && menuUrl.value.trim();
            
            if (!hasMenuFile && !hasMenuUrl) {
                errors.push('Please upload a menu PDF file or enter a menu URL');
                if (menuFile) menuFile.closest('.border-dashed')?.classList.add('border-red-500');
                if (menuUrl) menuUrl.classList.add('border-red-500');
            } else {
                if (menuFile) menuFile.closest('.border-dashed')?.classList.remove('border-red-500');
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
            
        case 'location':
            const address = document.getElementById('address');
            if (!address || !address.value.trim()) {
                errors.push('Address is required');
                if (address) address.classList.add('border-red-500');
            } else if (address) {
                address.classList.remove('border-red-500');
            }
            break;
            
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
            
        case 'mp3':
            const mp3File = document.getElementById('mp3_file');
            const songName = document.getElementById('song_name');
            const artistName = document.getElementById('artist_name');
            
            if (!mp3File || !mp3File.files || mp3File.files.length === 0) {
                errors.push('MP3 file is required');
                if (mp3File) mp3File.closest('.border-dashed')?.classList.add('border-red-500');
            } else if (mp3File) {
                mp3File.closest('.border-dashed')?.classList.remove('border-red-500');
            }
            
            if (!songName || !songName.value.trim()) {
                errors.push('Song name is required');
                if (songName) songName.classList.add('border-red-500');
            } else if (songName) {
                songName.classList.remove('border-red-500');
            }
            
            if (!artistName || !artistName.value.trim()) {
                errors.push('Artist name is required');
                if (artistName) artistName.classList.add('border-red-500');
            } else if (artistName) {
                artistName.classList.remove('border-red-500');
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
    
    // If going to Step 3, generate QR code first and wait for it
    if (step === 3) {
        // Show loading state on Next Step button
        const nextBtn = document.getElementById('step2-next-btn');
        const nextText = document.getElementById('step2-next-text');
        const nextLoading = document.getElementById('step2-next-loading');
        const backBtn = document.getElementById('step2-back-btn');
        
        if (nextBtn && nextText && nextLoading) {
            nextBtn.disabled = true;
            nextText.classList.add('hidden');
            nextLoading.classList.remove('hidden');
        }
        if (backBtn) {
            backBtn.disabled = true;
        }
        
        const success = await generateQRCode();
        
        // Restore button state
        if (nextBtn && nextText && nextLoading) {
            nextBtn.disabled = false;
            nextText.classList.remove('hidden');
            nextLoading.classList.add('hidden');
        }
        if (backBtn) {
            backBtn.disabled = false;
        }
        
        if (!success) {
            // Don't proceed to Step 3 if QR code generation failed
            return;
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

async function generateQRCode() {
    const formData = new FormData(document.getElementById('qr-form'));
    
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
        
        const data = await response.json();
        
        if (data.success) {
            qrCodeId = data.qr_code_id;
            
            // Hide loading, show QR code
            document.getElementById('qr-preview').innerHTML = `<img src="${data.preview_url}" alt="QR Code" class="w-64 h-64">`;
            
            // Enable download buttons
            document.getElementById('download-png-btn').disabled = false;
            document.getElementById('download-svg-btn').disabled = false;
            
            return true; // Success
        } else {
            // Show error in Step 2
            if (currentStep === 2) {
                showErrorInStep2(data.message || 'Failed to generate QR code. Please check your input and try again.');
            } else {
                showError(data.message || 'Failed to generate QR code. Please check your input and try again.');
            }
            return false; // Failed
        }
    } catch (error) {
        console.error('Error:', error);
        
        // Show error
        if (currentStep === 2) {
            showErrorInStep2('Network error. Please check your connection and try again.');
        } else {
            showError('Network error. Please check your connection and try again.');
        }
        return false; // Failed
    }
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


function downloadQR(format) {
    if (!qrCodeId) {
        alert('Please generate a QR code first.');
        return;
    }
    
    window.location.href = `/qr-codes/${qrCodeId}/download/${format}`;
}
</script>
@endpush
@endsection

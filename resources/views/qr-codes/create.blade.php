@extends('layouts.app')

@section('title', 'Create ' . ucfirst($type) . ' QR Code')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Progress Steps -->
    <div class="mb-12">
        <div class="flex items-center justify-center">
            <div class="flex items-center space-x-4">
                <!-- Step 1 -->
                <div class="flex items-center">
                    <div class="step-indicator step-active" id="step-1-indicator">
                        1
                    </div>
                    <span class="ml-2 text-sm font-medium text-dark-500">Setup Info</span>
                </div>
                
                <div class="w-16 h-0.5 bg-dark-200"></div>
                
                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="step-indicator step-inactive" id="step-2-indicator">
                        2
                    </div>
                    <span class="ml-2 text-sm font-medium text-dark-300">Customize</span>
                </div>
                
                <div class="w-16 h-0.5 bg-dark-200"></div>
                
                <!-- Step 3 -->
                <div class="flex items-center">
                    <div class="step-indicator step-inactive" id="step-3-indicator">
                        3
                    </div>
                    <span class="ml-2 text-sm font-medium text-dark-300">Design QR Code</span>
                </div>
            </div>
        </div>
    </div>

    <form id="qr-form" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        
        <!-- Step 1: Setup Info -->
        <div id="step-1" class="step-content">
            <div class="card max-w-3xl mx-auto">
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
        </div>

        <!-- Step 2: Customize -->
        <div id="step-2" class="step-content hidden">
            <div class="card max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-dark-500 mb-6">Customize Your {{ ucfirst($type) }} QR Code</h2>
                
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
                            <button type="button" class="color-preset" data-primary="#0EA5E9" data-secondary="#FFFFFF">
                                <div class="w-10 h-10 rounded border-2 border-dark-200 bg-primary-500"></div>
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
        </div>

        <!-- Step 3: Design QR Code -->
        <div id="step-3" class="step-content hidden">
            <div class="card max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-dark-500 mb-6">Your QR Code</h2>
                
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
    </form>
</div>

@push('scripts')
<script>
let currentStep = 1;
let qrCodeId = null;

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
        'phone': ['phone_number'],
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
document.getElementById('primary_color').addEventListener('input', (e) => {
    document.getElementById('primary_color_hex').value = e.target.value;
});

document.getElementById('primary_color_hex').addEventListener('input', (e) => {
    document.getElementById('primary_color').value = e.target.value;
});

document.getElementById('secondary_color').addEventListener('input', (e) => {
    document.getElementById('secondary_color_hex').value = e.target.value;
});

document.getElementById('secondary_color_hex').addEventListener('input', (e) => {
    document.getElementById('secondary_color').value = e.target.value;
});

// Setup real-time validation when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupRealTimeValidation();
});

// Color presets
document.querySelectorAll('.color-preset').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const primary = btn.dataset.primary;
        const secondary = btn.dataset.secondary;
        document.getElementById('primary_color').value = primary;
        document.getElementById('primary_color_hex').value = primary;
        document.getElementById('secondary_color').value = secondary;
        document.getElementById('secondary_color_hex').value = secondary;
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
            
        case 'coupon':
            const couponImage = document.getElementById('coupon_image');
            if (!couponImage || !couponImage.files || couponImage.files.length === 0) {
                errors.push('Coupon image is required');
                if (couponImage) couponImage.closest('.border-dashed')?.classList.add('border-red-500');
            } else if (couponImage) {
                couponImage.closest('.border-dashed')?.classList.remove('border-red-500');
            }
            break;
            
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

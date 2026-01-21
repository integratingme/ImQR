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
                    <span class="ml-2 text-sm font-medium text-dark-500">Customize</span>
                </div>
                
                <div class="w-16 h-0.5 bg-dark-200"></div>
                
                <!-- Step 2 -->
                <div class="flex items-center">
                    <div class="step-indicator step-inactive" id="step-2-indicator">
                        2
                    </div>
                    <span class="ml-2 text-sm font-medium text-dark-300">Setup Info</span>
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
        
        <!-- Step 1: Customize -->
        <div id="step-1" class="step-content">
            <div class="card max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-dark-500 mb-6">Customize Your {{ ucfirst($type) }} QR Code</h2>
                
                <!-- QR Name -->
                <div class="mb-6">
                    <label for="name" class="label">QR Code Name *</label>
                    <input type="text" id="name" name="name" class="input" placeholder="My {{ ucfirst($type) }} QR Code" required>
                </div>

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

                <div class="flex justify-end">
                    <button type="button" onclick="nextStep(2)" class="btn btn-primary">
                        Next Step →
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 2: Setup Info -->
        <div id="step-2" class="step-content hidden">
            <div class="card max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-dark-500 mb-6">Setup Information</h2>
                
                @include('qr-codes.forms.' . $type)

                <div class="flex justify-between mt-6">
                    <button type="button" onclick="prevStep(1)" class="btn btn-secondary">
                        ← Back
                    </button>
                    <button type="button" onclick="nextStep(3)" class="btn btn-primary">
                        Next Step →
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

function nextStep(step) {
    if (step === 3) {
        generateQRCode();
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
    
    // Show loading state
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
        } else {
            // Show error
            showError(data.message || 'Failed to generate QR code. Please check your input and try again.');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Network error. Please check your connection and try again.');
    }
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

<!-- App QR Code Form -->
<div class="space-y-6">
    <!-- Design and Customize Section -->
    <div class="card">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark-500">Design and customize</h3>
                    <p class="text-sm text-dark-300 mt-1">Choose your color scheme</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleAppSection('design-section')">
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        
        <div id="design-section-content">
            <!-- Color Palettes -->
            <div class="mb-6">
                <div class="grid grid-cols-2 md:flex gap-3 mb-4">
                    <button type="button" class="app-color-preset border-2 border-primary-500 rounded-lg p-2 hover:border-primary-600 transition-colors" data-primary="#6594FF" data-secondary="#FFFFFF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #6594FF;"></div>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                        </div>
                    </button>
                    <button type="button" class="app-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E5E7EB" data-secondary="#FFDAD8">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #E5E7EB;"></div>
                            <div class="w-8 h-8 rounded" style="background-color: #FFDAD8;"></div>
                        </div>
                    </button>
                    <button type="button" class="app-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E9D5FF" data-secondary="#FFFFFF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #E9D5FF;"></div>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                        </div>
                    </button>
                    <button type="button" class="app-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#FFD1DC" data-secondary="#B5E5CF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #FFD1DC;"></div>
                            <div class="w-8 h-8 rounded" style="background-color: #B5E5CF;"></div>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Color Inputs -->
            <div class="mb-4">
                <!-- Labels Row -->
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <label for="app_primary_color" class="text-sm font-bold text-dark-500">Primary color</label>
                    <label for="app_secondary_color" class="text-sm font-bold text-dark-500">Secondary color</label>
                </div>
                
                <!-- Inputs Row -->
                <div class="grid grid-cols-2 gap-4 relative">
                    <!-- Primary Color Input -->
                    <div class="flex items-center gap-3 relative">
                        <input type="text" id="app_primary_color_hex" name="app_primary_color" value="#6594FF" class="input flex-1" placeholder="#6594FF">
                        <div class="relative">
                            <input type="color" id="app_primary_color_picker" value="#6594FF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                            <button type="button" id="app-swap-colors" class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-dark-300 hover:text-dark-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Secondary Color Input -->
                    <div class="flex items-center gap-3">
                        <input type="text" id="app_secondary_color_hex" name="app_secondary_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                        <input type="color" id="app_secondary_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>
            
            <!-- Font Selection and Text Color -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="app_font_family" class="text-sm font-bold text-dark-500 mb-2 block">Font family</label>
                    <select id="app_font_family" name="app_font_family" class="input w-full">
                        <option value="Maven Pro">Maven Pro</option>
                        <option value="Inter">Inter</option>
                        <option value="Roboto">Roboto</option>
                        <option value="Open Sans">Open Sans</option>
                        <option value="Lato">Lato</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Poppins">Poppins</option>
                        <option value="Raleway">Raleway</option>
                        <option value="Nunito">Nunito</option>
                    </select>
                </div>
                <div>
                    <label for="app_text_color" class="text-sm font-bold text-dark-500 mb-2 block">Text color</label>
                    <div class="flex items-center gap-3">
                        <input type="text" id="app_text_color_hex" name="app_text_color" value="#000000" class="input flex-1" placeholder="#000000">
                        <input type="color" id="app_text_color_picker" value="#000000" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- App Information Section -->
    <div class="card">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark-500">App information</h3>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleAppSection('app-info-section')">
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        
        <div id="app-info-section-content" class="space-y-6">
            <!-- Logo Upload -->
            <div>
                <label for="app_image" class="label">Logo upload</label>
                <div id="app-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
                    <input type="file" id="app_image" name="app_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" onchange="handleImagePreview(this, 'app-img-preview', 'app-upload-area')">
                    <label for="app_image" class="cursor-pointer">
                        <svg class="w-10 h-10 text-dark-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-dark-300 text-sm">Upload app icon</p>
                        <p class="text-xs text-dark-200 mt-1">JPG, PNG, GIF, WebP, SVG - Max 5MB</p>
                    </label>
                </div>
                <div id="app-img-preview" class="mt-4 hidden">
                    <div class="relative inline-block">
                        <img src="" alt="App icon preview" class="w-24 h-24 object-contain mx-auto rounded-lg">
                        <button type="button" onclick="removeImage('app_image', 'app-img-preview', 'app-upload-area')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- App icon size (logo): 60px–50% of section height, default 95px -->
            <div class="rounded-xl border border-dark-200 bg-dark-50/50 p-4">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <label for="app_icon_size" class="text-sm font-semibold text-dark-500">App icon size</label>
                    <span id="app_icon_size_value" class="inline-flex items-center justify-center min-w-[3.5rem] px-2.5 py-1 rounded-lg text-sm font-semibold bg-primary-500/15 text-primary-600">95px</span>
                </div>
                <div id="app_icon_size_track" class="relative h-3 rounded-full bg-dark-200 overflow-visible" style="--p: 51.47%;">
                    <div class="absolute inset-y-0 left-0 rounded-full bg-primary-500/30 transition-all duration-150 pointer-events-none" style="width: var(--p);"></div>
                    <input type="range" id="app_icon_size" name="app_icon_size" min="60" max="128" value="95" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="absolute top-1/2 -translate-y-1/2 left-[var(--p)] -translate-x-1/2 w-5 h-5 rounded-full bg-white border-2 border-primary-500 shadow-md pointer-events-none z-20 transition-all duration-150"></div>
                </div>
            </div>

            <!-- App Name -->
            <div>
                <label for="app_name" class="label">App name</label>
                <input type="text" id="app_name" name="app_name" class="input" placeholder="My Awesome App">
            </div>

            <!-- Description -->
            <div>
                <label for="app_description" class="label">Description</label>
                <textarea id="app_description" name="app_description" rows="4" class="input" placeholder="Enter app description"></textarea>
            </div>

            <!-- Available languages -->
            <div id="app_languages_wrapper" class="rounded-xl border border-dark-200 bg-dark-50/30 p-3">
                <label class="text-xs font-semibold text-dark-500 mb-2 block">Available languages</label>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['en' => 'EN', 'de' => 'DE', 'hr' => 'HR', 'fr' => 'FR', 'es' => 'ES', 'it' => 'IT', 'nl' => 'NL', 'pt' => 'PT', 'pl' => 'PL', 'tr' => 'TR', 'ru' => 'RU', 'ja' => 'JA', 'zh' => 'ZH'] as $code => $short)
                    <label class="inline-flex items-center gap-1 px-2 py-1 rounded-md border border-dark-200 bg-white cursor-pointer transition-all duration-150 hover:border-primary-400 hover:bg-primary-50/50 has-[:checked]:border-primary-500 has-[:checked]:bg-primary-500/15 has-[:checked]:text-primary-700">
                        <input type="checkbox" name="app_languages[]" value="{{ $code }}" class="sr-only">
                        <span class="text-[11px] font-medium text-dark-600">{{ $short }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Rating and Reviews -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="app_rating" class="label">Rating</label>
                    <input type="number" id="app_rating" name="app_rating" step="0.1" min="0" max="5" class="input" placeholder="4.8" oninput="if(this.value > 5) this.value = 5; if(this.value < 0) this.value = 0;">
                    <p class="text-xs text-dark-300 mt-1">Rating from 0.0 to 5.0</p>
                </div>
                <div>
                    <label for="app_review_count" class="label">Review Count</label>
                    <input type="text" id="app_review_count" name="app_review_count" class="input" placeholder="1.2k">
                    <p class="text-xs text-dark-300 mt-1">e.g. 1200 or 1.2k</p>
                </div>
            </div>

            <!-- App name font size -->
            <div class="rounded-xl border border-dark-200 bg-dark-50/50 p-4">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <label for="app_text_font_size" class="text-sm font-semibold text-dark-500">App name font size</label>
                    <span id="app_text_font_size_value" class="inline-flex items-center justify-center min-w-[3.5rem] px-2.5 py-1 rounded-lg text-sm font-semibold bg-primary-500/15 text-primary-600">16px</span>
                </div>
                <div id="app_text_font_size_track" class="relative h-3 rounded-full bg-dark-200 overflow-visible" style="--p: 33.33%;">
                    <div class="absolute inset-y-0 left-0 rounded-full bg-primary-500/30 transition-all duration-150 pointer-events-none" style="width: var(--p);"></div>
                    <input type="range" id="app_text_font_size" name="app_text_font_size" min="12" max="24" value="16" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="absolute top-1/2 -translate-y-1/2 left-[var(--p)] -translate-x-1/2 w-5 h-5 rounded-full bg-white border-2 border-primary-500 shadow-md pointer-events-none z-20 transition-all duration-150"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- App Store Platform Links Section -->
    <div class="card">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark-500">App store platform links<span class="text-red-500">*</span></h3>
                    <p class="text-sm text-dark-300 mt-1">Choose at least one store below and add a link to your app</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleAppSection('app-store-section')">
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        
        <div id="app-store-section-content" class="space-y-4">
            <!-- App Store Link -->
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 mt-1 w-12">
                    <img src="{{ asset('apps-icons/appstore.svg') }}" alt="App Store" class="w-12 h-12">
                </div>
                <div class="flex-1 max-w-md">
                    <label for="app_store_link" class="text-sm font-medium text-dark-500 mb-1 block">App Store</label>
                    <div class="flex items-center gap-2">
                        <input type="url" id="app_store_link" name="app_store_link" class="input flex-1" placeholder="e.g. https://apps.apple.com/my-app">
                        <button type="button" onclick="clearAppStoreLink()" class="p-2 text-dark-300 hover:text-red-500 transition-colors">
                            <img src="{{ asset('bin.svg') }}" alt="Delete" class="w-5 h-5">
                        </button>
                    </div>
                </div>
            </div>

            <!-- Google Play Store Link -->
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 mt-1 w-12">
                    <img src="{{ asset('apps-icons/googleplay.svg') }}" alt="Google Play" class="w-12 h-12">
                </div>
                <div class="flex-1 max-w-md">
                    <label for="play_store_link" class="text-sm font-medium text-dark-500 mb-1 block">Google Play</label>
                    <div class="flex items-center gap-2">
                        <input type="url" id="play_store_link" name="play_store_link" class="input flex-1" placeholder="e.g. https://play.google.com/my-app">
                        <button type="button" onclick="clearPlayStoreLink()" class="p-2 text-dark-300 hover:text-red-500 transition-colors">
                            <img src="{{ asset('bin.svg') }}" alt="Delete" class="w-5 h-5">
                        </button>
                    </div>
                </div>
            </div>

            <!-- Store button colors (default from color scheme) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-dark-200">
                <div>
                    <label for="app_store_button_color_hex" class="text-sm font-bold text-dark-500 mb-2 block">Button color</label>
                    <div class="flex items-center gap-3">
                        <input type="text" id="app_store_button_color_hex" name="app_store_button_color" value="#6594FF" class="input flex-1" placeholder="#6594FF">
                        <input type="color" id="app_store_button_color_picker" value="#6594FF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label for="app_store_button_text_color_hex" class="text-sm font-bold text-dark-500 mb-2 block">Button text color</label>
                    <div class="flex items-center gap-3">
                        <input type="text" id="app_store_button_text_color_hex" name="app_store_button_text_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                        <input type="color" id="app_store_button_text_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Website URL (kept for backward compatibility) -->
    <div>
        <label for="website_url" class="label">Website URL</label>
        <input type="url" id="website_url" name="website_url" class="input" placeholder="https://myapp.com">
    </div>
</div>

<script>
function handleImagePreview(input, previewId, uploadAreaId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const uploadArea = document.getElementById(uploadAreaId);
            
            // Hide upload area
            if (uploadArea) {
                uploadArea.classList.add('hidden');
            }
            
            // Show preview
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
                preview.parentElement.classList.remove('hidden');
            } else {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
            }
            
            // Update Step 1 preview if on step 1
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        };
        reader.readAsDataURL(file);
    }
}

function removeImage(inputId, previewId, uploadAreaId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const uploadArea = document.getElementById(uploadAreaId);
    
    // Clear file input
    if (input) {
        input.value = '';
    }
    
    // Hide preview
    if (preview) {
        preview.classList.add('hidden');
    }
    
    // Show upload area
    if (uploadArea) {
        uploadArea.classList.remove('hidden');
    }
    
    // Update Step 1 preview if on step 1
    if (typeof updateStep1Preview === 'function') {
        updateStep1Preview();
    }
}

function toggleAppSection(sectionId) {
    const content = document.getElementById(sectionId + '-content');
    const button = event.target.closest('button');
    const icon = button.querySelector('svg');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}

function clearAppStoreLink() {
    document.getElementById('app_store_link').value = '';
}

function clearPlayStoreLink() {
    document.getElementById('play_store_link').value = '';
}

// Design and Customize Section - Color preset handlers for App
document.addEventListener('DOMContentLoaded', function() {
    // App Color Presets
    document.querySelectorAll('.app-color-preset').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const primary = btn.dataset.primary;
            const secondary = btn.dataset.secondary;
            
            // Update inputs
            const primaryInput = document.getElementById('app_primary_color_hex');
            const secondaryInput = document.getElementById('app_secondary_color_hex');
            const primaryColorPicker = document.getElementById('app_primary_color_picker');
            const secondaryColorPicker = document.getElementById('app_secondary_color_picker');
            
            if (primaryInput) primaryInput.value = primary;
            if (secondaryInput) secondaryInput.value = secondary;
            if (primaryColorPicker) primaryColorPicker.value = primary;
            if (secondaryColorPicker) secondaryColorPicker.value = secondary;
            
            // Sync store button colors to scheme (primary = button color, secondary = button text color)
            const storeButtonColorHex = document.getElementById('app_store_button_color_hex');
            const storeButtonColorPicker = document.getElementById('app_store_button_color_picker');
            const storeButtonTextColorHex = document.getElementById('app_store_button_text_color_hex');
            const storeButtonTextColorPicker = document.getElementById('app_store_button_text_color_picker');
            if (storeButtonColorHex) storeButtonColorHex.value = primary;
            if (storeButtonColorPicker) storeButtonColorPicker.value = primary;
            if (storeButtonTextColorHex) storeButtonTextColorHex.value = secondary;
            if (storeButtonTextColorPicker) storeButtonTextColorPicker.value = secondary;
            
            // Update active state
            document.querySelectorAll('.app-color-preset').forEach(b => {
                b.classList.remove('border-primary-500');
                b.classList.add('border-dark-200');
            });
            btn.classList.remove('border-dark-200');
            btn.classList.add('border-primary-500');
            
            // Update preview
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    });
    
    // Color input handlers
    const primaryInput = document.getElementById('app_primary_color_hex');
    const secondaryInput = document.getElementById('app_secondary_color_hex');
    const primaryColorPicker = document.getElementById('app_primary_color_picker');
    const secondaryColorPicker = document.getElementById('app_secondary_color_picker');
    
    // Primary color picker handler
    if (primaryColorPicker && primaryInput) {
        primaryColorPicker.addEventListener('input', function() {
            const color = this.value.toUpperCase();
            primaryInput.value = color;
            // Update active preset
            document.querySelectorAll('.app-color-preset').forEach(btn => {
                if (btn.dataset.primary === color && btn.dataset.secondary === secondaryInput.value) {
                    btn.classList.remove('border-dark-200');
                    btn.classList.add('border-primary-500');
                } else {
                    btn.classList.remove('border-primary-500');
                    btn.classList.add('border-dark-200');
                }
            });
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    // Secondary color picker handler
    if (secondaryColorPicker && secondaryInput) {
        secondaryColorPicker.addEventListener('input', function() {
            const color = this.value.toUpperCase();
            secondaryInput.value = color;
            // Update active preset
            document.querySelectorAll('.app-color-preset').forEach(btn => {
                if (btn.dataset.primary === primaryInput.value && btn.dataset.secondary === color) {
                    btn.classList.remove('border-dark-200');
                    btn.classList.add('border-primary-500');
                } else {
                    btn.classList.remove('border-primary-500');
                    btn.classList.add('border-dark-200');
                }
            });
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    if (primaryInput && primaryColorPicker) {
        primaryInput.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                primaryColorPicker.value = color;
                // Update active preset
                document.querySelectorAll('.app-color-preset').forEach(btn => {
                    if (btn.dataset.primary === color && btn.dataset.secondary === secondaryInput.value) {
                        btn.classList.remove('border-dark-200');
                        btn.classList.add('border-primary-500');
                    } else {
                        btn.classList.remove('border-primary-500');
                        btn.classList.add('border-dark-200');
                    }
                });
                if (typeof updateStep1Preview === 'function') {
                    updateStep1Preview();
                }
            }
        });
    }
    
    if (secondaryInput && secondaryColorPicker) {
        secondaryInput.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                secondaryColorPicker.value = color;
                // Update active preset
                document.querySelectorAll('.app-color-preset').forEach(btn => {
                    if (btn.dataset.primary === primaryInput.value && btn.dataset.secondary === color) {
                        btn.classList.remove('border-dark-200');
                        btn.classList.add('border-primary-500');
                    } else {
                        btn.classList.remove('border-primary-500');
                        btn.classList.add('border-dark-200');
                    }
                });
                if (typeof updateStep1Preview === 'function') {
                    updateStep1Preview();
                }
            }
        });
    }
    
    // Swap colors button
    const swapButton = document.getElementById('app-swap-colors');
    if (swapButton && primaryInput && secondaryInput && primaryColorPicker && secondaryColorPicker) {
        swapButton.addEventListener('click', function() {
            const tempPrimary = primaryInput.value;
            const tempSecondary = secondaryInput.value;
            
            primaryInput.value = tempSecondary;
            secondaryInput.value = tempPrimary;
            primaryColorPicker.value = tempSecondary;
            secondaryColorPicker.value = tempPrimary;
            
            // Update active preset
            document.querySelectorAll('.app-color-preset').forEach(btn => {
                if (btn.dataset.primary === tempSecondary && btn.dataset.secondary === tempPrimary) {
                    btn.classList.remove('border-dark-200');
                    btn.classList.add('border-primary-500');
                } else {
                    btn.classList.remove('border-primary-500');
                    btn.classList.add('border-dark-200');
                }
            });
            
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    // Font family handler
    const fontFamilySelect = document.getElementById('app_font_family');
    if (fontFamilySelect) {
        fontFamilySelect.addEventListener('change', function() {
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    // Text color handlers
    const textColorInput = document.getElementById('app_text_color_hex');
    const textColorPicker = document.getElementById('app_text_color_picker');
    
    if (textColorPicker && textColorInput) {
        textColorPicker.addEventListener('input', function() {
            textColorInput.value = this.value.toUpperCase();
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    if (textColorInput && textColorPicker) {
        textColorInput.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                textColorPicker.value = color;
                if (typeof updateStep1Preview === 'function') {
                    updateStep1Preview();
                }
            }
        });
    }

    // App name font size slider – update value label and track fill
    const appTextFontSizeInput = document.getElementById('app_text_font_size');
    const appTextFontSizeValue = document.getElementById('app_text_font_size_value');
    const appTextFontSizeTrack = document.getElementById('app_text_font_size_track');
    function updateAppTextFontSizeDisplay() {
        if (!appTextFontSizeInput) return;
        const v = parseInt(appTextFontSizeInput.value, 10);
        const min = 12, max = 24;
        const pct = ((v - min) / (max - min)) * 100;
        if (appTextFontSizeValue) appTextFontSizeValue.textContent = v + 'px';
        if (appTextFontSizeTrack) appTextFontSizeTrack.style.setProperty('--p', pct + '%');
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }
    if (appTextFontSizeInput) {
        appTextFontSizeInput.addEventListener('input', updateAppTextFontSizeDisplay);
        updateAppTextFontSizeDisplay(); // initial fill and label
    }

    // App icon size slider – update value label and track fill
    const appIconSizeInput = document.getElementById('app_icon_size');
    const appIconSizeValue = document.getElementById('app_icon_size_value');
    const appIconSizeTrack = document.getElementById('app_icon_size_track');
    function updateAppIconSizeDisplay() {
        if (!appIconSizeInput) return;
        const v = parseInt(appIconSizeInput.value, 10);
        const min = 60, max = 128;
        const pct = ((v - min) / (max - min)) * 100;
        if (appIconSizeValue) appIconSizeValue.textContent = v + 'px';
        if (appIconSizeTrack) appIconSizeTrack.style.setProperty('--p', pct + '%');
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }
    if (appIconSizeInput) {
        appIconSizeInput.addEventListener('input', updateAppIconSizeDisplay);
        updateAppIconSizeDisplay(); // initial fill and label
    }

    // Rating and review count handlers
    const appRatingInput = document.getElementById('app_rating');
    if (appRatingInput) {
        appRatingInput.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }

    const appReviewCountInput = document.getElementById('app_review_count');
    if (appReviewCountInput) {
        appReviewCountInput.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }

    // Store button color (App store section)
    const storeButtonColorHex = document.getElementById('app_store_button_color_hex');
    const storeButtonColorPicker = document.getElementById('app_store_button_color_picker');
    if (storeButtonColorPicker && storeButtonColorHex) {
        storeButtonColorPicker.addEventListener('input', function() {
            storeButtonColorHex.value = this.value.toUpperCase();
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }
    if (storeButtonColorHex && storeButtonColorPicker) {
        storeButtonColorHex.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                storeButtonColorPicker.value = color;
                if (typeof updateStep1Preview === 'function') updateStep1Preview();
            }
        });
    }

    // Store button text color (App store section)
    const storeButtonTextColorHex = document.getElementById('app_store_button_text_color_hex');
    const storeButtonTextColorPicker = document.getElementById('app_store_button_text_color_picker');
    if (storeButtonTextColorPicker && storeButtonTextColorHex) {
        storeButtonTextColorPicker.addEventListener('input', function() {
            storeButtonTextColorHex.value = this.value.toUpperCase();
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }
    if (storeButtonTextColorHex && storeButtonTextColorPicker) {
        storeButtonTextColorHex.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                storeButtonTextColorPicker.value = color;
                if (typeof updateStep1Preview === 'function') updateStep1Preview();
            }
        });
    }
});
</script>

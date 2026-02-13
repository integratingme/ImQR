<!-- Event QR Code Form -->
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
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleEventSection('design-section')">
                <svg class="w-5 h-5 transition-transform" id="design-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>

        <div id="design-section-content">
            <!-- Color Palettes -->
            <div class="mb-6">
                <div class="grid grid-cols-2 md:flex gap-3 mb-4">
                    <button type="button" class="event-color-preset border-2 border-primary-500 rounded-lg p-2 hover:border-primary-600 transition-colors" data-primary="#6594FF" data-secondary="#FFFFFF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #6594FF;"></div>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                        </div>
                    </button>
                    <button type="button" class="event-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E5E7EB" data-secondary="#FFDAD8">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #E5E7EB;"></div>
                            <div class="w-8 h-8 rounded" style="background-color: #FFDAD8;"></div>
                        </div>
                    </button>
                    <button type="button" class="event-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E9D5FF" data-secondary="#FFFFFF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #E9D5FF;"></div>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                        </div>
                    </button>
                    <button type="button" class="event-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#FFD1DC" data-secondary="#B5E5CF">
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
                    <label for="event_primary_color" class="text-sm font-bold text-dark-500">Primary color</label>
                    <label for="event_secondary_color" class="text-sm font-bold text-dark-500">Secondary color</label>
                </div>
                
                <!-- Inputs Row -->
                <div class="grid grid-cols-2 gap-4 relative">
                    <!-- Primary Color Input -->
                    <div class="flex items-center gap-3 relative">
                        <input type="text" id="event_primary_color_hex" name="event_primary_color" value="#6594FF" class="input flex-1" placeholder="#6594FF">
                        <div class="relative">
                            <input type="color" id="event_primary_color_picker" value="#6594FF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                            <button type="button" id="event-swap-colors" class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-dark-300 hover:text-dark-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Secondary Color Input -->
                    <div class="flex items-center gap-3">
                        <input type="text" id="event_secondary_color_hex" name="event_secondary_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                        <input type="color" id="event_secondary_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="event_font_family" class="text-sm font-bold text-dark-500 mb-2 block">Font family</label>
                <select id="event_font_family" name="event_font_family" class="input w-full">
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
        </div>
    </div>

    <div>
        <label for="event_image" class="label">Event Image (Optional)</label>
        <div id="event-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
            <input type="file" id="event_image" name="event_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" onchange="handleImagePreview(this, 'event-img-preview', 'event-upload-area')">
            <label for="event_image" class="cursor-pointer">
                <svg class="w-10 h-10 text-dark-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-dark-300 text-sm">Upload event image</p>
                <p class="text-xs text-dark-200 mt-1">JPG, PNG, GIF, WebP, SVG - Max 5MB</p>
            </label>
        </div>
        <div id="event-img-preview" class="mt-4 hidden">
            <div class="relative inline-block">
                <img src="" alt="Event preview" class="max-w-full h-48 object-contain mx-auto rounded-lg">
                <button type="button" onclick="removeImage('event_image', 'event-img-preview', 'event-upload-area')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="company_name" class="label">Company/Host Name</label>
            <input type="text" id="company_name" name="company_name" class="input" placeholder="ABC Company">
        </div>

        <div>
            <label for="event_name" class="label">Event Name *</label>
            <input type="text" id="event_name" name="event_name" class="input" placeholder="Annual Conference 2026" required>
        </div>
    </div>

    <div>
        <label for="description" class="label">Event Description</label>
        <textarea id="description" name="description" rows="3" class="input" placeholder="Brief description of the event"></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="date" class="label">Date</label>
            <input type="date" id="date" name="date" class="input">
        </div>

        <div>
            <label for="time" class="label">Time</label>
            <input type="time" id="time" name="time" class="input">
        </div>
    </div>

    <div>
        <label for="location" class="label">Location</label>
        <input type="text" id="location" name="location" class="input" placeholder="123 Event Center, City">
    </div>

    <div>
        <label class="label">Available Amenities</label>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="parking" class="rounded text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-dark-400">Parking</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="wifi" class="rounded text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-dark-400">WiFi</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="food" class="rounded text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-dark-400">Food</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="drinks" class="rounded text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-dark-400">Drinks</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="wheelchair" class="rounded text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-dark-400">Wheelchair Access</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="amenities[]" value="ac" class="rounded text-primary-600 focus:ring-primary-500">
                <span class="text-sm text-dark-400">Air Conditioning</span>
            </label>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="dress_code_color" class="label">Dress Code Color</label>
            <div class="flex items-center gap-3">
                <input type="color" id="dress_code_color" name="dress_code_color" class="h-10 w-20 rounded border border-gray-300 cursor-pointer" value="#000000">
                <input type="text" id="dress_code_color_hex" class="input flex-1" placeholder="#000000" value="#000000">
            </div>
        </div>

        <div>
            <label for="contact" class="label">Contact Information</label>
            <input type="text" id="contact" name="contact" class="input" placeholder="Phone or email">
        </div>
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
}

// Toggle design section
function toggleEventSection(sectionId) {
    const content = document.getElementById(sectionId + '-content');
    const icon = document.getElementById(sectionId + '-icon');
    if (content && icon) {
        const isHidden = content.classList.contains('hidden');
        if (isHidden) {
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
}

// Sync color picker with hex input
document.addEventListener('DOMContentLoaded', function() {
    // Dress code color picker sync
    const colorPicker = document.getElementById('dress_code_color');
    const hexInput = document.getElementById('dress_code_color_hex');
    
    if (colorPicker && hexInput) {
        // Update hex input when color picker changes
        colorPicker.addEventListener('input', function(e) {
            hexInput.value = e.target.value.toUpperCase();
            if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                updateStep1Preview();
            }
        });
        
        // Update color picker when hex input changes (if valid hex)
        hexInput.addEventListener('input', function(e) {
            const value = e.target.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                colorPicker.value = value.toUpperCase();
                if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                    updateStep1Preview();
                }
            }
        });
        
        // Initialize hex input with color picker value
        hexInput.value = colorPicker.value.toUpperCase();
    }

    // Event color presets
    document.querySelectorAll('.event-color-preset').forEach(btn => {
        btn.addEventListener('click', function() {
            const primary = this.dataset.primary;
            const secondary = this.dataset.secondary;
            
            const primaryHex = document.getElementById('event_primary_color_hex');
            const primaryPicker = document.getElementById('event_primary_color_picker');
            const secondaryHex = document.getElementById('event_secondary_color_hex');
            const secondaryPicker = document.getElementById('event_secondary_color_picker');
            
            if (primaryHex) primaryHex.value = primary;
            if (primaryPicker) primaryPicker.value = primary;
            if (secondaryHex) secondaryHex.value = secondary;
            if (secondaryPicker) secondaryPicker.value = secondary;
            
            // Update active state
            document.querySelectorAll('.event-color-preset').forEach(b => {
                b.classList.remove('border-primary-500', 'border-primary-600');
                b.classList.add('border-dark-200');
            });
            this.classList.remove('border-dark-200');
            this.classList.add('border-primary-500', 'border-primary-600');
            
            if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                updateStep1Preview();
            }
        });
    });

    // Event color picker sync
    const eventPrimaryPicker = document.getElementById('event_primary_color_picker');
    const eventPrimaryHex = document.getElementById('event_primary_color_hex');
    const eventSecondaryPicker = document.getElementById('event_secondary_color_picker');
    const eventSecondaryHex = document.getElementById('event_secondary_color_hex');

    if (eventPrimaryPicker && eventPrimaryHex) {
        eventPrimaryPicker.addEventListener('input', function() {
            eventPrimaryHex.value = this.value.toUpperCase();
            if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                updateStep1Preview();
            }
        });
        eventPrimaryHex.addEventListener('input', function() {
            const value = this.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                eventPrimaryPicker.value = value.toUpperCase();
                if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                    updateStep1Preview();
                }
            }
        });
    }

    if (eventSecondaryPicker && eventSecondaryHex) {
        eventSecondaryPicker.addEventListener('input', function() {
            eventSecondaryHex.value = this.value.toUpperCase();
            if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                updateStep1Preview();
            }
        });
        eventSecondaryHex.addEventListener('input', function() {
            const value = this.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(value)) {
                eventSecondaryPicker.value = value.toUpperCase();
                if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                    updateStep1Preview();
                }
            }
        });
    }

    // Swap colors button
    const swapColorsBtn = document.getElementById('event-swap-colors');
    if (swapColorsBtn && eventPrimaryHex && eventSecondaryHex && eventPrimaryPicker && eventSecondaryPicker) {
        swapColorsBtn.addEventListener('click', function() {
            const tempPrimary = eventPrimaryHex.value;
            const tempSecondary = eventSecondaryHex.value;
            
            eventPrimaryHex.value = tempSecondary;
            eventPrimaryPicker.value = tempSecondary;
            eventSecondaryHex.value = tempPrimary;
            eventSecondaryPicker.value = tempPrimary;
            
            if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                updateStep1Preview();
            }
        });
    }

    // Font family handler
    const eventFontFamily = document.getElementById('event_font_family');
    if (eventFontFamily) {
        eventFontFamily.addEventListener('change', function() {
            if (typeof updateStep1Preview === 'function' && currentStep === 1) {
                updateStep1Preview();
            }
        });
    }
});
</script>

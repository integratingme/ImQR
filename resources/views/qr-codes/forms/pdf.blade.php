<!-- PDF QR Code Form -->
<!-- Design and Customize Section -->
<div class="mb-8">
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
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleSection('design-section')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        
        <div id="design-section-content">
            <!-- Color Palettes -->
            <div class="mb-6">
                <div class="grid grid-cols-2 md:flex gap-3 mb-4">
                    <button type="button" class="pdf-color-preset border-2 border-primary-500 rounded-lg p-2 hover:border-primary-600 transition-colors" data-primary="#6594FF" data-secondary="#FFFFFF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #6594FF;"></div>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                        </div>
                    </button>
                    <button type="button" class="pdf-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E5E7EB" data-secondary="#000000">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #E5E7EB;"></div>
                            <div class="w-8 h-8 rounded" style="background-color: #000000;"></div>
                        </div>
                    </button>
                    <button type="button" class="pdf-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E9D5FF" data-secondary="#FFFFFF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #E9D5FF;"></div>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                        </div>
                    </button>
                    <button type="button" class="pdf-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#FFD1DC" data-secondary="#B5E5CF">
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
                    <label for="pdf_primary_color" class="text-sm font-bold text-dark-500">Primary color</label>
                    <label for="pdf_secondary_color" class="text-sm font-bold text-dark-500">Secondary color</label>
                </div>
                
                <!-- Inputs Row -->
                <div class="grid grid-cols-2 gap-4 relative">
                    <!-- Primary Color Input -->
                    <div class="flex items-center gap-3 relative">
                        <input type="text" id="pdf_primary_color_hex" name="pdf_primary_color" value="#6594FF" class="input flex-1" placeholder="#6594FF">
                        <div class="relative">
                            <input type="color" id="pdf_primary_color_picker" value="#6594FF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                            <button type="button" id="pdf-swap-colors" class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-dark-300 hover:text-dark-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Secondary Color Input -->
                    <div class="flex items-center gap-3">
                        <input type="text" id="pdf_secondary_color_hex" name="pdf_secondary_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                        <input type="color" id="pdf_secondary_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>
            
            <!-- Button Customization -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="pdf_button_text" class="text-sm font-bold text-dark-500 mb-2 block">Button text</label>
                    <input type="text" id="pdf_button_text" name="pdf_button_text" value="Download PDF" class="input w-full" placeholder="Download PDF">
                </div>
                
                <div>
                    <label for="pdf_button_color" class="text-sm font-bold text-dark-500 mb-2 block">Button color</label>
                    <div class="flex items-center gap-3">
                        <input type="text" id="pdf_button_color_hex" name="pdf_button_color" value="#D6D6D6" class="input flex-1" placeholder="#D6D6D6">
                        <input type="color" id="pdf_button_color_picker" value="#D6D6D6" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>
            
            <!-- Font Selection -->
            <div class="mb-4">
                <label for="pdf_font_family" class="text-sm font-bold text-dark-500 mb-2 block">Font family</label>
                <select id="pdf_font_family" name="pdf_font_family" class="input w-full">
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
</div>

<!-- Document Information Section -->
<div class="mb-8">
    <div class="card">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">i</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark-500">Document information</h3>
                    <p class="text-sm text-dark-300 mt-1">Provide information about your PDF file</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleSection('document-section')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        
        <div id="document-section-content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label for="company_name" class="label">Company name</label>
                        <input type="text" id="company_name" name="company_name" class="input" placeholder="Company name">
                    </div>
                    <div>
                        <label for="file_description" class="label">File description</label>
                        <input type="text" id="file_description" name="file_description" class="input" placeholder="File description">
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label for="pdf_title" class="label">Title</label>
                        <input type="text" id="pdf_title" name="pdf_title" class="input" placeholder="Title">
                    </div>
                    <div>
                        <label for="pdf_website" class="label">Website</label>
                        <input type="text" id="pdf_website" name="pdf_website" class="input" placeholder="https://example.com" onblur="validateWebsite(this)">
                        <div id="pdf_website_error" class="hidden mt-1 text-sm text-red-600">You have entered an invalid link. Please try again.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PDF File Upload -->
<div class="mb-6">
    <label for="pdf_file" class="label">PDF File *</label>
    <div id="pdf-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-8 text-center hover:border-primary-400 transition-colors cursor-pointer">
        <input type="file" id="pdf_file" name="pdf_file" accept=".pdf,application/pdf" class="hidden" required onchange="handlePdfSelect(this)">
        <label for="pdf_file" class="cursor-pointer">
            <svg class="w-12 h-12 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <p class="text-dark-300 font-medium">Click to upload PDF</p>
            <p class="text-sm text-dark-200 mt-1">Maximum file size: 5MB</p>
        </label>
    </div>
    <div id="pdf-preview" class="mt-4 hidden">
        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-dark-500 truncate" id="pdf-filename"></p>
                    <p class="text-xs text-dark-200" id="pdf-filesize"></p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <button type="button" onclick="clearPdfFile()" class="ml-4 text-red-600 hover:text-red-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
function handlePdfSelect(input) {
    const file = input.files[0];
    if (file) {
        // Check if file is PDF
        if (file.type !== 'application/pdf') {
            alert('Please select a PDF file.');
            input.value = '';
            return;
        }
        
        // Check size (5MB = 5242880 bytes)
        if (file.size > 5242880) {
            alert('PDF file is too large. Maximum size is 5MB.');
            input.value = '';
            return;
        }
        
        document.getElementById('pdf-preview').classList.remove('hidden');
        document.getElementById('pdf-upload-area').classList.add('border-green-400', 'bg-green-50');
        document.getElementById('pdf-filename').textContent = file.name;
        document.getElementById('pdf-filesize').textContent = formatFileSize(file.size);
        
        // Update preview if on step 1
        if (typeof updateStep1Preview === 'function') {
            updateStep1Preview();
        }
    }
}

function clearPdfFile() {
    document.getElementById('pdf_file').value = '';
    document.getElementById('pdf-preview').classList.add('hidden');
    document.getElementById('pdf-upload-area').classList.remove('border-green-400', 'bg-green-50');
    
    // Update preview if on step 1
    if (typeof updateStep1Preview === 'function') {
        updateStep1Preview();
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function toggleSection(sectionId) {
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

function validateWebsite(input) {
    const website = input.value.trim();
    const errorDiv = document.getElementById('pdf_website_error');
    
    if (website === '') {
        input.classList.remove('border-red-500');
        errorDiv.classList.add('hidden');
        return true;
    }
    
    // Check if URL starts with https://
    if (!website.startsWith('https://')) {
        input.classList.add('border-red-500');
        errorDiv.classList.remove('hidden');
        errorDiv.textContent = 'Website URL must start with https://';
        return false;
    }
    
    try {
        const url = new URL(website);
        if (url.protocol === 'https:') {
            input.classList.remove('border-red-500');
            errorDiv.classList.add('hidden');
            return true;
        }
    } catch (e) {
        // Invalid URL
    }
    
    input.classList.add('border-red-500');
    errorDiv.classList.remove('hidden');
    errorDiv.textContent = 'You have entered an invalid link. Please try again.';
    return false;
}

// Color preset handlers for PDF
document.addEventListener('DOMContentLoaded', function() {
    // PDF Color Presets
    document.querySelectorAll('.pdf-color-preset').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const primary = btn.dataset.primary;
            const secondary = btn.dataset.secondary;
            
            // Update inputs
            const primaryInput = document.getElementById('pdf_primary_color_hex');
            const secondaryInput = document.getElementById('pdf_secondary_color_hex');
            const primaryColorPicker = document.getElementById('pdf_primary_color_picker');
            const secondaryColorPicker = document.getElementById('pdf_secondary_color_picker');
            
            if (primaryInput) primaryInput.value = primary;
            if (secondaryInput) secondaryInput.value = secondary;
            if (primaryColorPicker) primaryColorPicker.value = primary;
            if (secondaryColorPicker) secondaryColorPicker.value = secondary;
            
            // Update active state
            document.querySelectorAll('.pdf-color-preset').forEach(b => {
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
    const primaryInput = document.getElementById('pdf_primary_color_hex');
    const secondaryInput = document.getElementById('pdf_secondary_color_hex');
    const primaryColorPicker = document.getElementById('pdf_primary_color_picker');
    const secondaryColorPicker = document.getElementById('pdf_secondary_color_picker');
    
    // Primary color picker handler
    if (primaryColorPicker && primaryInput) {
        primaryColorPicker.addEventListener('input', function() {
            const color = this.value.toUpperCase();
            primaryInput.value = color;
            // Update active preset
            document.querySelectorAll('.pdf-color-preset').forEach(btn => {
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
            document.querySelectorAll('.pdf-color-preset').forEach(btn => {
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
                document.querySelectorAll('.pdf-color-preset').forEach(btn => {
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
                document.querySelectorAll('.pdf-color-preset').forEach(btn => {
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
    const swapButton = document.getElementById('pdf-swap-colors');
    if (swapButton && primaryInput && secondaryInput && primaryColorPicker && secondaryColorPicker) {
        swapButton.addEventListener('click', function() {
            const tempPrimary = primaryInput.value;
            const tempSecondary = secondaryInput.value;
            
            primaryInput.value = tempSecondary;
            secondaryInput.value = tempPrimary;
            primaryColorPicker.value = tempSecondary;
            secondaryColorPicker.value = tempPrimary;
            
            // Update active preset
            document.querySelectorAll('.pdf-color-preset').forEach(btn => {
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
    
    // Button color picker handler
    const buttonColorPicker = document.getElementById('pdf_button_color_picker');
    const buttonColorHex = document.getElementById('pdf_button_color_hex');
    const primaryColorInput = document.getElementById('pdf_primary_color_hex');
    
    // Initialize button color with #D6D6D6 if not set
    if (buttonColorHex && buttonColorPicker) {
        if (!buttonColorHex.value || buttonColorHex.value === '#FFFFFF' || buttonColorHex.value === '#6594FF') {
            buttonColorHex.value = '#D6D6D6';
            buttonColorPicker.value = '#D6D6D6';
        }
    }
    
    if (buttonColorPicker && buttonColorHex) {
        buttonColorPicker.addEventListener('input', function() {
            const color = this.value.toUpperCase();
            buttonColorHex.value = color;
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    if (buttonColorHex && buttonColorPicker) {
        buttonColorHex.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                buttonColorPicker.value = color;
                if (typeof updateStep1Preview === 'function') {
                    updateStep1Preview();
                }
            }
        });
    }
    
    // Button text handler
    const buttonText = document.getElementById('pdf_button_text');
    if (buttonText) {
        buttonText.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    // Font family handler
    const fontFamily = document.getElementById('pdf_font_family');
    if (fontFamily) {
        fontFamily.addEventListener('change', function() {
            // Load Google Font if needed
            const selectedFont = this.value;
            if (selectedFont !== 'Maven Pro') {
                loadGoogleFont(selectedFont);
            }
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
        // Load initial font if needed
        const initialFont = fontFamily.value;
        if (initialFont !== 'Maven Pro') {
            loadGoogleFont(initialFont);
        }
    }
    
    // Function to load Google Fonts
    function loadGoogleFont(fontName) {
        const fontId = fontName.replace(/\s+/g, '+');
        const linkId = 'google-font-' + fontId;
        
        // Check if font is already loaded
        if (document.getElementById(linkId)) {
            return;
        }
        
        const link = document.createElement('link');
        link.id = linkId;
        link.rel = 'stylesheet';
        link.href = `https://fonts.googleapis.com/css2?family=${fontId}:wght@400;500;600;700&display=swap`;
        document.head.appendChild(link);
    }
    
    // Add event listeners for PDF document fields
    const pdfFields = ['company_name', 'file_description', 'pdf_title', 'pdf_website'];
    pdfFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', () => {
                if (typeof updateStep1Preview === 'function') {
                    updateStep1Preview();
                }
            });
        }
    });
});
</script>

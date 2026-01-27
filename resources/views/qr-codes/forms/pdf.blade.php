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
                <div class="flex gap-3 mb-4">
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
                    <button type="button" class="pdf-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#D1FAE5" data-secondary="#000000">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #D1FAE5;"></div>
                            <div class="w-8 h-8 rounded" style="background-color: #000000;"></div>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Primary Color Input -->
            <div class="mb-4">
                <label for="pdf_primary_color" class="text-sm font-bold text-dark-500 mb-2 block">Primary color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="pdf_primary_color_hex" name="pdf_primary_color" value="#6594FF" class="input flex-1" placeholder="#6594FF">
                    <div class="w-10 h-10 rounded border border-gray-200" id="pdf_primary_color_swatch" style="background-color: #6594FF;"></div>
                </div>
            </div>
            
            <!-- Swap Button -->
            <div class="flex justify-center mb-4">
                <button type="button" id="pdf-swap-colors" class="text-dark-300 hover:text-dark-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Secondary Color Input -->
            <div class="mb-4">
                <label for="pdf_secondary_color" class="text-sm font-bold text-dark-500 mb-2 block">Secondary color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="pdf_secondary_color_hex" name="pdf_secondary_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                    <div class="w-10 h-10 rounded border border-gray-200" id="pdf_secondary_color_swatch" style="background-color: #FFFFFF;"></div>
                </div>
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
                        <input type="text" id="pdf_website" name="pdf_website" class="input" placeholder="Website" onblur="validateWebsite(this)">
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
            <p class="text-sm text-dark-200 mt-1">Maximum file size: 10MB</p>
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
        // Proveri da li je PDF
        if (file.type !== 'application/pdf') {
            alert('Molimo odaberite PDF fajl.');
            input.value = '';
            return;
        }
        
        // Proveri veličinu (10MB = 10485760 bytes)
        if (file.size > 10485760) {
            alert('PDF fajl je prevelik. Maksimalna veličina je 10MB.');
            input.value = '';
            return;
        }
        
        // Prikaži informacije o fajlu
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
    
    try {
        const url = new URL(website.startsWith('http') ? website : 'https://' + website);
        if (url.protocol === 'http:' || url.protocol === 'https:') {
            input.classList.remove('border-red-500');
            errorDiv.classList.add('hidden');
            return true;
        }
    } catch (e) {
        // Invalid URL
    }
    
    input.classList.add('border-red-500');
    errorDiv.classList.remove('hidden');
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
            const primarySwatch = document.getElementById('pdf_primary_color_swatch');
            const secondarySwatch = document.getElementById('pdf_secondary_color_swatch');
            
            if (primaryInput) primaryInput.value = primary;
            if (secondaryInput) secondaryInput.value = secondary;
            if (primarySwatch) {
                primarySwatch.style.backgroundColor = primary;
            }
            if (secondarySwatch) {
                secondarySwatch.style.backgroundColor = secondary;
            }
            
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
    const primarySwatch = document.getElementById('pdf_primary_color_swatch');
    const secondarySwatch = document.getElementById('pdf_secondary_color_swatch');
    
    if (primaryInput && primarySwatch) {
        primaryInput.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                primarySwatch.style.backgroundColor = color;
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
    
    if (secondaryInput && secondarySwatch) {
        secondaryInput.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                secondarySwatch.style.backgroundColor = color;
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
    if (swapButton && primaryInput && secondaryInput && primarySwatch && secondarySwatch) {
        swapButton.addEventListener('click', function() {
            const tempPrimary = primaryInput.value;
            const tempSecondary = secondaryInput.value;
            
            primaryInput.value = tempSecondary;
            secondaryInput.value = tempPrimary;
            primarySwatch.style.backgroundColor = tempSecondary;
            secondarySwatch.style.backgroundColor = tempPrimary;
            
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

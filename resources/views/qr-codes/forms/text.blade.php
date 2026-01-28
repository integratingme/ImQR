<!-- Text QR Code Form -->
<div class="mb-6">
    <label for="text" class="label">Text Content *</label>
    <textarea id="text" name="text" rows="6" class="input" placeholder="Enter your text here..." required maxlength="500"></textarea>
    <p class="text-sm text-dark-200 mt-1">Maximum 500 characters</p>
</div>

<!-- Text Customization Section -->
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
                    <p class="text-sm text-dark-300 mt-1">Customize text appearance</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleTextSection('text-design-section')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        
        <div id="text-design-section-content">
            <!-- Background Color Input -->
            <div class="mb-4">
                <label for="text_background_color" class="text-sm font-bold text-dark-500 mb-2 block">Background color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="text_background_color_hex" name="text_background_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                    <input type="color" id="text_background_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                </div>
            </div>
            
            <!-- Text Color Input -->
            <div class="mb-4">
                <label for="text_text_color" class="text-sm font-bold text-dark-500 mb-2 block">Text color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="text_text_color_hex" name="text_text_color" value="#000000" class="input flex-1" placeholder="#000000">
                    <input type="color" id="text_text_color_picker" value="#000000" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                </div>
            </div>
            
            <!-- Font Selection -->
            <div class="mb-4">
                <label for="text_font_family" class="text-sm font-bold text-dark-500 mb-2 block">Font family</label>
                <select id="text_font_family" name="text_font_family" class="input w-full">
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

<script>
function toggleTextSection(sectionId) {
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

// Text customization handlers
document.addEventListener('DOMContentLoaded', function() {
    // Background color picker handler
    const backgroundColorPicker = document.getElementById('text_background_color_picker');
    const backgroundColorHex = document.getElementById('text_background_color_hex');
    
    if (backgroundColorPicker && backgroundColorHex) {
        backgroundColorPicker.addEventListener('input', function() {
            const color = this.value.toUpperCase();
            backgroundColorHex.value = color;
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    if (backgroundColorHex && backgroundColorPicker) {
        backgroundColorHex.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                backgroundColorPicker.value = color;
                if (typeof updateStep1Preview === 'function') {
                    updateStep1Preview();
                }
            }
        });
    }
    
    // Text color picker handler
    const textColorPicker = document.getElementById('text_text_color_picker');
    const textColorHex = document.getElementById('text_text_color_hex');
    
    if (textColorPicker && textColorHex) {
        textColorPicker.addEventListener('input', function() {
            const color = this.value.toUpperCase();
            textColorHex.value = color;
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
    
    if (textColorHex && textColorPicker) {
        textColorHex.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                textColorPicker.value = color;
                if (typeof updateStep1Preview === 'function') {
                    updateStep1Preview();
                }
            }
        });
    }
    
    // Font family handler
    const fontFamily = document.getElementById('text_font_family');
    if (fontFamily) {
        fontFamily.addEventListener('change', function() {
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
    
    // Text content handler
    const textContent = document.getElementById('text');
    if (textContent) {
        textContent.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
            }
        });
    }
});
</script>


<!-- Phone QR Code Form -->
<div class="mb-6">
    <label for="full_name" class="label">Full Name</label>
    <input type="text" id="full_name" name="full_name" class="input" placeholder="John Doe" maxlength="255">
    <p class="text-sm text-dark-200 mt-1">Name to display above the phone number on the landing page</p>
</div>
<div class="mb-6">
    <label for="phone_number" class="label">Phone Number *</label>
    <input type="tel" id="phone_number" name="phone_number" class="input" placeholder="+1234567890" required maxlength="50">
    <p class="text-sm text-dark-200 mt-1">Enter phone number with country code (e.g., +381123456789)</p>
</div>

<!-- Phone customization: background color and font (used on landing page) -->
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
                    <h3 class="text-lg font-bold text-dark-500">Customization</h3>
                    <p class="text-sm text-dark-300 mt-1">Background and font for the landing page</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="togglePhoneDesignSection('phone-design-section')">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>

        <div id="phone-design-section-content">
            <div class="mb-4">
                <label for="phone_background_color" class="text-sm font-bold text-dark-500 mb-2 block">Background color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="phone_background_color_hex" name="phone_background_color" value="#2d3748" class="input flex-1" placeholder="#2d3748">
                    <input type="color" id="phone_background_color_picker" value="#2d3748" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                </div>
            </div>

            <div class="mb-4">
                <label for="phone_font_family" class="text-sm font-bold text-dark-500 mb-2 block">Font family</label>
                <select id="phone_font_family" name="phone_font_family" class="input w-full">
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
function togglePhoneDesignSection(sectionId) {
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

document.addEventListener('DOMContentLoaded', function() {
    const bgPicker = document.getElementById('phone_background_color_picker');
    const bgHex = document.getElementById('phone_background_color_hex');
    if (bgPicker && bgHex) {
        bgPicker.addEventListener('input', function() {
            bgHex.value = this.value.toUpperCase();
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
        bgHex.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                bgPicker.value = this.value;
                if (typeof updateStep1Preview === 'function') updateStep1Preview();
            }
        });
    }
    const phoneFont = document.getElementById('phone_font_family');
    if (phoneFont) {
        phoneFont.addEventListener('change', function() {
            const fontName = this.value;
            if (fontName !== 'Maven Pro') {
                const fontId = fontName.replace(/\s+/g, '+');
                const linkId = 'google-font-phone-' + fontId;
                if (!document.getElementById(linkId)) {
                    const link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = 'https://fonts.googleapis.com/css2?family=' + fontId + ':wght@400;500;600;700&display=swap';
                    document.head.appendChild(link);
                }
            }
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }
});
</script>

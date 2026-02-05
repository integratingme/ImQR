<!-- Coupon QR Code Form -->
<div class="space-y-6">
    <!-- 1. Design and Customize Section (like PDF / App) -->
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
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleCouponSection('design-section')">
                <svg class="w-5 h-5 transition-transform" id="design-section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>

        <div id="design-section-content">
            <div class="grid grid-cols-2 md:flex gap-3 mb-4">
                <button type="button" class="coupon-color-preset border-2 border-primary-500 rounded-lg p-2 hover:border-primary-600 transition-colors" data-primary="#6594FF" data-secondary="#FFFFFF">
                    <div class="flex gap-1">
                        <div class="w-8 h-8 rounded" style="background-color: #6594FF;"></div>
                        <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                    </div>
                </button>
                <button type="button" class="coupon-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E5E7EB" data-secondary="#000000">
                    <div class="flex gap-1">
                        <div class="w-8 h-8 rounded" style="background-color: #E5E7EB;"></div>
                        <div class="w-8 h-8 rounded" style="background-color: #000000;"></div>
                    </div>
                </button>
                <button type="button" class="coupon-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E9D5FF" data-secondary="#FFFFFF">
                    <div class="flex gap-1">
                        <div class="w-8 h-8 rounded" style="background-color: #E9D5FF;"></div>
                        <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                    </div>
                </button>
                <button type="button" class="coupon-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#FFD1DC" data-secondary="#B5E5CF">
                    <div class="flex gap-1">
                        <div class="w-8 h-8 rounded" style="background-color: #FFD1DC;"></div>
                        <div class="w-8 h-8 rounded" style="background-color: #B5E5CF;"></div>
                    </div>
                </button>
            </div>

            <div class="mb-4">
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <label for="coupon_primary_color" class="text-sm font-bold text-dark-500">Primary color</label>
                    <label for="coupon_secondary_color" class="text-sm font-bold text-dark-500">Secondary color</label>
                </div>
                <div class="grid grid-cols-2 gap-4 relative">
                    <div class="flex items-center gap-3 relative">
                        <input type="text" id="coupon_primary_color_hex" name="coupon_primary_color" value="#6594FF" class="input flex-1" placeholder="#6594FF">
                        <div class="relative">
                            <input type="color" id="coupon_primary_color_picker" value="#6594FF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                            <button type="button" id="coupon-swap-colors" class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-dark-300 hover:text-dark-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="text" id="coupon_secondary_color_hex" name="coupon_secondary_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                        <input type="color" id="coupon_secondary_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="coupon_font_family" class="text-sm font-bold text-dark-500 mb-2 block">Font family</label>
                <select id="coupon_font_family" name="coupon_font_family" class="input w-full">
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

    <!-- 2. Offer Information Section -->
    <div class="card">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <div class="w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">i</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark-500">Offer information</h3>
                    <p class="text-sm text-dark-300 mt-1">Presentation and details for your coupon</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleCouponSection('offer-section')">
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>

        <div id="offer-section-content" class="space-y-6">
            <!-- Presentation image for coupon -->
            <div>
                <label for="coupon_image" class="label">Presentation image for your coupon</label>
                <div id="coupon-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-8 text-center hover:border-primary-400 transition-colors">
                    <input type="file" id="coupon_image" name="coupon_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" onchange="handleCouponImagePreview(this)">
                    <label for="coupon_image" class="cursor-pointer">
                        <svg class="w-12 h-12 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-dark-300 font-medium">Click to upload presentation image</p>
                        <p class="text-sm text-dark-200 mt-1">JPG, PNG, GIF, WebP, SVG - Maximum 5MB</p>
                    </label>
                </div>
                <div id="coupon-img-preview" class="mt-4 hidden">
                    <div class="relative inline-block">
                        <img src="" alt="Coupon preview" class="max-w-full h-48 object-contain mx-auto rounded-lg">
                        <button type="button" onclick="removeImage('coupon_image', 'coupon-img-preview', 'coupon-upload-area')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label for="coupon_company" class="label">Company <span class="text-red-500">*</span></label>
                <input type="text" id="coupon_company" name="coupon_company" class="input" placeholder="Company name" required>
            </div>

            <div>
                <label for="coupon_title" class="label">Title <span class="text-red-500">*</span></label>
                <input type="text" id="coupon_title" name="coupon_title" class="input" placeholder="Coupon title" required>
            </div>

            <div>
                <label for="coupon_description" class="label">Description</label>
                <textarea id="coupon_description" name="coupon_description" rows="3" class="input" placeholder="e.g. Available on all products for a limited time only"></textarea>
            </div>

            <div>
                <label for="coupon_sales_badge" class="label">Sales badge <span class="text-red-500">*</span></label>
                <input type="text" id="coupon_sales_badge" name="coupon_sales_badge" class="input" placeholder="e.g. 25% off" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="coupon_sales_badge_color" class="label">Sales badge color</label>
                    <div class="flex items-center gap-3">
                        <input type="text" id="coupon_sales_badge_color_hex" name="coupon_sales_badge_color" value="#9FE2BF" class="input flex-1" placeholder="#9FE2BF">
                        <input type="color" id="coupon_sales_badge_color_picker" value="#9FE2BF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label for="coupon_sales_badge_text_color" class="label">Sales badge text color</label>
                    <div class="flex items-center gap-3">
                        <input type="text" id="coupon_sales_badge_text_color_hex" name="coupon_sales_badge_text_color" value="#1f2937" class="input flex-1" placeholder="#1f2937">
                        <input type="color" id="coupon_sales_badge_text_color_picker" value="#1f2937" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Coupon Section (barcode, valid until, button, view more) -->
    <div class="card">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark-500">Coupon</h3>
                    <p class="text-sm text-dark-300 mt-1">Barcode, validity and view more link</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleCouponSection('coupon-section')">
                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>

        <div id="coupon-section-content" class="space-y-6">
            <!-- Use barcode toggle -->
            <div class="flex items-center justify-between">
                <label for="coupon_use_barcode" class="label mb-0">Use barcode</label>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="coupon_use_barcode" name="coupon_use_barcode" value="1" class="sr-only peer" onchange="toggleCouponBarcodeUpload()">
                    <div class="w-11 h-6 bg-dark-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                    <span class="ml-3 text-sm font-medium text-dark-500">No / Yes</span>
                </label>
            </div>

            <!-- Barcode upload (shown when use barcode = yes) -->
            <div id="coupon-barcode-upload-wrap" class="hidden">
                <label for="coupon_barcode_image" class="label">Upload barcode <span class="text-red-500">*</span></label>
                <div id="coupon-barcode-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
                    <input type="file" id="coupon_barcode_image" name="coupon_barcode_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" onchange="handleImagePreview(this, 'coupon-barcode-preview', 'coupon-barcode-upload-area', function() { if (typeof updateStep1Preview === 'function') updateStep1Preview(); });">
                    <label for="coupon_barcode_image" class="cursor-pointer">
                        <svg class="w-10 h-10 text-dark-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-dark-300 text-sm font-medium">Upload barcode image</p>
                        <p class="text-xs text-dark-200 mt-1">JPG, PNG, GIF, WebP, SVG - Max 2MB</p>
                    </label>
                </div>
                <div id="coupon-barcode-preview" class="mt-4 hidden">
                    <div class="relative inline-block">
                        <img src="" alt="Barcode preview" class="max-w-full h-24 object-contain mx-auto rounded-lg">
                        <button type="button" onclick="removeImage('coupon_barcode_image', 'coupon-barcode-preview', 'coupon-barcode-upload-area')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label for="coupon_valid_until" class="label">Valid until <span class="text-red-500">*</span></label>
                <input type="date" id="coupon_valid_until" name="coupon_valid_until" class="input" required>
            </div>

            <div>
                <label for="coupon_code_button_text" class="label">Button to see the code</label>
                <input type="text" id="coupon_code_button_text" name="coupon_code_button_text" class="input" value="Get code" placeholder="e.g. Get code">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="coupon_button_color" class="label">Button color</label>
                    <div class="flex items-center gap-3">
                        <input type="text" id="coupon_button_color_hex" name="coupon_button_color" value="#D6D6D6" class="input flex-1" placeholder="#D6D6D6">
                        <input type="color" id="coupon_button_color_picker" value="#D6D6D6" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label for="coupon_button_text_color" class="label">Button text color</label>
                    <div class="flex items-center gap-3">
                        <input type="text" id="coupon_button_text_color_hex" name="coupon_button_text_color" value="#1f2937" class="input flex-1" placeholder="#1f2937">
                        <input type="color" id="coupon_button_text_color_picker" value="#1f2937" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>

            <div>
                <label for="coupon_view_more_website" class="label">Website (where Get coupon button leads) <span class="text-red-500">*</span></label>
                <input type="url" id="coupon_view_more_website" name="coupon_view_more_website" class="input" placeholder="https://example.com" onblur="validateCouponWebsite(this)">
                <div class="text-xs text-dark-300 mt-1">Required if you do not use a barcode.</div>
                <div id="coupon_view_more_website_error" class="hidden mt-1 text-sm text-red-600">Website URL must start with https://</div>
            </div>
        </div>
    </div>

    <!-- Optional logo (kept for backward compatibility) -->
    <div>
        <label for="logo" class="label">Logo (Optional)</label>
        <div id="logo-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
            <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden" onchange="handleImagePreview(this, 'logo-img-preview', 'logo-upload-area', function() { if (typeof updateStep1Preview === 'function') updateStep1Preview(); });">
            <label for="logo" class="cursor-pointer">
                <svg class="w-10 h-10 text-dark-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-dark-300 text-sm font-medium">Upload logo</p>
                <p class="text-xs text-dark-200 mt-1">JPG, PNG, GIF, WebP, SVG - Max 2MB</p>
            </label>
        </div>
        <div id="logo-img-preview" class="mt-4 hidden">
            <div class="relative inline-block">
                <img src="" alt="Logo preview" class="max-w-full h-24 object-contain mx-auto rounded-lg">
                <button type="button" onclick="removeImage('logo', 'logo-img-preview', 'logo-upload-area')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCouponSection(sectionId) {
    const content = document.getElementById(sectionId + '-content');
    const button = event.target.closest('button');
    const icon = button ? button.querySelector('svg') : null;
    if (!content) return;
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
    }
}

function toggleCouponBarcodeUpload() {
    const useBarcode = document.getElementById('coupon_use_barcode');
    const wrap = document.getElementById('coupon-barcode-upload-wrap');
    if (!wrap) return;
    if (useBarcode && useBarcode.checked) {
        wrap.classList.remove('hidden');
    } else {
        wrap.classList.add('hidden');
    }
    if (typeof updateStep1Preview === 'function') updateStep1Preview();
}

function handleCouponImagePreview(input) {
    handleImagePreview(input, 'coupon-img-preview', 'coupon-upload-area', function() {
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    });
}

function handleImagePreview(input, previewId, uploadAreaId, onLoaded) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const uploadArea = document.getElementById(uploadAreaId);
            if (uploadArea) uploadArea.classList.add('hidden');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
                preview.parentElement.classList.remove('hidden');
            } else {
                const img = preview.querySelector('img');
                if (img) img.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (typeof onLoaded === 'function') onLoaded();
        };
        reader.readAsDataURL(file);
    }
}

function removeImage(inputId, previewId, uploadAreaId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const uploadArea = document.getElementById(uploadAreaId);
    if (input) input.value = '';
    if (preview) preview.classList.add('hidden');
    if (uploadArea) uploadArea.classList.remove('hidden');
    if (typeof updateStep1Preview === 'function') updateStep1Preview();
}

// Coupon color presets and sync
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.coupon-color-preset').forEach(btn => {
        btn.addEventListener('click', function() {
            const primary = this.dataset.primary;
            const secondary = this.dataset.secondary;
            const primaryHex = document.getElementById('coupon_primary_color_hex');
            const primaryPicker = document.getElementById('coupon_primary_color_picker');
            const secondaryHex = document.getElementById('coupon_secondary_color_hex');
            const secondaryPicker = document.getElementById('coupon_secondary_color_picker');
            if (primaryHex) { primaryHex.value = primary; }
            if (primaryPicker) { primaryPicker.value = primary; }
            if (secondaryHex) { secondaryHex.value = secondary; }
            if (secondaryPicker) { secondaryPicker.value = secondary; }
            document.querySelectorAll('.coupon-color-preset').forEach(b => { b.classList.remove('border-primary-500'); b.classList.add('border-dark-200'); });
            this.classList.add('border-primary-500');
            this.classList.remove('border-dark-200');
            // Update mockup background when color scheme (preset) is selected
            if (typeof updateStep1Preview === 'function') {
                updateStep1Preview();
                setTimeout(updateStep1Preview, 0);
            }
        });
    });

    const couponPrimaryHex = document.getElementById('coupon_primary_color_hex');
    const couponPrimaryPicker = document.getElementById('coupon_primary_color_picker');
    const couponSecondaryHex = document.getElementById('coupon_secondary_color_hex');
    const couponSecondaryPicker = document.getElementById('coupon_secondary_color_picker');
    const couponButtonHex = document.getElementById('coupon_button_color_hex');
    const couponButtonPicker = document.getElementById('coupon_button_color_picker');
    const couponButtonTextColorHex = document.getElementById('coupon_button_text_color_hex');
    const couponButtonTextColorPicker = document.getElementById('coupon_button_text_color_picker');
    const couponSalesBadgeColorHex = document.getElementById('coupon_sales_badge_color_hex');
    const couponSalesBadgeColorPicker = document.getElementById('coupon_sales_badge_color_picker');
    const couponSalesBadgeTextColorHex = document.getElementById('coupon_sales_badge_text_color_hex');
    const couponSalesBadgeTextColorPicker = document.getElementById('coupon_sales_badge_text_color_picker');

    function syncCouponPrimary() {
        const v = couponPrimaryPicker ? couponPrimaryPicker.value : (couponPrimaryHex ? couponPrimaryHex.value : '#6594FF');
        if (couponPrimaryHex) couponPrimaryHex.value = v;
        if (couponPrimaryPicker) couponPrimaryPicker.value = v;
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }
    function syncCouponSecondary() {
        const v = couponSecondaryPicker ? couponSecondaryPicker.value : (couponSecondaryHex ? couponSecondaryHex.value : '#FFFFFF');
        if (couponSecondaryHex) couponSecondaryHex.value = v;
        if (couponSecondaryPicker) couponSecondaryPicker.value = v;
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }
    function syncCouponButton() {
        const v = couponButtonPicker ? couponButtonPicker.value : (couponButtonHex ? couponButtonHex.value : '#D6D6D6');
        if (couponButtonHex) couponButtonHex.value = v;
        if (couponButtonPicker) couponButtonPicker.value = v;
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }
    function syncCouponButtonTextColor() {
        const v = couponButtonTextColorPicker ? couponButtonTextColorPicker.value : (couponButtonTextColorHex ? couponButtonTextColorHex.value : '#1f2937');
        if (couponButtonTextColorHex) couponButtonTextColorHex.value = v;
        if (couponButtonTextColorPicker) couponButtonTextColorPicker.value = v;
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }
    function syncCouponSalesBadgeColor() {
        const v = couponSalesBadgeColorPicker ? couponSalesBadgeColorPicker.value : (couponSalesBadgeColorHex ? couponSalesBadgeColorHex.value : '#9FE2BF');
        if (couponSalesBadgeColorHex) couponSalesBadgeColorHex.value = v;
        if (couponSalesBadgeColorPicker) couponSalesBadgeColorPicker.value = v;
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }
    function syncCouponSalesBadgeTextColor() {
        const v = couponSalesBadgeTextColorPicker ? couponSalesBadgeTextColorPicker.value : (couponSalesBadgeTextColorHex ? couponSalesBadgeTextColorHex.value : '#1f2937');
        if (couponSalesBadgeTextColorHex) couponSalesBadgeTextColorHex.value = v;
        if (couponSalesBadgeTextColorPicker) couponSalesBadgeTextColorPicker.value = v;
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }

    if (couponPrimaryHex) couponPrimaryHex.addEventListener('input', syncCouponPrimary);
    if (couponPrimaryPicker) couponPrimaryPicker.addEventListener('input', syncCouponPrimary);
    if (couponSecondaryHex) couponSecondaryHex.addEventListener('input', syncCouponSecondary);
    if (couponSecondaryPicker) couponSecondaryPicker.addEventListener('input', syncCouponSecondary);
    if (couponButtonHex) couponButtonHex.addEventListener('input', syncCouponButton);
    if (couponButtonPicker) couponButtonPicker.addEventListener('input', syncCouponButton);
    if (couponButtonTextColorHex) couponButtonTextColorHex.addEventListener('input', syncCouponButtonTextColor);
    if (couponButtonTextColorPicker) couponButtonTextColorPicker.addEventListener('input', syncCouponButtonTextColor);
    if (couponSalesBadgeColorHex) couponSalesBadgeColorHex.addEventListener('input', syncCouponSalesBadgeColor);
    if (couponSalesBadgeColorPicker) couponSalesBadgeColorPicker.addEventListener('input', syncCouponSalesBadgeColor);
    if (couponSalesBadgeTextColorHex) couponSalesBadgeTextColorHex.addEventListener('input', syncCouponSalesBadgeTextColor);
    if (couponSalesBadgeTextColorPicker) couponSalesBadgeTextColorPicker.addEventListener('input', syncCouponSalesBadgeTextColor);

    document.getElementById('coupon-swap-colors')?.addEventListener('click', function() {
        const p = couponPrimaryHex?.value || '#6594FF';
        const s = couponSecondaryHex?.value || '#FFFFFF';
        if (couponPrimaryHex) couponPrimaryHex.value = s;
        if (couponPrimaryPicker) couponPrimaryPicker.value = s;
        if (couponSecondaryHex) couponSecondaryHex.value = p;
        if (couponSecondaryPicker) couponSecondaryPicker.value = p;
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    });
});
</script>

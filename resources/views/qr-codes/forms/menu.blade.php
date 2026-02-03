<!-- Menu QR Code Form -->
<!-- Design and Customize Section (colors + font only) -->
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
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleMenuSection('menu-design-section', event)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        
        <div id="menu-design-section-content">
            <!-- Color Palettes -->
            <div class="mb-6">
                <div class="grid grid-cols-2 md:flex gap-3 mb-4">
                    <button type="button" class="menu-color-preset border-2 border-primary-500 rounded-lg p-2 hover:border-primary-600 transition-colors" data-primary="#6594FF" data-secondary="#FFFFFF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #6594FF;"></div>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                        </div>
                    </button>
                    <button type="button" class="menu-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E5E7EB" data-secondary="#000000">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #E5E7EB;"></div>
                            <div class="w-8 h-8 rounded" style="background-color: #000000;"></div>
                        </div>
                    </button>
                    <button type="button" class="menu-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#E9D5FF" data-secondary="#FFFFFF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #E9D5FF;"></div>
                            <div class="w-8 h-8 rounded border border-gray-200" style="background-color: #FFFFFF;"></div>
                        </div>
                    </button>
                    <button type="button" class="menu-color-preset border-2 border-dark-200 rounded-lg p-2 hover:border-primary-400 transition-colors" data-primary="#FFD1DC" data-secondary="#B5E5CF">
                        <div class="flex gap-1">
                            <div class="w-8 h-8 rounded" style="background-color: #FFD1DC;"></div>
                            <div class="w-8 h-8 rounded" style="background-color: #B5E5CF;"></div>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Color Inputs -->
            <div class="mb-4">
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <label for="menu_primary_color" class="text-sm font-bold text-dark-500">Primary color</label>
                    <label for="menu_secondary_color" class="text-sm font-bold text-dark-500">Secondary color</label>
                </div>
                <div class="grid grid-cols-2 gap-4 relative">
                    <div class="flex items-center gap-3 relative">
                        <input type="text" id="menu_primary_color_hex" name="menu_primary_color" value="#6594FF" class="input flex-1" placeholder="#6594FF">
                        <div class="relative">
                            <input type="color" id="menu_primary_color_picker" value="#6594FF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                            <button type="button" id="menu-swap-colors" class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-dark-300 hover:text-dark-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="text" id="menu_secondary_color_hex" name="menu_secondary_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                        <input type="color" id="menu_secondary_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                    </div>
                </div>
            </div>
            
            <!-- Font Selection -->
            <div class="mb-4">
                <label for="menu_font_family" class="text-sm font-bold text-dark-500 mb-2 block">Font family</label>
                <select id="menu_font_family" name="menu_font_family" class="input w-full">
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

<!-- Restaurant information Section -->
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
                    <h3 class="text-lg font-bold text-dark-500">Restaurant information</h3>
                    <p class="text-sm text-dark-300 mt-1">Provide details about your restaurant</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleMenuSection('menu-restaurant-section', event)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        
        <div id="menu-restaurant-section-content">
            <div class="space-y-6">
                <!-- Restaurant image -->
                <div>
                    <label for="menu_restaurant_image" class="label">Restaurant image</label>
                    <div id="menu-restaurant-image-area" class="border-2 border-dashed border-dark-200 rounded-lg p-8 text-center hover:border-primary-400 transition-colors cursor-pointer">
                        <input type="file" id="menu_restaurant_image" name="menu_restaurant_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" class="hidden" onchange="handleMenuRestaurantImageSelect(this)">
                        <label for="menu_restaurant_image" class="cursor-pointer">
                            <svg class="w-12 h-12 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-dark-300 font-medium">Click to upload restaurant image</p>
                            <p class="text-sm text-dark-200 mt-1">JPG, PNG, GIF, WebP or SVG. Max 5MB</p>
                        </label>
                    </div>
                    <div id="menu-restaurant-image-preview" class="mt-4 hidden">
                        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden bg-gray-100">
                                    <img id="menu-restaurant-image-thumb" src="" alt="" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-dark-500 truncate" id="menu-restaurant-image-filename"></p>
                                    <p class="text-xs text-dark-200" id="menu-restaurant-image-filesize"></p>
                                </div>
                            </div>
                            <button type="button" onclick="clearMenuRestaurantImage()" class="ml-4 text-red-600 hover:text-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Restaurant name -->
                <div>
                    <label for="restaurant_name" class="label">Restaurant name</label>
                    <input type="text" id="restaurant_name" name="restaurant_name" class="input" placeholder="Restaurant name">
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="menu_restaurant_name_font_size" class="label flex items-center justify-between">
                                <span>Name font size</span>
                                <span id="menu_restaurant_name_font_size_value" class="text-sm font-medium text-dark-500">18px</span>
                            </label>
                            <input type="range" id="menu_restaurant_name_font_size" name="menu_restaurant_name_font_size" min="12" max="28" value="18" class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-dark-500 accent-primary-500 [&::-webkit-slider-runnable-track]:bg-dark-500 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-primary-500 [&::-webkit-slider-thumb]:border-0 [&::-webkit-slider-thumb]:cursor-pointer">
                        </div>
                        <div>
                            <label for="menu_restaurant_name_color" class="label">Name text color</label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="menu_restaurant_name_color_hex" name="menu_restaurant_name_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                                <input type="color" id="menu_restaurant_name_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer flex-shrink-0">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Restaurant description -->
                <div>
                    <label for="restaurant_description" class="label">Restaurant description</label>
                    <textarea id="restaurant_description" name="restaurant_description" class="input min-h-[100px]" placeholder="Short description of your restaurant" rows="4"></textarea>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="menu_restaurant_description_font_size" class="label flex items-center justify-between">
                                <span>Description font size</span>
                                <span id="menu_restaurant_description_font_size_value" class="text-sm font-medium text-dark-500">14px</span>
                            </label>
                            <input type="range" id="menu_restaurant_description_font_size" name="menu_restaurant_description_font_size" min="10" max="20" value="14" class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-dark-500 accent-primary-500 [&::-webkit-slider-runnable-track]:bg-dark-500 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-primary-500 [&::-webkit-slider-thumb]:border-0 [&::-webkit-slider-thumb]:cursor-pointer">
                        </div>
                        <div>
                            <label for="menu_restaurant_description_color" class="label">Description text color</label>
                            <div class="flex items-center gap-2">
                                <input type="text" id="menu_restaurant_description_color_hex" name="menu_restaurant_description_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                                <input type="color" id="menu_restaurant_description_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer flex-shrink-0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu sections (Input your menu) -->
<div class="mb-8">
    <div class="card">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1 w-10 h-10 rounded-lg bg-dark-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark-500">Menu</h3>
                    <p class="text-sm text-dark-300 mt-1">Input your menu</p>
                </div>
            </div>
            <button type="button" class="text-dark-300 hover:text-dark-500 transition-colors" onclick="toggleMenuSection('menu-input-section', event)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                </svg>
            </button>
        </div>
        <div id="menu-input-section-content" class="border-t border-dark-200 pt-6">
            <div id="menu-sections-container" class="space-y-6 mb-6">
                <!-- Sections added dynamically -->
            </div>
            <button type="button" id="add-menu-section-btn" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-lg border-2 border-dashed border-primary-400 text-primary-600 hover:bg-primary-50 hover:border-primary-500 transition-colors font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add section
            </button>
        </div>
    </div>
</div>

<div class="space-y-6">
    <div>
        <p class="label">Upload Menu PDF or Enter Menu URL</p>
        <p class="text-sm text-dark-200 mb-4">Choose one option below</p>
    </div>

    <div>
        <label for="menu_file" class="label">Menu PDF File</label>
        <div id="menu-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-8 text-center hover:border-primary-400 transition-colors cursor-pointer">
            <input type="file" id="menu_file" name="menu_file" accept=".pdf,application/pdf" class="hidden" onchange="handleMenuSelect(this)">
            <label for="menu_file" class="cursor-pointer">
                <svg class="w-12 h-12 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <p class="text-dark-300 font-medium">Click to upload menu PDF</p>
                <p class="text-sm text-dark-200 mt-1">Maximum file size: 5MB</p>
            </label>
        </div>
        <div id="menu-preview" class="mt-4 hidden">
            <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-dark-500 truncate" id="menu-filename"></p>
                        <p class="text-xs text-dark-200" id="menu-filesize"></p>
                    </div>
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <button type="button" onclick="clearMenuFile()" class="ml-4 text-red-600 hover:text-red-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-dark-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-dark-200">OR</span>
        </div>
    </div>

    <div>
        <label for="menu_url" class="label">Menu URL</label>
        <input type="url" id="menu_url" name="menu_url" class="input" placeholder="https://example.com/menu">
        <p class="text-sm text-dark-200 mt-1">Link to your online menu</p>
    </div>
</div>

<script>
function handleMenuSelect(input) {
    const file = input.files[0];
    if (file) {
        // Proveri da li je PDF
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
        
        // Prikaži informacije o fajlu
        document.getElementById('menu-preview').classList.remove('hidden');
        document.getElementById('menu-upload-area').classList.add('border-green-400', 'bg-green-50');
        document.getElementById('menu-filename').textContent = file.name;
        document.getElementById('menu-filesize').textContent = formatFileSize(file.size);
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }
}

function clearMenuFile() {
    document.getElementById('menu_file').value = '';
    document.getElementById('menu-preview').classList.add('hidden');
    document.getElementById('menu-upload-area').classList.remove('border-green-400', 'bg-green-50');
    if (typeof updateStep1Preview === 'function') updateStep1Preview();
}

function handleMenuRestaurantImageSelect(input) {
    const file = input.files[0];
    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file (JPG, PNG, GIF, WebP or SVG).');
            input.value = '';
            return;
        }
        if (file.size > 5242880) {
            alert('Image is too large. Maximum size is 5MB.');
            input.value = '';
            return;
        }
        document.getElementById('menu-restaurant-image-preview').classList.remove('hidden');
        document.getElementById('menu-restaurant-image-area').classList.add('border-green-400', 'bg-green-50');
        document.getElementById('menu-restaurant-image-filename').textContent = file.name;
        document.getElementById('menu-restaurant-image-filesize').textContent = formatFileSize(file.size);

        const reader = new FileReader();
        reader.onload = function(e) {
            const thumb = document.getElementById('menu-restaurant-image-thumb');
            if (thumb) {
                thumb.src = e.target.result;
                thumb.alt = file.name;
            }
            requestAnimationFrame(function() {
                if (typeof updateStep1Preview === 'function') updateStep1Preview();
            });
        };
        reader.readAsDataURL(file);
    }
}

function clearMenuRestaurantImage() {
    document.getElementById('menu_restaurant_image').value = '';
    document.getElementById('menu-restaurant-image-preview').classList.add('hidden');
    document.getElementById('menu-restaurant-image-area').classList.remove('border-green-400', 'bg-green-50');
    document.getElementById('menu-restaurant-image-thumb').src = '';
    document.getElementById('menu-restaurant-image-thumb').alt = '';
    if (typeof updateStep1Preview === 'function') updateStep1Preview();
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function toggleMenuSection(sectionId, ev) {
    const content = document.getElementById(sectionId + '-content');
    const button = ev && ev.target ? ev.target.closest('button') : null;
    if (!content) return;
    const icon = button ? button.querySelector('svg') : null;
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        if (icon) icon.style.transform = 'rotate(0deg)';
    } else {
        content.classList.add('hidden');
        if (icon) icon.style.transform = 'rotate(180deg)';
    }
}

// Menu color preset and sync handlers
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.menu-color-preset').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const primary = btn.dataset.primary;
            const secondary = btn.dataset.secondary;
            const primaryInput = document.getElementById('menu_primary_color_hex');
            const secondaryInput = document.getElementById('menu_secondary_color_hex');
            const primaryColorPicker = document.getElementById('menu_primary_color_picker');
            const secondaryColorPicker = document.getElementById('menu_secondary_color_picker');
            if (primaryInput) primaryInput.value = primary;
            if (secondaryInput) secondaryInput.value = secondary;
            if (primaryColorPicker) primaryColorPicker.value = primary;
            if (secondaryColorPicker) secondaryColorPicker.value = secondary;
            document.querySelectorAll('.menu-color-preset').forEach(b => {
                b.classList.remove('border-primary-500');
                b.classList.add('border-dark-200');
            });
            btn.classList.remove('border-dark-200');
            btn.classList.add('border-primary-500');
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    });

    const primaryInput = document.getElementById('menu_primary_color_hex');
    const secondaryInput = document.getElementById('menu_secondary_color_hex');
    const primaryColorPicker = document.getElementById('menu_primary_color_picker');
    const secondaryColorPicker = document.getElementById('menu_secondary_color_picker');

    if (primaryColorPicker && primaryInput) {
        primaryColorPicker.addEventListener('input', function() {
            const color = this.value.toUpperCase();
            primaryInput.value = color;
            document.querySelectorAll('.menu-color-preset').forEach(btn => {
                if (btn.dataset.primary === color && btn.dataset.secondary === secondaryInput.value) {
                    btn.classList.remove('border-dark-200');
                    btn.classList.add('border-primary-500');
                } else {
                    btn.classList.remove('border-primary-500');
                    btn.classList.add('border-dark-200');
                }
            });
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }
    if (secondaryColorPicker && secondaryInput) {
        secondaryColorPicker.addEventListener('input', function() {
            const color = this.value.toUpperCase();
            secondaryInput.value = color;
            document.querySelectorAll('.menu-color-preset').forEach(btn => {
                if (btn.dataset.primary === primaryInput.value && btn.dataset.secondary === color) {
                    btn.classList.remove('border-dark-200');
                    btn.classList.add('border-primary-500');
                } else {
                    btn.classList.remove('border-primary-500');
                    btn.classList.add('border-dark-200');
                }
            });
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }
    if (primaryInput && primaryColorPicker) {
        primaryInput.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                primaryColorPicker.value = color;
                document.querySelectorAll('.menu-color-preset').forEach(btn => {
                    if (btn.dataset.primary === color && btn.dataset.secondary === secondaryInput.value) {
                        btn.classList.remove('border-dark-200');
                        btn.classList.add('border-primary-500');
                    } else {
                        btn.classList.remove('border-primary-500');
                        btn.classList.add('border-dark-200');
                    }
                });
                if (typeof updateStep1Preview === 'function') updateStep1Preview();
            }
        });
    }
    if (secondaryInput && secondaryColorPicker) {
        secondaryInput.addEventListener('input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                secondaryColorPicker.value = color;
                document.querySelectorAll('.menu-color-preset').forEach(btn => {
                    if (btn.dataset.primary === primaryInput.value && btn.dataset.secondary === color) {
                        btn.classList.remove('border-dark-200');
                        btn.classList.add('border-primary-500');
                    } else {
                        btn.classList.remove('border-primary-500');
                        btn.classList.add('border-dark-200');
                    }
                });
                if (typeof updateStep1Preview === 'function') updateStep1Preview();
            }
        });
    }

    const swapButton = document.getElementById('menu-swap-colors');
    if (swapButton && primaryInput && secondaryInput && primaryColorPicker && secondaryColorPicker) {
        swapButton.addEventListener('click', function() {
            const tempPrimary = primaryInput.value;
            const tempSecondary = secondaryInput.value;
            primaryInput.value = tempSecondary;
            secondaryInput.value = tempPrimary;
            primaryColorPicker.value = tempSecondary;
            secondaryColorPicker.value = tempPrimary;
            document.querySelectorAll('.menu-color-preset').forEach(btn => {
                if (btn.dataset.primary === tempSecondary && btn.dataset.secondary === tempPrimary) {
                    btn.classList.remove('border-dark-200');
                    btn.classList.add('border-primary-500');
                } else {
                    btn.classList.remove('border-primary-500');
                    btn.classList.add('border-dark-200');
                }
            });
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    const fontFamily = document.getElementById('menu_font_family');
    if (fontFamily) {
        fontFamily.addEventListener('change', function() {
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    const menuUrlInput = document.getElementById('menu_url');
    if (menuUrlInput) {
        menuUrlInput.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    const restaurantNameInput = document.getElementById('restaurant_name');
    const restaurantDescInput = document.getElementById('restaurant_description');
    if (restaurantNameInput) {
        restaurantNameInput.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }
    if (restaurantDescInput) {
        restaurantDescInput.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    const menuNameFontSizeInput = document.getElementById('menu_restaurant_name_font_size');
    const menuNameFontSizeValue = document.getElementById('menu_restaurant_name_font_size_value');
    if (menuNameFontSizeInput && menuNameFontSizeValue) {
        menuNameFontSizeInput.addEventListener('input', function() {
            menuNameFontSizeValue.textContent = this.value + 'px';
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    const menuDescFontSizeInput = document.getElementById('menu_restaurant_description_font_size');
    const menuDescFontSizeValue = document.getElementById('menu_restaurant_description_font_size_value');
    if (menuDescFontSizeInput && menuDescFontSizeValue) {
        menuDescFontSizeInput.addEventListener('input', function() {
            menuDescFontSizeValue.textContent = this.value + 'px';
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    const menuNameColorHex = document.getElementById('menu_restaurant_name_color_hex');
    const menuNameColorPicker = document.getElementById('menu_restaurant_name_color_picker');
    if (menuNameColorPicker && menuNameColorHex) {
        menuNameColorPicker.addEventListener('input', function() {
            menuNameColorHex.value = this.value;
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
        menuNameColorHex.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                menuNameColorPicker.value = this.value;
                if (typeof updateStep1Preview === 'function') updateStep1Preview();
            }
        });
    }

    const menuDescColorHex = document.getElementById('menu_restaurant_description_color_hex');
    const menuDescColorPicker = document.getElementById('menu_restaurant_description_color_picker');
    if (menuDescColorPicker && menuDescColorHex) {
        menuDescColorPicker.addEventListener('input', function() {
            menuDescColorHex.value = this.value;
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
        menuDescColorHex.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                menuDescColorPicker.value = this.value;
                if (typeof updateStep1Preview === 'function') updateStep1Preview();
            }
        });
    }

    // Menu sections: Add section / Add product
    const menuSectionsContainer = document.getElementById('menu-sections-container');
    const addMenuSectionBtn = document.getElementById('add-menu-section-btn');
    if (addMenuSectionBtn && menuSectionsContainer) {
        addMenuSectionBtn.addEventListener('click', addMenuSection);
    }
});

function addMenuSection() {
    const container = document.getElementById('menu-sections-container');
    if (!container) return;
    const sectionIndex = container.querySelectorAll('.menu-section-block').length;
    const sectionId = 'menu-section-' + sectionIndex;
    const productsId = 'menu-section-products-' + sectionIndex;
    const block = document.createElement('div');
    block.className = 'menu-section-block border border-dark-200 rounded-lg p-6 bg-white';
    block.dataset.sectionIndex = sectionIndex;
    block.innerHTML = `
        <div class="flex items-start justify-between mb-4">
            <h4 class="text-base font-bold text-dark-500">Section ${sectionIndex + 1}</h4>
            <button type="button" class="text-dark-300 hover:text-red-600 transition-colors remove-menu-section" title="Remove section">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        <div class="space-y-4 mb-4">
            <div>
                <label class="label">Name of section</label>
                <input type="text" name="menu_sections[${sectionIndex}][section_name]" class="input w-full" placeholder="e.g. Breakfast">
            </div>
            <div>
                <label class="label">Description of section</label>
                <textarea name="menu_sections[${sectionIndex}][section_description]" class="input w-full min-h-[80px]" placeholder="e.g. Served until 11am" rows="3"></textarea>
            </div>
        </div>
        <div class="border-t border-dark-200 pt-4">
            <p class="text-sm font-medium text-dark-500 mb-3">Products</p>
            <div id="${productsId}" class="space-y-6 mb-4"></div>
            <button type="button" class="add-menu-product-btn inline-flex items-center gap-2 py-2 px-3 rounded-lg border border-primary-400 text-primary-600 hover:bg-primary-50 text-sm font-medium transition-colors" data-section-index="${sectionIndex}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add product
            </button>
        </div>
    `;
    container.appendChild(block);
    block.querySelector('.remove-menu-section').addEventListener('click', function() {
        block.remove();
        reindexMenuSections();
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    });
    block.querySelector('.add-menu-product-btn').addEventListener('click', function() {
        addMenuProduct(sectionIndex);
    });
    if (typeof updateStep1Preview === 'function') updateStep1Preview();
    block.querySelectorAll('input, textarea').forEach(function(el) {
        el.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    });
}

function addMenuProduct(sectionIndex) {
    const productsContainer = document.getElementById('menu-section-products-' + sectionIndex);
    if (!productsContainer) return;
    const productIndex = productsContainer.querySelectorAll('.menu-product-block').length;
    const productId = 'menu-product-' + sectionIndex + '-' + productIndex;
    const block = document.createElement('div');
    block.className = 'menu-product-block border border-dark-100 rounded-lg p-4 bg-dark-50/50';
    block.dataset.sectionIndex = sectionIndex;
    block.dataset.productIndex = productIndex;
    block.innerHTML = `
        <div class="flex items-start justify-between mb-3">
            <h5 class="text-sm font-bold text-dark-500">Product ${productIndex + 1}</h5>
            <button type="button" class="text-dark-300 hover:text-red-600 transition-colors remove-menu-product" title="Remove product">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        <div class="space-y-3">
            <div>
                <label class="label text-sm">Add image</label>
                <div class="menu-product-image-upload-area border-2 border-dashed border-dark-200 rounded-lg p-4 text-center cursor-pointer hover:border-primary-400 transition-colors">
                    <input type="file" name="menu_sections[${sectionIndex}][products][${productIndex}][product_image]" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" class="hidden menu-product-image-input" id="${productId}-image">
                    <label for="${productId}-image" class="cursor-pointer flex flex-col items-center gap-1">
                        <svg class="w-8 h-8 text-dark-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-xs text-dark-300">Upload image (jpg, png, svg)</span>
                        <span class="text-xs text-dark-200">Maximum size: 3MB</span>
                    </label>
                </div>
                <div class="menu-product-image-preview mt-2 hidden flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                    <img class="menu-product-image-thumb w-12 h-12 rounded-lg object-cover flex-shrink-0 bg-gray-100" src="" alt="">
                    <span class="text-xs text-dark-500 flex-1 truncate menu-product-image-filename"></span>
                    <button type="button" class="menu-product-image-clear text-red-600 hover:text-red-700 p-1" title="Remove image">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="label text-sm">Product name *</label>
                    <input type="text" name="menu_sections[${sectionIndex}][products][${productIndex}][product_name]" class="input w-full" placeholder="e.g. Eggs Benedict" required>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="label text-sm">Description</label>
                    <input type="text" name="menu_sections[${sectionIndex}][products][${productIndex}][product_description]" class="input w-full" placeholder="e.g. Served with sourdough toast">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="label text-sm">Price</label>
                    <input type="text" name="menu_sections[${sectionIndex}][products][${productIndex}][price]" class="input w-full" placeholder="e.g. 10 €">
                </div>
                <div>
                    <label class="label text-sm">Allergens present</label>
                    <input type="text" name="menu_sections[${sectionIndex}][products][${productIndex}][allergens]" class="input w-full" placeholder="e.g. Gluten, Eggs">
                </div>
            </div>
        </div>
    `;
    productsContainer.appendChild(block);

    const fileInput = block.querySelector('.menu-product-image-input');
    const uploadArea = block.querySelector('.menu-product-image-upload-area');
    const previewDiv = block.querySelector('.menu-product-image-preview');
    const thumbImg = block.querySelector('.menu-product-image-thumb');
    const filenameSpan = block.querySelector('.menu-product-image-filename');
    const clearBtn = block.querySelector('.menu-product-image-clear');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (JPG, PNG, GIF, WebP or SVG).');
                this.value = '';
                return;
            }
            if (file.size > 3145728) {
                alert('Image is too large. Maximum size is 3MB.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                if (thumbImg) thumbImg.src = e.target.result;
                if (filenameSpan) filenameSpan.textContent = file.name;
                if (previewDiv) previewDiv.classList.remove('hidden');
                if (uploadArea) uploadArea.classList.add('border-green-400', 'bg-green-50');
                requestAnimationFrame(function() {
                    if (typeof updateStep1Preview === 'function') updateStep1Preview();
                });
            };
            reader.readAsDataURL(file);
        });
    }

    if (clearBtn && fileInput && thumbImg && previewDiv && uploadArea) {
        clearBtn.addEventListener('click', function() {
            fileInput.value = '';
            thumbImg.src = '';
            thumbImg.alt = '';
            previewDiv.classList.add('hidden');
            uploadArea.classList.remove('border-green-400', 'bg-green-50');
            if (filenameSpan) filenameSpan.textContent = '';
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    block.querySelector('.remove-menu-product').addEventListener('click', function() {
        block.remove();
        reindexMenuProductsInSection(sectionIndex);
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    });
    block.querySelectorAll('input, textarea').forEach(function(el) {
        el.addEventListener('input', function() {
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    });
    if (typeof updateStep1Preview === 'function') updateStep1Preview();
}

function reindexMenuSections() {
    const container = document.getElementById('menu-sections-container');
    if (!container) return;
    const blocks = container.querySelectorAll('.menu-section-block');
    blocks.forEach((block, i) => {
        block.dataset.sectionIndex = i;
        block.querySelector('h4').textContent = 'Section ' + (i + 1);
        const sectionNameInput = block.querySelector('input[name*="[section_name]"]');
        const sectionDescInput = block.querySelector('textarea[name*="[section_description]"]');
        if (sectionNameInput) sectionNameInput.name = 'menu_sections[' + i + '][section_name]';
        if (sectionDescInput) sectionDescInput.name = 'menu_sections[' + i + '][section_description]';
        const productsContainer = block.querySelector('[id^="menu-section-products-"]');
        if (productsContainer) {
            productsContainer.id = 'menu-section-products-' + i;
            const addProductBtn = block.querySelector('.add-menu-product-btn');
            if (addProductBtn) {
                addProductBtn.dataset.sectionIndex = i;
                addProductBtn.replaceWith(addProductBtn.cloneNode(true));
                block.querySelector('.add-menu-product-btn').addEventListener('click', function() { addMenuProduct(i); });
            }
            const productBlocks = productsContainer.querySelectorAll('.menu-product-block');
            productBlocks.forEach((pb, j) => {
                pb.dataset.sectionIndex = i;
                pb.dataset.productIndex = j;
                pb.querySelector('h5').textContent = 'Product ' + (j + 1);
                pb.querySelectorAll('input, textarea').forEach(el => {
                    if (el.name && el.name.includes('[products]')) {
                        const key = el.name.replace(/.*\[products\]\[\d+\]\[([^\]]+)\]$/, '$1');
                        el.name = 'menu_sections[' + i + '][products][' + j + '][' + key + ']';
                    }
                });
                const fileInput = pb.querySelector('.menu-product-image-input');
                if (fileInput) {
                    fileInput.name = 'menu_sections[' + i + '][products][' + j + '][product_image]';
                    const fid = 'menu-product-' + i + '-' + j + '-image';
                    fileInput.id = fid;
                    const label = pb.querySelector('label[for]');
                    if (label) label.setAttribute('for', fid);
                }
            });
        }
    });
}

function reindexMenuProductsInSection(sectionIndex) {
    const productsContainer = document.getElementById('menu-section-products-' + sectionIndex);
    if (!productsContainer) return;
    const productBlocks = productsContainer.querySelectorAll('.menu-product-block');
    productBlocks.forEach((pb, j) => {
        pb.dataset.productIndex = j;
        pb.querySelector('h5').textContent = 'Product ' + (j + 1);
        pb.querySelectorAll('input, textarea').forEach(el => {
            if (el.name && el.name.includes('[products]')) {
                const key = el.name.replace(/.*\[products\]\[\d+\]\[([^\]]+)\]$/, '$1');
                el.name = 'menu_sections[' + sectionIndex + '][products][' + j + '][' + key + ']';
            }
        });
        const fileInput = pb.querySelector('.menu-product-image-input');
        if (fileInput) {
            fileInput.name = 'menu_sections[' + sectionIndex + '][products][' + j + '][product_image]';
            const fid = 'menu-product-' + sectionIndex + '-' + j + '-image';
            fileInput.id = fid;
            const label = pb.querySelector('label[for]');
            if (label) label.setAttribute('for', fid);
        }
    });
}
</script>

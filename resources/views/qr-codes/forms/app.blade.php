<!-- App QR Code Form -->
<div class="space-y-6">
    <div>
        <label for="app_color" class="label">App Color</label>
        <div class="flex items-center space-x-3">
            <input type="color" id="app_color" name="color" value="#0EA5E9" class="w-16 h-12 rounded border-2 border-dark-200 cursor-pointer">
            <input type="text" id="app_color_hex" value="#0EA5E9" class="input flex-1" placeholder="#0EA5E9">
        </div>
    </div>

    <div>
        <label for="app_image" class="label">App Icon/Image (Optional)</label>
        <div id="app-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
            <input type="file" id="app_image" name="app_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" class="hidden" onchange="handleImagePreview(this, 'app-img-preview', 'app-upload-area')">
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

    <div>
        <label for="app_name" class="label">App Name</label>
        <input type="text" id="app_name" name="app_name" class="input" placeholder="My Awesome App">
    </div>

    <div>
        <label for="website_url" class="label">Website URL</label>
        <input type="url" id="website_url" name="website_url" class="input" placeholder="https://myapp.com">
    </div>

    <div>
        <label for="app_store_link" class="label">App Store Link</label>
        <input type="url" id="app_store_link" name="app_store_link" class="input" placeholder="https://apps.apple.com/...">
    </div>

    <div>
        <label for="play_store_link" class="label">Google Play Store Link</label>
        <input type="url" id="play_store_link" name="play_store_link" class="input" placeholder="https://play.google.com/store/apps/...">
    </div>
</div>

<script>
document.getElementById('app_color').addEventListener('input', (e) => {
    document.getElementById('app_color_hex').value = e.target.value;
});

document.getElementById('app_color_hex').addEventListener('input', (e) => {
    document.getElementById('app_color').value = e.target.value;
});

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
</script>

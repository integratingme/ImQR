<!-- Coupon QR Code Form -->
<div class="space-y-6">
    <div>
        <label for="coupon_image" class="label">Coupon Image *</label>
        <div class="border-2 border-dashed border-dark-200 rounded-lg p-8 text-center hover:border-primary-400 transition-colors">
            <input type="file" id="coupon_image" name="coupon_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" class="hidden" required onchange="handleImagePreview(this, 'coupon-img-preview')">
            <label for="coupon_image" class="cursor-pointer">
                <svg class="w-12 h-12 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-dark-300 font-medium">Click to upload coupon image</p>
                <p class="text-sm text-dark-200 mt-1">JPG, PNG, GIF, WebP, SVG - Maximum 5MB</p>
            </label>
        </div>
        <div id="coupon-img-preview" class="mt-4 hidden">
            <img src="" alt="Coupon preview" class="max-w-full h-48 object-contain mx-auto rounded-lg">
        </div>
    </div>

    <div>
        <label for="logo" class="label">Logo (Optional)</label>
        <div class="border-2 border-dashed border-dark-200 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
            <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" class="hidden" onchange="handleImagePreview(this, 'logo-img-preview')">
            <label for="logo" class="cursor-pointer">
                <svg class="w-10 h-10 text-dark-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-dark-300 text-sm font-medium">Upload logo</p>
                <p class="text-xs text-dark-200 mt-1">JPG, PNG, GIF, WebP, SVG - Max 2MB</p>
            </label>
        </div>
        <div id="logo-img-preview" class="mt-4 hidden">
            <img src="" alt="Logo preview" class="max-w-full h-24 object-contain mx-auto rounded-lg">
        </div>
    </div>
</div>

<script>
function handleImagePreview(input, previewId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
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
</script>

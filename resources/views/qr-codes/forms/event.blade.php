<!-- Event QR Code Form -->
<div class="space-y-6">
    <div>
        <label for="event_image" class="label">Event Image (Optional)</label>
        <div id="event-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
            <input type="file" id="event_image" name="event_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" class="hidden" onchange="handleImagePreview(this, 'event-img-preview', 'event-upload-area')">
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
            <input type="text" id="dress_code_color" name="dress_code_color" class="input" placeholder="e.g., Black Tie, Casual">
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
</script>

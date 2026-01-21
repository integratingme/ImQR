<!-- Menu QR Code Form -->
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
                <p class="text-sm text-dark-200 mt-1">Maximum file size: 10MB</p>
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
        document.getElementById('menu-preview').classList.remove('hidden');
        document.getElementById('menu-upload-area').classList.add('border-green-400', 'bg-green-50');
        document.getElementById('menu-filename').textContent = file.name;
        document.getElementById('menu-filesize').textContent = formatFileSize(file.size);
    }
}

function clearMenuFile() {
    document.getElementById('menu_file').value = '';
    document.getElementById('menu-preview').classList.add('hidden');
    document.getElementById('menu-upload-area').classList.remove('border-green-400', 'bg-green-50');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>

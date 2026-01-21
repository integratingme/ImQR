<!-- PDF QR Code Form -->
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
    }
}

function clearPdfFile() {
    document.getElementById('pdf_file').value = '';
    document.getElementById('pdf-preview').classList.add('hidden');
    document.getElementById('pdf-upload-area').classList.remove('border-green-400', 'bg-green-50');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>

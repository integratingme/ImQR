<!-- MP3 QR Code Form -->
<div class="mb-6">
    <label for="mp3_file" class="label">Audio File *</label>
    <div id="mp3-upload-area" class="border-2 border-dashed border-dark-200 rounded-lg p-8 text-center hover:border-primary-400 transition-colors cursor-pointer">
        <input type="file" id="mp3_file" name="mp3_file" accept=".mp3,.m4a,audio/mpeg,audio/mp3,audio/m4a,audio/x-m4a" class="hidden" required onchange="handleMp3Select(this)">
        <label for="mp3_file" class="cursor-pointer">
            <svg class="w-12 h-12 text-dark-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
            </svg>
            <p class="text-dark-300 font-medium">Click to upload audio file</p>
            <p class="text-sm text-dark-200 mt-1">Supported formats: MP3, M4A - Maximum file size: 20MB</p>
        </label>
    </div>
    <div id="mp3-preview" class="mt-4 hidden">
        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-dark-500 truncate" id="mp3-filename"></p>
                    <p class="text-xs text-dark-200" id="mp3-filesize"></p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <button type="button" onclick="clearMp3File()" class="ml-4 text-red-600 hover:text-red-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<div class="mb-6">
    <label for="song_name" class="label">Song Name *</label>
    <input type="text" id="song_name" name="song_name" class="input" placeholder="Enter song name" required maxlength="255">
</div>

<div class="mb-6">
    <label for="artist_name" class="label">Artist Name *</label>
    <input type="text" id="artist_name" name="artist_name" class="input" placeholder="Enter artist name" required maxlength="255">
</div>

<script>
function handleMp3Select(input) {
    const file = input.files[0];
    if (file) {
        // Check if audio format is supported (MP3, M4A)
        const validTypes = ['audio/mpeg', 'audio/mp3', 'audio/mpeg3', 'audio/m4a', 'audio/x-m4a'];
        const validExtensions = ['.mp3', '.m4a'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!validTypes.includes(file.type) && !validExtensions.includes(fileExtension)) {
            alert('Please select an audio file (MP3 or M4A format).');
            input.value = '';
            return;
        }
        
        // Check file size (20MB = 20971520 bytes)
        if (file.size > 20971520) {
            alert('Audio file is too large. Maximum size is 20MB.');
            input.value = '';
            return;
        }
        
        // Show file info
        document.getElementById('mp3-preview').classList.remove('hidden');
        document.getElementById('mp3-upload-area').classList.add('border-green-400', 'bg-green-50');
        document.getElementById('mp3-filename').textContent = file.name;
        document.getElementById('mp3-filesize').textContent = formatFileSize(file.size);
    }
}

function clearMp3File() {
    document.getElementById('mp3_file').value = '';
    document.getElementById('mp3-preview').classList.add('hidden');
    document.getElementById('mp3-upload-area').classList.remove('border-green-400', 'bg-green-50');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>

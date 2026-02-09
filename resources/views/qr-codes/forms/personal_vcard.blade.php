<!-- Personal vCard QR Code Form -->
<div class="space-y-6">
    {{-- Design: colors & font --}}
    <div class="card">
        <h3 class="text-lg font-bold text-dark-500 mb-4">Design</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="personal_vcard_primary_color_hex" class="label">Primary color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="personal_vcard_primary_color_hex" name="personal_vcard_primary_color" value="#b45341" class="input flex-1" placeholder="#b45341">
                    <input type="color" id="personal_vcard_primary_color_picker" value="#b45341" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                </div>
            </div>
            <div>
                <label for="personal_vcard_secondary_color_hex" class="label">Background color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="personal_vcard_secondary_color_hex" name="personal_vcard_secondary_color" value="#ffffff" class="input flex-1" placeholder="#ffffff">
                    <input type="color" id="personal_vcard_secondary_color_picker" value="#ffffff" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                </div>
            </div>
        </div>
        <div class="mb-4">
            <label for="personal_vcard_font_family" class="label">Font</label>
            <select id="personal_vcard_font_family" name="personal_vcard_font_family" class="input w-full">
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

    {{-- Profile: name, title, profile image --}}
    <div>
        <label for="personal_vcard_name" class="label">Full name *</label>
        <input type="text" id="personal_vcard_name" name="personal_vcard_name" class="input w-full" placeholder="John Doe" value="John Doe" required>
    </div>
    <div>
        <label for="personal_vcard_title" class="label">Title / role</label>
        <input type="text" id="personal_vcard_title" name="personal_vcard_title" class="input w-full" placeholder="Senior Web Developer">
    </div>
    <div>
        <span class="text-xs text-dark-300 block mb-2">Profile photo (optional)</span>
        <div id="personal-vcard-profile-upload" class="border-2 border-dashed border-dark-200 rounded-full w-28 h-28 flex items-center justify-center overflow-hidden bg-dark-50 hover:border-primary-400 transition-colors cursor-pointer mx-auto">
            <input type="file" id="personal_vcard_profile_image" name="personal_vcard_profile_image" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden">
            <label for="personal_vcard_profile_image" class="cursor-pointer w-full h-full flex items-center justify-center">
                <svg id="personal-vcard-profile-placeholder" class="w-10 h-10 text-dark-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <img id="personal-vcard-profile-preview-img" src="" alt="" class="hidden w-full h-full object-cover">
            </label>
        </div>
        <button type="button" id="personal-vcard-profile-remove" class="text-xs text-dark-400 hover:text-red-600 hidden block mx-auto mt-2">Remove</button>
    </div>

    {{-- About --}}
    <div>
        <label for="personal_vcard_about" class="label">About me</label>
        <textarea id="personal_vcard_about" name="personal_vcard_about" class="input" rows="4" placeholder="A short bio or tagline..."></textarea>
    </div>

    {{-- Contact --}}
    <div class="card">
        <h3 class="text-lg font-bold text-dark-500 mb-4">Contact</h3>
        <div class="space-y-4">
            <div>
                <label for="personal_vcard_phone" class="label">Phone *</label>
                <input type="tel" id="personal_vcard_phone" name="personal_vcard_phone" class="input" placeholder="+1 234 567 8900" required>
            </div>
            <div>
                <label for="personal_vcard_email" class="label">Email *</label>
                <input type="email" id="personal_vcard_email" name="personal_vcard_email" class="input" placeholder="john@example.com" required>
            </div>
        </div>
    </div>

    {{-- Location --}}
    <div class="card">
        <h3 class="text-lg font-bold text-dark-500 mb-4">Location</h3>
        <div class="space-y-4">
            <div>
                <label for="personal_vcard_address" class="label">Address</label>
                <input type="text" id="personal_vcard_address" name="personal_vcard_address" class="input" placeholder="123 Main Street, City">
            </div>
            <div>
                <label for="personal_vcard_maps_link" class="label">Google Maps link</label>
                <input type="url" id="personal_vcard_maps_link" name="personal_vcard_maps_link" class="input" placeholder="https://maps.google.com/...">
            </div>
        </div>
    </div>

    {{-- Social media --}}
    <div class="card">
        <h3 class="text-lg font-bold text-dark-500 mb-4">Social media</h3>
        <div class="space-y-4">
            <div id="personal-vcard-socials-container" class="space-y-3">
                <div class="personal-vcard-social-row flex gap-2 items-center">
                    <select name="personal_vcard_socials[0][platform]" class="input flex-1 max-w-[140px] h-10">
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="twitter">Twitter/X</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                    <input type="url" name="personal_vcard_socials[0][url]" class="input flex-1 h-10" placeholder="https://...">
                    <button type="button" class="btn btn-secondary btn-xs remove-personal-vcard-social hidden" aria-label="Remove">✕</button>
                </div>
            </div>
            <button type="button" id="add-personal-vcard-social" class="btn btn-secondary btn-sm">+ Add social link</button>
        </div>
    </div>
</div>

<script>
(function() {
    function syncColor(pickerId, hexId) {
        var picker = document.getElementById(pickerId);
        var hex = document.getElementById(hexId);
        if (!picker || !hex) return;
        picker.addEventListener('input', function() { hex.value = this.value; if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
        picker.addEventListener('change', function() { hex.value = this.value; if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
        hex.addEventListener('input', function() {
            var v = this.value.trim();
            if (/^#[0-9A-Fa-f]{6}$/.test(v) || /^[0-9A-Fa-f]{6}$/.test(v)) picker.value = v.startsWith('#') ? v : '#' + v;
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }
    syncColor('personal_vcard_primary_color_picker', 'personal_vcard_primary_color_hex');
    syncColor('personal_vcard_secondary_color_picker', 'personal_vcard_secondary_color_hex');

    var profileInput = document.getElementById('personal_vcard_profile_image');
    var profilePreviewImg = document.getElementById('personal-vcard-profile-preview-img');
    var profilePlaceholder = document.getElementById('personal-vcard-profile-placeholder');
    var profileRemoveBtn = document.getElementById('personal-vcard-profile-remove');
    if (profileInput && profilePreviewImg) {
        profileInput.addEventListener('change', function() {
            var file = this.files && this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    profilePreviewImg.src = e.target.result;
                    profilePreviewImg.classList.remove('hidden');
                    if (profilePlaceholder) profilePlaceholder.classList.add('hidden');
                    if (profileRemoveBtn) profileRemoveBtn.classList.remove('hidden');
                    if (typeof updateStep1Preview === 'function') updateStep1Preview();
                };
                reader.readAsDataURL(file);
            }
        });
    }
    if (profileRemoveBtn && profileInput) {
        profileRemoveBtn.addEventListener('click', function() {
            profileInput.value = '';
            if (profilePreviewImg) { profilePreviewImg.src = ''; profilePreviewImg.classList.add('hidden'); }
            if (profilePlaceholder) profilePlaceholder.classList.remove('hidden');
            profileRemoveBtn.classList.add('hidden');
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    var socContainer = document.getElementById('personal-vcard-socials-container');
    var addSoc = document.getElementById('add-personal-vcard-social');
    if (addSoc && socContainer) {
        addSoc.addEventListener('click', function() {
            var n = socContainer.querySelectorAll('.personal-vcard-social-row').length;
            var row = document.createElement('div');
            row.className = 'personal-vcard-social-row flex gap-2 items-center';
            row.innerHTML = '<select name="personal_vcard_socials[' + n + '][platform]" class="input flex-1 max-w-[140px] h-10">' +
                '<option value="facebook">Facebook</option><option value="instagram">Instagram</option><option value="twitter">Twitter/X</option><option value="linkedin">LinkedIn</option><option value="whatsapp">WhatsApp</option></select>' +
                '<input type="url" name="personal_vcard_socials[' + n + '][url]" class="input flex-1 h-10" placeholder="https://...">' +
                '<button type="button" class="btn btn-secondary btn-xs remove-personal-vcard-social" aria-label="Remove">✕</button>';
            socContainer.appendChild(row);
            row.querySelector('.remove-personal-vcard-social').addEventListener('click', function() { row.remove(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
        socContainer.querySelectorAll('.remove-personal-vcard-social').forEach(function(btn) {
            btn.addEventListener('click', function() { btn.closest('.personal-vcard-social-row').remove(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
        });
    }
})();
</script>

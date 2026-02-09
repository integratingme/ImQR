<!-- Business Card QR Code Form -->
<div class="space-y-6">
    {{-- Design: colors & font --}}
    <div class="card">
        <h3 class="text-lg font-bold text-dark-500 mb-4">Design</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="business_card_primary_color_hex" class="label">Primary color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="business_card_primary_color_hex" name="business_card_primary_color" value="#e54e1a" class="input flex-1" placeholder="#e54e1a">
                    <input type="color" id="business_card_primary_color_picker" value="#e54e1a" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                </div>
            </div>
            <div>
                <label for="business_card_secondary_color_hex" class="label">Background color</label>
                <div class="flex items-center gap-3">
                    <input type="text" id="business_card_secondary_color_hex" name="business_card_secondary_color" value="#FFFFFF" class="input flex-1" placeholder="#FFFFFF">
                    <input type="color" id="business_card_secondary_color_picker" value="#FFFFFF" class="w-10 h-10 rounded border border-gray-200 cursor-pointer">
                </div>
            </div>
        </div>
        <div class="mb-4">
            <label for="business_card_font_family" class="label">Font</label>
            <select id="business_card_font_family" name="business_card_font_family" class="input w-full">
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

    {{-- Company info: logo + name --}}
    <div>
        <label for="business_card_company_name" class="label">Company name *</label>
        <div class="flex flex-wrap items-end gap-4">
            <div class="flex flex-col gap-2">
                <span class="text-xs text-dark-300">Logo (optional)</span>
                <div id="business-card-logo-upload" class="border-2 border-dashed border-dark-200 rounded-lg w-20 h-20 flex items-center justify-center overflow-hidden bg-dark-50 hover:border-primary-400 transition-colors cursor-pointer">
                    <input type="file" id="business_card_logo" name="business_card_logo" accept=".jpg,.jpeg,.png,image/jpeg,image/png" class="hidden">
                    <label for="business_card_logo" class="cursor-pointer w-full h-full flex items-center justify-center">
                        <svg id="business-card-logo-placeholder" class="w-8 h-8 text-dark-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <img id="business-card-logo-preview-img" src="" alt="" class="hidden w-full h-full object-contain">
                    </label>
                </div>
                <button type="button" id="business-card-logo-remove" class="text-xs text-dark-400 hover:text-red-600 hidden">Remove</button>
            </div>
            <div class="flex-1 min-w-0">
                <input type="text" id="business_card_company_name" name="business_card_company_name" class="input w-full" placeholder="Your Company Name" value="Your Company Name" required>
            </div>
        </div>
    </div>
    <div>
        <label for="business_card_subtitle" class="label">Subtitle / tagline</label>
        <input type="text" id="business_card_subtitle" name="business_card_subtitle" class="input" placeholder="Your success is our goal">
    </div>

    {{-- Quick action buttons --}}
    <div>
        <label class="label">Quick action buttons</label>
        <p class="text-sm text-dark-300 mb-3">Add links (e.g. website, booking, catalog).</p>
        <div id="business-card-buttons-container" class="space-y-3">
            <div class="business-card-button-row flex gap-2 items-start">
                <input type="text" name="business_card_buttons[0][label]" class="input flex-1" placeholder="Label (e.g. Our Website)">
                <input type="url" name="business_card_buttons[0][url]" class="input flex-1" placeholder="https://...">
                <button type="button" class="btn btn-secondary btn-xs remove-business-card-button hidden" aria-label="Remove">✕</button>
            </div>
        </div>
        <button type="button" id="add-business-card-button" class="btn btn-secondary btn-sm mt-2">+ Add button</button>
    </div>

    {{-- About --}}
    <div>
        <label for="business_card_about" class="label">About us *</label>
        <textarea id="business_card_about" name="business_card_about" class="input" rows="4" placeholder="Short description of your company..." required></textarea>
    </div>

    {{-- Contact --}}
    <div class="card">
        <h3 class="text-lg font-bold text-dark-500 mb-4">Contact</h3>
        <div class="space-y-4">
            <div>
                <label for="business_card_contact_name" class="label">Contact name</label>
                <input type="text" id="business_card_contact_name" name="business_card_contact_name" class="input" placeholder="John Doe">
            </div>
            <div>
                <label for="business_card_phone" class="label">Phone *</label>
                <input type="tel" id="business_card_phone" name="business_card_phone" class="input" placeholder="+1 234 567 8900" required>
            </div>
            <div>
                <label for="business_card_email" class="label">Email *</label>
                <input type="email" id="business_card_email" name="business_card_email" class="input" placeholder="contact@company.com" required>
            </div>
        </div>
    </div>

    {{-- Location --}}
    <div class="card">
        <h3 class="text-lg font-bold text-dark-500 mb-4">Location</h3>
        <div class="space-y-4">
            <div>
                <label for="business_card_address" class="label">Address</label>
                <input type="text" id="business_card_address" name="business_card_address" class="input" placeholder="123 Main Street, City">
            </div>
            <div>
                <label for="business_card_maps_link" class="label">Google Maps link</label>
                <input type="url" id="business_card_maps_link" name="business_card_maps_link" class="input" placeholder="https://maps.google.com/...">
            </div>
        </div>
    </div>

    {{-- Opening hours --}}
    <div class="card">
        <button type="button" id="opening-hours-toggle" class="w-full flex items-center justify-between text-left" aria-expanded="true">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-dark-500">Opening hours</h3>
                    <p class="text-sm text-dark-300">If applicable, provide your business hours.</p>
                </div>
            </div>
            <svg id="opening-hours-chevron" class="w-5 h-5 text-dark-400 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
            </svg>
        </button>
        <div id="opening-hours-content" class="mt-4 space-y-4">
            {{-- Time format --}}
            <div class="flex flex-wrap gap-2">
                <button type="button" class="opening-hours-format px-4 py-2 rounded-lg text-sm font-medium border-2 transition-colors bg-primary-600 text-white border-primary-600" data-format="ampm">AM/PM</button>
                <button type="button" class="opening-hours-format px-4 py-2 rounded-lg text-sm font-medium border-2 border-primary-500 text-dark-500 bg-white hover:border-primary-600 transition-colors" data-format="24h">24 hrs</button>
                <button type="button" class="opening-hours-format px-4 py-2 rounded-lg text-sm font-medium border-2 border-primary-500 text-dark-500 bg-white hover:border-primary-600 transition-colors" data-format="24/7">Open 24/7</button>
            </div>
            <div id="opening-hours-rows-wrap">
                <div class="opening-hours-row flex items-center gap-2 mb-3">
                    <label class="flex items-center gap-2 cursor-pointer flex-shrink-0">
                        <input type="checkbox" class="opening-hours-day-check rounded border-dark-300 text-primary-600 focus:ring-primary-500" checked>
                        <select class="opening-hours-days input w-[140px]">
                            <option value="Monday - Friday">Monday - Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                            <option value="Monday - Saturday">Monday - Saturday</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                        </select>
                    </label>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <select class="opening-hours-start input w-[110px]">
                            @foreach(['12:00 AM','01:00 AM','02:00 AM','03:00 AM','04:00 AM','05:00 AM','06:00 AM','07:00 AM','08:00 AM','09:00 AM','10:00 AM','11:00 AM','12:00 PM','01:00 PM','02:00 PM','03:00 PM','04:00 PM','05:00 PM','06:00 PM','07:00 PM','08:00 PM','09:00 PM','10:00 PM','11:00 PM'] as $t)
                            <option value="{{ $t }}" {{ $t === '07:00 AM' ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                        <span class="text-dark-400">–</span>
                        <select class="opening-hours-end input w-[110px]">
                            @foreach(['12:00 AM','01:00 AM','02:00 AM','03:00 AM','04:00 AM','05:00 AM','06:00 AM','07:00 AM','08:00 AM','09:00 AM','10:00 AM','11:00 AM','12:00 PM','01:00 PM','02:00 PM','03:00 PM','04:00 PM','05:00 PM','06:00 PM','07:00 PM','08:00 PM','09:00 PM','10:00 PM','11:00 PM'] as $t)
                            <option value="{{ $t }}" {{ $t === '05:00 PM' ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="opening-hours-add w-9 h-9 rounded-lg border-2 border-primary-500 text-primary-600 flex items-center justify-center hover:bg-primary-50 transition-colors flex-shrink-0" aria-label="Add hours">+</button>
                </div>
            </div>
            <div class="flex items-center gap-3 my-3">
                <span class="flex-1 border-t border-dark-200"></span>
                <span class="text-sm font-medium text-dark-400">OR</span>
                <span class="flex-1 border-t border-dark-200"></span>
            </div>
            <div>
                <label for="business_card_working_hours" class="label">Custom hours (optional)</label>
                <textarea id="business_card_working_hours" name="business_card_working_hours" class="input" rows="2" placeholder="e.g. Monday: 9am - 5pm"></textarea>
            </div>
        </div>
        <input type="hidden" id="opening_hours_format" name="opening_hours_format" value="ampm">
    </div>

    {{-- Social media --}}
    <div class="card">
        <h3 class="text-lg font-bold text-dark-500 mb-4">Social media</h3>
        <div class="space-y-4">
            <div id="business-card-socials-container" class="space-y-3">
                <div class="business-card-social-row flex gap-2 items-center">
                    <select name="business_card_socials[0][platform]" class="input flex-1 max-w-[140px] h-10">
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                        <option value="twitter">Twitter/X</option>
                        <option value="linkedin">LinkedIn</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                    <input type="url" name="business_card_socials[0][url]" class="input flex-1 h-10" placeholder="https://...">
                    <button type="button" class="btn btn-secondary btn-xs remove-business-card-social hidden" aria-label="Remove">✕</button>
                </div>
            </div>
            <button type="button" id="add-business-card-social" class="btn btn-secondary btn-sm">+ Add social link</button>
        </div>
    </div>
</div>

<script>
(function() {
    // Primary/secondary color sync
    function syncColor(pickerId, hexId, name) {
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
    syncColor('business_card_primary_color_picker', 'business_card_primary_color_hex');
    syncColor('business_card_secondary_color_picker', 'business_card_secondary_color_hex');

    // Logo upload
    var logoInput = document.getElementById('business_card_logo');
    var logoPreviewImg = document.getElementById('business-card-logo-preview-img');
    var logoPlaceholder = document.getElementById('business-card-logo-placeholder');
    var logoRemoveBtn = document.getElementById('business-card-logo-remove');
    if (logoInput && logoPreviewImg) {
        logoInput.addEventListener('change', function() {
            var file = this.files && this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    logoPreviewImg.src = e.target.result;
                    logoPreviewImg.classList.remove('hidden');
                    if (logoPlaceholder) logoPlaceholder.classList.add('hidden');
                    if (logoRemoveBtn) logoRemoveBtn.classList.remove('hidden');
                    if (typeof updateStep1Preview === 'function') updateStep1Preview();
                };
                reader.readAsDataURL(file);
            }
        });
    }
    if (logoRemoveBtn && logoInput) {
        logoRemoveBtn.addEventListener('click', function() {
            logoInput.value = '';
            if (logoPreviewImg) { logoPreviewImg.src = ''; logoPreviewImg.classList.add('hidden'); }
            if (logoPlaceholder) logoPlaceholder.classList.remove('hidden');
            logoRemoveBtn.classList.add('hidden');
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
    }

    // Buttons: add row
    var btnContainer = document.getElementById('business-card-buttons-container');
    var addBtn = document.getElementById('add-business-card-button');
    if (addBtn && btnContainer) {
        addBtn.addEventListener('click', function() {
            var n = btnContainer.querySelectorAll('.business-card-button-row').length;
            var row = document.createElement('div');
            row.className = 'business-card-button-row flex gap-2 items-start';
            row.innerHTML = '<input type="text" name="business_card_buttons[' + n + '][label]" class="input flex-1" placeholder="Label">' +
                '<input type="url" name="business_card_buttons[' + n + '][url]" class="input flex-1" placeholder="https://...">' +
                '<button type="button" class="btn btn-secondary btn-xs remove-business-card-button" aria-label="Remove">✕</button>';
            btnContainer.appendChild(row);
            row.querySelector('.remove-business-card-button').addEventListener('click', function() { row.remove(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
        btnContainer.querySelectorAll('.remove-business-card-button').forEach(function(btn) {
            btn.addEventListener('click', function() { btn.closest('.business-card-button-row').remove(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
        });
    }

    // Socials: add row
    var socContainer = document.getElementById('business-card-socials-container');
    var addSoc = document.getElementById('add-business-card-social');
    if (addSoc && socContainer) {
        addSoc.addEventListener('click', function() {
            var n = socContainer.querySelectorAll('.business-card-social-row').length;
            var row = document.createElement('div');
            row.className = 'business-card-social-row flex gap-2 items-center';
            row.innerHTML = '<select name="business_card_socials[' + n + '][platform]" class="input flex-1 max-w-[140px] h-10">' +
                '<option value="facebook">Facebook</option><option value="instagram">Instagram</option><option value="twitter">Twitter/X</option><option value="linkedin">LinkedIn</option><option value="whatsapp">WhatsApp</option></select>' +
                '<input type="url" name="business_card_socials[' + n + '][url]" class="input flex-1 h-10" placeholder="https://...">' +
                '<button type="button" class="btn btn-secondary btn-xs remove-business-card-social" aria-label="Remove">✕</button>';
            socContainer.appendChild(row);
            row.querySelector('.remove-business-card-social').addEventListener('click', function() { row.remove(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
            if (typeof updateStep1Preview === 'function') updateStep1Preview();
        });
        socContainer.querySelectorAll('.remove-business-card-social').forEach(function(btn) {
            btn.addEventListener('click', function() { btn.closest('.business-card-social-row').remove(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
        });
    }

    // Show remove on first row when there are multiple (optional UX)
    function toggleRemoveVisibility(containerClass, removeClass) {
        var rows = document.querySelectorAll(containerClass);
        rows.forEach(function(r, i) {
            var removeBtn = r.querySelector(removeClass);
            if (removeBtn) removeBtn.classList.toggle('hidden', rows.length <= 1);
        });
    }
    if (btnContainer) {
        btnContainer.addEventListener('input', function() { toggleRemoveVisibility('.business-card-button-row', '.remove-business-card-button'); });
        btnContainer.addEventListener('change', function() { toggleRemoveVisibility('.business-card-button-row', '.remove-business-card-button'); });
    }
    if (socContainer) {
        socContainer.addEventListener('input', function() { toggleRemoveVisibility('.business-card-social-row', '.remove-business-card-social'); });
        socContainer.addEventListener('change', function() { toggleRemoveVisibility('.business-card-social-row', '.remove-business-card-social'); });
    }

    // Opening hours: collapsible
    var openingHoursToggle = document.getElementById('opening-hours-toggle');
    var openingHoursContent = document.getElementById('opening-hours-content');
    var openingHoursChevron = document.getElementById('opening-hours-chevron');
    if (openingHoursToggle && openingHoursContent) {
        openingHoursToggle.addEventListener('click', function() {
            var expanded = openingHoursContent.classList.toggle('hidden');
            openingHoursToggle.setAttribute('aria-expanded', !expanded);
            if (openingHoursChevron) openingHoursChevron.style.transform = expanded ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    }

    // Opening hours: format buttons
    var currentFormat = 'ampm';
    var formatButtons = document.querySelectorAll('.opening-hours-format');
    var rowsWrap = document.getElementById('opening-hours-rows-wrap');
    var openingHoursTextarea = document.getElementById('business_card_working_hours');
    var openingHoursFormatInput = document.getElementById('opening_hours_format');

    function setFormat(format) {
        currentFormat = format;
        if (openingHoursFormatInput) openingHoursFormatInput.value = format;
        formatButtons.forEach(function(btn) {
            var isActive = btn.getAttribute('data-format') === format;
            btn.classList.toggle('bg-primary-600', isActive);
            btn.classList.toggle('text-white', isActive);
            btn.classList.toggle('border-primary-600', isActive);
            btn.classList.toggle('bg-white', !isActive);
            btn.classList.toggle('text-dark-500', !isActive);
            btn.classList.toggle('border-primary-500', !isActive);
        });
        if (format === '24/7') {
            if (rowsWrap) rowsWrap.classList.add('hidden');
            if (openingHoursTextarea) openingHoursTextarea.value = 'Open 24/7';
        } else {
            if (rowsWrap) rowsWrap.classList.remove('hidden');
            buildOpeningHoursString();
        }
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }

    formatButtons.forEach(function(btn) {
        btn.addEventListener('click', function() { setFormat(this.getAttribute('data-format')); });
    });

    function ampmTo24(t) {
        if (!t || currentFormat !== '24h') return t;
        var m = t.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
        if (!m) return t;
        var h = parseInt(m[1], 10);
        var min = m[2];
        if (m[3].toUpperCase() === 'PM' && h !== 12) h += 12;
        if (m[3].toUpperCase() === 'AM' && h === 12) h = 0;
        return (h < 10 ? '0' : '') + h + ':' + min;
    }

    function buildOpeningHoursString() {
        if (currentFormat === '24/7' || !openingHoursTextarea) return;
        var rows = document.querySelectorAll('.opening-hours-row');
        var lines = [];
        rows.forEach(function(row) {
            var check = row.querySelector('.opening-hours-day-check');
            if (check && !check.checked) return;
            var daysSelect = row.querySelector('.opening-hours-days');
            var startSelect = row.querySelector('.opening-hours-start');
            var endSelect = row.querySelector('.opening-hours-end');
            if (!daysSelect || !startSelect || !endSelect) return;
            var days = daysSelect.value;
            var start = startSelect.value;
            var end = endSelect.value;
            if (currentFormat === '24h') {
                start = ampmTo24(start);
                end = ampmTo24(end);
            }
            if (days && start && end) lines.push(days + ': ' + start + ' - ' + end);
        });
        openingHoursTextarea.value = lines.join('\n');
    }

    function attachRowListeners(row) {
        row.querySelectorAll('.opening-hours-day-check, .opening-hours-days, .opening-hours-start, .opening-hours-end').forEach(function(el) {
            el.addEventListener('change', function() { buildOpeningHoursString(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
        });
        var addBtn = row.querySelector('.opening-hours-add');
        var removeBtn = row.querySelector('.opening-hours-remove');
        if (addBtn) addBtn.addEventListener('click', addOpeningHoursRow);
        if (removeBtn) removeBtn.addEventListener('click', function() { row.remove(); buildOpeningHoursString(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
    }

    function addOpeningHoursRow() {
        var wrap = document.getElementById('opening-hours-rows-wrap');
        if (!wrap) return;
        var firstRow = wrap.querySelector('.opening-hours-row');
        if (!firstRow) return;
        var newRow = firstRow.cloneNode(true);
        newRow.querySelector('.opening-hours-day-check').checked = true;
        var daysSelect = newRow.querySelector('.opening-hours-days');
        if (daysSelect) { daysSelect.selectedIndex = 1; }
        var startSelect = newRow.querySelector('.opening-hours-start');
        var endSelect = newRow.querySelector('.opening-hours-end');
        if (startSelect) startSelect.selectedIndex = 0;
        if (endSelect) endSelect.selectedIndex = 0;
        var addBtn = newRow.querySelector('.opening-hours-add');
        if (addBtn) { addBtn.remove(); }
        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'opening-hours-remove w-9 h-9 rounded-lg border-2 border-dark-200 text-dark-500 flex items-center justify-center hover:bg-dark-50 transition-colors';
        removeBtn.setAttribute('aria-label', 'Remove');
        removeBtn.innerHTML = '−';
        removeBtn.addEventListener('click', function() { newRow.remove(); buildOpeningHoursString(); if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
        newRow.appendChild(removeBtn);
        wrap.appendChild(newRow);
        attachRowListeners(newRow);
        buildOpeningHoursString();
        if (typeof updateStep1Preview === 'function') updateStep1Preview();
    }

    document.querySelectorAll('.opening-hours-row').forEach(attachRowListeners);
    var firstAddBtn = document.querySelector('.opening-hours-row .opening-hours-add');
    if (firstAddBtn) firstAddBtn.addEventListener('click', addOpeningHoursRow);
    if (openingHoursTextarea) {
        openingHoursTextarea.addEventListener('input', function() { if (typeof updateStep1Preview === 'function') updateStep1Preview(); });
        buildOpeningHoursString();
    }
})();
</script>

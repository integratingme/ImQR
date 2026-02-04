<!-- Location QR Code Form -->
<div class="mb-6 space-y-4">
    <p class="text-sm text-dark-200">Set a location by searching, pasting a Google Maps link, or using your device location.</p>

    <!-- Search / Address -->
    <div>
        <label for="address" class="label">Address or place name</label>
        <div class="flex gap-2">
            <input type="text" id="address" name="address" class="input flex-1" placeholder="Search or enter address (e.g. 123 Main St, City)">
            <button type="button" id="location-search-btn" class="btn btn-secondary whitespace-nowrap" title="Search location">Search</button>
        </div>
    </div>

    <!-- Paste Google Maps link -->
    <div>
        <label for="location-paste-link" class="label">Or paste Google Maps link</label>
        <div class="flex gap-2">
            <input type="url" id="location-paste-link" class="input flex-1" placeholder="https://www.google.com/maps/@45.123,15.456,17z">
            <button type="button" id="location-apply-link-btn" class="btn btn-secondary whitespace-nowrap">Use this link</button>
        </div>
    </div>

    <!-- Use my location -->
    <div>
        <button type="button" id="location-use-device" class="btn btn-secondary inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Use my location
        </button>
        <span id="location-device-status" class="ml-2 text-sm text-dark-200"></span>
    </div>

    <!-- Hidden: coordinates or direct Maps link (QR opens this URL as-is) -->
    <input type="hidden" id="latitude" name="latitude" value="">
    <input type="hidden" id="longitude" name="longitude" value="">
    <input type="hidden" id="location_url" name="location_url" value="">
</div>

@push('scripts')
<script>
(function() {
    const form = document.getElementById('qr-form');
    if (!form || document.querySelector('input[name="type"]')?.value !== 'location') return;

    const addressInput = document.getElementById('address');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const locationUrlInput = document.getElementById('location_url');
    const pasteLinkInput = document.getElementById('location-paste-link');
    const applyLinkBtn = document.getElementById('location-apply-link-btn');
    const searchBtn = document.getElementById('location-search-btn');
    const useDeviceBtn = document.getElementById('location-use-device');
    const deviceStatus = document.getElementById('location-device-status');

    if (!addressInput || !latitudeInput || !longitudeInput || !locationUrlInput) return;

    function setCoordinates(lat, lng) {
        latitudeInput.value = lat;
        longitudeInput.value = lng;
        locationUrlInput.value = '';
    }

    function setLocationUrl(url) {
        locationUrlInput.value = (url || '').trim();
        latitudeInput.value = '';
        longitudeInput.value = '';
    }

    function setAddress(value) {
        addressInput.value = value || '';
        addressInput.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function isGoogleMapsLink(url) {
        if (!url || typeof url !== 'string') return false;
        const s = url.trim().toLowerCase();
        return s.startsWith('https://www.google.com/maps') ||
            s.startsWith('https://maps.google.com') ||
            s.startsWith('https://goo.gl/maps') ||
            s.startsWith('https://maps.app.goo.gl');
    }

    function parseGoogleMapsUrl(url) {
        if (!url || typeof url !== 'string') return null;
        const s = url.trim();
        // @lat,lng (e.g. .../maps/@45.123,15.456,17z or .../place/Name/@45.123,15.456)
        const atMatch = s.match(/@(-?\d+\.?\d*),(-?\d+\.?\d*)/);
        if (atMatch) return { lat: parseFloat(atMatch[1]), lng: parseFloat(atMatch[2]) };
        // q=lat,lng
        const qMatch = s.match(/[?&]q=(-?\d+\.?\d*),(-?\d+\.?\d*)/);
        if (qMatch) return { lat: parseFloat(qMatch[1]), lng: parseFloat(qMatch[2]) };
        // query=lat,lng
        const queryMatch = s.match(/[?&]query=(-?\d+\.?\d*),(-?\d+\.?\d*)/);
        if (queryMatch) return { lat: parseFloat(queryMatch[1]), lng: parseFloat(queryMatch[2]) };
        // ll=lat,lng (maps.google.com style)
        const llMatch = s.match(/[?&]ll=(-?\d+\.?\d*),(-?\d+\.?\d*)/);
        if (llMatch) return { lat: parseFloat(llMatch[1]), lng: parseFloat(llMatch[2]) };
        return null;
    }

    function reverseGeocode(lat, lng, callback) {
        var fallbackAddress = lat + ', ' + lng;
        deviceStatus.textContent = 'Loading address…';
        fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=18&addressdetails=1', {
            headers: { 'Accept': 'application/json' }
        })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data || data.error) {
                    setAddress(fallbackAddress);
                    return;
                }
                const name = data.address?.name || data.address?.road || '';
                const parts = [
                    name,
                    data.address?.suburb || data.address?.neighbourhood || data.address?.quarter,
                    data.address?.city || data.address?.town || data.address?.village,
                    data.address?.state,
                    data.address?.country
                ].filter(Boolean);
                setAddress(parts.join(', ') || data.display_name || fallbackAddress);
            })
            .catch(function() {
                setAddress(fallbackAddress);
            })
            .finally(function() {
                deviceStatus.textContent = '';
                if (typeof callback === 'function') callback();
            });
    }

    function geocodeAddress(address, callback) {
        if (!address || !address.trim()) return;
        deviceStatus.textContent = 'Searching…';
        fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address.trim()) + '&limit=1', {
            headers: { 'Accept': 'application/json' }
        })
            .then(function(r) { return r.json(); })
            .then(function(results) {
                if (results && results[0]) {
                    const lat = parseFloat(results[0].lat);
                    const lng = parseFloat(results[0].lon);
                    setCoordinates(lat, lng);
                    setAddress(results[0].display_name || address.trim());
                }
            })
            .catch(function() {})
            .finally(function() {
                deviceStatus.textContent = '';
                if (typeof callback === 'function') callback();
            });
    }

    // Search button
    searchBtn.addEventListener('click', function() {
        geocodeAddress(addressInput.value.trim());
    });

    // Enter in address field
    addressInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            geocodeAddress(addressInput.value.trim());
        }
    });

    function tryParsePlaceNameFromUrl(url) {
        var s = (url || '').trim();
        var placeMatch = s.match(/\/place\/([^/]+)(?:\/|$)/);
        if (placeMatch) {
            try {
                return decodeURIComponent(placeMatch[1].replace(/\+/g, ' '));
            } catch (e) {
                return placeMatch[1].replace(/\+/g, ' ');
            }
        }
        return null;
    }

    function processPastedOrEnteredLink(url) {
        const u = (url || '').trim();
        if (!u) return;
        const coords = parseGoogleMapsUrl(u);
        if (coords) {
            setCoordinates(coords.lat, coords.lng);
            var placeName = tryParsePlaceNameFromUrl(u);
            if (placeName) {
                setAddress(placeName);
            } else {
                setAddress(coords.lat + ', ' + coords.lng);
            }
            reverseGeocode(coords.lat, coords.lng);
        } else if (isGoogleMapsLink(u)) {
            setLocationUrl(u);
            deviceStatus.textContent = 'Resolving link…';
            fetch('{{ route("qr-codes.resolve-maps-link") }}?url=' + encodeURIComponent(u))
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success && data.place_name) {
                        setAddress(data.place_name);
                        deviceStatus.textContent = 'Location loaded.';
                    } else {
                        setAddress(u);
                        deviceStatus.textContent = 'Link will be used as-is.';
                    }
                    setTimeout(function() { deviceStatus.textContent = ''; }, 2000);
                })
                .catch(function() {
                    setAddress(u);
                    deviceStatus.textContent = 'Could not resolve name. Link will be used.';
                    setTimeout(function() { deviceStatus.textContent = ''; }, 3000);
                });
        } else if (u) {
            deviceStatus.textContent = 'Not a Google Maps link. Enter a link from Google Maps (e.g. maps.app.goo.gl or google.com/maps).';
            setTimeout(function() { deviceStatus.textContent = ''; }, 4000);
        }
    }

    pasteLinkInput.addEventListener('paste', function(e) {
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        const url = (typeof pasted === 'string' ? pasted : '').trim();
        processPastedOrEnteredLink(url);
        setTimeout(function() {
            const urlFromInput = pasteLinkInput.value.trim();
            if (urlFromInput && !latitudeInput.value && !locationUrlInput.value) {
                processPastedOrEnteredLink(urlFromInput);
            }
        }, 50);
    });
    pasteLinkInput.addEventListener('input', function() {
        processPastedOrEnteredLink(pasteLinkInput.value);
    });

    function applyPastedLink() {
        processPastedOrEnteredLink(pasteLinkInput.value);
    }
    if (applyLinkBtn) applyLinkBtn.addEventListener('click', applyPastedLink);
    pasteLinkInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyPastedLink();
        }
    });

    // Use my location
    useDeviceBtn.addEventListener('click', function() {
        if (!navigator.geolocation) {
            deviceStatus.textContent = 'Geolocation is not supported by your browser.';
            return;
        }
        deviceStatus.textContent = 'Getting location…';
        useDeviceBtn.disabled = true;
        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                setCoordinates(lat, lng);
                reverseGeocode(lat, lng, function() {
                    useDeviceBtn.disabled = false;
                });
            },
            function() {
                deviceStatus.textContent = 'Could not get location. Check permissions or try again.';
                useDeviceBtn.disabled = false;
            }
        );
    });
})();
</script>
@endpush

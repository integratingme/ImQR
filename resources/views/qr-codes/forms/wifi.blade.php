<!-- WiFi QR Code Form -->
<div class="space-y-6">
    <div>
        <label for="ssid" class="label">Network Name (SSID) *</label>
        <input type="text" id="ssid" name="ssid" class="input" placeholder="My WiFi Network" required>
    </div>

    <div>
        <label for="encryption" class="label">Encryption Type *</label>
        <select id="encryption" name="encryption" class="input" required onchange="togglePassword()">
            <option value="WPA2">WPA2 (Recommended)</option>
            <option value="WPA">WPA</option>
            <option value="WEP">WEP</option>
            <option value="nopass">No Encryption</option>
        </select>
    </div>

    <div id="password-field">
        <label for="password" class="label">Password *</label>
        <input type="text" id="password" name="password" class="input" placeholder="WiFi password" required>
    </div>
</div>

<script>
function togglePassword() {
    const encryption = document.getElementById('encryption').value;
    const passwordField = document.getElementById('password-field');
    const passwordInput = document.getElementById('password');
    
    if (encryption === 'nopass') {
        passwordField.classList.add('hidden');
        passwordInput.required = false;
        passwordInput.value = ''; // Clear password when no encryption is selected
    } else {
        passwordField.classList.remove('hidden');
        passwordInput.required = true;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePassword();
});
</script>

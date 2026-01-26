<!-- Email QR Code Form -->
<div class="space-y-6">
    <div>
        <label for="email" class="label">Email Address *</label>
        <input type="email" id="email" name="email" class="input" placeholder="contact@example.com" required>
    </div>

    <div>
        <label for="subject" class="label">Subject</label>
        <input type="text" id="subject" name="subject" class="input" placeholder="Email subject">
    </div>

    <div>
        <label for="message" class="label">Message *</label>
        <textarea id="message" name="message" rows="4" class="input" placeholder="Pre-filled message content" required></textarea>
        <p class="text-sm text-dark-200 mt-1">Scanners can send an email with this pre-filled content</p>
    </div>
</div>

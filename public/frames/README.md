# QR code frames

This folder contains SVG frames that wrap around the QR code in the generator.

## How to add your own frame

1. **Create an SVG** with this structure:
   - **ViewBox:** Use a consistent size, e.g. `400` width. Height can vary (e.g. 500 if you have a bar below, 400 if frame only).
   - **QR “hole”:** A clear area where the QR will be placed. In the reference frames it is a rectangle at **x=20, y=20, width=360, height=360** (so 20px margin from the edges in a 400px-wide SVG).
   - **Optional:** A bar or shape below with text like “Scan me!” (e.g. from y=390 down).

2. **Save the file** in this folder, e.g. `my-frame.svg`.

3. **Register the frame in the app:**
   - Open `resources/views/qr-codes/create.blade.php`.
   - In the **Frame** section (around line 491), add a new button:
     ```html
     <button type="button" class="frame-option border-2 border-dark-200 ..." data-frame="my-frame" onclick="selectFrame(this, 'my-frame')">
       <img src="{{ asset('frames/my-frame.svg') }}" alt="My frame" class="...">
       <p class="text-xs text-center mt-2 text-dark-400">My frame</p>
     </button>
     ```
   - In **FRAME_CONFIG** (in the same file, script section), add:
     ```javascript
     'my-frame': {
         url: '{{ asset("frames/my-frame.svg") }}',
         qrLeft: 5,   // 20/400 = 5%
         qrTop: 4,    // 20/500 = 4% (use 4 for 500px height)
         qrWidth: 90, // 360/400 = 90%
         qrHeight: 72, // 360/500 = 72% (adjust if your SVG height differs)
         frameWidth: 400,
         frameHeight: 500  // your SVG height
     }
     ```
   - If your “hole” is different, set `qrLeft`, `qrTop`, `qrWidth`, `qrHeight` as **percentages** of the SVG size (0–100). `frameWidth` / `frameHeight` must match your SVG viewBox.

4. **Themable frames (palette):** In your SVG use `#PRIMARY#` and `#SECONDARY#`; the app replaces them with the Step 2 colors. In FRAME_CONFIG add `themable: true`. See `menu-qr.svg` and `location.svg`.

5. **Review-us frame icons:** Predefined icons for the “Review us” frame live in `frames/review-us-icons/`. Add `.svg` files there; they appear as selectable options (see that folder’s README).

6. **Reference examples** in this folder:
   - `standard-border.svg` – single thick border + bar below.
   - `thick-border.svg` – extra thick border.
   - `speech-bubble.svg` – border + speech bubble below.
   - `menu-qr.svg`, `location.svg`, `wifi.svg`, `chat.svg`, `coupon.svg` – themable.
   - (removed: `double-border.svg`) – two rectangles around the QR.
   - `speech-bubble.svg` – border + speech bubble and “Scan me!” below.

You can use any SVG editor (Figma, Inkscape, etc.) and export SVG; just keep the “hole” area and proportions as above so the QR aligns correctly.

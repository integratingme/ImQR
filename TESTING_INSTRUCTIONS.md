# Testing Instructions for QR History Fix

## Overview
This fix ensures QR codes display with their full customization (frames, patterns, colors, logos) in the history page and downloads, exactly as shown in Step 3.

## Prerequisites
1. Make sure database is running
2. Run migrations: `php artisan migrate`
3. Make sure Vite is running: `npm run dev` or use production build: `npm run build`
4. Clear cache if needed: `php artisan cache:clear`

## Test Cases

### Test 1: Basic QR Code with Frame
**Steps:**
1. Go to homepage
2. Select any QR type (e.g., "Text")
3. Fill in required fields in Step 1
4. Click "Next Step"
5. In Step 2, select a frame (e.g., "Standard Border")
6. Select a pattern (e.g., "Rounded")
7. Select corner styles
8. Change colors (primary and secondary)
9. Click "Next Step"
10. In Step 3, verify the QR appears with the frame and all customizations
11. Click "Save QR Code"
12. Go to History page

**Expected Result:**
- QR code in history should show the same frame, pattern, and colors as Step 3
- QR code should render with JavaScript (may see brief loading spinner)

**What to Check:**
- ✓ Frame is visible around QR code
- ✓ QR code has rounded pattern
- ✓ Colors match what was selected
- ✓ No broken images or errors in console

### Test 2: QR Code with Logo
**Steps:**
1. Create a new QR code
2. In Step 2, upload a logo (use the "Upload Logo" button)
3. Verify logo appears centered in QR preview
4. Complete and save
5. Check history

**Expected Result:**
- Logo should be visible in the center of QR code in history
- Logo should be preserved in download

### Test 3: Review Us Frame with Custom Text
**Steps:**
1. Create a new QR code
2. In Step 2, select "Review Us" frame
3. Customize the frame:
   - Change frame color
   - Change text color
   - Edit the 3 text lines
   - Select a predefined icon OR upload custom logo
4. Save and check history

**Expected Result:**
- Review us frame appears with custom colors
- Custom text is displayed
- Selected icon/logo is shown

### Test 4: Download PNG
**Steps:**
1. From history page, click "PNG" button on any QR code
2. Wait for download page to load
3. Download should start automatically

**Expected Result:**
- Browser navigates to download page
- Shows "Preparing your QR code..." message
- PNG file downloads automatically after ~1-2 seconds
- Downloaded PNG has high resolution (1000px)
- Downloaded PNG includes frame, colors, pattern, logo

### Test 5: Download SVG
**Steps:**
1. From history page, click "SVG" button on any QR code
2. Wait for download

**Expected Result:**
- SVG file downloads
- SVG includes customization (may not have frame if complex)

### Test 6: Multiple QR Types
**Test each type:**
- Text QR
- PDF QR
- Menu QR
- Coupon QR
- App QR
- Phone QR
- Location QR

**For each:**
1. Create with different frame
2. Use different patterns and colors
3. Save
4. Verify in history

**Expected Result:**
- All types should render correctly in history
- Each should maintain its specific frame and customization

### Test 7: Backward Compatibility
**Steps:**
1. If you have old QR codes (created before this fix)
2. Check if they still display in history

**Expected Result:**
- Old QR codes should still work
- May not have frames (if customization data is missing)
- Should gracefully fallback to basic QR code

## Common Issues and Solutions

### Issue: QR codes show loading spinner indefinitely
**Cause:** JavaScript module not loading or QRCodeStyling not installed
**Solution:** 
```bash
npm install qr-code-styling
npm run build
```

### Issue: Frames not appearing
**Cause:** Frame SVG files missing or incorrect paths
**Solution:** 
- Check that all SVG files exist in `public/frames/`
- Check browser console for 404 errors

### Issue: Downloads not working
**Cause:** Pop-up blocker or JavaScript error
**Solution:**
- Allow pop-ups for the site
- Check browser console for errors
- Make sure QRCodeStyling library loaded correctly

### Issue: Colors not matching
**Cause:** Color normalization issue
**Solution:**
- Check that colors are saved in hex format (#RRGGBB)
- Check browser console for color-related errors

### Issue: Logo not appearing
**Cause:** Logo URL not saved or CORS issue
**Solution:**
- Verify logo_url is in customization field in database
- Check if logo is data URL (should start with data:image)

## Database Check

To verify data is saved correctly:

```sql
-- Check a QR code record
SELECT id, type, colors, customization FROM qr_codes WHERE id = 1;

-- Customization should contain:
{
  "pattern": "rounded",
  "corner_style": "rounded", 
  "corner_dot_style": "circle",
  "frame": "standard-border",
  "logo_url": "data:image/png;base64,...",
  "review_us_config": { ... } // only if frame is review-us
}
```

## Browser Console Checks

Open browser DevTools (F12) and check:

1. **Console tab:**
   - Should not have any red errors
   - May see "QR code rendered" messages (if added for debugging)

2. **Network tab:**
   - Check for failed requests (404s)
   - SVG frame files should load successfully
   - Blob URLs should be created for themed frames

3. **Elements tab:**
   - Inspect QR code containers
   - Should see `<canvas>` elements inside frame wrappers
   - Frame images should have loaded src

## Performance Notes

- First QR code render may be slightly slower (library loading)
- Subsequent renders should be fast
- History page with many QR codes will take a few seconds to render all
- Download page should complete in 1-2 seconds

## Success Criteria

All tests pass when:
- ✓ QR codes in history match Step 3 appearance
- ✓ Frames are visible and correct
- ✓ Colors, patterns, and corners are preserved
- ✓ Logos appear correctly
- ✓ Downloads include all customizations
- ✓ No JavaScript errors in console
- ✓ Page loads without broken images
- ✓ Old QR codes still work

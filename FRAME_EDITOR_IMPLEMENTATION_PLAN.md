# Frame Editor Implementation Plan

## Goal

Build a custom frame editor for Step 2 frame styling that is fully aligned with the existing application architecture:

- Keep current QR content types (`url`, `pdf`, `menu`, etc.) unchanged.
- Treat frames as styling/customization, not as a new QR type.
- Reuse the current client-side rendering approach (`qr-code-styling` + canvas composition).
- Avoid server-side HTML-to-image rendering (Puppeteer/Browsershot) in the main flow.

---

## Architecture Decision

Use a **JSON-based frame definition** rendered via a **shared canvas renderer**.

### Why this is the best fit here

- Existing app already renders framed QR on the client in Step 2 and download flow.
- Existing frames already use theme placeholders (`#PRIMARY#`, `#SECONDARY#`) and percent-based QR zones.
- This approach preserves backward compatibility for current SVG frames.
- It avoids heavy infrastructure complexity and latency from server-side image rendering.

---

## Data Model Design

### Frame Design JSON Schema (v1)

```json
{
  "version": 1,
  "canvas_width": 400,
  "canvas_height": 500,
  "background": "#ffffff",
  "qr_zone": { "x_pct": 5, "y_pct": 4, "w_pct": 90, "h_pct": 72 },
  "layers": [
    {
      "id": "l1",
      "type": "rect",
      "x": 20,
      "y": 20,
      "width": 360,
      "height": 360,
      "fill": "none",
      "stroke": "#PRIMARY#",
      "stroke_width": 10,
      "border_radius": 0,
      "opacity": 1,
      "z_index": 0
    },
    {
      "id": "l2",
      "type": "text",
      "x": 200,
      "y": 440,
      "text": "Scan me!",
      "font_family": "Arial",
      "font_size": 32,
      "font_weight": "bold",
      "color": "#SECONDARY#",
      "text_align": "center",
      "opacity": 1,
      "z_index": 1
    }
  ]
}
```

### Notes

- `#PRIMARY#` and `#SECONDARY#` must be supported in all color-like layer properties.
- `qr_zone` is stored in percentages to remain responsive to output size.
- Layer types in v1: `rect`, `circle`, `text`, `image`.

---

## Database and Models

## 1) `frame_designs` table

Create migration:

`database/migrations/2026_04_XX_create_frame_designs_table.php`

Columns:

- `id` bigIncrements
- `user_id` nullable FK to `users.id` with `nullOnDelete()`
- `name` string(255)
- `design_json` json
- `thumbnail_url` nullable string(500)
- `is_template` boolean default false
- timestamps
- index on `user_id`

## 2) `FrameDesign` model

Create:

`app/Models/FrameDesign.php`

Requirements:

- Fillable: `user_id`, `name`, `design_json`, `thumbnail_url`, `is_template`
- Casts: `design_json => array`, `is_template => boolean`
- Relations:
  - `belongsTo(User::class)`
  - `hasMany(QrCode::class, 'frame_design_id')` (only if DB column is added later)
- Helper: `isOwnedByOrTemplate(User $user): bool`

## 3) QR model integration

Recommended integration path in this codebase:

- Save selected frame design id inside existing `customization` JSON as `frame_design_id`.
- Keep existing `customization.frame` key and use value `custom` for editor-based frames.

This avoids a risky schema migration on `qr_codes` and stays aligned with current usage.

---

## Shared Renderer (Core)

Create:

`resources/js/frame-canvas-renderer.js`

### Public API

```js
async function renderFrameDesign(
  canvas,
  designJson,
  primaryColor,
  secondaryColor,
  qrCanvas = null
) {}
```

### Responsibilities

1. Set canvas dimensions from `designJson`.
2. Paint canvas background.
3. Sort `layers` by `z_index`.
4. Draw each layer by type (`rect`, `circle`, `text`, `image`).
5. Replace placeholders (`#PRIMARY#`, `#SECONDARY#`) with current QR colors.
6. Draw QR area:
   - Placeholder block in editor context.
   - Actual QR canvas in preview/download context.

### Reuse targets

- `frames/editor.blade.php` (editor preview)
- `qr-codes/create.blade.php` (Step 2 live preview)
- `qr-codes/download.blade.php` (export/download composite)

---

## Frame Editor UI

Create view:

`resources/views/frames/editor.blade.php`

### Layout

- Left panel: tools and frame settings
- Center panel: Fabric authoring canvas + output preview canvas
- Right panel: selected element properties + layer ordering

### Feature set (v1)

- Add layers: text, rectangle, circle, image
- Edit properties: position, size, fill, stroke, opacity
- Text controls: font family, size, weight, alignment, content
- Layer order: move up/down
- Non-deletable `qr_zone` object with drag + resize
- Frame name input
- Save button

### Fabric.js scope

- Fabric.js is used only in the editor page as an authoring tool.
- Final app rendering uses the shared canvas renderer, not Fabric.

### Thumbnail generation

On save:

1. Render a reduced preview (e.g., 200x250) on offscreen canvas.
2. Convert to PNG data URL.
3. Send with `design_json` as `thumbnail_data_url`.

---

## Backend API

Create controller:

`app/Http/Controllers/FrameDesignController.php`

### Endpoints

- `GET /frames/editor` -> editor page
- `GET /frames/{id}/edit` -> editor page with existing design
- `POST /frames` -> create design
- `GET /frames` -> user frames + templates list
- `GET /frames/{id}` -> full design JSON
- `DELETE /frames/{id}` -> delete user-owned frame

### Validation (store)

- `name`: required|string|max:255
- `design_json`: required|array
- `design_json.version`: required|integer
- `design_json.canvas_width`: required|integer|min:100|max:1000
- `design_json.canvas_height`: required|integer|min:100|max:1200
- `design_json.qr_zone.*`: numeric and bounded
- `design_json.layers`: array|max:30
- `thumbnail_data_url`: nullable|string|max reasonable bound

### Security

- All routes under `auth` middleware.
- `show/delete/edit` must enforce ownership or template access.
- Never trust client-provided image paths for stored assets.

---

## Route Plan

Add in `routes/web.php`:

```php
Route::middleware('auth')->prefix('frames')->name('frames.')->group(function () {
    Route::get('/editor', [FrameDesignController::class, 'editor'])->name('editor');
    Route::get('/{id}/edit', [FrameDesignController::class, 'edit'])->name('edit');
    Route::get('/', [FrameDesignController::class, 'index'])->name('index');
    Route::post('/', [FrameDesignController::class, 'store'])->name('store');
    Route::get('/{id}', [FrameDesignController::class, 'show'])->name('show');
    Route::delete('/{id}', [FrameDesignController::class, 'destroy'])->name('destroy');
});
```

---

## Step 2 Integration Plan

Modify:

- `app/Http/Controllers/QrCodeController.php`
- `resources/views/qr-codes/create.blade.php`

### Behavior

- Keep current SVG frame options as they are.
- Add a new "Custom Frames" section in Step 2:
  - `Create new frame` button -> `/frames/editor`
  - Existing user/template custom frames as selectable cards (thumbnail + name)
- On selection:
  - set `frame = custom`
  - set hidden `frame_design_id`
  - load `design_json`
  - render through shared renderer into preview

### Payload

Store custom frame selection in existing customization data:

```json
{
  "frame": "custom",
  "frame_design_id": 123
}
```

---

## Download Flow Integration

Modify:

`resources/views/qr-codes/download.blade.php`

### Behavior

- If frame is standard SVG -> keep existing logic unchanged.
- If frame is `custom`:
  1. fetch design JSON by `frame_design_id`
  2. render frame using shared renderer
  3. compose QR in `qr_zone`
  4. export as PNG

SVG export for custom canvas frames can remain out-of-scope in v1 (PNG fallback).

---

## Implementation Phases

## Phase A: Foundation

1. Add `frame_designs` migration
2. Add `FrameDesign` model
3. Add frame relationships on `User` model

## Phase B: Rendering Core

4. Implement `frame-canvas-renderer.js`
5. Add placeholder test page or debug harness

## Phase C: Editor

6. Build `frames/editor.blade.php` with Fabric
7. Implement serialize/deserialize to `design_json`
8. Implement save endpoint + thumbnail handling

## Phase D: Product Integration

9. Add custom frame picker in Step 2
10. Update Step 2 preview pipeline to include custom frame rendering
11. Update download page logic for custom frames

## Phase E: Hardening

12. Validation hardening and ownership checks
13. Add template seed(s) for frame designs
14. Add feature tests for frame CRUD and ownership

---

## Testing Plan

### Backend

- Frame create/list/show/delete (auth owner)
- Access denied for non-owner frame access
- Template frame read allowed
- Validation rejects malformed `design_json`

### Frontend

- Editor: add/edit/delete/reorder layers
- QR zone cannot be deleted
- Save + reload roundtrip preserves visual output
- Step 2 preview updates with color changes and custom frame selection
- Download output visually matches Step 2 preview

---

## Non-Goals (for v1)

- No new QR type (`custom_frame`)
- No Puppeteer/Browsershot rendering in primary request flow
- No replacement of existing SVG frame files
- No migration of legacy frame data

---

## Risks and Mitigations

- **Large design JSON or embedded images**
  - Mitigation: layer count limits + image size constraints + optional image upload to storage in v2.
- **Cross-origin image taint in canvas**
  - Mitigation: same-origin uploads only; sanitize image sources.
- **Preview/download mismatch**
  - Mitigation: one shared renderer used by editor, Step 2, and download.

---

## Recommended File List

### New

- `database/migrations/2026_04_XX_create_frame_designs_table.php`
- `app/Models/FrameDesign.php`
- `app/Http/Controllers/FrameDesignController.php`
- `resources/views/frames/editor.blade.php`
- `resources/js/frame-canvas-renderer.js`
- `resources/views/qr-codes/forms/custom-frames-picker.blade.php` (optional partial)

### Modified

- `app/Http/Controllers/QrCodeController.php`
- `app/Models/User.php`
- `resources/views/qr-codes/create.blade.php`
- `resources/views/qr-codes/download.blade.php`
- `routes/web.php`
- `resources/js/app.js` (or relevant Vite entry)

---

## Delivery Estimate

- Phase A: ~1 hour
- Phase B: ~3 hours
- Phase C: ~5 hours
- Phase D: ~3 hours
- Phase E: ~2 hours

Estimated total: **~14 hours** for a complete v1.

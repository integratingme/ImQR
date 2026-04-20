# Cursor Agent Prompt — QR Frame Editor (Laravel + Blade + Fabric.js)

> Paste this entire prompt into Cursor Agent. It covers Phases A through D.
> Execute phases in order. Do not skip ahead. After each phase, confirm what was created before proceeding.

---

## CONTEXT & CONSTRAINTS

You are working inside a **Laravel 10+ application** with **Blade templating**, **Tailwind CSS**, and **Vite** as the asset bundler. The app already has:

- A multi-step QR code creator (`resources/views/qr-codes/create.blade.php`)
- A download/export page (`resources/views/qr-codes/download.blade.php`)
- A `QrCode` model with a `customization` JSON column (already in use — do NOT alter its schema)
- Existing SVG-based frame rendering on the client using `qr-code-styling` and a canvas compositor
- Existing frame placeholders `#PRIMARY#` and `#SECONDARY#` in SVG frames
- An authenticated `User` model at `app/Models/User.php`
- Standard Laravel auth middleware

**Hard rules you must follow at all times:**

1. Do NOT create a new QR content type. Frames are styling, not a QR type.
2. Do NOT touch the `qr_codes` table schema. Store frame selection inside the existing `customization` JSON column.
3. Do NOT introduce Puppeteer, Browsershot, or any server-side image rendering.
4. Do NOT break or replace any existing SVG frame logic. New custom frames live alongside it.
5. Fabric.js is used ONLY in the editor page as an authoring tool. The shared renderer (`frame-canvas-renderer.js`) does all final rendering — not Fabric.
6. Use `shallowRef` or plain variables for Fabric canvas instances — never put a Fabric canvas object inside Vue/Alpine reactive state.
7. All new routes must be under `auth` middleware.
8. Never trust client-provided image URLs for stored image layers. Same-origin only.

---

## PHASE A — DATABASE & MODELS

### A1. Migration

Create file: `database/migrations/2026_04_10_000000_create_frame_designs_table.php`

The migration must create a `frame_designs` table with exactly these columns:

```php
$table->id();
$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
$table->string('name', 255);
$table->json('design_json');
$table->string('thumbnail_url', 500)->nullable();
$table->boolean('is_template')->default(false);
$table->timestamps();
$table->index('user_id');
```

### A2. FrameDesign Model

Create file: `app/Models/FrameDesign.php`

Requirements:
- `$fillable`: `['user_id', 'name', 'design_json', 'thumbnail_url', 'is_template']`
- `$casts`: `['design_json' => 'array', 'is_template' => 'boolean']`
- Relationship: `belongsTo(User::class)`
- Helper method:

```php
public function isOwnedByOrTemplate(User $user): bool
{
    return $this->is_template || $this->user_id === $user->id;
}
```

### A3. User Model

Open `app/Models/User.php` and add:

```php
public function frameDesigns(): HasMany
{
    return $this->hasMany(FrameDesign::class);
}
```

### A4. Run the migration

Run `php artisan migrate` and confirm the table was created.

---

## PHASE B — SHARED CANVAS RENDERER

Create file: `resources/js/frame-canvas-renderer.js`

This is the most critical file in the entire feature. It is used in three places: the editor preview, the Step 2 live preview, and the download/export page. It must never depend on Fabric.js.

### B1. Design JSON Schema

The renderer accepts a `designJson` object in this format:

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
      "x": 20, "y": 20,
      "width": 360, "height": 360,
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
      "x": 200, "y": 440,
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

Layer types supported in v1: `rect`, `circle`, `text`, `image`.

### B2. Public API

Export a single async function:

```js
/**
 * Renders a frame design onto a given HTMLCanvasElement.
 *
 * @param {HTMLCanvasElement} canvas        - Target canvas to draw onto
 * @param {Object}            designJson    - Parsed frame design JSON (v1 schema)
 * @param {string}            primaryColor  - Replaces all #PRIMARY# placeholders
 * @param {string}            secondaryColor- Replaces all #SECONDARY# placeholders
 * @param {HTMLCanvasElement|null} qrCanvas - If provided, draw real QR into qr_zone.
 *                                            If null, draw a grey placeholder block.
 */
export async function renderFrameDesign(
  canvas,
  designJson,
  primaryColor,
  secondaryColor,
  qrCanvas = null
) {}
```

### B3. Renderer Implementation Requirements

Implement the function body with this exact logic:

1. **Set canvas dimensions** from `designJson.canvas_width` and `designJson.canvas_height`.
2. **Get 2D context** and clear the canvas.
3. **Paint background** using `designJson.background`.
4. **Resolve color** helper: `function resolveColor(value, primary, secondary)` — replaces `#PRIMARY#` with `primaryColor` and `#SECONDARY#` with `secondaryColor`. Applied to all color-like properties before drawing.
5. **Sort layers** by `z_index` ascending.
6. **Draw each layer** using a `switch` on `layer.type`:

   - **`rect`**: `ctx.beginPath()`, use `roundRect()` if `border_radius > 0`, else `rect()`. Apply `fill` and/or `stroke` after resolving colors. Apply `opacity` via `ctx.globalAlpha`.
   - **`circle`**: `ctx.arc()` centered at `(x, y)` with `radius`. Apply fill/stroke.
   - **`text`**: Set `ctx.font` from `font_weight + font_size + font_family`. Set `ctx.fillStyle` from resolved `color`. Set `ctx.textAlign` from `text_align`. Call `ctx.fillText(layer.text, layer.x, layer.y)`.
   - **`image`**: Load image via `new Image()` + `await` a Promise on `onload`/`onerror`. Draw with `ctx.drawImage()`. Skip silently on load error.

7. **Draw QR zone** last (always on top of all layers):
   - Calculate absolute pixel coords from `qr_zone` percentages: `x = canvas.width * (qr_zone.x_pct / 100)`, same for y, w, h.
   - If `qrCanvas` is provided: `ctx.drawImage(qrCanvas, x, y, w, h)`
   - If `qrCanvas` is null: draw a light grey filled rect with a dashed border and centered text "QR Code" — this is the editor placeholder.

8. **Always reset** `ctx.globalAlpha` to 1 after each layer.

### B4. Export

The file must use ES module syntax (`export async function renderFrameDesign`). It will be imported by Vite.

### B5. Register in Vite entry

Open `resources/js/app.js` (or the relevant Vite entry point) and add:

```js
import { renderFrameDesign } from './frame-canvas-renderer.js';
window.renderFrameDesign = renderFrameDesign;
```

This exposes it to Blade pages that use inline `<script>` blocks.

---

## PHASE C — FABRIC.JS EDITOR

### C1. Install Fabric.js

Run:

```bash
npm install fabric
```

Confirm it is added to `package.json`.

### C2. Backend: Controller

Create file: `app/Http/Controllers/FrameDesignController.php`

Implement these methods:

#### `editor(Request $request)`
Returns view `frames.editor` with `$design = null`.

#### `edit(Request $request, int $id)`
Find `FrameDesign::findOrFail($id)`. Abort 403 if not `isOwnedByOrTemplate(auth()->user())`. Return view `frames.editor` with `$design`.

#### `index(Request $request)`
Return JSON: user's own frames + all templates.

```php
$frames = FrameDesign::where('user_id', auth()->id())
    ->orWhere('is_template', true)
    ->orderBy('is_template')
    ->orderBy('created_at', 'desc')
    ->get(['id', 'name', 'thumbnail_url', 'is_template']);

return response()->json($frames);
```

#### `show(Request $request, int $id)`
Find frame. Abort 403 if not accessible. Return full JSON: `['id', 'name', 'design_json', 'thumbnail_url', 'is_template']`.

#### `store(Request $request)`
Validate:

```php
$validated = $request->validate([
    'name'                        => 'required|string|max:255',
    'design_json'                 => 'required|array',
    'design_json.version'         => 'required|integer',
    'design_json.canvas_width'    => 'required|integer|min:100|max:1000',
    'design_json.canvas_height'   => 'required|integer|min:100|max:1200',
    'design_json.qr_zone'         => 'required|array',
    'design_json.qr_zone.x_pct'   => 'required|numeric|min:0|max:100',
    'design_json.qr_zone.y_pct'   => 'required|numeric|min:0|max:100',
    'design_json.qr_zone.w_pct'   => 'required|numeric|min:5|max:100',
    'design_json.qr_zone.h_pct'   => 'required|numeric|min:5|max:100',
    'design_json.layers'          => 'required|array|max:30',
    'thumbnail_data_url'          => 'nullable|string|max:500000',
]);
```

Handle thumbnail: if `thumbnail_data_url` is provided and starts with `data:image/png;base64,`, decode and store to `storage/app/public/frame-thumbnails/{uuid}.png`. Set `thumbnail_url` to the public URL via `Storage::url(...)`. If not provided, set `thumbnail_url` to null.

Create and return the `FrameDesign`:

```php
$frame = FrameDesign::create([
    'user_id'      => auth()->id(),
    'name'         => $validated['name'],
    'design_json'  => $validated['design_json'],
    'thumbnail_url'=> $thumbnailUrl,
    'is_template'  => false,
]);

return response()->json($frame, 201);
```

#### `destroy(Request $request, int $id)`
Find frame. Abort 403 if `user_id !== auth()->id()` (users cannot delete templates). Delete thumbnail file from storage if exists. Delete model. Return 204.

### C3. Routes

Add to `routes/web.php`:

```php
use App\Http\Controllers\FrameDesignController;

Route::middleware('auth')->prefix('frames')->name('frames.')->group(function () {
    Route::get('/editor',      [FrameDesignController::class, 'editor'])->name('editor');
    Route::get('/{id}/edit',   [FrameDesignController::class, 'edit'])->name('edit');
    Route::get('/',            [FrameDesignController::class, 'index'])->name('index');
    Route::post('/',           [FrameDesignController::class, 'store'])->name('store');
    Route::get('/{id}',        [FrameDesignController::class, 'show'])->name('show');
    Route::delete('/{id}',     [FrameDesignController::class, 'destroy'])->name('destroy');
});
```

Note: Place the `editor` and `index` routes BEFORE the `{id}` wildcard routes to avoid routing conflicts.

### C4. Editor View

Create file: `resources/views/frames/editor.blade.php`

This view must extend the app layout and load Fabric.js via Vite. The page has a **three-panel layout** using Tailwind CSS:

```
[ Left Panel (240px) ] [ Center Canvas Area (flex-1) ] [ Right Panel (280px) ]
```

#### Left Panel — Toolbox
Contains:
- **Frame Settings** section: text input for frame name (id: `frame-name-input`), canvas width/height inputs (defaults: 400 x 500), background color picker
- **Add Layer** section: four buttons — "＋ Rectangle", "＋ Circle", "＋ Text", "＋ Image"
- **Layer List** section: a `<ul id="layer-list">` — each `<li>` shows layer type icon + id, has "▲ ▼" reorder buttons and a "🗑" delete button (disabled/hidden for `qr_zone`)

#### Center Panel — Canvas Area
Contains:
- A `<div class="relative">` wrapper with Tailwind `aspect-[4/5] max-w-[480px] w-full mx-auto`
- Inside: `<canvas id="fabric-canvas"></canvas>` — this is the Fabric.js authoring canvas
- Below: `<canvas id="preview-canvas" class="mt-4 w-full"></canvas>` — this is the read-only output preview rendered by `frame-canvas-renderer.js`
- A "Refresh Preview" button that manually triggers preview re-render

#### Right Panel — Properties
Contains a `<div id="properties-panel">` that shows contextual controls for the selected Fabric object:
- **If nothing selected**: placeholder text "Select a layer to edit its properties"
- **If rect or circle selected**: fill color picker, stroke color picker, stroke width input, opacity slider, X/Y position inputs, width/height inputs
- **If text selected**: all of the above + font family select (Arial, Georgia, Verdana, monospace options), font size input, font weight toggle (normal/bold), text alignment (left/center/right), text content textarea
- **If qr_zone selected**: only X/Y/W/H inputs (no fill/stroke/delete controls)

#### JavaScript (inline `<script type="module">` at bottom of view)

Implement the following with plain JavaScript (no Alpine, no Vue):

**Initialization:**

```js
import { renderFrameDesign } from '/build/assets/frame-canvas-renderer-[hash].js';
// Use window.renderFrameDesign instead if the above hash is unknown at blade time
```

Use `window.renderFrameDesign` (set in app.js) to avoid hash issues.

```js
document.addEventListener('DOMContentLoaded', () => {
  const fabricCanvas = new fabric.Canvas('fabric-canvas', {
    width: 400,
    height: 500,
    backgroundColor: '#ffffff',
    preserveObjectStacking: true,
  });

  // Add QR zone placeholder — this object cannot be deleted
  const qrZone = new fabric.Rect({
    left: 20, top: 20,
    width: 360, height: 360,
    fill: 'rgba(100,160,255,0.15)',
    stroke: '#4a90e2',
    strokeWidth: 2,
    strokeDashArray: [6, 4],
    rx: 4, ry: 4,
    selectable: true,
    hasControls: true,
    lockUniScaling: true,
    id: 'qr_zone',
    layerType: 'qr_zone',
  });

  fabricCanvas.add(qrZone);
  fabricCanvas.renderAll();
```

**Add Layer buttons:** Each "Add" button creates a Fabric object with a unique `id` (e.g., `layer_${Date.now()}`) and `layerType` property set. Default positions should be centered on canvas.

**Selection handler:** On `fabricCanvas.on('selection:created')` and `selection:updated`, read `fabricCanvas.getActiveObject()` and populate the right panel with the correct controls. On `selection:cleared`, show the placeholder text.

**Property inputs:** Each input in the right panel has an event listener that calls `activeObject.set(property, value)` followed by `fabricCanvas.renderAll()`.

**Layer list sync:** After every add/delete/reorder, re-render the `<ul id="layer-list">` from `fabricCanvas.getObjects()`. The qr_zone item must have its delete button disabled and shown greyed out.

**Reorder buttons:** "▲" calls `fabricCanvas.bringForward(obj)`, "▼" calls `fabricCanvas.sendBackwards(obj)`. Then sync layer list.

**Delete button:** Calls `fabricCanvas.remove(obj)` then sync layer list. Must be disabled for `qr_zone`.

**Centering guidelines:** On `object:moving`, check if the moving object's center is within 8px of the canvas center. If so, snap it and draw a temporary red dashed line across the canvas center (horizontal or vertical). Remove the line on `object:modified` or `mouse:up`.

**Serialize to design_json:** Create a function `serializeToDesignJson()`:

```js
function serializeToDesignJson() {
  const canvasWidth  = fabricCanvas.getWidth();
  const canvasHeight = fabricCanvas.getHeight();
  const objects      = fabricCanvas.getObjects();

  const qrObj = objects.find(o => o.id === 'qr_zone');
  const qrZoneJson = {
    x_pct: (qrObj.left / canvasWidth) * 100,
    y_pct: (qrObj.top  / canvasHeight) * 100,
    w_pct: (qrObj.getScaledWidth()  / canvasWidth)  * 100,
    h_pct: (qrObj.getScaledHeight() / canvasHeight) * 100,
  };

  const layers = objects
    .filter(o => o.id !== 'qr_zone')
    .map((o, idx) => {
      const base = {
        id: o.id,
        type: o.layerType,
        x: Math.round(o.left),
        y: Math.round(o.top),
        opacity: o.opacity,
        z_index: idx,
      };
      if (o.layerType === 'rect') {
        return { ...base,
          width: Math.round(o.getScaledWidth()),
          height: Math.round(o.getScaledHeight()),
          fill: o.fill || 'none',
          stroke: o.stroke || 'none',
          stroke_width: o.strokeWidth,
          border_radius: o.rx || 0,
        };
      }
      if (o.layerType === 'circle') {
        return { ...base,
          radius: Math.round(o.radius * o.scaleX),
          fill: o.fill || 'none',
          stroke: o.stroke || 'none',
          stroke_width: o.strokeWidth,
        };
      }
      if (o.layerType === 'text') {
        return { ...base,
          text: o.text,
          font_family: o.fontFamily,
          font_size: o.fontSize,
          font_weight: o.fontWeight,
          color: o.fill,
          text_align: o.textAlign,
        };
      }
      if (o.layerType === 'image') {
        return { ...base,
          src: o.getSrc(),
          width: Math.round(o.getScaledWidth()),
          height: Math.round(o.getScaledHeight()),
        };
      }
      return base;
    });

  return {
    version: 1,
    canvas_width: canvasWidth,
    canvas_height: canvasHeight,
    background: fabricCanvas.backgroundColor,
    qr_zone: qrZoneJson,
    layers,
  };
}
```

**Deserialize (for edit mode):** If `$design` is not null (passed from Blade as `@json($design)`), on init call `loadDesignJson(designJson)`:

```js
async function loadDesignJson(designJson) {
  fabricCanvas.setDimensions({
    width: designJson.canvas_width,
    height: designJson.canvas_height,
  });
  fabricCanvas.setBackgroundColor(designJson.background, fabricCanvas.renderAll.bind(fabricCanvas));

  // Restore qr_zone from percentages
  const w = designJson.canvas_width;
  const h = designJson.canvas_height;
  const qz = designJson.qr_zone;
  qrZone.set({
    left:  (qz.x_pct / 100) * w,
    top:   (qz.y_pct / 100) * h,
    width: (qz.w_pct / 100) * w,
    height:(qz.h_pct / 100) * h,
    scaleX: 1, scaleY: 1,
  });

  // Re-add layers
  for (const layer of designJson.layers.sort((a,b) => a.z_index - b.z_index)) {
    // create Fabric objects from layer data (reverse of serializeToDesignJson)
    // ... (implement per type)
  }

  fabricCanvas.renderAll();
}
```

**Thumbnail generation:**

```js
function generateThumbnail() {
  const offscreen = document.createElement('canvas');
  offscreen.width  = 200;
  offscreen.height = 250;
  // Use renderFrameDesign with designJson and placeholder colors
  return window.renderFrameDesign(offscreen, serializeToDesignJson(), '#333333', '#666666', null)
    .then(() => offscreen.toDataURL('image/png'));
}
```

**Save button handler:**

```js
document.getElementById('save-btn').addEventListener('click', async () => {
  const designJson      = serializeToDesignJson();
  const name            = document.getElementById('frame-name-input').value.trim();
  const thumbnailDataUrl = await generateThumbnail();

  if (!name) {
    alert('Please enter a frame name.');
    return;
  }

  const existingId = document.getElementById('save-btn').dataset.frameId || null;
  const url    = existingId ? `/frames/${existingId}` : '/frames';
  const method = existingId ? 'PUT' : 'POST';

  const res = await fetch(url, {
    method,
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({ name, design_json: designJson, thumbnail_data_url: thumbnailDataUrl }),
  });

  if (res.ok) {
    const data = await res.json();
    document.getElementById('save-btn').dataset.frameId = data.id;
    // Show success toast
  } else {
    // Show error toast with res.statusText
  }
});
```

Note: Add a `PUT /frames/{id}` route and `update()` controller method that mirrors `store()` with ownership check.

**Preview canvas refresh:** On "Refresh Preview" button click and after every `object:modified` event:

```js
function refreshPreview() {
  const previewCanvas = document.getElementById('preview-canvas');
  const designJson    = serializeToDesignJson();
  window.renderFrameDesign(previewCanvas, designJson, '#000000', '#555555', null);
}
```

---

## PHASE D — STEP 2 INTEGRATION

### D1. Custom Frames Partial

Create file: `resources/views/qr-codes/forms/custom-frames-picker.blade.php`

This partial is included inside the existing Step 2 frame selection section. It renders:

1. A section heading: "Custom Frames"
2. A card grid (`grid grid-cols-2 gap-3 sm:grid-cols-3`) that is populated via JavaScript (AJAX call to `GET /frames`)
3. A "+ Create New Frame" card that links to `/frames/editor` in a new tab
4. A hidden input: `<input type="hidden" name="frame_design_id" id="frame-design-id-input">`

Each frame card (rendered by JS) shows:
- Thumbnail image (or a grey placeholder if no thumbnail)
- Frame name below
- A selected state (ring highlight) when active
- On click: set `frame_design_id` hidden input, set `frame = custom` on the existing frame radio/select, trigger the preview update

### D2. Load frames via JS

In the partial's script section:

```js
async function loadCustomFrames() {
  const res     = await fetch('/frames', { headers: { 'Accept': 'application/json' } });
  const frames  = await res.json();
  const grid    = document.getElementById('custom-frames-grid');

  frames.forEach(frame => {
    const card = document.createElement('div');
    card.className   = 'cursor-pointer rounded-lg border-2 border-transparent p-1 transition hover:border-indigo-400';
    card.dataset.frameId = frame.id;
    card.innerHTML = `
      <img src="${frame.thumbnail_url || '/images/frame-placeholder.svg'}"
           class="w-full aspect-[4/5] object-cover rounded" alt="${frame.name}" />
      <p class="text-xs text-center mt-1 truncate">${frame.name}</p>
    `;
    card.addEventListener('click', () => selectCustomFrame(frame.id, card));
    grid.prepend(card);
  });
}

async function selectCustomFrame(frameId, cardEl) {
  // Deselect all cards
  document.querySelectorAll('#custom-frames-grid [data-frame-id]')
    .forEach(c => c.classList.remove('border-indigo-500'));

  // Select this card
  cardEl.classList.add('border-indigo-500');

  // Set hidden input
  document.getElementById('frame-design-id-input').value = frameId;

  // Set frame type to 'custom' on existing frame selector
  // (adjust selector to match actual element in create.blade.php)
  const frameInput = document.querySelector('[name="customization[frame]"]');
  if (frameInput) frameInput.value = 'custom';

  // Load design JSON and render preview
  const res        = await fetch(`/frames/${frameId}`, { headers: { 'Accept': 'application/json' } });
  const frameData  = await res.json();
  const designJson = frameData.design_json;

  // Get current QR colors from existing Step 2 color pickers
  const primaryColor   = document.querySelector('[name="customization[primary_color]"]')?.value   || '#000000';
  const secondaryColor = document.querySelector('[name="customization[secondary_color]"]')?.value || '#555555';

  // Render into existing Step 2 preview canvas
  const previewCanvas = document.getElementById('qr-preview-canvas'); // adjust to match real id
  if (previewCanvas && window.renderFrameDesign) {
    await window.renderFrameDesign(previewCanvas, designJson, primaryColor, secondaryColor, null);
    // Pass actual qrCanvas if available: replace null with the qr-code-styling canvas element
  }

  // Store design_json in a hidden input for use by download page
  let designJsonInput = document.getElementById('frame-design-json-input');
  if (!designJsonInput) {
    designJsonInput = document.createElement('input');
    designJsonInput.type = 'hidden';
    designJsonInput.id   = 'frame-design-json-input';
    designJsonInput.name = 'customization[frame_design_json]';
    document.querySelector('form').appendChild(designJsonInput);
  }
  designJsonInput.value = JSON.stringify(designJson);
}

document.addEventListener('DOMContentLoaded', loadCustomFrames);
```

### D3. Include Partial in create.blade.php

Open `resources/views/qr-codes/create.blade.php`.

Find the existing frame selection section in Step 2. After the existing SVG frame options block, add:

```blade
@include('qr-codes.forms.custom-frames-picker')
```

Do not remove or modify any existing SVG frame logic.

### D4. QrCodeController — pass frame data

Open `app/Http/Controllers/QrCodeController.php`.

In the `store` / `update` method, when reading `customization` from the request, ensure that if `customization.frame === 'custom'` and `customization.frame_design_id` is present, those values are stored as-is into the `customization` JSON. No additional processing needed — the JSON column handles it.

### D5. Download Page Integration

Open `resources/views/qr-codes/download.blade.php`.

Add a script block that checks if the stored customization has `frame === 'custom'`. If so:

```js
const customization = @json($qrCode->customization);

if (customization.frame === 'custom' && customization.frame_design_id) {
  fetch(`/frames/${customization.frame_design_id}`, {
    headers: { 'Accept': 'application/json' }
  })
  .then(r => r.json())
  .then(async frameData => {
    const primaryColor   = customization.primary_color   || '#000000';
    const secondaryColor = customization.secondary_color || '#555555';

    // Get the QR canvas produced by qr-code-styling (adjust selector)
    const qrCanvas = document.querySelector('#qr-code-container canvas');

    const outputCanvas = document.getElementById('download-canvas'); // adjust to match real id
    await window.renderFrameDesign(
      outputCanvas,
      frameData.design_json,
      primaryColor,
      secondaryColor,
      qrCanvas
    );
  });
}
// Else: existing SVG frame logic runs unchanged
```

---

## FINAL CHECKLIST

Before marking implementation complete, verify:

- [ ] `php artisan migrate` runs without errors
- [ ] `FrameDesign` model has correct fillable, casts, and relations
- [ ] `frame-canvas-renderer.js` handles all 4 layer types without throwing
- [ ] `#PRIMARY#` and `#SECONDARY#` are resolved in all color properties
- [ ] QR zone placeholder renders when `qrCanvas` is null
- [ ] QR zone object in Fabric cannot be deleted
- [ ] Centering snap/guidelines work in editor
- [ ] Serialize → save → reload → deserialize roundtrip preserves visual output
- [ ] All `/frames` routes are behind `auth` middleware
- [ ] Ownership check on show/edit/delete — non-owners get 403
- [ ] Template frames are readable by any authenticated user
- [ ] `POST /frames` saves thumbnail to storage and sets `thumbnail_url`
- [ ] Step 2: custom frame cards load from `/frames` on page load
- [ ] Step 2: selecting a custom frame updates the preview canvas
- [ ] Download page: if `frame === 'custom'`, renders via shared renderer with real QR canvas
- [ ] Existing SVG frame logic is completely untouched
- [ ] No Fabric.js dependency in `frame-canvas-renderer.js`
- [ ] `npm run build` completes without errors
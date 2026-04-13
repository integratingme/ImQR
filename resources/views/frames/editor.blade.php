@extends('layouts.app')

@section('title', 'Frame Editor')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-dark-500">Frame Editor</h1>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <aside class="lg:col-span-3 card p-4 space-y-4">
            <div>
                <label class="label" for="frame_name">Frame name</label>
                <input id="frame_name" type="text" class="input" value="{{ $frame->name ?? 'My Frame' }}">
            </div>
            <div>
                <label class="label">Canvas size</label>
                <div class="grid grid-cols-2 gap-2">
                    <input id="canvas_width" type="number" class="input" value="{{ $frame->design_json['canvas_width'] ?? 400 }}" min="100" max="1000">
                    <input id="canvas_height" type="number" class="input" value="{{ $frame->design_json['canvas_height'] ?? 500 }}" min="100" max="1200">
                </div>
            </div>
            <div>
                <label class="label" for="canvas_bg">Background</label>
                <input id="canvas_bg" type="color" class="h-10 w-full border border-dark-200 rounded" value="{{ $frame->design_json['background'] ?? '#ffffff' }}">
            </div>

            <div class="space-y-2">
                <button type="button" onclick="addTextLayer()" class="btn btn-secondary w-full">+ Add Text</button>
                <button type="button" onclick="addRectLayer()" class="btn btn-secondary w-full">+ Add Rectangle</button>
                <button type="button" onclick="addCircleLayer()" class="btn btn-secondary w-full">+ Add Circle</button>
                <button type="button" onclick="document.getElementById('image_layer_input').click()" class="btn btn-secondary w-full">+ Add Image</button>
                <input id="image_layer_input" type="file" accept=".png,.jpg,.jpeg,image/png,image/jpeg" class="hidden">
            </div>

            <button type="button" onclick="saveFrameDesign()" class="btn btn-primary w-full">Save Frame</button>
            <div id="save_status" class="text-xs text-dark-300"></div>
        </aside>

        <section class="lg:col-span-6 card p-4">
            <p class="text-sm text-dark-300 mb-3">Drag layers directly on canvas. Dashed block is QR position (editable).</p>
            <canvas id="editor_canvas" class="border border-dark-200 rounded mx-auto"></canvas>
            <div class="mt-4 border-t border-dark-100 pt-4">
                <p class="text-sm text-dark-300 mb-2">Renderer preview</p>
                <canvas id="renderer_preview" class="border border-dark-200 rounded mx-auto"></canvas>
            </div>
        </section>

        <aside class="lg:col-span-3 card p-4">
            <h3 class="font-semibold text-dark-500 mb-3">Selected element</h3>
            <div id="props_empty" class="text-sm text-dark-300">Select an element to edit.</div>
            <div id="props_panel" class="hidden space-y-2">
                <label class="label">Fill</label>
                <input id="prop_fill" type="color" class="h-10 w-full border border-dark-200 rounded">

                <label class="label">Opacity</label>
                <input id="prop_opacity" type="range" min="0" max="1" step="0.05" class="w-full">

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="moveSelectedLayerUp()" class="btn btn-secondary">Layer Up</button>
                    <button type="button" onclick="moveSelectedLayerDown()" class="btn btn-secondary">Layer Down</button>
                </div>
                <button type="button" onclick="deleteSelectedLayer()" class="btn btn-outline w-full">Delete</button>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script>
const existingFrame = @json($frame ?? null);
const editorCanvasEl = document.getElementById('editor_canvas');
const previewCanvasEl = document.getElementById('renderer_preview');
const widthInput = document.getElementById('canvas_width');
const heightInput = document.getElementById('canvas_height');
const bgInput = document.getElementById('canvas_bg');
let selectedObj = null;

const fabricCanvas = new fabric.Canvas('editor_canvas', {
    width: Number(widthInput.value || 400),
    height: Number(heightInput.value || 500),
    backgroundColor: bgInput.value || '#ffffff'
});

function createQrZoneFromPercent(zone, width, height) {
    return new fabric.Rect({
        left: (zone.x_pct / 100) * width,
        top: (zone.y_pct / 100) * height,
        width: (zone.w_pct / 100) * width,
        height: (zone.h_pct / 100) * height,
        fill: 'rgba(79, 70, 229, 0.08)',
        stroke: '#4f46e5',
        strokeDashArray: [8, 4],
        strokeWidth: 2,
        name: 'qr_zone'
    });
}

function ensureQrZone() {
    const existing = fabricCanvas.getObjects().find(obj => obj.name === 'qr_zone');
    if (existing) return existing;
    const zone = createQrZoneFromPercent({ x_pct: 5, y_pct: 4, w_pct: 90, h_pct: 72 }, fabricCanvas.width, fabricCanvas.height);
    fabricCanvas.add(zone);
    return zone;
}

function addTextLayer() {
    const text = new fabric.Textbox('Scan me!', {
        left: 80, top: 420, fontSize: 32, fill: '#000000'
    });
    fabricCanvas.add(text).setActiveObject(text);
}

function addRectLayer() {
    const rect = new fabric.Rect({
        left: 30, top: 30, width: 340, height: 340, fill: 'transparent', stroke: '#000000', strokeWidth: 10
    });
    fabricCanvas.add(rect).setActiveObject(rect);
}

function addCircleLayer() {
    const circle = new fabric.Circle({ left: 170, top: 170, radius: 70, fill: '#cccccc' });
    fabricCanvas.add(circle).setActiveObject(circle);
}

function addImageLayerFromDataUrl(dataUrl) {
    fabric.Image.fromURL(dataUrl, (img) => {
        img.set({
            left: 80,
            top: 80,
            scaleX: 0.5,
            scaleY: 0.5,
            opacity: 1
        });
        fabricCanvas.add(img);
        fabricCanvas.setActiveObject(img);
        fabricCanvas.renderAll();
    }, { crossOrigin: 'anonymous' });
}

function deleteSelectedLayer() {
    const active = fabricCanvas.getActiveObject();
    if (!active || active.name === 'qr_zone') return;
    fabricCanvas.remove(active);
    fabricCanvas.renderAll();
}

function moveSelectedLayerUp() {
    const active = fabricCanvas.getActiveObject();
    if (!active || active.name === 'qr_zone') return;
    fabricCanvas.bringForward(active);
    fabricCanvas.renderAll();
    syncRendererPreview();
}

function moveSelectedLayerDown() {
    const active = fabricCanvas.getActiveObject();
    if (!active || active.name === 'qr_zone') return;
    fabricCanvas.sendBackwards(active);
    // Keep qr_zone visually on top for editing.
    const qrZone = fabricCanvas.getObjects().find(obj => obj.name === 'qr_zone');
    if (qrZone) {
        fabricCanvas.bringToFront(qrZone);
    }
    fabricCanvas.renderAll();
    syncRendererPreview();
}

function buildLayer(obj, zIndex) {
    const base = {
        id: obj.__uid || ('layer_' + zIndex),
        z_index: zIndex,
        opacity: obj.opacity ?? 1
    };

    if (obj.type === 'textbox' || obj.type === 'text') {
        return {
            ...base,
            type: 'text',
            x: obj.left || 0,
            y: obj.top || 0,
            text: obj.text || '',
            font_size: obj.fontSize || 20,
            font_family: obj.fontFamily || 'Arial',
            font_weight: obj.fontWeight || 'normal',
            color: obj.fill || '#000000',
            text_align: obj.textAlign || 'left'
        };
    }

    if (obj.type === 'rect') {
        return {
            ...base,
            type: 'rect',
            x: obj.left || 0,
            y: obj.top || 0,
            width: (obj.width || 100) * (obj.scaleX || 1),
            height: (obj.height || 100) * (obj.scaleY || 1),
            fill: obj.fill || 'transparent',
            stroke: obj.stroke || null,
            stroke_width: obj.strokeWidth || 0,
            border_radius: obj.rx || 0
        };
    }

    if (obj.type === 'circle') {
        return {
            ...base,
            type: 'circle',
            x: (obj.left || 0) + ((obj.radius || 40) * (obj.scaleX || 1)),
            y: (obj.top || 0) + ((obj.radius || 40) * (obj.scaleY || 1)),
            radius: (obj.radius || 40) * (obj.scaleX || 1),
            fill: obj.fill || '#cccccc',
            stroke: obj.stroke || null,
            stroke_width: obj.strokeWidth || 0
        };
    }

    if (obj.type === 'image') {
        return {
            ...base,
            type: 'image',
            x: obj.left || 0,
            y: obj.top || 0,
            width: (obj.width || 120) * (obj.scaleX || 1),
            height: (obj.height || 120) * (obj.scaleY || 1),
            src: obj.getSrc ? obj.getSrc() : (obj._element?.src || '')
        };
    }

    return null;
}

function serializeDesignJson() {
    const all = fabricCanvas.getObjects();
    const qrObj = all.find(o => o.name === 'qr_zone');
    const layers = all.filter(o => o.name !== 'qr_zone')
        .map((obj, index) => buildLayer(obj, index))
        .filter(Boolean);

    return {
        version: 1,
        canvas_width: fabricCanvas.width,
        canvas_height: fabricCanvas.height,
        background: bgInput.value || '#ffffff',
        qr_zone: {
            x_pct: ((qrObj.left || 0) / fabricCanvas.width) * 100,
            y_pct: ((qrObj.top || 0) / fabricCanvas.height) * 100,
            w_pct: (((qrObj.width || 0) * (qrObj.scaleX || 1)) / fabricCanvas.width) * 100,
            h_pct: (((qrObj.height || 0) * (qrObj.scaleY || 1)) / fabricCanvas.height) * 100
        },
        layers
    };
}

function escapeXml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&apos;');
}

function designJsonToSvg(design) {
    const width = design.canvas_width || 400;
    const height = design.canvas_height || 500;
    const layers = Array.isArray(design.layers) ? [...design.layers] : [];
    layers.sort((a, b) => (a.z_index ?? 0) - (b.z_index ?? 0));

    const output = [];
    output.push(`<svg width="${width}" height="${height}" viewBox="0 0 ${width} ${height}" xmlns="http://www.w3.org/2000/svg">`);
    output.push(`<rect x="0" y="0" width="${width}" height="${height}" fill="${escapeXml(design.background || '#ffffff')}" />`);

    for (const layer of layers) {
        const opacity = layer.opacity ?? 1;
        if (layer.type === 'rect') {
            output.push(
                `<rect x="${layer.x ?? 0}" y="${layer.y ?? 0}" width="${layer.width ?? 100}" height="${layer.height ?? 100}" ` +
                `fill="${escapeXml(layer.fill || 'none')}" stroke="${escapeXml(layer.stroke || 'none')}" ` +
                `stroke-width="${layer.stroke_width ?? 0}" rx="${layer.border_radius ?? 0}" ry="${layer.border_radius ?? 0}" opacity="${opacity}" />`
            );
        } else if (layer.type === 'circle') {
            output.push(
                `<circle cx="${layer.x ?? 0}" cy="${layer.y ?? 0}" r="${layer.radius ?? 40}" ` +
                `fill="${escapeXml(layer.fill || 'none')}" stroke="${escapeXml(layer.stroke || 'none')}" ` +
                `stroke-width="${layer.stroke_width ?? 0}" opacity="${opacity}" />`
            );
        } else if (layer.type === 'text') {
            output.push(
                `<text x="${layer.x ?? 0}" y="${layer.y ?? 0}" fill="${escapeXml(layer.color || '#000000')}" ` +
                `font-family="${escapeXml(layer.font_family || 'Arial')}" font-size="${layer.font_size ?? 20}" ` +
                `font-weight="${escapeXml(layer.font_weight || 'normal')}" text-anchor="${(layer.text_align === 'center') ? 'middle' : (layer.text_align === 'right' ? 'end' : 'start')}" ` +
                `dominant-baseline="middle" opacity="${opacity}">${escapeXml(layer.text || '')}</text>`
            );
        } else if (layer.type === 'image' && layer.src) {
            output.push(
                `<image x="${layer.x ?? 0}" y="${layer.y ?? 0}" width="${layer.width ?? 120}" height="${layer.height ?? 120}" ` +
                `href="${escapeXml(layer.src)}" preserveAspectRatio="xMidYMid meet" opacity="${opacity}" />`
            );
        }
    }

    output.push('</svg>');
    return output.join('');
}

async function syncRendererPreview() {
    if (!window.renderFrameDesign) return;
    const design = serializeDesignJson();
    await window.renderFrameDesign(previewCanvasEl, design, '#111111', '#ffffff', null);
}

function syncPropertiesPanel() {
    const panel = document.getElementById('props_panel');
    const empty = document.getElementById('props_empty');
    const active = fabricCanvas.getActiveObject();
    if (!active || active.name === 'qr_zone') {
        panel.classList.add('hidden');
        empty.classList.remove('hidden');
        return;
    }

    selectedObj = active;
    empty.classList.add('hidden');
    panel.classList.remove('hidden');
    document.getElementById('prop_fill').value = (active.fill && active.fill.startsWith('#')) ? active.fill : '#000000';
    document.getElementById('prop_opacity').value = active.opacity ?? 1;
}

document.getElementById('prop_fill').addEventListener('input', (e) => {
    if (!selectedObj) return;
    selectedObj.set('fill', e.target.value);
    fabricCanvas.renderAll();
});

document.getElementById('prop_opacity').addEventListener('input', (e) => {
    if (!selectedObj) return;
    selectedObj.set('opacity', Number(e.target.value));
    fabricCanvas.renderAll();
});

document.getElementById('image_layer_input').addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => addImageLayerFromDataUrl(String(ev.target?.result || ''));
    reader.readAsDataURL(file);
    e.target.value = '';
});

fabricCanvas.on('selection:created', syncPropertiesPanel);
fabricCanvas.on('selection:updated', syncPropertiesPanel);
fabricCanvas.on('selection:cleared', syncPropertiesPanel);
fabricCanvas.on('object:modified', syncRendererPreview);
fabricCanvas.on('object:added', syncRendererPreview);
fabricCanvas.on('object:removed', syncRendererPreview);

widthInput.addEventListener('change', () => {
    fabricCanvas.setWidth(Number(widthInput.value || 400));
    fabricCanvas.renderAll();
    syncRendererPreview();
});

heightInput.addEventListener('change', () => {
    fabricCanvas.setHeight(Number(heightInput.value || 500));
    fabricCanvas.renderAll();
    syncRendererPreview();
});

bgInput.addEventListener('input', () => {
    fabricCanvas.backgroundColor = bgInput.value;
    fabricCanvas.renderAll();
    syncRendererPreview();
});

async function saveFrameDesign() {
    const status = document.getElementById('save_status');
    status.textContent = 'Saving...';

    const designJson = serializeDesignJson();
    const svgContent = designJsonToSvg(designJson);
    const thumb = previewCanvasEl.toDataURL('image/png');

    const isUpdate = !!(existingFrame && existingFrame.id);
    const endpoint = isUpdate ? `{{ url('/frames') }}/${existingFrame.id}` : '{{ route('frames.store') }}';
    const response = await fetch(endpoint, {
        method: isUpdate ? 'PUT' : 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name: document.getElementById('frame_name').value || 'My Frame',
            design_json: designJson,
            svg_content: svgContent,
            thumbnail_data_url: thumb
        })
    });

    const data = await response.json();
    if (response.ok && data.success) {
        status.textContent = isUpdate ? 'Updated successfully.' : 'Saved successfully.';
        return;
    }
    status.textContent = data.message || 'Save failed.';
}

function restoreFromExistingFrame() {
    if (!existingFrame || !existingFrame.design_json) {
        ensureQrZone();
        syncRendererPreview();
        return;
    }

    const design = existingFrame.design_json;
    fabricCanvas.clear();
    fabricCanvas.setWidth(design.canvas_width || 400);
    fabricCanvas.setHeight(design.canvas_height || 500);
    widthInput.value = fabricCanvas.width;
    heightInput.value = fabricCanvas.height;
    bgInput.value = design.background || '#ffffff';
    fabricCanvas.backgroundColor = bgInput.value;

    const zone = createQrZoneFromPercent(design.qr_zone || { x_pct: 5, y_pct: 4, w_pct: 90, h_pct: 72 }, fabricCanvas.width, fabricCanvas.height);
    fabricCanvas.add(zone);

    (design.layers || []).forEach(layer => {
        if (layer.type === 'text') {
            fabricCanvas.add(new fabric.Textbox(layer.text || 'Text', {
                left: layer.x || 0,
                top: layer.y || 0,
                fontSize: layer.font_size || 20,
                fill: layer.color || '#000000',
                fontFamily: layer.font_family || 'Arial',
                fontWeight: layer.font_weight || 'normal',
                opacity: layer.opacity ?? 1
            }));
        }
        if (layer.type === 'rect') {
            fabricCanvas.add(new fabric.Rect({
                left: layer.x || 0,
                top: layer.y || 0,
                width: layer.width || 100,
                height: layer.height || 100,
                fill: layer.fill || 'transparent',
                stroke: layer.stroke || null,
                strokeWidth: layer.stroke_width || 0,
                opacity: layer.opacity ?? 1
            }));
        }
        if (layer.type === 'circle') {
            fabricCanvas.add(new fabric.Circle({
                left: (layer.x || 0) - (layer.radius || 40),
                top: (layer.y || 0) - (layer.radius || 40),
                radius: layer.radius || 40,
                fill: layer.fill || '#cccccc',
                stroke: layer.stroke || null,
                strokeWidth: layer.stroke_width || 0,
                opacity: layer.opacity ?? 1
            }));
        }
        if (layer.type === 'image' && layer.src) {
            fabric.Image.fromURL(layer.src, (img) => {
                img.set({
                    left: layer.x || 0,
                    top: layer.y || 0,
                    scaleX: (layer.width || 120) / (img.width || 120),
                    scaleY: (layer.height || 120) / (img.height || 120),
                    opacity: layer.opacity ?? 1
                });
                fabricCanvas.add(img);
                fabricCanvas.renderAll();
            }, { crossOrigin: 'anonymous' });
        }
    });

    fabricCanvas.renderAll();
    syncRendererPreview();
}

restoreFromExistingFrame();
</script>
@endpush

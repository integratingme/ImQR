import { fabric } from 'fabric';

function initFrameEditor() {
    const editorCanvasEl = document.getElementById('editor_canvas');
    if (!editorCanvasEl) {
        return;
    }

    const config = window.__FRAME_EDITOR_CONFIG__ || {};
    const existingFrame = config.existingFrame || null;
    const storeUrl = config.storeUrl || '/frames';
    const updateBaseUrl = config.updateBaseUrl || '/frames';
    const returnTo = config.returnTo || '';
    const returnStep = Number(config.returnStep || 3);
    const returnFrameMode = config.returnFrameMode || 'custom';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const previewCanvasEl = document.getElementById('renderer_preview');
    const widthInput = document.getElementById('canvas_width');
    const heightInput = document.getElementById('canvas_height');
    const bgInput = document.getElementById('canvas_bg');
    const saveStatus = document.getElementById('save_status');
    const saveBtn = document.getElementById('save-btn');
    const finishBtn = document.getElementById('finish-btn');
    const layerList = document.getElementById('layer-list');
    const refreshPreviewBtn = document.getElementById('refresh-preview-btn');
    const viewToggleCanvasBtn = document.getElementById('view_toggle_canvas');
    const viewTogglePreviewBtn = document.getElementById('view_toggle_preview');
    const editorCanvasPanel = document.getElementById('editor_canvas_panel');
    const editorPreviewPanel = document.getElementById('editor_preview_panel');
    const propFill = document.getElementById('prop_fill');
    const propStroke = document.getElementById('prop_stroke');
    const propStrokeWidth = document.getElementById('prop_stroke_width');
    const propOpacity = document.getElementById('prop_opacity');
    const propText = document.getElementById('prop_text');
    const propFontSize = document.getElementById('prop_font_size');
    const textProps = document.getElementById('text_props');
    const imageLayerInput = document.getElementById('image_layer_input');
    const elementSearchInput = document.getElementById('element_search');
    const elementSections = document.getElementById('element_sections');
    const elementRegionSelect = document.getElementById('element_region');
    const categoryButtons = Array.from(document.querySelectorAll('.element-category-btn'));
    const templateButtons = Array.from(document.querySelectorAll('.template-category-btn'));
    const templateList = document.getElementById('template_list');
    const safetyWarnings = document.getElementById('safety_warnings');
    const canvasDropzone = document.getElementById('editor_canvas_dropzone');

    let selectedObj = null;
    let currentCategory = 'shapes';
    let dragElementId = null;
    let horizontalGuide = null;
    let verticalGuide = null;
    let qrPreviewCanvasCache = null;
    let draggedLayerId = null;
    const SNAP_DISTANCE = 12;
    const MAX_UPLOAD_IMAGE_BYTES = 2 * 1024 * 1024;
    const ALLOWED_UPLOAD_MIME_TYPES = new Set(['image/jpeg', 'image/png']);
    const ALLOWED_UPLOAD_EXTENSIONS = new Set(['jpg', 'jpeg', 'png']);

    const fabricCanvas = new fabric.Canvas('editor_canvas', {
        width: Number(widthInput?.value || 400),
        height: Number(heightInput?.value || 500),
        backgroundColor: bgInput?.value || '#ffffff'
    });

    function renderDeleteControlIcon(ctx, left, top, styleOverride, fabricObject) {
        const size = 20;
        ctx.save();
        ctx.translate(left, top);
        ctx.rotate(fabric.util.degreesToRadians(fabricObject.angle || 0));

        // Minimal trash-bin control button.
        ctx.fillStyle = 'rgba(15, 23, 42, 0.92)';
        ctx.beginPath();
        ctx.arc(0, 0, size / 2, 0, Math.PI * 2);
        ctx.fill();

        ctx.strokeStyle = '#f8fafc';
        ctx.lineWidth = 1.6;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        // Lid
        ctx.beginPath();
        ctx.moveTo(-4.5, -4);
        ctx.lineTo(4.5, -4);
        ctx.stroke();

        // Handle
        ctx.beginPath();
        ctx.moveTo(-1.6, -5.6);
        ctx.lineTo(1.6, -5.6);
        ctx.stroke();

        // Bin body
        ctx.beginPath();
        ctx.moveTo(-4.2, -3.4);
        ctx.lineTo(-3.2, 4.6);
        ctx.lineTo(3.2, 4.6);
        ctx.lineTo(4.2, -3.4);
        ctx.closePath();
        ctx.stroke();

        // Inner lines
        ctx.beginPath();
        ctx.moveTo(-1.8, -1.8);
        ctx.lineTo(-1.2, 3.2);
        ctx.moveTo(0, -1.8);
        ctx.lineTo(0, 3.2);
        ctx.moveTo(1.8, -1.8);
        ctx.lineTo(1.2, 3.2);
        ctx.stroke();
        ctx.restore();
    }

    function deleteControlMouseUpHandler(eventData, transform) {
        const target = transform?.target;
        if (!target || target.name === 'qr_zone' || target.name === 'qr_zone_label') {
            return false;
        }
        fabricCanvas.remove(target);
        fabricCanvas.discardActiveObject();
        fabricCanvas.requestRenderAll();
        syncLayerList();
        syncRendererPreview();
        syncPropertiesPanel();
        return true;
    }

    fabric.Object.prototype.controls.deleteControl = new fabric.Control({
        x: 0.5,
        y: -0.5,
        offsetX: 14,
        offsetY: -14,
        cursorStyle: 'pointer',
        visible: function(fabricObject) {
            return fabricObject?.name !== 'qr_zone' && fabricObject?.name !== 'qr_zone_label';
        },
        mouseUpHandler: deleteControlMouseUpHandler,
        render: renderDeleteControlIcon,
        cornerSize: 20
    });

    function applyCanvasLayerStyles() {
        const lower = fabricCanvas.lowerCanvasEl;
        const upper = fabricCanvas.upperCanvasEl;
        if (lower) {
            lower.style.backgroundColor = bgInput?.value || '#ffffff';
            lower.style.borderRadius = '0.75rem';
            lower.style.display = 'block';
            lower.style.zIndex = '1';
        }
        if (upper) {
            upper.style.backgroundColor = 'transparent';
            upper.style.borderRadius = '0.75rem';
            upper.style.display = 'block';
            upper.style.zIndex = '2';
        }
    }

    function uid(prefix = 'layer') {
        return `${prefix}_${Date.now()}_${Math.random().toString(36).slice(2, 7)}`;
    }

    function attachLayerMeta(object, layerType) {
        object.set({
            id: object.id || uid(layerType),
            layerType: layerType || object.layerType || object.type
        });
        return object;
    }

    function createQrZoneFromPercent(zone, width, height) {
        const rect = new fabric.Rect({
            left: (zone.x_pct / 100) * width,
            top: (zone.y_pct / 100) * height,
            width: (zone.w_pct / 100) * width,
            height: (zone.h_pct / 100) * height,
            fill: 'rgba(79, 70, 229, 0.12)',
            stroke: 'rgba(79, 70, 229, 0.95)',
            strokeDashArray: [8, 6],
            strokeWidth: 3,
            name: 'qr_zone',
            id: 'qr_zone',
            layerType: 'qr_zone',
            selectable: true,
            evented: true,
            lockMovementX: false,
            lockMovementY: false,
            lockScalingX: false,
            lockScalingY: false,
            lockRotation: true,
            hasRotatingPoint: false,
            transparentCorners: false,
            cornerColor: '#4f46e5',
            borderColor: '#4f46e5',
            hoverCursor: 'nwse-resize'
        });
        rect.setControlsVisibility({
            mt: false,
            mb: false,
            ml: false,
            mr: false,
            mtr: false
        });
        return rect;
    }

    function getDefaultSquareQrZone(width, height, sidePctOfShorter = 56) {
        const shorter = Math.min(width, height);
        const side = (sidePctOfShorter / 100) * shorter;
        const wPct = (side / width) * 100;
        const hPct = (side / height) * 100;
        return {
            x_pct: (100 - wPct) / 2,
            y_pct: (100 - hPct) / 2,
            w_pct: wPct,
            h_pct: hPct
        };
    }

    function enforceQrZoneSquare(zoneRect) {
        if (!zoneRect) return;
        const width = (zoneRect.width || 0) * (zoneRect.scaleX || 1);
        const height = (zoneRect.height || 0) * (zoneRect.scaleY || 1);
        const side = Math.max(40, Math.min(width, height));
        const centerX = (zoneRect.left || 0) + width / 2;
        const centerY = (zoneRect.top || 0) + height / 2;

        let left = centerX - side / 2;
        let top = centerY - side / 2;
        left = Math.max(0, Math.min((fabricCanvas.width || 0) - side, left));
        top = Math.max(0, Math.min((fabricCanvas.height || 0) - side, top));

        zoneRect.set({
            left,
            top,
            width: side,
            height: side,
            scaleX: 1,
            scaleY: 1
        });
        zoneRect.setCoords();
    }

    function handleQrZoneScaling(target) {
        if (!target || target.name !== 'qr_zone') return;
        enforceQrZoneSquare(target);
        updateQrZoneLabel(target);
        ensureQrZone();
        fabricCanvas.requestRenderAll();
    }

    function handleQrZoneMoving(target) {
        if (!target || target.name !== 'qr_zone') return;
        const width = (target.width || 0) * (target.scaleX || 1);
        const height = (target.height || 0) * (target.scaleY || 1);
        const maxLeft = Math.max(0, (fabricCanvas.width || 0) - width);
        const maxTop = Math.max(0, (fabricCanvas.height || 0) - height);
        target.set({
            left: Math.max(0, Math.min(maxLeft, target.left || 0)),
            top: Math.max(0, Math.min(maxTop, target.top || 0))
        });
        target.setCoords();
        updateQrZoneLabel(target);
        ensureQrZone();
        fabricCanvas.requestRenderAll();
    }

    function createQrZoneLabel(zoneRect) {
        return new fabric.Textbox('QR SAFE AREA\nDo not place elements here', {
            left: zoneRect.left + 12,
            top: zoneRect.top + 12,
            width: Math.max((zoneRect.width || 0) * (zoneRect.scaleX || 1) - 24, 140),
            fontSize: 14,
            lineHeight: 1.25,
            fill: 'rgba(30, 41, 59, 0.95)',
            fontWeight: '600',
            textAlign: 'left',
            selectable: false,
            evented: false,
            name: 'qr_zone_label',
            id: 'qr_zone_label',
            layerType: 'system'
        });
    }

    function updateCanvasQrOverlay() {
        // Intentionally no-op: keep canvas rendering fully managed by Fabric.
    }

    function updateQrZoneLabel(zoneRect) {
        const label = fabricCanvas.getObjects().find((obj) => obj.name === 'qr_zone_label');
        if (!label || !zoneRect) return;
        label.set({
            left: (zoneRect.left || 0) + 12,
            top: (zoneRect.top || 0) + 12,
            width: Math.max(((zoneRect.width || 0) * (zoneRect.scaleX || 1)) - 24, 140)
        });
    }

    function ensureQrZone() {
        const existing = fabricCanvas.getObjects().find((obj) => obj.name === 'qr_zone');
        if (existing) {
            enforceQrZoneSquare(existing);
            fabricCanvas.bringToFront(existing);
            updateQrZoneLabel(existing);
            const existingLabel = fabricCanvas.getObjects().find((obj) => obj.name === 'qr_zone_label');
            if (existingLabel) {
                fabricCanvas.bringToFront(existingLabel);
            } else {
                const label = createQrZoneLabel(existing);
                fabricCanvas.add(label);
                fabricCanvas.bringToFront(label);
            }
            updateCanvasQrOverlay();
            return existing;
        }
        const defaultSquareZone = getDefaultSquareQrZone(fabricCanvas.width, fabricCanvas.height, 56);
        const zone = createQrZoneFromPercent(defaultSquareZone, fabricCanvas.width, fabricCanvas.height);
        const label = createQrZoneLabel(zone);
        fabricCanvas.add(zone);
        fabricCanvas.add(label);
        fabricCanvas.bringToFront(zone);
        fabricCanvas.bringToFront(label);
        updateCanvasQrOverlay();
        return zone;
    }

    function setQrZonePercent(xPct = 22, yPct = 22, wPct = 56, hPct = 56) {
        const zone = ensureQrZone();
        zone.set({
            left: (xPct / 100) * fabricCanvas.width,
            top: (yPct / 100) * fabricCanvas.height,
            width: (wPct / 100) * fabricCanvas.width,
            height: (hPct / 100) * fabricCanvas.height,
            scaleX: 1,
            scaleY: 1
        });
        enforceQrZoneSquare(zone);
        updateQrZoneLabel(zone);
        ensureQrZone();
        updateCanvasQrOverlay();
    }

    function makeStarPoints(cx, cy, points, outerRadius, innerRadius) {
        const result = [];
        for (let i = 0; i < points * 2; i += 1) {
            const angle = (Math.PI / points) * i - Math.PI / 2;
            const radius = i % 2 === 0 ? outerRadius : innerRadius;
            result.push({
                x: cx + Math.cos(angle) * radius,
                y: cy + Math.sin(angle) * radius
            });
        }
        return result;
    }

    function inferLayerType(object) {
        if (object.layerType) return object.layerType;
        if (object.type === 'textbox' || object.type === 'text') return 'text';
        if (object.type === 'rect') return 'rect';
        if (object.type === 'circle') return 'circle';
        if (object.type === 'image') return 'image';
        if (object.type === 'polygon' || object.type === 'triangle') return 'rect';
        return 'rect';
    }

    function addFabricObject(object) {
        attachLayerMeta(object, inferLayerType(object));
        if (typeof object.visible !== 'boolean') {
            object.set('visible', true);
        }
        fabricCanvas.add(object);
        fabricCanvas.setActiveObject(object);
        ensureQrZone();
        object.setCoords();
        fabricCanvas.renderAll();
        fabricCanvas.requestRenderAll();
    }

    function getObjectSize(object) {
        const width = (object.width || (object.radius ? object.radius * 2 : 80)) * (object.scaleX || 1);
        const height = (object.height || (object.radius ? object.radius * 2 : 80)) * (object.scaleY || 1);
        return { width, height };
    }

    function getQrBounds() {
        const qr = fabricCanvas.getObjects().find((obj) => obj.name === 'qr_zone');
        if (!qr) return null;
        const width = (qr.width || 0) * (qr.scaleX || 1);
        const height = (qr.height || 0) * (qr.scaleY || 1);
        return {
            left: qr.left || 0,
            top: qr.top || 0,
            right: (qr.left || 0) + width,
            bottom: (qr.top || 0) + height,
            centerX: (qr.left || 0) + width / 2,
            centerY: (qr.top || 0) + height / 2
        };
    }

    function placeObjectInRegion(object, region) {
        const qr = getQrBounds();
        if (!qr) return;
        const { width, height } = getObjectSize(object);
        const padding = 14;
        const placement = {
            top: { left: qr.centerX - width / 2, top: Math.max(padding, qr.top - height - padding) },
            bottom: { left: qr.centerX - width / 2, top: Math.min(fabricCanvas.height - height - padding, qr.bottom + padding) },
            left: { left: Math.max(padding, qr.left - width - padding), top: qr.centerY - height / 2 },
            right: { left: Math.min(fabricCanvas.width - width - padding, qr.right + padding), top: qr.centerY - height / 2 },
            'corner-tl': { left: Math.max(padding, qr.left - width - padding), top: Math.max(padding, qr.top - height - padding) },
            'corner-tr': { left: Math.min(fabricCanvas.width - width - padding, qr.right + padding), top: Math.max(padding, qr.top - height - padding) },
            'corner-bl': { left: Math.max(padding, qr.left - width - padding), top: Math.min(fabricCanvas.height - height - padding, qr.bottom + padding) },
            'corner-br': { left: Math.min(fabricCanvas.width - width - padding, qr.right + padding), top: Math.min(fabricCanvas.height - height - padding, qr.bottom + padding) },
            overlay: { left: qr.centerX - width / 2, top: qr.centerY - height / 2 },
            background: { left: padding, top: padding }
        };

        const target = placement[region || 'top'] || placement.top;
        object.set({
            left: target.left,
            top: target.top
        });
    }

    function addFabricObjectAt(object, x, y) {
        if (typeof x === 'number' && typeof y === 'number') {
            if (object.type === 'circle') {
                object.set({
                    left: x - (object.radius || 0),
                    top: y - (object.radius || 0)
                });
            } else {
                object.set({
                    left: x - ((object.width || 0) * (object.scaleX || 1)) / 2,
                    top: y - ((object.height || 0) * (object.scaleY || 1)) / 2
                });
            }
        } else {
            placeObjectInRegion(object, elementRegionSelect?.value || 'top');
        }
        addFabricObject(object);
    }

    const ELEMENT_CATALOG = {
        shapes: [
            {
                id: 'shape-rect',
                label: 'Rectangle',
                section: 'Basic shapes',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><rect x="12" y="18" width="76" height="64" rx="0" fill="#cbd5e1"/></svg>',
                create: () => new fabric.Rect({ left: 60, top: 70, width: 220, height: 140, fill: '#7c3aed', opacity: 0.95, stroke: '#111827', strokeWidth: 0 })
            },
            {
                id: 'shape-rounded',
                label: 'Rounded',
                section: 'Basic shapes',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><rect x="12" y="18" width="76" height="64" rx="18" fill="#cbd5e1"/></svg>',
                create: () => new fabric.Rect({ left: 70, top: 80, width: 220, height: 120, rx: 28, ry: 28, fill: '#06b6d4', opacity: 0.95, stroke: '#111827', strokeWidth: 0 })
            },
            {
                id: 'shape-circle',
                label: 'Circle',
                section: 'Basic shapes',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><circle cx="50" cy="50" r="32" fill="#cbd5e1"/></svg>',
                create: () => new fabric.Circle({ left: 140, top: 110, radius: 65, fill: '#10b981', opacity: 0.95, stroke: '#111827', strokeWidth: 0 })
            },
            {
                id: 'shape-triangle',
                label: 'Triangle',
                section: 'Basic shapes',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><polygon points="50,16 84,82 16,82" fill="#cbd5e1"/></svg>',
                create: () => new fabric.Triangle({ left: 130, top: 110, width: 150, height: 130, fill: '#f59e0b', opacity: 0.95, stroke: '#111827', strokeWidth: 0 })
            },
            {
                id: 'shape-star',
                label: 'Star',
                section: 'Polygons',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><polygon points="50,10 61,38 92,38 67,56 76,86 50,68 24,86 33,56 8,38 39,38" fill="#cbd5e1"/></svg>',
                create: () => new fabric.Polygon(makeStarPoints(0, 0, 5, 70, 32), { left: 180, top: 150, fill: '#ec4899', opacity: 0.95, stroke: '#111827', strokeWidth: 0 })
            },
            {
                id: 'shape-hex',
                label: 'Hexagon',
                section: 'Polygons',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><polygon points="20,50 35,24 65,24 80,50 65,76 35,76" fill="#cbd5e1"/></svg>',
                create: () => new fabric.Polygon([
                    { x: 0, y: 40 }, { x: 35, y: 0 }, { x: 95, y: 0 }, { x: 130, y: 40 }, { x: 95, y: 80 }, { x: 35, y: 80 }
                ], { left: 130, top: 150, fill: '#8b5cf6', opacity: 0.95, stroke: '#111827', strokeWidth: 0 })
            }
        ],
        arrows: [
            {
                id: 'arrow-right',
                label: 'Right Arrow',
                section: 'Lines',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><path d="M10 50h62M58 34l16 16-16 16" stroke="#cbd5e1" stroke-width="8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                create: () => new fabric.Polygon([{ x: 0, y: 20 }, { x: 95, y: 20 }, { x: 95, y: 0 }, { x: 150, y: 40 }, { x: 95, y: 80 }, { x: 95, y: 60 }, { x: 0, y: 60 }], { left: 120, top: 160, fill: '#2563eb', opacity: 1 })
            },
            {
                id: 'arrow-left',
                label: 'Left Arrow',
                section: 'Lines',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><path d="M90 50H28M42 34L26 50l16 16" stroke="#cbd5e1" stroke-width="8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                create: () => new fabric.Polygon([{ x: 150, y: 20 }, { x: 55, y: 20 }, { x: 55, y: 0 }, { x: 0, y: 40 }, { x: 55, y: 80 }, { x: 55, y: 60 }, { x: 150, y: 60 }], { left: 120, top: 160, fill: '#1d4ed8', opacity: 1 })
            },
            {
                id: 'arrow-down',
                label: 'Down Arrow',
                section: 'Lines',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><path d="M50 12v62M34 60l16 16 16-16" stroke="#cbd5e1" stroke-width="8" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                create: () => new fabric.Polygon([{ x: 20, y: 0 }, { x: 60, y: 0 }, { x: 60, y: 95 }, { x: 80, y: 95 }, { x: 40, y: 150 }, { x: 0, y: 95 }, { x: 20, y: 95 }], { left: 160, top: 130, fill: '#0ea5e9', opacity: 1 })
            }
        ],
        labels: [
            {
                id: 'label-pill',
                label: 'Pill Label',
                section: 'Label styles',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><rect x="12" y="30" width="76" height="40" rx="20" fill="#cbd5e1"/></svg>',
                create: () => new fabric.Rect({ left: 90, top: 360, width: 220, height: 70, rx: 35, ry: 35, fill: '#111827', opacity: 0.9 })
            },
            {
                id: 'label-banner',
                label: 'Banner',
                section: 'Label styles',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><rect x="10" y="25" width="80" height="50" fill="#cbd5e1"/></svg>',
                create: () => new fabric.Rect({ left: 70, top: 350, width: 260, height: 80, fill: '#7c3aed', opacity: 0.95 })
            },
            {
                id: 'label-ticket',
                label: 'Ticket',
                section: 'Label styles',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><rect x="12" y="22" width="76" height="56" rx="8" fill="#cbd5e1"/><circle cx="12" cy="50" r="7" fill="#111827"/><circle cx="88" cy="50" r="7" fill="#111827"/></svg>',
                create: () => new fabric.Rect({ left: 80, top: 340, width: 240, height: 90, rx: 14, ry: 14, fill: '#f43f5e', opacity: 0.95 })
            }
        ],
        icons: [
            {
                id: 'icon-check',
                label: 'Check',
                section: 'Icons',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><text x="50" y="65" text-anchor="middle" font-size="62" fill="#cbd5e1">✓</text></svg>',
                create: () => new fabric.Text('✓', { left: 185, top: 200, fontSize: 90, fill: '#16a34a', fontWeight: 'bold' })
            },
            {
                id: 'icon-plus',
                label: 'Plus',
                section: 'Icons',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><text x="50" y="70" text-anchor="middle" font-size="70" fill="#cbd5e1">+</text></svg>',
                create: () => new fabric.Text('+', { left: 185, top: 190, fontSize: 100, fill: '#0ea5e9', fontWeight: 'bold' })
            },
            {
                id: 'icon-heart',
                label: 'Heart',
                section: 'Icons',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><text x="50" y="70" text-anchor="middle" font-size="62" fill="#cbd5e1">♥</text></svg>',
                create: () => new fabric.Text('♥', { left: 175, top: 200, fontSize: 90, fill: '#ef4444', fontWeight: 'bold' })
            },
            {
                id: 'icon-star',
                label: 'Star Icon',
                section: 'Icons',
                preview: '<svg viewBox="0 0 100 100" class="w-10 h-10"><text x="50" y="68" text-anchor="middle" font-size="62" fill="#cbd5e1">★</text></svg>',
                create: () => new fabric.Text('★', { left: 175, top: 200, fontSize: 90, fill: '#f59e0b', fontWeight: 'bold' })
            }
        ]
    };

    const TEMPLATE_LIBRARY = {
        minimal: [
            {
                id: 'minimal-soft',
                name: 'Rounded Soft',
                background: '#f8fafc',
                layers: [
                    { type: 'rect', options: { left: 20, top: 20, width: 360, height: 460, rx: 26, ry: 26, fill: '#ffffff', stroke: '#e2e8f0', strokeWidth: 3, opacity: 1 } },
                    { type: 'text', options: { left: 94, top: 56, text: 'SCAN ME', fontSize: 32, fill: '#0f172a', fontWeight: '700' } }
                ]
            }
        ],
        floral: [
            {
                id: 'floral-corners',
                name: 'Floral Corners',
                background: '#fdf2f8',
                layers: [
                    { type: 'circle', options: { left: 12, top: 12, radius: 24, fill: '#f9a8d4', opacity: 0.9 } },
                    { type: 'circle', options: { left: 340, top: 12, radius: 24, fill: '#f9a8d4', opacity: 0.9 } },
                    { type: 'circle', options: { left: 12, top: 438, radius: 24, fill: '#f472b6', opacity: 0.95 } },
                    { type: 'circle', options: { left: 340, top: 438, radius: 24, fill: '#f472b6', opacity: 0.95 } }
                ]
            }
        ],
        luxury: [
            {
                id: 'luxury-gold',
                name: 'Luxury Gold',
                background: '#111827',
                layers: [
                    { type: 'rect', options: { left: 16, top: 16, width: 368, height: 468, rx: 16, ry: 16, fill: 'transparent', stroke: '#d4af37', strokeWidth: 3 } },
                    { type: 'text', options: { left: 100, top: 66, text: 'EXCLUSIVE', fontSize: 30, fill: '#f8e7b8', fontWeight: '700' } }
                ]
            }
        ],
        wedding: [
            {
                id: 'wedding-elegance',
                name: 'Wedding Elegance',
                background: '#fff7ed',
                layers: [
                    { type: 'rect', options: { left: 26, top: 26, width: 348, height: 448, rx: 14, ry: 14, fill: 'transparent', stroke: '#eab308', strokeWidth: 2 } },
                    { type: 'text', options: { left: 85, top: 72, text: 'WE ARE GETTING MARRIED', fontSize: 22, fill: '#92400e', fontWeight: '700' } }
                ]
            }
        ],
        birthday: [
            {
                id: 'birthday-party',
                name: 'Birthday Party',
                background: '#1e1b4b',
                layers: [
                    { type: 'text', options: { left: 80, top: 70, text: 'BIRTHDAY PARTY', fontSize: 30, fill: '#f9fafb', fontWeight: '700' } },
                    { type: 'rect', options: { left: 74, top: 410, width: 252, height: 56, rx: 20, ry: 20, fill: '#f97316', opacity: 0.9 } }
                ]
            }
        ],
        tech: [
            {
                id: 'tech-neon',
                name: 'Tech Neon',
                background: '#020617',
                layers: [
                    { type: 'rect', options: { left: 30, top: 30, width: 340, height: 440, rx: 12, ry: 12, fill: 'transparent', stroke: '#22d3ee', strokeWidth: 2, opacity: 0.95 } },
                    { type: 'text', options: { left: 108, top: 68, text: 'CONNECT NOW', fontSize: 28, fill: '#67e8f9', fontWeight: '700' } }
                ]
            }
        ]
    };

    function createTemplateLayer(layerDef) {
        if (layerDef.type === 'rect') return new fabric.Rect(layerDef.options);
        if (layerDef.type === 'circle') return new fabric.Circle(layerDef.options);
        if (layerDef.type === 'text') return new fabric.Textbox(layerDef.options.text || 'Text', layerDef.options);
        return null;
    }

    function applyTemplate(template) {
        if (!template) return;
        fabricCanvas.clear();
        if (bgInput) bgInput.value = template.background || '#ffffff';
        fabricCanvas.backgroundColor = bgInput?.value || '#ffffff';
        ensureQrZone();
        (template.layers || []).forEach((layerDef) => {
            const layer = createTemplateLayer(layerDef);
            if (!layer) return;
            addFabricObject(layer);
        });
        ensureQrZone();
        syncLayerList();
        syncRendererPreview();
    }

    function renderTemplates(category = 'minimal') {
        if (!templateList) return;
        const templates = TEMPLATE_LIBRARY[category] || [];
        templateList.innerHTML = '';
        templates.forEach((template) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-full rounded-xl border border-slate-700 bg-slate-900 hover:border-violet-500 px-3 py-2 text-left transition';
            btn.innerHTML = `<p class="text-sm font-semibold text-slate-100">${template.name}</p><p class="text-xs text-slate-400 mt-1">Apply base frame and edit.</p>`;
            btn.addEventListener('click', () => applyTemplate(template));
            templateList.appendChild(btn);
        });
        if (!templates.length) {
            templateList.innerHTML = '<p class="text-xs text-slate-400">No templates in this category yet.</p>';
        }
    }

    function findCatalogItemById(id) {
        for (const items of Object.values(ELEMENT_CATALOG)) {
            const found = items.find((item) => item.id === id);
            if (found) return found;
        }
        return null;
    }

    function dropClientPointToCanvas(clientX, clientY) {
        const rect = fabricCanvas.upperCanvasEl.getBoundingClientRect();
        const x = ((clientX - rect.left) / rect.width) * fabricCanvas.getWidth();
        const y = ((clientY - rect.top) / rect.height) * fabricCanvas.getHeight();
        return {
            x: Math.max(0, Math.min(fabricCanvas.getWidth(), x)),
            y: Math.max(0, Math.min(fabricCanvas.getHeight(), y))
        };
    }

    function setDropzoneActive(isActive) {
        if (!canvasDropzone) return;
        canvasDropzone.classList.toggle('ring-2', isActive);
        canvasDropzone.classList.toggle('ring-primary-500', isActive);
        canvasDropzone.classList.toggle('rounded-lg', isActive);
    }

    function addTextLayer() {
        const text = attachLayerMeta(new fabric.Textbox('Scan me!', {
            left: 80,
            top: 420,
            fontSize: 32,
            fill: '#000000'
        }), 'text');
        fabricCanvas.add(text).setActiveObject(text);
    }

    function addRectLayer() {
        const rect = attachLayerMeta(new fabric.Rect({
            left: 30,
            top: 30,
            width: 340,
            height: 340,
            fill: 'transparent',
            stroke: '#000000',
            strokeWidth: 10
        }), 'rect');
        fabricCanvas.add(rect).setActiveObject(rect);
    }

    function addCircleLayer() {
        const circle = attachLayerMeta(new fabric.Circle({
            left: 170,
            top: 170,
            radius: 70,
            fill: '#cccccc'
        }), 'circle');
        fabricCanvas.add(circle).setActiveObject(circle);
    }

    function addImageLayerFromDataUrl(dataUrl) {
        fabric.Image.fromURL(dataUrl, (img) => {
            attachLayerMeta(img, 'image');
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

    function setUploadFeedback(message, isError = false) {
        if (!saveStatus) return;
        saveStatus.textContent = message;
        saveStatus.classList.toggle('text-red-300', !!isError);
        saveStatus.classList.toggle('text-slate-300', !isError);
    }

    function getFileExtension(fileName = '') {
        const parts = String(fileName).toLowerCase().split('.');
        return parts.length > 1 ? parts.pop() || '' : '';
    }

    async function readFileHeaderBytes(file, length = 12) {
        const chunk = file.slice(0, length);
        const buffer = await chunk.arrayBuffer();
        return new Uint8Array(buffer);
    }

    function isValidImageSignature(bytes) {
        const isJpeg = bytes.length >= 3 &&
            bytes[0] === 0xFF &&
            bytes[1] === 0xD8 &&
            bytes[2] === 0xFF;
        const isPng = bytes.length >= 8 &&
            bytes[0] === 0x89 &&
            bytes[1] === 0x50 &&
            bytes[2] === 0x4E &&
            bytes[3] === 0x47 &&
            bytes[4] === 0x0D &&
            bytes[5] === 0x0A &&
            bytes[6] === 0x1A &&
            bytes[7] === 0x0A;
        return isJpeg || isPng;
    }

    async function validateImageUploadFile(file) {
        if (!file) {
            return { valid: false, message: 'No file selected.' };
        }
        if (file.size > MAX_UPLOAD_IMAGE_BYTES) {
            return { valid: false, message: 'Image cannot exceed 2MB.' };
        }
        const extension = getFileExtension(file.name);
        if (!ALLOWED_UPLOAD_EXTENSIONS.has(extension)) {
            return { valid: false, message: 'Only JPG and PNG images are allowed.' };
        }
        if (!ALLOWED_UPLOAD_MIME_TYPES.has(file.type)) {
            return { valid: false, message: 'Only JPG and PNG images are allowed.' };
        }
        try {
            const header = await readFileHeaderBytes(file, 12);
            if (!isValidImageSignature(header)) {
                return { valid: false, message: 'The file is not a valid image (invalid file signature).' };
            }
        } catch (error) {
            return { valid: false, message: 'Could not read file. Please try another image.' };
        }
        return { valid: true, message: '' };
    }

    function renderElementCatalog(category) {
        if (!elementSections) {
            return;
        }

        const query = (elementSearchInput?.value || '').trim().toLowerCase();
        const items = (ELEMENT_CATALOG[category] || []).filter((item) => {
            if (!query) return true;
            return item.label.toLowerCase().includes(query) || (item.section || '').toLowerCase().includes(query);
        });

        const grouped = new Map();
        for (const item of items) {
            const section = item.section || 'Elements';
            if (!grouped.has(section)) {
                grouped.set(section, []);
            }
            grouped.get(section).push(item);
        }

        elementSections.innerHTML = '';
        for (const [section, sectionItems] of grouped.entries()) {
            const sectionWrap = document.createElement('div');
            const header = document.createElement('div');
            header.className = 'flex items-center justify-between mb-2';
            header.innerHTML = `<p class="text-sm font-semibold text-dark-100">${section}</p><span class="text-xs text-dark-300">${sectionItems.length}</span>`;
            sectionWrap.appendChild(header);

            const row = document.createElement('div');
            row.className = 'flex gap-2 overflow-x-auto pb-1';

            for (const item of sectionItems) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'shrink-0 w-16 h-16 rounded-xl border border-dark-200 bg-dark-800 hover:border-primary-500 flex items-center justify-center';
                button.innerHTML = item.preview || `<span class="text-xs text-dark-300">${item.label}</span>`;
                button.title = item.label;
                button.addEventListener('click', () => addFabricObjectAt(item.create()));
                button.draggable = true;
                button.dataset.elementId = item.id;
                button.addEventListener('dragstart', (event) => {
                    dragElementId = item.id;
                    setDropzoneActive(true);
                    event.dataTransfer?.setData('text/plain', item.id);
                    if (event.dataTransfer) {
                        event.dataTransfer.effectAllowed = 'copy';
                    }
                });
                button.addEventListener('dragend', () => {
                    dragElementId = null;
                    setDropzoneActive(false);
                });
                row.appendChild(button);
            }

            sectionWrap.appendChild(row);
            elementSections.appendChild(sectionWrap);
        }

        if (!items.length) {
            elementSections.innerHTML = '<p class="text-xs text-slate-400">No elements found for this search.</p>';
        }
    }

    function setActiveCategory(category) {
        currentCategory = category;
        categoryButtons.forEach((btn) => {
            if (btn.getAttribute('data-category') === category) {
                btn.classList.add('border-violet-500', 'bg-violet-500/10', 'text-violet-300');
                btn.classList.remove('border-slate-700', 'bg-slate-900', 'text-slate-200');
            } else {
                btn.classList.remove('border-violet-500', 'bg-violet-500/10', 'text-violet-300');
                btn.classList.add('border-slate-700', 'bg-slate-900', 'text-slate-200');
            }
        });
        renderElementCatalog(category);
    }

    function deleteSelectedLayer() {
        const active = fabricCanvas.getActiveObject();
        if (!active || active.name === 'qr_zone' || active.name === 'qr_zone_label') return;
        fabricCanvas.remove(active);
        fabricCanvas.renderAll();
        syncLayerList();
    }

    function moveSelectedLayerUp() {
        const active = fabricCanvas.getActiveObject();
        if (!active || active.name === 'qr_zone' || active.name === 'qr_zone_label') return;
        fabricCanvas.bringForward(active);
        fabricCanvas.renderAll();
        syncRendererPreview();
        syncLayerList();
    }

    function moveSelectedLayerDown() {
        const active = fabricCanvas.getActiveObject();
        if (!active || active.name === 'qr_zone' || active.name === 'qr_zone_label') return;
        fabricCanvas.sendBackwards(active);
        const qrZone = fabricCanvas.getObjects().find((obj) => obj.name === 'qr_zone');
        const qrZoneLabel = fabricCanvas.getObjects().find((obj) => obj.name === 'qr_zone_label');
        if (qrZone) {
            fabricCanvas.bringToFront(qrZone);
        }
        if (qrZoneLabel) {
            fabricCanvas.bringToFront(qrZoneLabel);
        }
        fabricCanvas.renderAll();
        syncRendererPreview();
        syncLayerList();
    }

    function isGuideObject(obj) {
        return !!obj.__guideLine;
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

        if (obj.type === 'polygon' || obj.type === 'triangle' || obj.type === 'path') {
            let src = '';
            try {
                src = obj.toDataURL({
                    format: 'png',
                    multiplier: 1,
                    enableRetinaScaling: false
                });
            } catch (error) {
                src = '';
            }
            return {
                ...base,
                type: 'image',
                x: obj.left || 0,
                y: obj.top || 0,
                width: (obj.width || 120) * (obj.scaleX || 1),
                height: (obj.height || 120) * (obj.scaleY || 1),
                src
            };
        }

        return null;
    }

    function serializeDesignJson() {
        const all = fabricCanvas.getObjects().filter((o) => !isGuideObject(o) && o.name !== 'qr_zone_label');
        const qrObj = all.find((o) => o.name === 'qr_zone');
        const layers = all.filter((o) => o.name !== 'qr_zone')
            .map((obj, index) => buildLayer(obj, index))
            .filter(Boolean);

        return {
            version: 1,
            canvas_width: fabricCanvas.width,
            canvas_height: fabricCanvas.height,
            background: bgInput?.value || '#ffffff',
            qr_zone: {
                x_pct: ((qrObj?.left || 0) / fabricCanvas.width) * 100,
                y_pct: ((qrObj?.top || 0) / fabricCanvas.height) * 100,
                w_pct: (((qrObj?.width || 0) * (qrObj?.scaleX || 1)) / fabricCanvas.width) * 100,
                h_pct: (((qrObj?.height || 0) * (qrObj?.scaleY || 1)) / fabricCanvas.height) * 100
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

    function buildFinishRedirectUrl(frameId) {
        if (!returnTo || !frameId) return null;
        try {
            const base = new URL(returnTo, window.location.origin);
            if (base.origin !== window.location.origin) return null;
            base.searchParams.set('selected_frame', returnFrameMode);
            base.searchParams.set('selected_frame_design_id', String(frameId));
            base.searchParams.set('step', String(Number.isFinite(returnStep) ? returnStep : 3));
            base.searchParams.set('from_frame_editor', '1');
            return base.toString();
        } catch (error) {
            return null;
        }
    }

    function notifyOpenerOnFinish(frameId) {
        if (!frameId) return false;
        try {
            if (window.opener && window.opener !== window) {
                window.opener.postMessage({
                    type: 'frame-editor-finished',
                    frameId: String(frameId),
                    frameMode: returnFrameMode || 'custom',
                    step: Number.isFinite(returnStep) ? returnStep : 3
                }, window.location.origin);
                return true;
            }
        } catch (error) {
            return false;
        }
        return false;
    }

    async function buildQrPreviewCanvas(width = 240, height = 240, value = 'https://example.com') {
        if (window.QRCodeStyling) {
            try {
                const host = document.createElement('div');
                host.style.position = 'fixed';
                host.style.left = '-9999px';
                host.style.top = '-9999px';
                document.body.appendChild(host);
                const qr = new window.QRCodeStyling({
                    width,
                    height,
                    data: value,
                    type: 'canvas',
                    qrOptions: { errorCorrectionLevel: 'M' },
                    dotsOptions: { color: '#0f172a', type: 'rounded' },
                    backgroundOptions: { color: '#ffffff' }
                });
                qr.append(host);
                await new Promise((resolve) => setTimeout(resolve, 60));
                const generated = host.querySelector('canvas');
                if (generated) {
                    const buffer = document.createElement('canvas');
                    buffer.width = width;
                    buffer.height = height;
                    buffer.getContext('2d')?.drawImage(generated, 0, 0, width, height);
                    document.body.removeChild(host);
                    return buffer;
                }
                document.body.removeChild(host);
            } catch (error) {
                console.warn('Failed to generate QR preview, using fallback.', error);
            }
        }

        const fallback = document.createElement('canvas');
        fallback.width = width;
        fallback.height = height;
        const ctx = fallback.getContext('2d');
        if (!ctx) return null;
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, width, height);
        const cell = Math.max(4, Math.floor(width / 29));
        const seed = 17;
        for (let row = 0; row < 29; row += 1) {
            for (let col = 0; col < 29; col += 1) {
                const finderZone = (row < 7 && col < 7) || (row < 7 && col > 21) || (row > 21 && col < 7);
                const on = finderZone || ((row * 31 + col * 17 + seed) % 5 === 0);
                if (on) {
                    ctx.fillStyle = '#111827';
                    ctx.fillRect(col * cell, row * cell, cell, cell);
                }
            }
        }
        return fallback;
    }

    function parseHexColor(value) {
        if (typeof value !== 'string') return null;
        const normalized = value.trim();
        const short = normalized.match(/^#([0-9a-f]{3})$/i);
        if (short) {
            const [r, g, b] = short[1].split('').map((ch) => parseInt(ch + ch, 16));
            return { r, g, b };
        }
        const long = normalized.match(/^#([0-9a-f]{6})$/i);
        if (!long) return null;
        return {
            r: parseInt(long[1].slice(0, 2), 16),
            g: parseInt(long[1].slice(2, 4), 16),
            b: parseInt(long[1].slice(4, 6), 16)
        };
    }

    function luminanceFromRgb({ r, g, b }) {
        const toLinear = (v) => {
            const s = v / 255;
            return s <= 0.03928 ? s / 12.92 : ((s + 0.055) / 1.055) ** 2.4;
        };
        const rl = toLinear(r);
        const gl = toLinear(g);
        const bl = toLinear(b);
        return 0.2126 * rl + 0.7152 * gl + 0.0722 * bl;
    }

    function intersectsRect(a, b) {
        return !(a.right <= b.left || a.left >= b.right || a.bottom <= b.top || a.top >= b.bottom);
    }

    function syncWarnings() {
        if (!safetyWarnings) return;
        const qrRect = getQrBounds();
        if (!qrRect) return;

        const warnings = [];
        const activeObjects = fabricCanvas.getObjects().filter((obj) => (
            obj.name !== 'qr_zone' &&
            obj.name !== 'qr_zone_label' &&
            !isGuideObject(obj)
        ));

        activeObjects.forEach((obj) => {
            const bbox = obj.getBoundingRect(true, true);
            const objRect = {
                left: bbox.left,
                top: bbox.top,
                right: bbox.left + bbox.width,
                bottom: bbox.top + bbox.height
            };
            if (intersectsRect(objRect, qrRect)) {
                warnings.push('This element may block QR readability.');
                if ((obj.opacity ?? 1) > 0.35) {
                    warnings.push('High opacity over safe area may reduce scan success.');
                }
            }
        });

        const bg = parseHexColor(bgInput?.value || '#ffffff');
        if (bg) {
            const contrastWithDark = (Math.max(luminanceFromRgb(bg), 0.03) + 0.05) / (0.02 + 0.05);
            if (contrastWithDark < 4.5) {
                warnings.push('Low contrast detected for dark QR modules.');
            }
        }

        const uniqueWarnings = [...new Set(warnings)];
        safetyWarnings.innerHTML = '';
        if (!uniqueWarnings.length) {
            safetyWarnings.innerHTML = '<li class="rounded-lg border border-emerald-500/40 bg-emerald-500/10 px-2 py-1 text-emerald-200">No warnings detected.</li>';
            return;
        }
        uniqueWarnings.forEach((warning) => {
            const item = document.createElement('li');
            item.className = 'rounded-lg border border-amber-500/50 bg-amber-500/10 px-2 py-1 text-amber-100';
            item.textContent = `⚠ ${warning}`;
            safetyWarnings.appendChild(item);
        });
    }

    async function syncRendererPreview() {
        if (!window.renderFrameDesign || !previewCanvasEl) return;
        const design = serializeDesignJson();
        if (!qrPreviewCanvasCache) {
            qrPreviewCanvasCache = await buildQrPreviewCanvas(260, 260, 'https://example.com');
        }
        await window.renderFrameDesign(previewCanvasEl, design, '#111111', '#ffffff', qrPreviewCanvasCache);
        syncWarnings();
    }

    function syncPropertiesPanel() {
        const panel = document.getElementById('props_panel');
        const empty = document.getElementById('props_empty');
        const active = fabricCanvas.getActiveObject();

        if (!active || active.name === 'qr_zone' || active.name === 'qr_zone_label') {
            panel?.classList.add('hidden');
            empty?.classList.remove('hidden');
            return;
        }

        selectedObj = active;
        empty?.classList.add('hidden');
        panel?.classList.remove('hidden');

        if (propFill) {
            propFill.value = (typeof active.fill === 'string' && active.fill.startsWith('#')) ? active.fill : '#000000';
            propFill.disabled = active.type === 'image';
        }
        if (propStroke) {
            propStroke.value = (typeof active.stroke === 'string' && active.stroke.startsWith('#')) ? active.stroke : '#000000';
            propStroke.disabled = active.type === 'image';
        }
        if (propStrokeWidth) {
            propStrokeWidth.value = Number(active.strokeWidth || 0);
            propStrokeWidth.disabled = active.type === 'image';
        }
        if (propOpacity) {
            propOpacity.value = active.opacity ?? 1;
        }
        if (textProps) {
            const isText = active.type === 'textbox' || active.type === 'text';
            textProps.classList.toggle('hidden', !isText);
            if (isText) {
                if (propText) propText.value = active.text || '';
                if (propFontSize) propFontSize.value = Number(active.fontSize || 20);
            }
        }
    }

    function setEditorView(mode = 'canvas') {
        const showPreview = mode === 'preview';
        editorCanvasPanel?.classList.toggle('hidden', showPreview);
        editorPreviewPanel?.classList.toggle('hidden', !showPreview);

        if (viewToggleCanvasBtn) {
            viewToggleCanvasBtn.classList.toggle('border-violet-500', !showPreview);
            viewToggleCanvasBtn.classList.toggle('bg-violet-500/10', !showPreview);
            viewToggleCanvasBtn.classList.toggle('text-violet-300', !showPreview);
            viewToggleCanvasBtn.classList.toggle('border-slate-600', showPreview);
            viewToggleCanvasBtn.classList.toggle('text-slate-200', showPreview);
        }
        if (viewTogglePreviewBtn) {
            viewTogglePreviewBtn.classList.toggle('border-violet-500', showPreview);
            viewTogglePreviewBtn.classList.toggle('bg-violet-500/10', showPreview);
            viewTogglePreviewBtn.classList.toggle('text-violet-300', showPreview);
            viewTogglePreviewBtn.classList.toggle('border-slate-600', !showPreview);
            viewTogglePreviewBtn.classList.toggle('text-slate-200', !showPreview);
        }

        if (showPreview) {
            syncRendererPreview();
        } else {
            updateCanvasQrOverlay();
            applyCanvasLayerStyles();
            fabricCanvas.calcOffset();
            fabricCanvas.getObjects().forEach((obj) => {
                if (obj.visible !== false) {
                    obj.dirty = true;
                }
            });
            fabricCanvas.renderAll();
            fabricCanvas.requestRenderAll();
        }
    }

    function reorderLayerToTarget(sourceId, targetId) {
        if (!sourceId || !targetId || sourceId === targetId) return;
        const objects = fabricCanvas.getObjects().filter((obj) => !isGuideObject(obj) && obj.name !== 'qr_zone_label');
        const source = objects.find((obj) => obj.id === sourceId);
        const target = objects.find((obj) => obj.id === targetId);
        if (!source || !target || source.name === 'qr_zone' || target.name === 'qr_zone') return;

        const sourceIndex = fabricCanvas.getObjects().indexOf(source);
        const targetIndex = fabricCanvas.getObjects().indexOf(target);
        if (sourceIndex < 0 || targetIndex < 0) return;

        source.moveTo(targetIndex);
        ensureQrZone();
        fabricCanvas.renderAll();
        syncLayerList();
        syncRendererPreview();
    }

    function syncLayerList() {
        if (!layerList) return;

        const objects = fabricCanvas.getObjects().filter((obj) => !isGuideObject(obj) && obj.name !== 'qr_zone_label');
        const ordered = [...objects].reverse();
        layerList.innerHTML = '';

        for (const obj of ordered) {
            const isQrZone = obj.name === 'qr_zone';
            const layerType = isQrZone ? 'qr_zone' : (obj.layerType || obj.type || 'layer');
            const li = document.createElement('li');
            li.className = `rounded-lg border px-2 py-1 text-xs flex items-center justify-between gap-2 ${isQrZone ? 'border-blue-500/60 bg-blue-500/10 text-blue-100' : 'border-slate-700 bg-slate-900/80 text-slate-200'}`;
            li.draggable = !isQrZone;
            li.dataset.layerId = obj.id;

            if (!isQrZone) {
                li.addEventListener('dragstart', () => {
                    draggedLayerId = obj.id;
                    li.classList.add('opacity-60');
                });
                li.addEventListener('dragend', () => {
                    draggedLayerId = null;
                    li.classList.remove('opacity-60');
                });
                li.addEventListener('dragover', (event) => {
                    event.preventDefault();
                    li.classList.add('border-violet-500');
                });
                li.addEventListener('dragleave', () => {
                    li.classList.remove('border-violet-500');
                });
                li.addEventListener('drop', (event) => {
                    event.preventDefault();
                    li.classList.remove('border-violet-500');
                    if (!draggedLayerId) return;
                    reorderLayerToTarget(draggedLayerId, obj.id);
                });
            }

            const left = document.createElement('button');
            left.type = 'button';
            left.className = 'text-left truncate flex-1';
            left.textContent = isQrZone ? 'QR Safe Area (resize only)' : layerType;
            left.addEventListener('click', () => {
                fabricCanvas.setActiveObject(obj);
                fabricCanvas.renderAll();
                syncPropertiesPanel();
            });

            const actions = document.createElement('div');
            actions.className = 'flex items-center gap-1';

            const upBtn = document.createElement('button');
            upBtn.type = 'button';
            upBtn.textContent = '▲';
            upBtn.className = 'px-1 rounded border border-slate-600 hover:bg-slate-800 disabled:opacity-40';
            upBtn.disabled = isQrZone;
            upBtn.addEventListener('click', () => {
                if (isQrZone) return;
                fabricCanvas.bringForward(obj);
                fabricCanvas.renderAll();
                ensureQrZone();
                syncLayerList();
                syncRendererPreview();
            });

            const downBtn = document.createElement('button');
            downBtn.type = 'button';
            downBtn.textContent = '▼';
            downBtn.className = 'px-1 rounded border border-slate-600 hover:bg-slate-800 disabled:opacity-40';
            downBtn.disabled = isQrZone;
            downBtn.addEventListener('click', () => {
                if (isQrZone) return;
                fabricCanvas.sendBackwards(obj);
                ensureQrZone();
                fabricCanvas.renderAll();
                syncLayerList();
                syncRendererPreview();
            });

            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.textContent = '🗑';
            delBtn.className = 'px-1 rounded border border-red-500/60 text-red-300 hover:bg-red-500/20 disabled:opacity-40';
            delBtn.disabled = isQrZone;
            delBtn.addEventListener('click', () => {
                if (isQrZone) return;
                fabricCanvas.remove(obj);
                fabricCanvas.renderAll();
                syncLayerList();
                syncRendererPreview();
            });

            actions.appendChild(upBtn);
            actions.appendChild(downBtn);
            actions.appendChild(delBtn);
            li.appendChild(left);
            li.appendChild(actions);
            layerList.appendChild(li);
        }
    }

    if (propFill) {
        propFill.addEventListener('input', (e) => {
            if (!selectedObj) return;
            selectedObj.set('fill', e.target.value);
            fabricCanvas.renderAll();
            syncRendererPreview();
        });
    }

    if (propStroke) {
        propStroke.addEventListener('input', (e) => {
            if (!selectedObj) return;
            selectedObj.set('stroke', e.target.value);
            fabricCanvas.renderAll();
            syncRendererPreview();
        });
    }

    if (propStrokeWidth) {
        propStrokeWidth.addEventListener('input', (e) => {
            if (!selectedObj) return;
            selectedObj.set('strokeWidth', Number(e.target.value || 0));
            fabricCanvas.renderAll();
            syncRendererPreview();
        });
    }

    if (propOpacity) {
        propOpacity.addEventListener('input', (e) => {
            if (!selectedObj) return;
            selectedObj.set('opacity', Number(e.target.value));
            fabricCanvas.renderAll();
            syncRendererPreview();
        });
    }

    if (propText) {
        propText.addEventListener('input', (e) => {
            if (!selectedObj) return;
            if (selectedObj.type === 'textbox' || selectedObj.type === 'text') {
                selectedObj.set('text', e.target.value);
                fabricCanvas.renderAll();
                syncRendererPreview();
            }
        });
    }

    if (propFontSize) {
        propFontSize.addEventListener('input', (e) => {
            if (!selectedObj) return;
            if (selectedObj.type === 'textbox' || selectedObj.type === 'text') {
                const size = Number(e.target.value || 20);
                selectedObj.set('fontSize', Math.max(8, Math.min(200, size)));
                fabricCanvas.renderAll();
                syncRendererPreview();
            }
        });
    }

    if (imageLayerInput) {
        imageLayerInput.addEventListener('change', async (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            const validation = await validateImageUploadFile(file);
            if (!validation.valid) {
                e.target.value = '';
                setUploadFeedback(validation.message, true);
                return;
            }
            const reader = new FileReader();
            reader.onload = (ev) => {
                addImageLayerFromDataUrl(String(ev.target?.result || ''));
                setUploadFeedback(`Image added: ${file.name}`, false);
            };
            reader.readAsDataURL(file);
            e.target.value = '';
        });
    }

    function removeCenterGuides() {
        if (horizontalGuide) {
            fabricCanvas.remove(horizontalGuide);
            horizontalGuide = null;
        }
        if (verticalGuide) {
            fabricCanvas.remove(verticalGuide);
            verticalGuide = null;
        }
    }

    function updateCenterGuides(target) {
        if (!target || target.name === 'qr_zone' || target.name === 'qr_zone_label' || isGuideObject(target)) {
            removeCenterGuides();
            return;
        }

        const canvasCenterX = fabricCanvas.getWidth() / 2;
        const canvasCenterY = fabricCanvas.getHeight() / 2;
        const targetCenter = target.getCenterPoint();
        const threshold = 8;

        let snappedX = false;
        let snappedY = false;

        if (Math.abs(targetCenter.x - canvasCenterX) <= threshold) {
            target.setPositionByOrigin(new fabric.Point(canvasCenterX, targetCenter.y), 'center', 'center');
            snappedX = true;
        }

        if (Math.abs(targetCenter.y - canvasCenterY) <= threshold) {
            target.setPositionByOrigin(new fabric.Point(target.getCenterPoint().x, canvasCenterY), 'center', 'center');
            snappedY = true;
        }

        if (snappedX) {
            if (!verticalGuide) {
                verticalGuide = new fabric.Line([canvasCenterX, 0, canvasCenterX, fabricCanvas.getHeight()], {
                    stroke: '#ef4444',
                    strokeWidth: 1,
                    strokeDashArray: [6, 4],
                    selectable: false,
                    evented: false
                });
                verticalGuide.__guideLine = true;
                fabricCanvas.add(verticalGuide);
            } else {
                verticalGuide.set({ x1: canvasCenterX, x2: canvasCenterX, y1: 0, y2: fabricCanvas.getHeight() });
            }
            fabricCanvas.bringToFront(verticalGuide);
        } else if (verticalGuide) {
            fabricCanvas.remove(verticalGuide);
            verticalGuide = null;
        }

        if (snappedY) {
            if (!horizontalGuide) {
                horizontalGuide = new fabric.Line([0, canvasCenterY, fabricCanvas.getWidth(), canvasCenterY], {
                    stroke: '#ef4444',
                    strokeWidth: 1,
                    strokeDashArray: [6, 4],
                    selectable: false,
                    evented: false
                });
                horizontalGuide.__guideLine = true;
                fabricCanvas.add(horizontalGuide);
            } else {
                horizontalGuide.set({ x1: 0, x2: fabricCanvas.getWidth(), y1: canvasCenterY, y2: canvasCenterY });
            }
            fabricCanvas.bringToFront(horizontalGuide);
        } else if (horizontalGuide) {
            fabricCanvas.remove(horizontalGuide);
            horizontalGuide = null;
        }
    }

    function snapToQrZone(target) {
        if (!target || target.name === 'qr_zone' || target.name === 'qr_zone_label' || isGuideObject(target)) {
            return;
        }
        const qrRect = getQrBounds();
        if (!qrRect) return;
        const bbox = target.getBoundingRect(true, true);
        const objW = bbox.width;
        const objH = bbox.height;

        const candidates = [
            { diff: Math.abs((bbox.left + objW) - qrRect.left), left: qrRect.left - objW, top: bbox.top },
            { diff: Math.abs(bbox.left - qrRect.right), left: qrRect.right, top: bbox.top },
            { diff: Math.abs((bbox.top + objH) - qrRect.top), left: bbox.left, top: qrRect.top - objH },
            { diff: Math.abs(bbox.top - qrRect.bottom), left: bbox.left, top: qrRect.bottom },
            { diff: Math.abs((bbox.left + objW / 2) - qrRect.centerX), left: qrRect.centerX - objW / 2, top: bbox.top },
            { diff: Math.abs((bbox.top + objH / 2) - qrRect.centerY), left: bbox.left, top: qrRect.centerY - objH / 2 }
        ].filter((candidate) => candidate.diff <= SNAP_DISTANCE);

        if (!candidates.length) return;
        const nearest = candidates.sort((a, b) => a.diff - b.diff)[0];
        target.set({
            left: nearest.left,
            top: nearest.top
        });
        target.setCoords();
    }

    fabricCanvas.on('selection:created', syncPropertiesPanel);
    fabricCanvas.on('selection:updated', syncPropertiesPanel);
    fabricCanvas.on('selection:cleared', syncPropertiesPanel);
    fabricCanvas.on('object:scaling', (event) => {
        if (event.target?.name === 'qr_zone') {
            handleQrZoneScaling(event.target);
            return;
        }
        updateCanvasQrOverlay();
    });
    fabricCanvas.on('object:moving', (event) => {
        if (event.target?.name === 'qr_zone') {
            handleQrZoneMoving(event.target);
            return;
        }
        snapToQrZone(event.target);
        updateCenterGuides(event.target);
        syncWarnings();
        updateCanvasQrOverlay();
    });
    fabricCanvas.on('object:modified', () => {
        removeCenterGuides();
        syncRendererPreview();
        syncLayerList();
        updateCanvasQrOverlay();
    });
    fabricCanvas.on('mouse:up', () => {
        removeCenterGuides();
        syncRendererPreview();
        syncLayerList();
        updateCanvasQrOverlay();
    });
    fabricCanvas.on('object:added', () => {
        syncRendererPreview();
        syncLayerList();
        updateCanvasQrOverlay();
        fabricCanvas.requestRenderAll();
    });
    fabricCanvas.on('object:removed', () => {
        syncRendererPreview();
        syncLayerList();
        updateCanvasQrOverlay();
        fabricCanvas.requestRenderAll();
    });
    applyCanvasLayerStyles();

    if (canvasDropzone) {
        canvasDropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
            setDropzoneActive(true);
            if (event.dataTransfer) {
                event.dataTransfer.dropEffect = 'copy';
            }
        });

        canvasDropzone.addEventListener('dragleave', () => {
            if (!dragElementId) {
                setDropzoneActive(false);
            }
        });

        canvasDropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            const elementId = event.dataTransfer?.getData('text/plain') || dragElementId;
            const catalogItem = findCatalogItemById(elementId);
            if (!catalogItem) {
                setDropzoneActive(false);
                return;
            }

            const point = dropClientPointToCanvas(event.clientX, event.clientY);
            const obj = catalogItem.create();
            addFabricObjectAt(obj, point.x, point.y);
            setDropzoneActive(false);
            dragElementId = null;
        });
    }

    document.addEventListener('keydown', (event) => {
        if (!fabricCanvas) return;
        const active = fabricCanvas.getActiveObject();
        if (!active || active.name === 'qr_zone' || active.name === 'qr_zone_label') return;

        const isInput = ['INPUT', 'TEXTAREA'].includes(document.activeElement?.tagName || '');
        if ((event.key === 'Backspace' || event.key === 'Delete') && !isInput) {
            event.preventDefault();
            deleteSelectedLayer();
            return;
        }

        const step = event.shiftKey ? 10 : 1;
        if (event.key === 'ArrowLeft') active.set('left', (active.left || 0) - step);
        if (event.key === 'ArrowRight') active.set('left', (active.left || 0) + step);
        if (event.key === 'ArrowUp') active.set('top', (active.top || 0) - step);
        if (event.key === 'ArrowDown') active.set('top', (active.top || 0) + step);
        if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(event.key)) {
            event.preventDefault();
            fabricCanvas.renderAll();
            syncRendererPreview();
        }
    });

    widthInput?.addEventListener('change', () => {
        const design = serializeDesignJson();
        fabricCanvas.setWidth(Number(widthInput.value || 400));
        setQrZonePercent(
            design.qr_zone?.x_pct ?? 22,
            design.qr_zone?.y_pct ?? 22,
            design.qr_zone?.w_pct ?? 56,
            design.qr_zone?.h_pct ?? 56
        );
        applyCanvasLayerStyles();
        fabricCanvas.renderAll();
        syncRendererPreview();
    });

    heightInput?.addEventListener('change', () => {
        const design = serializeDesignJson();
        fabricCanvas.setHeight(Number(heightInput.value || 500));
        setQrZonePercent(
            design.qr_zone?.x_pct ?? 22,
            design.qr_zone?.y_pct ?? 22,
            design.qr_zone?.w_pct ?? 56,
            design.qr_zone?.h_pct ?? 56
        );
        applyCanvasLayerStyles();
        fabricCanvas.renderAll();
        syncRendererPreview();
    });

    bgInput?.addEventListener('input', () => {
        fabricCanvas.backgroundColor = bgInput.value;
        applyCanvasLayerStyles();
        fabricCanvas.renderAll();
        syncRendererPreview();
    });

    categoryButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const category = button.getAttribute('data-category') || 'shapes';
            setActiveCategory(category);
        });
    });

    templateButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const category = button.getAttribute('data-template-category') || 'minimal';
            templateButtons.forEach((btn) => {
                if (btn === button) {
                    btn.classList.add('border-violet-500', 'bg-violet-500/10', 'text-violet-300');
                    btn.classList.remove('border-slate-700', 'bg-slate-900', 'text-slate-200');
                } else {
                    btn.classList.remove('border-violet-500', 'bg-violet-500/10', 'text-violet-300');
                    btn.classList.add('border-slate-700', 'bg-slate-900', 'text-slate-200');
                }
            });
            renderTemplates(category);
        });
    });

    if (elementSearchInput) {
        elementSearchInput.addEventListener('input', () => {
            renderElementCatalog(currentCategory);
        });
    }

    setActiveCategory(currentCategory);
    renderTemplates('minimal');
    if (refreshPreviewBtn) {
        refreshPreviewBtn.addEventListener('click', syncRendererPreview);
    }
    viewToggleCanvasBtn?.addEventListener('click', () => setEditorView('canvas'));
    viewTogglePreviewBtn?.addEventListener('click', () => setEditorView('preview'));
    window.addEventListener('resize', () => updateCanvasQrOverlay());
    if (saveBtn && existingFrame?.id) {
        saveBtn.dataset.frameId = String(existingFrame.id);
    }
    if (finishBtn) {
        finishBtn.disabled = !returnTo;
        finishBtn.classList.toggle('opacity-50', !returnTo);
        finishBtn.classList.toggle('cursor-not-allowed', !returnTo);
        finishBtn.addEventListener('click', async () => {
            if (!returnTo) {
                if (saveStatus) saveStatus.textContent = 'Open editor from QR wizard to use Finish.';
                return;
            }
            finishBtn.disabled = true;
            try {
                await saveFrameDesign({ finish: true });
            } finally {
                finishBtn.disabled = false;
            }
        });
    }

    async function saveFrameDesign(options = {}) {
        const shouldFinish = !!options.finish;
        if (!saveStatus) return;
        saveStatus.textContent = 'Saving...';

        const designJson = serializeDesignJson();
        const svgContent = designJsonToSvg(designJson);
        const thumb = previewCanvasEl?.toDataURL('image/png') || null;

        const currentFrameId = saveBtn?.dataset.frameId || existingFrame?.id || null;
        const isUpdate = !!currentFrameId;
        const endpoint = isUpdate ? `${updateBaseUrl}/${currentFrameId}` : storeUrl;

        const response = await fetch(endpoint, {
            method: isUpdate ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                name: document.getElementById('frame_name')?.value || 'My Frame',
                design_json: designJson,
                svg_content: svgContent,
                thumbnail_data_url: thumb
            })
        });

        const data = await response.json();
        if (response.ok && data.success) {
            saveStatus.textContent = isUpdate ? 'Updated successfully.' : 'Saved successfully.';
            const savedFrameId = data.frame_id || data.id || currentFrameId;
            if (saveBtn && savedFrameId) {
                saveBtn.dataset.frameId = String(savedFrameId);
            }
            if (shouldFinish && savedFrameId) {
                const openerNotified = notifyOpenerOnFinish(savedFrameId);
                if (openerNotified) {
                    saveStatus.textContent = 'Saved. Returning to wizard...';
                    setTimeout(() => {
                        window.close();
                    }, 120);
                    return savedFrameId;
                }
                const redirectUrl = buildFinishRedirectUrl(savedFrameId);
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                    return savedFrameId;
                }
                saveStatus.textContent = 'Saved successfully. Return URL is not available.';
            }
            return savedFrameId;
        }

        saveStatus.textContent = data.message || 'Save failed.';
        return null;
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
        if (widthInput) widthInput.value = String(fabricCanvas.width);
        if (heightInput) heightInput.value = String(fabricCanvas.height);
        if (bgInput) {
            bgInput.value = design.background || '#ffffff';
            fabricCanvas.backgroundColor = bgInput.value;
        }

        const zone = createQrZoneFromPercent(
            design.qr_zone || { x_pct: 22, y_pct: 22, w_pct: 56, h_pct: 56 },
            fabricCanvas.width,
            fabricCanvas.height
        );
        fabricCanvas.add(zone);
        fabricCanvas.add(createQrZoneLabel(zone));

        (design.layers || []).forEach((layer) => {
            if (layer.type === 'text') {
                const text = attachLayerMeta(new fabric.Textbox(layer.text || 'Text', {
                    left: layer.x || 0,
                    top: layer.y || 0,
                    fontSize: layer.font_size || 20,
                    fill: layer.color || '#000000',
                    fontFamily: layer.font_family || 'Arial',
                    fontWeight: layer.font_weight || 'normal',
                    opacity: layer.opacity ?? 1
                }), 'text');
                if (layer.id) text.set('id', layer.id);
                fabricCanvas.add(text);
            } else if (layer.type === 'rect') {
                const rect = attachLayerMeta(new fabric.Rect({
                    left: layer.x || 0,
                    top: layer.y || 0,
                    width: layer.width || 100,
                    height: layer.height || 100,
                    fill: layer.fill || 'transparent',
                    stroke: layer.stroke || null,
                    strokeWidth: layer.stroke_width || 0,
                    opacity: layer.opacity ?? 1
                }), 'rect');
                if (layer.id) rect.set('id', layer.id);
                fabricCanvas.add(rect);
            } else if (layer.type === 'circle') {
                const circle = attachLayerMeta(new fabric.Circle({
                    left: (layer.x || 0) - (layer.radius || 40),
                    top: (layer.y || 0) - (layer.radius || 40),
                    radius: layer.radius || 40,
                    fill: layer.fill || '#cccccc',
                    stroke: layer.stroke || null,
                    strokeWidth: layer.stroke_width || 0,
                    opacity: layer.opacity ?? 1
                }), 'circle');
                if (layer.id) circle.set('id', layer.id);
                fabricCanvas.add(circle);
            } else if (layer.type === 'image' && layer.src) {
                fabric.Image.fromURL(layer.src, (img) => {
                    attachLayerMeta(img, 'image');
                    if (layer.id) img.set('id', layer.id);
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

        ensureQrZone();
        fabricCanvas.renderAll();
        syncRendererPreview();
        syncLayerList();
    }

    // Expose for existing inline onclick handlers in Blade.
    window.addTextLayer = addTextLayer;
    window.addRectLayer = addRectLayer;
    window.addCircleLayer = addCircleLayer;
    window.moveSelectedLayerUp = moveSelectedLayerUp;
    window.moveSelectedLayerDown = moveSelectedLayerDown;
    window.deleteSelectedLayer = deleteSelectedLayer;
    window.saveFrameDesign = saveFrameDesign;

    restoreFromExistingFrame();
    setEditorView('canvas');
    applyCanvasLayerStyles();
    syncLayerList();
    updateCanvasQrOverlay();
    fabricCanvas.requestRenderAll();
}

document.addEventListener('DOMContentLoaded', initFrameEditor);

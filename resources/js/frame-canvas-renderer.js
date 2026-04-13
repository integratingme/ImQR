function resolveColor(value, primaryColor, secondaryColor) {
    if (!value || typeof value !== 'string') {
        return value;
    }

    return value
        .replaceAll('#PRIMARY#', primaryColor)
        .replaceAll('#SECONDARY#', secondaryColor);
}

function drawRoundedRect(ctx, x, y, width, height, radius = 0) {
    const r = Math.max(0, Math.min(radius, Math.min(width / 2, height / 2)));
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.lineTo(x + width - r, y);
    ctx.quadraticCurveTo(x + width, y, x + width, y + r);
    ctx.lineTo(x + width, y + height - r);
    ctx.quadraticCurveTo(x + width, y + height, x + width - r, y + height);
    ctx.lineTo(x + r, y + height);
    ctx.quadraticCurveTo(x, y + height, x, y + height - r);
    ctx.lineTo(x, y + r);
    ctx.quadraticCurveTo(x, y, x + r, y);
    ctx.closePath();
}

async function drawImageLayer(ctx, layer, primaryColor, secondaryColor) {
    if (!layer.src) {
        return;
    }

    const image = new Image();
    image.crossOrigin = 'anonymous';

    await new Promise((resolve, reject) => {
        image.onload = resolve;
        image.onerror = reject;
        image.src = resolveColor(layer.src, primaryColor, secondaryColor);
    });

    ctx.globalAlpha = layer.opacity ?? 1;
    ctx.drawImage(image, layer.x ?? 0, layer.y ?? 0, layer.width ?? 120, layer.height ?? 120);
    ctx.globalAlpha = 1;
}

function drawLayer(ctx, layer, primaryColor, secondaryColor) {
    const fill = resolveColor(layer.fill, primaryColor, secondaryColor);
    const stroke = resolveColor(layer.stroke, primaryColor, secondaryColor);
    const color = resolveColor(layer.color, primaryColor, secondaryColor);
    const opacity = layer.opacity ?? 1;

    ctx.globalAlpha = opacity;

    if (layer.type === 'rect') {
        drawRoundedRect(ctx, layer.x ?? 0, layer.y ?? 0, layer.width ?? 100, layer.height ?? 100, layer.border_radius ?? 0);
        if (fill && fill !== 'none') {
            ctx.fillStyle = fill;
            ctx.fill();
        }
        if (stroke) {
            ctx.strokeStyle = stroke;
            ctx.lineWidth = layer.stroke_width ?? 1;
            ctx.stroke();
        }
    } else if (layer.type === 'circle') {
        const radius = layer.radius ?? Math.max((layer.width ?? 100) / 2, 1);
        ctx.beginPath();
        ctx.arc(layer.x ?? 0, layer.y ?? 0, radius, 0, Math.PI * 2);
        if (fill && fill !== 'none') {
            ctx.fillStyle = fill;
            ctx.fill();
        }
        if (stroke) {
            ctx.strokeStyle = stroke;
            ctx.lineWidth = layer.stroke_width ?? 1;
            ctx.stroke();
        }
    } else if (layer.type === 'text') {
        ctx.fillStyle = color || '#000000';
        const size = layer.font_size ?? 20;
        const family = layer.font_family || 'Arial';
        const weight = layer.font_weight || 'normal';
        ctx.font = `${weight} ${size}px ${family}`;
        ctx.textAlign = layer.text_align || 'left';
        ctx.textBaseline = 'middle';
        ctx.fillText(layer.text || '', layer.x ?? 0, layer.y ?? 0);
    }

    ctx.globalAlpha = 1;
}

function drawQrZone(ctx, designJson, qrCanvas = null) {
    const zone = designJson?.qr_zone;
    if (!zone) {
        return;
    }

    const x = (zone.x_pct / 100) * ctx.canvas.width;
    const y = (zone.y_pct / 100) * ctx.canvas.height;
    const w = (zone.w_pct / 100) * ctx.canvas.width;
    const h = (zone.h_pct / 100) * ctx.canvas.height;

    if (qrCanvas) {
        ctx.drawImage(qrCanvas, x, y, w, h);
        return;
    }

    ctx.fillStyle = 'rgba(99, 102, 241, 0.08)';
    ctx.strokeStyle = 'rgba(99, 102, 241, 0.8)';
    ctx.lineWidth = 2;
    ctx.setLineDash([7, 5]);
    ctx.fillRect(x, y, w, h);
    ctx.strokeRect(x, y, w, h);
    ctx.setLineDash([]);
}

export async function renderFrameDesign(canvas, designJson, primaryColor = '#000000', secondaryColor = '#ffffff', qrCanvas = null) {
    if (!canvas || !designJson) {
        return;
    }

    const ctx = canvas.getContext('2d');
    canvas.width = designJson.canvas_width || 400;
    canvas.height = designJson.canvas_height || 500;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = resolveColor(designJson.background || '#ffffff', primaryColor, secondaryColor);
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    const layers = Array.isArray(designJson.layers) ? [...designJson.layers] : [];
    layers.sort((a, b) => (a.z_index ?? 0) - (b.z_index ?? 0));

    for (const layer of layers) {
        if (layer.type === 'image') {
            try {
                await drawImageLayer(ctx, layer, primaryColor, secondaryColor);
            } catch (error) {
                console.warn('Failed to draw image layer:', error);
            }
            continue;
        }
        drawLayer(ctx, layer, primaryColor, secondaryColor);
    }

    drawQrZone(ctx, designJson, qrCanvas);
}

window.renderFrameDesign = renderFrameDesign;

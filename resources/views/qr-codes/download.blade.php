<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloading QR Code...</title>
    <style>
        body {
            margin: 0;
            padding: 40px;
            font-family: system-ui, -apple-system, sans-serif;
            background: #f9fafb;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
            max-width: 500px;
        }
        .spinner {
            border: 3px solid #e5e7eb;
            border-top-color: #6366f1;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 24px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 8px;
        }
        p {
            font-size: 16px;
            color: #6b7280;
            margin: 0 0 24px;
        }
        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background: #6366f1;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .back-link:hover {
            background: #4f46e5;
        }
        #qr-container {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner"></div>
        <h1>Preparing your QR code...</h1>
        <p>Your download will start automatically.</p>
        <a href="{{ route('qr-codes.history') }}" class="back-link">Back to History</a>
    </div>
    
    <div id="qr-container"></div>

    <script src="https://unpkg.com/qr-code-styling@1.9.2/lib/qr-code-styling.js"></script>
    <script>
        var QRCodeStyling = window.QRCodeStyling;
        var qrData = @json($qrCode->data);
        var colors = @json($qrCode->colors);
        var customization = @json($qrCode->customization);
        var qrType = '{{ $qrCode->type }}';
        var format = '{{ $format }}';
        var qrId = '{{ $qrCode->id }}';

        // Frame configuration
        const FRAME_CONFIG = {
            'standard-border': {
                url: '{{ asset("frames/standard-border.svg") }}',
                qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
                frameWidth: 400, frameHeight: 500,
                themable: true
            },
            'thick-border': {
                url: '{{ asset("frames/thick-border.svg") }}',
                qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
                frameWidth: 400, frameHeight: 500,
                themable: true
            },
            'speech-bubble': {
                url: '{{ asset("frames/speech-bubble.svg") }}',
                qrLeft: 5, qrTop: 3.85, qrWidth: 90, qrHeight: 69.2,
                frameWidth: 400, frameHeight: 520,
                themable: true
            },
            'menu-qr': {
                url: '{{ asset("frames/menu-qr.svg") }}',
                qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
                frameWidth: 400, frameHeight: 500,
                themable: true
            },
            'location': {
                url: '{{ asset("frames/location.svg") }}',
                qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
                frameWidth: 400, frameHeight: 500,
                themable: true
            },
            'wifi': {
                url: '{{ asset("frames/wifi.svg") }}',
                qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
                frameWidth: 400, frameHeight: 500,
                themable: true
            },
            'chat': {
                url: '{{ asset("frames/chat.svg") }}',
                qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
                frameWidth: 400, frameHeight: 500,
                themable: true
            },
            'coupon': {
                url: '{{ asset("frames/coupon.svg") }}',
                qrLeft: 5, qrTop: 4, qrWidth: 90, qrHeight: 72,
                frameWidth: 400, frameHeight: 500,
                themable: true
            },
            'review-us': {
                url: '{{ asset("frames/review-us.svg") }}',
                qrLeft: 15, qrTop: 6.15, qrWidth: 70, qrHeight: 43.08,
                frameWidth: 400, frameHeight: 650
            }
        };

        function normalizeHexColor(val) {
            if (!val || typeof val !== 'string') return '#000000';
            val = val.trim().replace(/^#/, '');
            if (/^[0-9A-Fa-f]{6}$/.test(val)) return '#' + val;
            if (/^[0-9A-Fa-f]{3}$/.test(val)) return '#' + val[0] + val[0] + val[1] + val[1] + val[2] + val[2];
            return '#000000';
        }

        function escapeSvgText(s) {
            if (s == null) return '';
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        async function getThemedFrameUrl(svgUrl, primaryHex, secondaryHex) {
            const primary = normalizeHexColor(primaryHex);
            const secondary = normalizeHexColor(secondaryHex);
            const res = await fetch(svgUrl);
            let text = await res.text();
            text = text.replace(/#PRIMARY#/gi, primary).replace(/#SECONDARY#/gi, secondary);
            const blob = new Blob([text], { type: 'image/svg+xml' });
            return URL.createObjectURL(blob);
        }

        async function getReviewUsFrameUrl(config) {
            var reviewUs = FRAME_CONFIG['review-us'];
            var svgUrl = reviewUs && reviewUs.url;
            if (!svgUrl) return '';
            const res = await fetch(svgUrl);
            let svg = await res.text();
            
            const frameColor = normalizeHexColor(config.color || '#84BD00');
            const textColor = normalizeHexColor(config.text_color || '#000000');
            
            svg = svg.replace(/fill="#84BD00"/, 'fill="' + frameColor + '"');
            svg = svg.replace(/(<text[^>]*?)fill="#000000"([^>]*>)/g, '$1fill="' + textColor + '"$2');
            
            const line1 = config.line1 || 'your';
            const line2 = config.line2 || 'text';
            const line3 = config.line3 || 'here';
            
            svg = svg.replace(/>your<\/text>/, '>' + escapeSvgText(line1) + '</text>');
            svg = svg.replace(/>text<\/text>/, '>' + escapeSvgText(line2) + '</text>');
            svg = svg.replace(/>here<\/text>/, '>' + escapeSvgText(line3) + '</text>');
            
            const iconValue = config.icon || 'default';
            const iconGroupRegex = /<g transform="translate\(100 480\)">[\s\S]*?<\/g>/;
            
            if (iconValue === 'custom' && config.logo_url) {
                const iconReplacement = '<image x="100" y="480" width="200" height="80" href="' + config.logo_url.replace(/"/g, '&quot;') + '" preserveAspectRatio="xMidYMid meet"/>';
                svg = svg.replace(iconGroupRegex, iconReplacement);
            }
            
            const blob = new Blob([svg], { type: 'image/svg+xml' });
            return URL.createObjectURL(blob);
        }

        async function generateAndDownload() {
            if (!QRCodeStyling) {
                alert('QR code library failed to load. Please check your connection and try again.');
                return;
            }
            var primaryColor = normalizeHexColor(colors.primary || '#000000');
            const secondaryColor = normalizeHexColor(colors.secondary || '#FFFFFF');
            const pattern = customization.pattern || 'square';
            const cornerStyle = customization.corner_style || 'square';
            const cornerDotStyle = customization.corner_dot_style || 'square';
            const frameId = customization.frame || 'none';
            const logoUrl = customization.logo_url || '';

            // Build QR content
            let qrContent = '';
            switch (qrType) {
                case 'text':
                    qrContent = qrData.text_page_url || '';
                    break;
                case 'pdf':
                    qrContent = qrData.pdf_page_url || '';
                    break;
                case 'coupon':
                    qrContent = qrData.coupon_page_url || '';
                    break;
                case 'app':
                    qrContent = qrData.app_page_url || '';
                    break;
                case 'phone':
                    qrContent = qrData.phone_page_url || '';
                    break;
                case 'menu':
                    qrContent = qrData.menu_page_url || '';
                    break;
                case 'location':
                    qrContent = qrData.location_url || '';
                    break;
                default:
                    qrContent = qrData.url || '';
            }

            const dotsTypeMap = { square: 'square', circle: 'dots', rounded: 'rounded' };
            const cornersSquareTypeMap = { square: 'square', rounded: 'rounded', 'extra-rounded': 'extra-rounded' };
            const cornersDotTypeMap = { square: 'square', circle: 'dot', rounded: 'rounded' };

            const dotsType = dotsTypeMap[pattern] || 'square';
            const cornersSquareType = cornersSquareTypeMap[cornerStyle] || 'square';
            const cornersDotType = cornersDotTypeMap[cornerDotStyle] || 'square';

            const DOWNLOAD_SIZE = 1000;
            const qrSize = (frameId && frameId !== 'none') ? 850 : DOWNLOAD_SIZE;

            const container = document.getElementById('qr-container');
            container.innerHTML = '';

            if (frameId && frameId !== 'none' && FRAME_CONFIG[frameId]) {
                const cfg = FRAME_CONFIG[frameId];
                if (cfg && cfg.url) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'frame-wrapper';
                    wrapper.style.position = 'relative';
                    wrapper.style.display = 'inline-block';

                    const holePx = DOWNLOAD_SIZE;
                    const totalW = holePx / (cfg.qrWidth / 100);
                    const totalH = totalW * (cfg.frameHeight / cfg.frameWidth);
                    wrapper.style.width = totalW + 'px';
                    wrapper.style.height = totalH + 'px';

                    const img = document.createElement('img');
                    if (frameId === 'review-us') {
                        img.src = await getReviewUsFrameUrl(customization.review_us_config || {});
                    } else {
                        img.src = cfg.themable
                            ? await getThemedFrameUrl(cfg.url, primaryColor, secondaryColor)
                            : cfg.url;
                    }
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'contain';

                    await new Promise(resolve => {
                        img.onload = resolve;
                    });

                    const qrInFrame = document.createElement('div');
                    qrInFrame.style.position = 'absolute';
                    qrInFrame.style.left = cfg.qrLeft + '%';
                    qrInFrame.style.top = cfg.qrTop + '%';
                    qrInFrame.style.width = cfg.qrWidth + '%';
                    qrInFrame.style.height = cfg.qrHeight + '%';
                    qrInFrame.style.display = 'flex';
                    qrInFrame.style.alignItems = 'center';
                    qrInFrame.style.justifyContent = 'center';

                    wrapper.appendChild(img);
                    wrapper.appendChild(qrInFrame);
                    container.appendChild(wrapper);

                    const options = {
                        width: qrSize,
                        height: qrSize,
                        type: 'canvas',
                        data: qrContent,
                        margin: 0,
                        qrOptions: { errorCorrectionLevel: 'H' },
                        dotsOptions: { color: primaryColor, type: dotsType },
                        backgroundOptions: { color: secondaryColor },
                        cornersSquareOptions: { type: cornersSquareType, color: primaryColor },
                        cornersDotOptions: { type: cornersDotType, color: primaryColor },
                        image: logoUrl || undefined,
                        imageOptions: {
                            hideBackgroundDots: true,
                            imageSize: 0.4,
                            margin: 4,
                            crossOrigin: 'anonymous',
                        },
                    };

                    const qrCodeStyling = new QRCodeStyling(options);
                    qrCodeStyling.append(qrInFrame);

                    // Wait for QR to render, then convert frame+QR to image
                    await new Promise(resolve => setTimeout(resolve, 500));

                    // Convert the entire wrapper to canvas and download
                    const canvas = document.createElement('canvas');
                    canvas.width = totalW;
                    canvas.height = totalH;
                    const ctx = canvas.getContext('2d');

                    // Draw frame
                    ctx.drawImage(img, 0, 0, totalW, totalH);

                    // Draw QR code
                    const qrCanvas = qrInFrame.querySelector('canvas');
                    if (qrCanvas) {
                        const qrLeft = totalW * (cfg.qrLeft / 100);
                        const qrTop = totalH * (cfg.qrTop / 100);
                        const qrWidth = totalW * (cfg.qrWidth / 100);
                        const qrHeight = totalH * (cfg.qrHeight / 100);
                        const qrX = qrLeft + (qrWidth - qrSize) / 2;
                        const qrY = qrTop + (qrHeight - qrSize) / 2;
                        ctx.drawImage(qrCanvas, qrX, qrY, qrSize, qrSize);
                    }

                    // Download
                    if (format === 'png') {
                        canvas.toBlob(blob => {
                            const url = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `qr-code-${qrId}.png`;
                            a.click();
                            URL.revokeObjectURL(url);
                        });
                    } else {
                        // SVG export for frames is complex, fallback to PNG
                        canvas.toBlob(blob => {
                            const url = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `qr-code-${qrId}.png`;
                            a.click();
                            URL.revokeObjectURL(url);
                        });
                    }
                }
            } else {
                // No frame - simple download
                const options = {
                    width: qrSize,
                    height: qrSize,
                    type: 'canvas',
                    data: qrContent,
                    margin: 0,
                    qrOptions: { errorCorrectionLevel: 'H' },
                    dotsOptions: { color: primaryColor, type: dotsType },
                    backgroundOptions: { color: secondaryColor },
                    cornersSquareOptions: { type: cornersSquareType, color: primaryColor },
                    cornersDotOptions: { type: cornersDotType, color: primaryColor },
                    image: logoUrl || undefined,
                    imageOptions: {
                        hideBackgroundDots: true,
                        imageSize: 0.4,
                        margin: 4,
                        crossOrigin: 'anonymous',
                    },
                };

                const qrCodeStyling = new QRCodeStyling(options);
                qrCodeStyling.append(container);

                await new Promise(resolve => setTimeout(resolve, 500));

                if (format === 'svg') {
                    qrCodeStyling.download({
                        name: `qr-code-${qrId}`,
                        extension: 'svg'
                    });
                } else {
                    qrCodeStyling.download({
                        name: `qr-code-${qrId}`,
                        extension: 'png'
                    });
                }
            }
        }

        // Start generation on page load
        generateAndDownload().catch(err => {
            console.error('Download error:', err);
            alert('Failed to generate QR code. Please try again.');
        });
    </script>
</body>
</html>

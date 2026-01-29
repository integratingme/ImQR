<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company ?: 'Coupon' }} - {{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $fontFamily = $fontFamily ?? 'Maven Pro';
        $googleFonts = ['Maven Pro', 'Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Poppins', 'Raleway', 'Nunito'];
    @endphp
    @if(in_array($fontFamily, $googleFonts))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $fontFamily) }}:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif
    <style>
        body { margin: 0; padding: 0; overflow-x: hidden; font-family: '{{ $fontFamily }}', sans-serif; }
        .coupon-page-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: {{ $primaryColor }};
        }
        .coupon-page-company {
            min-height: 20vh;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 24px 16px;
            font-size: 2rem;
            font-weight: 3rem;
            color: {{ $secondaryColor }};
        }
        .coupon-page-company-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .coupon-page-card-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 16px;
            min-height: 0;
        }
        .coupon-card {
            background: {{ $secondaryColor }};
            border-radius: 15px;
            max-width: 420px;
            width: 100%;
            height: 70vh;
            min-height: 420px;
            max-height: 90vh;
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 0;
            overflow: hidden;
        }
        .coupon-card-promo {
            height: 40%;
            min-height: 40%;
            flex-shrink: 0;
            background: {{ $secondaryColor }};
        }
        .coupon-card-promo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px 15px 0 0;
        }
        .coupon-card-content {
            flex: 1;
            padding: 24px 24px 80px;
            display: flex;
            flex-direction: column;
        }
        .coupon-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .coupon-card-description {
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.4;
        }
        .coupon-card-button-wrap { margin-top: auto; padding-top: 8px; }
        .coupon-card-button {
            display: block;
            width: 100%;
            padding: 18px 28px;
            font-size: 1.25rem;
            font-weight: 600;
            color: {{ $buttonTextColor }};
            background: {{ $buttonColor }};
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-align: center;
        }
        .coupon-card-barcode-wrap {
            position: absolute;
            bottom: 28px;
            left: 0;
            right: 0;
            padding: 0 24px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .coupon-card-barcode-wrap img {
            max-width: 100%;
            width: auto;
            max-height: 90px;
            object-fit: contain;
            border: 1px solid #e5e7eb;
        }
        .coupon-card-dashed-line {
            position: absolute;
            left: 25px;
            right: 25px;
            top: 70%;
            transform: translateY(-50%);
            height: 0;
            border-top: 4px dashed {{ $primaryColor }};
            z-index: 1;
        }
        .coupon-sales-badge-wrap {
            position: absolute;
            left: 50%;
            top: 70%;
            transform: translate(-50%, -50%);
            z-index: 2;
        }
        .coupon-sales-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: {{ $salesBadgeColor }};
            color: {{ $salesBadgeTextColor }};
            padding: 8px 16px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 1.25rem;
        }
        .coupon-sales-badge svg {
            flex-shrink: 0;
            width: 28px;
            height: 24px;
        }
        .coupon-card::before {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: {{ $primaryColor }};
            border-radius: 50%;
            left: -40px;
            top: 70%;
            transform: translateY(-50%);
        }
        .coupon-card::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            background: {{ $primaryColor }};
            border-radius: 50%;
            right: -40px;
            top: 70%;
            transform: translateY(-50%);
        }
        .coupon-card-valid-until {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 6px 8px;
            font-size: 1rem;
            color: #6b7280;
            text-align: center;
        }
        .coupon-card-button[href] {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="coupon-page-wrap">
        <div class="coupon-page-company">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo" class="coupon-page-company-logo">
            @endif
            <span>{{ $company ?: 'Your company name' }}</span>
        </div>

        <div class="coupon-page-card-wrap">
            <div class="coupon-card">
                <div class="coupon-card-promo">
                    <img src="{{ $promoImageUrl }}" alt="Promo">
                </div>
                <div class="coupon-card-content">
                    <div class="coupon-card-title">{{ $title }}</div>
                    <div class="coupon-card-description">{{ $description }}</div>
                    @if(!$barcodeImageUrl)
                        <div class="coupon-card-button-wrap">
                            @php
                                $buttonHref = $viewMoreWebsite ? (str_starts_with($viewMoreWebsite, 'http') ? $viewMoreWebsite : 'https://' . $viewMoreWebsite) : null;
                            @endphp
                            @if($buttonHref)
                                <a href="{{ $buttonHref }}" target="_blank" rel="noopener noreferrer" class="coupon-card-button">{{ $codeButtonText }}</a>
                            @else
                                <button type="button" class="coupon-card-button">{{ $codeButtonText }}</button>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="coupon-card-dashed-line"></div>
                <div class="coupon-sales-badge-wrap">
                    <span class="coupon-sales-badge">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                        <span>{{ $salesBadge }}</span>
                    </span>
                </div>
                @if($barcodeImageUrl)
                    <div class="coupon-card-barcode-wrap">
                        <img src="{{ $barcodeImageUrl }}" alt="Barcode">
                    </div>
                @endif
                @if($validUntil)
                    <div class="coupon-card-valid-until">Valid until: {{ $validUntil }}</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>

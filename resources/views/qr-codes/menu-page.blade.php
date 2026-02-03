<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $restaurantName ?? 'Menu' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $menuFontFamily ?? 'Maven+Pro') }}:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: '{{ $menuFontFamily ?? "Maven Pro" }}', sans-serif;
            background-color: {{ $menuSecondaryColor }};
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: {{ $menuPrimaryColor }};
            padding: 2rem 1rem 0;
        }
        .header-content {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .restaurant-name {
            font-size: {{ $restaurantNameFontSize ?? 18 }}px;
            font-weight: 700;
            color: {{ $restaurantNameColor }};
            margin-bottom: 0.5rem;
        }
        .restaurant-description {
            font-size: {{ $restaurantDescFontSize ?? 14 }}px;
            color: {{ $restaurantDescColor }};
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }
        .restaurant-image {
            width: 100%;
            display: block;
            max-height: 280px;
            object-fit: cover;
            object-position: center;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        .restaurant-image-placeholder {
            width: 100%;
            height: 180px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 0.5rem 0.5rem 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .content {
            flex: 1;
            background-color: {{ $menuSecondaryColor }};
            padding: 1rem;
        }
        .content-inner {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .categories {
            display: flex;
            gap: 0.5rem;
            overflow-x: auto;
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
            -webkit-overflow-scrolling: touch;
        }
        .categories::-webkit-scrollbar {
            height: 4px;
        }
        .categories::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 2px;
        }
        .category-pill {
            flex-shrink: 0;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background-color: #f3f4f6;
            color: #374151;
        }
        .category-pill.active {
            background-color: {{ $menuPrimaryColor }};
            color: white;
        }
        .category-pill:not(.active):hover {
            background-color: #e5e7eb;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        .product-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid #f3f4f6;
        }
        .product-header {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .product-image {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }
        .product-image-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .product-info {
            flex: 1;
            min-width: 0;
        }
        .product-name {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }
        .product-description {
            font-size: 0.8125rem;
            color: #6b7280;
            line-height: 1.4;
        }
        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.5rem;
            gap: 0.5rem;
        }
        .product-price {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #1f2937;
        }
        .product-allergens {
            font-size: 0.75rem;
            color: #9ca3af;
            flex-shrink: 0;
            max-width: 50%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .section-content {
            display: none;
        }
        .section-content.active {
            display: block;
        }
        .pdf-container {
            text-align: center;
            padding: 3rem 1rem;
        }
        .pdf-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: white;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .pdf-filename {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
            word-break: break-all;
        }
        .action-button {
            display: inline-block;
            padding: 0.75rem 2rem;
            background-color: {{ $menuPrimaryColor }};
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.75rem;
            text-decoration: none;
            transition: opacity 0.2s;
            border: none;
            cursor: pointer;
        }
        .action-button:hover {
            opacity: 0.9;
        }
        @media (min-width: 768px) {
            .header {
                padding-left: 2rem;
                padding-right: 2rem;
            }
            .content {
                padding-left: 2rem;
                padding-right: 2rem;
            }
            .header-content,
            .content-inner {
                width: 50%;
                max-width: none;
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1 class="restaurant-name">{{ $restaurantName ?? 'Restaurant Name' }}</h1>
            @if(!empty($restaurantDescription))
                <p class="restaurant-description">{{ $restaurantDescription }}</p>
            @endif
            @if($restaurantImageUrl ?? null)
                <img src="{{ $restaurantImageUrl }}" alt="{{ $restaurantName }}" class="restaurant-image">
            @else
                <div class="restaurant-image-placeholder">
                    <svg width="48" height="48" fill="none" stroke="rgba(255,255,255,0.5)" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
        </div>
    </div>
    <div class="content">
        <div class="content-inner">
            @if(($menuMode ?? '') === 'sections' && count($menuSections ?? []) > 0)
                <div class="categories">
                    @foreach($menuSections as $index => $section)
                        <button
                            type="button"
                            class="category-pill {{ $index === 0 ? 'active' : '' }}"
                            onclick="showSection({{ $index }})"
                            data-section="{{ $index }}">
                            {{ $section['section_name'] ?? 'Section' }}
                        </button>
                    @endforeach
                </div>
                @foreach($menuSections as $index => $section)
                    <div class="section-content {{ $index === 0 ? 'active' : '' }}" id="section-{{ $index }}">
                        <div class="products-grid">
                            @foreach(($section['products'] ?? []) as $product)
                                <div class="product-card">
                                    <div class="product-header">
                                        @if(!empty($product['product_image_url']))
                                            <img src="{{ $product['product_image_url'] }}" alt="" class="product-image">
                                        @else
                                            <div class="product-image-placeholder">
                                                <svg width="20" height="20" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="product-info">
                                            <div class="product-name">{{ $product['product_name'] ?? '—' }}</div>
                                            @if(!empty($product['product_description']))
                                                <div class="product-description">{{ $product['product_description'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product-footer">
                                        @if(!empty($product['price']))
                                            <span class="product-price">{{ $product['price'] }}</span>
                                        @endif
                                        @if(!empty($product['allergens']))
                                            <span class="product-allergens">{{ $product['allergens'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @elseif(($menuMode ?? '') === 'pdf' && ($pdfUrl ?? null))
                <div class="pdf-container">
                    <div class="pdf-icon">
                        <svg width="40" height="40" fill="none" stroke="#ef4444" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="pdf-filename">{{ $pdfFileName ?? 'menu.pdf' }}</p>
                    <a href="{{ $pdfUrl }}" class="action-button" target="_blank" rel="noopener">Open PDF</a>
                </div>
            @endif
        </div>
    </div>
    <script>
        function showSection(index) {
            document.querySelectorAll('.section-content').forEach(function(el) { el.classList.remove('active'); });
            document.querySelectorAll('.category-pill').forEach(function(el) { el.classList.remove('active'); });
            var sectionEl = document.getElementById('section-' + index);
            var pillEl = document.querySelector('[data-section="' + index + '"]');
            if (sectionEl) sectionEl.classList.add('active');
            if (pillEl) pillEl.classList.add('active');
        }
    </script>
</body>
</html>

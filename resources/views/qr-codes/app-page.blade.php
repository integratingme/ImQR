<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName ?: 'Premium App Preview' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $fontFamily = $appFontFamily ?? 'Maven Pro';
        $textColor = $textColor ?? '#111827';
        $primaryColor = $primaryColor ?? '#6594FF';
        $secondaryColor = $secondaryColor ?? '#FFFFFF';
        $appStoreButtonColor = $appStoreButtonColor ?? '#000000';
        $appStoreButtonTextColor = $appStoreButtonTextColor ?? '#FFFFFF';
        $appImageUrl = $appImageUrl ?? null;
        $appName = $appName ?? '';
        $appDescription = $appDescription ?? '';
        $appStoreLink = $appStoreLink ?? '';
        $playStoreLink = $playStoreLink ?? '';
        $appTextFontSize = $appTextFontSize ?? 15;
        $appIconSize = $appIconSize ?? 110;
        $appRating = $appRating ?? null;
        $appReviewCount = $appReviewCount ?? null;
        $googleFonts = ['Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Poppins', 'Raleway', 'Nunito'];
        
        // Calculate stars display
        $ratingValue = $appRating ? (float) $appRating : 4.8;
        $fullStars = floor($ratingValue);
        $hasHalfStar = ($ratingValue - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
        $reviewText = $appReviewCount ? $appReviewCount : '1.2k';
    @endphp
    <link href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @if(in_array($fontFamily, $googleFonts))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $fontFamily) }}:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: '{{ $fontFamily }}', sans-serif;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .app-gradient {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, #4a74d6 100%);
        }
        .custom-shadow {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="antialiased">

    <div id="preview_root" class="max-w-md mx-auto min-h-screen flex flex-col overflow-hidden bg-white relative" style="font-family: '{{ $fontFamily }}', sans-serif;">
        
        <!-- Hero Section (Gradient Background) -->
        <div id="top_section" class="app-gradient h-[35vh] flex flex-col items-center justify-center relative pt-8 px-6 text-center">
            
            <!-- Floating Icon -->
            <div id="app_icon_container" class="custom-shadow rounded-[24px] bg-white p-1 mb-4 flex items-center justify-center overflow-hidden transition-transform hover:scale-105" 
                 style="width: {{ $appIconSize }}px; height: {{ $appIconSize }}px;">
                @if($appImageUrl)
                    <img id="preview_app_image" src="{{ $appImageUrl }}" alt="App Icon" class="w-full h-full object-cover rounded-[20px]">
                    <div id="preview_app_initial" class="hidden text-4xl font-extrabold" style="color: {{ $primaryColor }};">{{ $appName ? strtoupper(substr($appName, 0, 1)) : 'A' }}</div>
                @else
                    <img id="preview_app_image" src="" alt="App Icon" class="hidden w-full h-full object-cover rounded-[20px]">
                    <div id="preview_app_initial" class="text-4xl font-extrabold" style="color: {{ $primaryColor }};">{{ $appName ? strtoupper(substr($appName, 0, 1)) : 'A' }}</div>
                @endif
            </div>

            <!-- Category Tag -->
            <span id="preview_app_category" class="bg-white/20 backdrop-blur-md text-white text-[10px] uppercase tracking-widest px-3 py-1 rounded-full mb-2">
                Mobile App
            </span>
        </div>

        <!-- Content Section -->
        <div id="bottom_section" class="flex-grow -mt-8 rounded-t-[32px] glass-effect relative p-8 flex flex-col items-center" style="background: {{ $secondaryColor }};">
            
            <!-- Name & Rating -->
            <div class="text-center w-full mb-6">
                <h1 id="preview_app_name" class="font-extrabold mb-1 leading-tight" style="font-size: 28px; color: {{ $textColor }};">
                    {{ $appName ?: 'Your Awesome App' }}
                </h1>
                
                @if($appRating || $appReviewCount)
                <div class="flex items-center justify-center space-x-1 mb-3">
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < $fullStars; $i++)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        @if($hasHalfStar)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endif
                        @for($i = 0; $i < $emptyStars; $i++)
                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500 font-medium">({{ number_format($ratingValue, 1) }} / {{ $reviewText }} reviews)</span>
                </div>
                @endif

                <p id="preview_app_description" class="text-gray-600 leading-relaxed px-2" style="font-size: {{ $appTextFontSize }}px; color: {{ $textColor }}; opacity: 0.85;">
                    {{ $appDescription ?: 'Enter your application description here. Make it catchy and informative to attract more users.' }}
                </p>
            </div>

            <!-- Download Buttons -->
            <div class="w-full space-y-4 mb-8">
                <a href="{{ $appStoreLink ?: '#' }}" 
                   @if($appStoreLink) target="_blank" rel="noopener noreferrer" @endif
                   id="preview_app_store_btn" 
                   class="flex items-center justify-center space-x-3 w-full py-4 rounded-2xl text-white transition-transform active:scale-95 shadow-lg {{ !$appStoreLink ? 'opacity-50 cursor-not-allowed' : '' }}"
                   style="background-color: {{ $appStoreButtonColor }}; color: {{ $appStoreButtonTextColor }};">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.1 2.48-1.34.03-1.77-.79-3.29-.79-1.53 0-1.99.77-3.26.82-1.31.05-2.31-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                    <div class="text-left">
                        <div class="text-[10px] uppercase leading-none opacity-70">Download on the</div>
                        <div class="text-lg font-semibold leading-none">App Store</div>
                    </div>
                </a>

                <a href="{{ $playStoreLink ?: '#' }}" 
                   @if($playStoreLink) target="_blank" rel="noopener noreferrer" @endif
                   id="preview_play_store_btn" 
                   class="flex items-center justify-center space-x-3 w-full py-4 rounded-2xl text-white transition-transform active:scale-95 shadow-lg {{ !$playStoreLink ? 'opacity-50 cursor-not-allowed' : '' }}"
                   style="background-color: {{ $appStoreButtonColor }}; color: {{ $appStoreButtonTextColor }};">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.61 3,21.09 3,20.5M16.81,15.12L18.77,14.01C20.42,13.06 20.42,10.94 18.77,10L16.81,8.88L14.39,11.3L16.81,15.12M13.69,12L16.11,9.58L4.54,3L13.69,12M4.54,21L13.69,12L16.11,14.42L4.54,21Z"/></svg>
                    <div class="text-left">
                        <div class="text-[10px] uppercase leading-none opacity-70">Get it on</div>
                        <div class="text-lg font-semibold leading-none">Google Play</div>
                    </div>
                </a>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-2 gap-4 w-full mt-4">
                <div class="bg-gray-50 p-4 rounded-2xl flex flex-col items-center text-center">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-800">Ultra Fast</span>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl flex flex-col items-center text-center">
                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-800">Secure</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

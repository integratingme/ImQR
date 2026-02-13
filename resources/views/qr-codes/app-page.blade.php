<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName ?? 'App' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $fontFamily = $appFontFamily ?? 'Maven Pro';
        $textColor = $textColor ?? '#000000';
        $primaryColor = $primaryColor ?? '#6594FF';
        $secondaryColor = $secondaryColor ?? '#FFFFFF';
        $appStoreButtonColor = $appStoreButtonColor ?? $primaryColor;
        $appStoreButtonTextColor = $appStoreButtonTextColor ?? $secondaryColor;
        $appImageUrl = $appImageUrl ?? null;
        $appName = $appName ?? '';
        $appDescription = $appDescription ?? '';
        $appStoreLink = $appStoreLink ?? '';
        $playStoreLink = $playStoreLink ?? '';
        $appTextFontSize = $appTextFontSize ?? 16;
        $appIconSize = $appIconSize ?? 96;
        $googleFonts = ['Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Poppins', 'Raleway', 'Nunito'];
    @endphp
    @if(in_array($fontFamily, $googleFonts))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $fontFamily) }}:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: '{{ $fontFamily }}', sans-serif;
        }
    </style>
</head>
<body>
    <div class="max-w-md mx-auto min-h-screen shadow-2xl flex flex-col overflow-hidden" style="font-family: '{{ $fontFamily }}', sans-serif;">
        <!-- Top section: primary color, icon at bottom with -5% margin (overlaps onto secondary) -->
        <div class="flex-shrink-0 flex flex-col justify-end items-center" style="height: 25vh; background-color: {{ $primaryColor }};">
            @if($appImageUrl)
                <div class="rounded-3xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg border-4 border-white" style="width: {{ $appIconSize }}px; height: {{ $appIconSize }}px; background-color: {{ $primaryColor }}; margin-bottom: -5vh; position: relative; z-index: 10;">
                    <img src="{{ $appImageUrl }}" alt="App Logo" class="w-full h-full object-contain rounded-3xl">
                </div>
            @else
                <div class="rounded-3xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg border-4 border-white" style="width: {{ $appIconSize }}px; height: {{ $appIconSize }}px; background-color: {{ $primaryColor }}; margin-bottom: -5vh; position: relative; z-index: 10;">
                    {{ $appName ? strtoupper(substr($appName, 0, 1)) : 'A' }}
                </div>
            @endif
        </div>

        <!-- Bottom section: secondary color, name + description + buttons -->
        <div class="flex-1 flex flex-col min-h-0" style="background-color: {{ $secondaryColor }}; padding-top: calc(3vh + {{ (int)($appIconSize / 2) }}px);">
            <div class="flex flex-col items-center px-4 pt-0">
                <div class="font-bold mb-2 text-center" style="color: {{ $textColor }}; font-size: calc({{ $appTextFontSize }}px + 1rem);">
                    {{ $appName ?: 'Your app name here' }}
                </div>
                <div class="px-2 text-center max-w-md mb-6" style="color: {{ $textColor }}; font-size: {{ $appTextFontSize }}px;">
                    {{ $appDescription ?: 'Your app description here' }}
                </div>
            </div>
            <div class="flex-1 flex flex-col justify-center gap-3 px-4 pb-4 max-w-md w-full mx-auto">
                <a 
                    href="{{ $appStoreLink ?: '#' }}" 
                    @if($appStoreLink) target="_blank" rel="noopener noreferrer" @endif
                    class="w-full py-3 rounded-lg font-medium transition-colors shadow-lg hover:shadow-xl hover:scale-105 transform duration-200 text-center {{ !$appStoreLink ? 'opacity-50 cursor-not-allowed' : '' }}"
                    style="background-color: {{ $appStoreButtonColor }}; color: {{ $appStoreButtonTextColor }};">
                    Download on the App Store
                </a>
                <a 
                    href="{{ $playStoreLink ?: '#' }}" 
                    @if($playStoreLink) target="_blank" rel="noopener noreferrer" @endif
                    class="w-full py-3 rounded-lg font-medium transition-colors shadow-lg hover:shadow-xl hover:scale-105 transform duration-200 text-center {{ !$playStoreLink ? 'opacity-50 cursor-not-allowed' : '' }}"
                    style="background-color: {{ $appStoreButtonColor }}; color: {{ $appStoreButtonTextColor }};">
                    Get it on Google Play
                </a>
            </div>
        </div>
    </div>
</body>
</html>

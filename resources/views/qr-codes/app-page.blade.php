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
        $appImageUrl = $appImageUrl ?? null;
        $appName = $appName ?? '';
        $appDescription = $appDescription ?? '';
        $appStoreLink = $appStoreLink ?? '';
        $playStoreLink = $playStoreLink ?? '';
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
    <div class="min-h-screen flex flex-col relative">
        <!-- Top quarter - Primary color -->
        <div class="absolute top-0 left-0 right-0 h-1/4" style="background-color: {{ $primaryColor }};"></div>
        <!-- Bottom three quarters - Secondary color -->
        <div class="absolute bottom-0 left-0 right-0 h-3/4" style="background-color: {{ $secondaryColor }};"></div>
        
        <div class="relative z-10 flex-1 flex flex-col min-h-screen" style="font-family: '{{ $fontFamily }}', sans-serif;">
            <!-- Icon and App Info Section -->
            <div class="flex flex-col items-center px-4 pt-24" style="height: 25%;">
                @if($appImageUrl)
                    <div class="w-24 h-24 rounded-3xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg border-4 border-white" style="background-color: {{ $primaryColor }}; position: relative; z-index: 10;">
                        <img src="{{ $appImageUrl }}" alt="App Logo" class="w-full h-full object-contain rounded-3xl">
                    </div>
                @else
                    <div class="w-24 h-24 rounded-3xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg border-4 border-white" style="background-color: {{ $primaryColor }}; position: relative; z-index: 10;">
                        {{ $appName ? strtoupper(substr($appName, 0, 1)) : 'A' }}
                    </div>
                @endif
                <div class="mt-32 flex flex-col items-center">
                    <div class="text-lg font-bold mb-2 text-center" style="color: {{ $textColor }};">
                        {{ $appName ?: 'Your app name here' }}
                    </div>
                    <div class="text-sm px-2 text-center max-w-md" style="color: {{ $textColor }};">
                        {{ $appDescription ?: 'Your app description here' }}
                    </div>
                </div>
            </div>
            
            <!-- Buttons Section -->
            <div class="flex-1 flex flex-col items-center justify-center gap-3 px-4 pb-4 max-w-md w-full mx-auto">
                <a 
                    href="{{ $appStoreLink ?: '#' }}" 
                    @if($appStoreLink) target="_blank" rel="noopener noreferrer" @endif
                    class="w-full py-3 rounded-lg text-white font-medium transition-colors shadow-lg hover:shadow-xl hover:scale-105 transform duration-200 text-center {{ !$appStoreLink ? 'opacity-50 cursor-not-allowed' : '' }}"
                    style="background-color: {{ $primaryColor }};">
                    Download on the App Store
                </a>
                <a 
                    href="{{ $playStoreLink ?: '#' }}" 
                    @if($playStoreLink) target="_blank" rel="noopener noreferrer" @endif
                    class="w-full py-3 rounded-lg text-white font-medium transition-colors shadow-lg hover:shadow-xl hover:scale-105 transform duration-200 text-center {{ !$playStoreLink ? 'opacity-50 cursor-not-allowed' : '' }}"
                    style="background-color: {{ $primaryColor }};">
                    Get it on Google Play
                </a>
            </div>
        </div>
    </div>
</body>
</html>

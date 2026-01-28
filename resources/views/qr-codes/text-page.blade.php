<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $fontFamily = $textFontFamily ?? 'Maven Pro';
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
    <div class="min-h-screen flex flex-col items-center justify-center p-6" style="background-color: {{ $backgroundColor ?? '#FFFFFF' }};">
        <div class="w-full max-w-3xl">
            <div class="bg-white rounded-lg shadow-2xl p-8 md:p-12">
                <div class="prose max-w-none" style="color: {{ $textColor ?? '#000000' }}; font-family: '{{ $fontFamily }}', sans-serif;">
                    <p class="text-base md:text-lg leading-relaxed whitespace-pre-wrap">{{ $textContent }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

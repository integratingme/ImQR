<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $phoneNumberDisplay ?: 'Phone' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $fontFamily = $phoneFontFamily ?? 'Maven Pro';
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
    {{-- Phone landing page: 3 rows, 2 columns each (icon | tap for ... call) --}}
    <div
        class="min-h-screen flex flex-col items-center justify-center p-6"
        style="background-color: {{ $backgroundColor ?? '#2d3748' }}; font-family: '{{ $fontFamily }}', sans-serif;"
    >
        {{-- Person icon (circle) above name --}}
        <div class="flex-shrink-0 flex items-center justify-center rounded-full mb-4 shadow-lg" style="width: 72px; height: 72px; background-color: rgba(255,255,255,0.2);">
            <svg class="w-9 h-9" style="color: {{ $textColor ?? '#ffffff' }};" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path fill-rule="evenodd" d="M12 2a4 4 0 100 8 4 4 0 000-8zm0 10c-3.314 0-6 2.686-6 6v2h12v-2c0-3.314-2.686-6-6-6z" clip-rule="evenodd"/>
            </svg>
        </div>
        {{-- Full name (above number) --}}
        @if(!empty($fullName))
            <p class="text-xl font-semibold mb-1" style="color: {{ $textColor ?? '#ffffff' }};">
                {{ $fullName }}
            </p>
        @endif
        {{-- Phone number --}}
        <p class="text-lg mb-8" style="color: {{ $textColor ?? '#ffffff' }};">
            {{ $phoneNumberDisplay ?: '—' }}
        </p>

        {{-- Row 1: Cellular call icon | tap for cellular call --}}
        <div class="w-full max-w-md flex items-center gap-4 py-4 border-b border-white/20">
            <a
                href="tel:{{ $telUri }}"
                class="flex-shrink-0 flex items-center justify-center rounded-full shadow-lg transition-transform duration-200 hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-green-400/50"
                style="width: 64px; height: 64px; background-color: {{ $callButtonColor ?? '#22c55e' }};"
                aria-label="Tap for cellular call"
            >
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
            </a>
            <span class="text-left flex-1" style="color: {{ $textColor ?? '#ffffff' }};">Tap for cellular call</span>
        </div>

        {{-- Row 2: WhatsApp icon | tap for WhatsApp call --}}
        <div class="w-full max-w-md flex items-center gap-4 py-4 border-b border-white/20">
            <a
                href="{{ $whatsappUri ?? '#' }}"
                @if($whatsappUri !== '#') target="_blank" rel="noopener noreferrer" @endif
                class="flex-shrink-0 flex items-center justify-center rounded-full shadow-lg transition-transform duration-200 hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-green-400/50"
                style="width: 64px; height: 64px; background-color: #25D366;"
                aria-label="Tap for WhatsApp call"
            >
                {{-- WhatsApp icon --}}
                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
            <span class="text-left flex-1" style="color: {{ $textColor ?? '#ffffff' }};">Tap for WhatsApp call</span>
        </div>

        {{-- Row 3: Viber icon | tap for Viber call --}}
        <div class="w-full max-w-md flex items-center gap-4 py-4">
            <a
                href="{{ $viberUri ?? '#' }}"
                class="flex-shrink-0 flex items-center justify-center rounded-full shadow-lg transition-transform duration-200 hover:scale-105 active:scale-95 focus:outline-none focus:ring-4 focus:ring-purple-400/50"
                style="width: 64px; height: 64px; background-color: #7360f2;"
                aria-label="Tap for Viber call"
            >
                {{-- Viber icon --}}
                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M11.4 0C9.473-.028 5.333.553 2.846 2.666 1.357 3.737.02 5.597 0 8.233c-.014 1.645.303 3.255.93 4.756L0 24l5.301-1.398c1.467.795 3.093 1.211 4.742 1.21h.006c.022 0 .044-.002.066-.003.021.001.042.003.064.003 2.9 0 5.652-1.13 7.702-3.182C23.087 17.092 24 14.315 24 11.417 24 5.11 18.515.014 11.4 0zm.082 2.454c6.2.028 11.064 5.055 11.064 10.963 0 2.753-1.073 5.35-3.022 7.3-1.95 1.949-4.547 3.022-7.3 3.022-.02 0-.04-.002-.06-.003-.02.001-.04.003-.06.003-1.398 0-2.795-.354-4.026-1.038l-.284-.146-2.877.759.769-2.808-.181-.282a10.77 10.77 0 01-1.614-5.595c0-6.016 4.898-10.964 10.97-10.964zM6.345 4.383c-.26 0-.52.006-.778.02-.41.02-.41.32-.423.74-.02.54-.04 1.318.058 2.084.165 1.28.5 2.503 1.017 3.646.26.57.567 1.123.92 1.644.26.38.472.69.61.92.14.23.23.38.26.44.03.06.05.12.06.18.01.06.02.18-.01.3-.03.12-.09.27-.19.44-.1.17-.22.36-.35.55-.13.19-.27.38-.4.57-.1.14-.2.28-.28.4-.08.12-.14.21-.17.24-.03.03-.06.05-.08.06-.02.01-.05.02-.09.02-.04 0-.1-.01-.18-.02-.08-.01-.2-.05-.34-.1-.14-.05-.33-.12-.55-.22-.22-.1-.48-.22-.77-.36-1.7-.82-3.14-2.09-4.15-3.62-.5-.75-.88-1.55-1.13-2.36-.13-.42-.23-.84-.3-1.24-.07-.4-.12-.78-.15-1.13-.03-.35-.05-.66-.06-.92 0-.26-.01-.47-.01-.61 0-.42-.27-.74-.68-.74-.08 0-.16.01-.24.03-.08.02-.17.05-.26.09-.09.04-.2.09-.31.15-.11.06-.24.13-.38.2-.55.27-1.32.65-2.15 1.1-.42.23-.84.47-1.24.7-.2.12-.39.23-.56.34-.17.11-.32.2-.44.28-.12.08-.21.14-.27.18-.06.04-.1.07-.12.09-.02.02-.03.03-.03.03 0 0 .01.02.03.05.02.03.05.08.09.14.04.06.09.13.15.2.06.07.13.15.2.23.07.08.15.16.24.24.09.08.18.16.28.24.1.08.2.16.31.23.11.07.22.14.33.2.11.06.22.11.32.16.1.05.2.09.28.13.08.04.15.07.2.09.06.02.1.03.13.04.03.01.05.01.06.01.04 0 .07-.01.1-.02.03-.01.07-.03.12-.06.05-.03.12-.07.2-.12.08-.05.18-.11.29-.18.11-.07.24-.15.38-.24.14-.09.3-.19.46-.3.16-.11.34-.23.52-.36.36-.26.76-.54 1.18-.84.42-.3.84-.6 1.24-.9.4-.3.76-.58 1.08-.84.32-.26.58-.48.76-.66.18-.18.28-.31.31-.38.03-.07.04-.12.04-.16 0-.04-.01-.07-.02-.09-.01-.02-.03-.04-.06-.05-.03-.02-.07-.03-.12-.04-.05 0-.1-.01-.16-.01z"/>
                </svg>
            </a>
            <span class="text-left flex-1" style="color: {{ $textColor ?? '#ffffff' }};">Tap for Viber call</span>
        </div>
    </div>
</body>
</html>

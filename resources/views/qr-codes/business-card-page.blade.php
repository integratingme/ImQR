<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $card->company_name }}</title>

    @if($card->font_family !== 'Maven Pro')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $card->font_family) }}:wght@400;500;600;700&display=swap">
    @else
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;700&display=swap">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: '{{ $card->font_family }}', sans-serif;
            background-color: #f3f4f6;
        }
        .business-container {
            background-color: {{ $card->secondary_color }};
        }
    </style>
</head>
<body class="antialiased">
    <div class="max-w-md mx-auto min-h-screen shadow-2xl overflow-hidden flex flex-col business-container">

        <!-- Header (Primary Color) -->
        <div class="pt-16 pb-12 px-6 text-center transition-all" style="background-color: {{ $card->primary_color }}">
            @if(!empty($card->logo_url))
                <img src="{{ $card->logo_url }}" alt="" class="w-16 h-16 object-contain mx-auto mb-3 rounded-lg bg-white/10">
            @endif
            <h1 class="text-3xl font-bold text-white mb-1">{{ $card->company_name }}</h1>
            @if($card->subtitle)
                <p class="text-white/90 font-light text-sm">{{ $card->subtitle }}</p>
            @endif
        </div>

        <!-- Body Content -->
        <div class="p-6 space-y-8 -mt-6 bg-inherit rounded-t-[30px] flex-grow">

            <!-- Quick Actions -->
            <div class="space-y-3">
                @if(!empty($card->buttons))
                    @foreach($card->buttons as $btn)
                    <a href="{{ $btn['url'] }}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
                        <span class="font-semibold" style="color: {{ $card->primary_color }}">{{ $btn['label'] }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                    @endforeach
                @endif
            </div>

            <!-- About Section -->
            <section class="bg-white/50 p-4 rounded-2xl">
                <h3 class="font-bold text-gray-800 border-b pb-2 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $card->primary_color }}"></span>
                    About Us
                </h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $card->about }}</p>
            </section>

            <!-- Contact -->
            <section class="space-y-3">
                <h3 class="font-bold text-gray-800 border-b pb-2 mb-3 flex items-center gap-2">
                    <span class="text-gray-400">👤</span>
                    Contact
                </h3>
                <div class="space-y-2">
                    @if($card->contact_name)
                        <div class="flex items-center gap-3 text-sm text-gray-700">
                            <span class="text-gray-400">👤</span>
                            <span>{{ $card->contact_name }}</span>
                        </div>
                    @endif
                    <a href="tel:{{ preg_replace('/\s+/', '', $card->phone) }}" class="flex items-center gap-3 text-sm font-medium" style="color: {{ $card->primary_color }}">
                        <span class="text-gray-400">📞</span>
                        <span>{{ $card->phone }}</span>
                    </a>
                    <a href="mailto:{{ $card->email }}" class="flex items-center gap-3 text-sm font-medium" style="color: {{ $card->primary_color }}">
                        <span class="text-gray-400">📧</span>
                        <span>{{ $card->email }}</span>
                    </a>
                </div>
            </section>

            <!-- Location -->
            @if($card->address || $card->maps_link)
            <section>
                <h3 class="font-bold text-gray-800 border-b pb-2 mb-3 flex items-center gap-2">
                    <span class="text-gray-400">📍</span>
                    Location
                </h3>
                @if($card->address)
                    <p class="text-sm text-gray-600 mb-3">{{ $card->address }}</p>
                @endif
                @if($card->maps_link)
                <a href="{{ $card->maps_link }}" target="_blank" rel="noopener noreferrer"
                   class="inline-block px-5 py-2 text-white text-xs font-bold rounded-full transition-transform active:scale-95"
                   style="background-color: {{ $card->primary_color }}">
                    Open in Google Maps
                </a>
                @endif
            </section>
            @endif

            <!-- Working Hours (optional) -->
            @if(!empty(trim($card->working_hours ?? '')))
            <section class="bg-white/50 p-4 rounded-2xl">
                <h3 class="font-bold text-gray-800 border-b pb-2 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $card->primary_color }}"></span>
                    Working Hours
                </h3>
                <div class="text-sm text-gray-600 whitespace-pre-line">{{ $card->working_hours }}</div>
            </section>
            @endif
        </div>

        <!-- Social Footer -->
        @if(!empty($card->socials))
        <div class="p-6 flex justify-center gap-4 bg-gray-50/50 flex-wrap">
            @foreach($card->socials as $social)
                <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" class="transition-transform hover:scale-110" style="color: {{ $card->primary_color }}" title="{{ ucfirst($social['platform']) }}">
                    @if($social['platform'] === 'facebook')
                        <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    @elseif($social['platform'] === 'instagram')
                        <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    @elseif($social['platform'] === 'twitter')
                        <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    @elseif($social['platform'] === 'linkedin')
                        <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    @elseif($social['platform'] === 'whatsapp')
                        <svg class="w-9 h-9" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    @else
                        <span class="text-xs font-bold uppercase">{{ $social['platform'] }}</span>
                    @endif
                </a>
            @endforeach
        </div>
        @endif
    </div>
</body>
</html>

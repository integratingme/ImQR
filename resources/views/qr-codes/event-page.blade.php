<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->event_name }} | Event</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if($event->font_family !== 'Maven Pro')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $event->font_family) }}:wght@400;500;600;700&display=swap">
    @else
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;700&display=swap">
    @endif

    <style>
        body { font-family: '{{ $event->font_family }}', sans-serif; }
    </style>
</head>
<body style="background-color: {{ $event->secondary_color }};" class="antialiased">
    <div class="max-w-md mx-auto min-h-screen rounded-lg overflow-hidden shadow-lg flex flex-col" style="background-color: {{ $event->secondary_color }}; font-family: '{{ $event->font_family }}', sans-serif;">
        
        <!-- Hero Image Section (same as step 1 mockup) -->
        <div class="relative h-64 w-full shrink-0">
            @if(!empty($event->event_image))
                <img src="{{ $event->event_image }}" 
                     class="w-full h-full object-cover" 
                     alt="{{ $event->event_name }}">
            @else
                <div class="w-full h-full" style="background: linear-gradient(to bottom right, {{ $event->primary_color }}, {{ $event->primary_color }}dd);"></div>
            @endif
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            
            <div class="absolute bottom-4 left-6">
                @if(!empty($event->company_name))
                    <span class="inline-block px-2 py-1 rounded text-[10px] font-bold text-white uppercase tracking-wider mb-2" style="background-color: {{ $event->primary_color }};">
                        {{ $event->company_name }}
                    </span>
                @endif
                <h1 class="text-2xl font-bold text-white leading-tight">
                    {{ $event->event_name }}
                </h1>
            </div>
        </div>

        <!-- Quick Info Bar (Date/Time/DressCode) -->
        <div class="flex border-b border-gray-100 text-center" style="background-color: {{ $event->secondary_color }};">
            <div class="flex-1 p-4 border-r border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase font-bold">Date</p>
                <p class="text-sm font-bold text-gray-800">
                    @if(!empty($event->date))
                        @php
                            $date = new DateTime($event->date);
                            echo $date->format('d.m.Y');
                        @endphp
                    @else
                        -
                    @endif
                </p>
            </div>
            <div class="flex-1 p-4 border-r border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase font-bold">Time</p>
                <p class="text-sm font-bold text-gray-800">
                    @if(!empty($event->time))
                        @php
                            $parts = explode(':', $event->time);
                            $h = $parts[0] ?? '';
                            $m = $parts[1] ?? '00';
                            echo $h . ':' . $m . 'h';
                        @endphp
                    @else
                        -
                    @endif
                </p>
            </div>
            <div class="flex-1 p-4 flex flex-col items-center justify-center">
                <div class="w-4 h-4 rounded-full border border-gray-200 mb-1 shadow-sm"
                     style="background-color: {{ $event->dress_code_color }}"></div>
                <p class="text-[9px] text-gray-400 uppercase font-bold">Dress Code</p>
            </div>
        </div>

        <!-- Details Section -->
        <div class="p-6 space-y-6 flex-grow overflow-y-auto" style="background-color: {{ $event->secondary_color }};">
            @if(!empty($event->location))
                <!-- Location -->
                <div class="flex gap-3">
                    <div style="color: {{ $event->primary_color }};">📍</div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Location</h4>
                        <p class="text-xs text-gray-500">{{ $event->location }}</p>
                    </div>
                </div>
            @endif

            @if(!empty($event->description))
                <!-- Description -->
                <div class="space-y-2">
                    <h4 class="text-sm font-bold text-gray-800">About the Event</h4>
                    <p class="text-xs text-gray-600 leading-relaxed italic">
                        {{ $event->description }}
                    </p>
                </div>
            @endif

            @if(!empty($event->amenities) && count($event->amenities) > 0)
                <!-- Amenities -->
                <div class="space-y-3">
                    <h4 class="text-sm font-bold text-gray-800">Amenities</h4>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($event->amenities as $amenity)
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg border border-gray-100">
                                <span class="text-xs uppercase font-bold" style="color: {{ $event->primary_color }};">{{ ucfirst($amenity) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($event->contact))
                <!-- Contact -->
                <div class="rounded-2xl p-4 text-white flex items-center justify-between" style="background-color: #1e293b;">
                    <div>
                        <p class="text-[9px] text-slate-400 uppercase font-bold">Contact Info</p>
                        <p class="text-sm font-bold">{{ $event->contact }}</p>
                    </div>
                    <a href="tel:{{ preg_replace('/\s+/', '', $event->contact) }}" class="p-2 rounded-lg" style="background-color: {{ $event->primary_color }};">📞</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pdfTitle ?: 'PDF Document' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $fontFamily = $pdfFontFamily ?? 'Maven Pro';
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
    <div class="max-w-md mx-auto min-h-screen shadow-2xl flex flex-col relative overflow-hidden">
        <!-- Top half - Primary color -->
        <div class="absolute top-0 left-0 right-0 h-1/2" style="background-color: {{ $primaryColor }};"></div>
        <!-- Bottom half - Secondary color -->
        <div class="absolute bottom-0 left-0 right-0 h-1/2" style="background-color: {{ $secondaryColor }};"></div>
        
        <div class="relative z-10 flex-1 flex flex-col items-center justify-center p-6 min-h-screen" style="font-family: '{{ $pdfFontFamily ?? 'Maven Pro' }}', sans-serif;">
            @if($pdfTitle)
                <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center px-4" style="color: {{ $primaryColor === '#FFFFFF' ? '#000000' : '#FFFFFF' }}; font-family: '{{ $pdfFontFamily ?? 'Maven Pro' }}', sans-serif;">
                    {{ $pdfTitle }}
                </h1>
            @endif
            
            <!-- PDF Square Container -->
            <div class="w-full max-w-md mb-8">
                <div class="bg-white rounded-lg shadow-2xl p-6 flex flex-col items-center">
                    <!-- PDF Viewer/Preview -->
                    <div class="w-full aspect-square bg-gray-100 rounded-lg mb-6 flex items-center justify-center overflow-hidden">
                        <iframe 
                            src="{{ $pdfFile->url }}#toolbar=0&navpanes=0&scrollbar=0" 
                            class="w-full h-full border-0"
                            type="application/pdf"
                            title="PDF Document">
                            <p class="text-gray-500 p-4 text-center" style="font-family: '{{ $pdfFontFamily ?? 'Maven Pro' }}', sans-serif;">
                                Your browser does not support PDFs. 
                                <a href="{{ $pdfFile->url }}" class="text-blue-600 underline" download style="font-family: '{{ $pdfFontFamily ?? 'Maven Pro' }}', sans-serif;">Download the PDF</a> instead.
                            </p>
                        </iframe>
                    </div>
                    
                    @if(!empty($fileDescription))
                    <div class="w-[50%] mx-auto mb-6 text-center text-sm" style="color: {{ $primaryColor === '#FFFFFF' ? '#000000' : '#FFFFFF' }}; font-family: '{{ $pdfFontFamily ?? 'Maven Pro' }}', sans-serif;">
                        {{ $fileDescription }}
                    </div>
                    @endif
                    
                    <!-- Download Button -->
                    @php
                        // Button color defaults to #D6D6D6, text color defaults to secondary color
                        $buttonColorHex = $pdfButtonColor ?? '#D6D6D6';
                        $buttonTextColor = $secondaryColor ?? '#FFFFFF';
                    @endphp
                    <a 
                        href="{{ $pdfFile->url }}" 
                        download="{{ $pdfFile->original_name }}"
                        class="px-8 py-3.5 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 inline-flex items-center gap-2"
                        style="background-color: {{ $pdfButtonColor ?? '#FFFFFF' }}; color: {{ $buttonTextColor }}; font-family: '{{ $pdfFontFamily ?? 'Maven Pro' }}', sans-serif;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ $pdfButtonText ?? 'Download PDF' }}
                    </a>
                </div>
            </div>
            
            @if($pdfWebsite)
                @php
                    $websiteTextColor = $secondaryColor === '#FFFFFF' ? '#000000' : '#FFFFFF';
                @endphp
                <a 
                    href="{{ $pdfWebsite }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="text-sm underline hover:no-underline transition-all px-4 text-center" 
                    style="color: {{ $websiteTextColor }}; font-family: '{{ $pdfFontFamily ?? 'Maven Pro' }}', sans-serif;">
                    {{ $pdfWebsite }}
                </a>
            @endif
        </div>
    </div>
</body>
</html>

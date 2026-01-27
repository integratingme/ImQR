<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pdfTitle ?: 'PDF Document' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col relative">
        <!-- Top half - Primary color -->
        <div class="absolute top-0 left-0 right-0 h-1/2" style="background-color: {{ $primaryColor }};"></div>
        <!-- Bottom half - Secondary color -->
        <div class="absolute bottom-0 left-0 right-0 h-1/2" style="background-color: {{ $secondaryColor }};"></div>
        
        <div class="relative z-10 flex-1 flex flex-col items-center justify-center p-6 min-h-screen">
            @if($pdfTitle)
                <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center px-4" style="color: {{ $primaryColor === '#FFFFFF' ? '#000000' : '#FFFFFF' }};">
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
                            <p class="text-gray-500 p-4 text-center">
                                Your browser does not support PDFs. 
                                <a href="{{ $pdfFile->url }}" class="text-blue-600 underline" download>Download the PDF</a> instead.
                            </p>
                        </iframe>
                    </div>
                    
                    <!-- Download Button -->
                    <a 
                        href="{{ $pdfFile->url }}" 
                        download="{{ $pdfFile->original_name }}"
                        class="bg-gray-900 text-white px-8 py-3 rounded-lg font-medium shadow-md hover:shadow-lg transition-shadow inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
            
            @if($pdfWebsite)
                <a 
                    href="{{ $pdfWebsite }}" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="text-sm underline hover:no-underline transition-all px-4 text-center" 
                    style="color: {{ $secondaryColor === '#FFFFFF' ? '#000000' : '#FFFFFF' }};">
                    {{ $pdfWebsite }}
                </a>
            @endif
        </div>
    </div>
</body>
</html>

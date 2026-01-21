<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Services\QrCodeService;
use App\Http\Requests\StoreQrCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Display QR code type selection page
     */
    public function index()
    {
        return view('qr-codes.index');
    }

    /**
     * Show the form for creating a new QR code
     */
    public function create($type)
    {
        $validTypes = ['url', 'email', 'text', 'pdf', 'menu', 'coupon', 'event', 'app', 'location', 'wifi', 'phone', 'mp3'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        return view('qr-codes.create', compact('type'));
    }

    /**
     * Store a newly created QR code
     */
    public function store(StoreQrCodeRequest $request)
    {
        $validated = $request->validated();
        $type = $validated['type'];
        
        // Extract colors
        $colors = [
            'primary' => $validated['primary_color'] ?? '#000000',
            'secondary' => $validated['secondary_color'] ?? '#FFFFFF',
        ];

        // For PDF and MP3 types, handle file upload first to get the URL
        if (($type === 'pdf' && $request->hasFile('pdf_file')) || ($type === 'mp3' && $request->hasFile('mp3_file'))) {
            // Create a temporary QR code record to associate the file
            $qrCode = QrCode::create([
                'type' => $type,
                'name' => $validated['name'] ?? 'Untitled QR Code',
                'data' => $validated,
                'colors' => $colors,
            ]);

            // Upload the file and get the URL
            $fileField = $type === 'pdf' ? 'pdf_file' : 'mp3_file';
            $fileType = $type === 'pdf' ? 'pdf' : 'mp3';
            $file = $this->qrCodeService->handleFileUpload(
                $qrCode,
                $request->file($fileField),
                $fileType
            );

            // Add file URL to validated data
            $urlField = $type === 'pdf' ? 'pdf_url' : 'mp3_url';
            $validated[$urlField] = asset('storage/' . $file->file_path);
            
            // Update QR code data with file URL
            $qrCode->update(['data' => $validated]);

            // Now generate the QR code with the file URL
            $this->qrCodeService->regenerateQrCode($qrCode, $colors);
        } else {
            // Generate QR code for other types
            $qrCode = $this->qrCodeService->generate($type, $validated, $colors);

            // Handle file uploads based on type (for menu, coupon, event, app)
            $this->handleFileUploads($request, $qrCode, $type);
        }

        return response()->json([
            'success' => true,
            'qr_code_id' => $qrCode->id,
            'preview_url' => asset('storage/' . $qrCode->qr_image_path),
        ]);
    }

    /**
     * Generate preview of QR code
     */
    public function preview(Request $request)
    {
        $type = $request->input('type');
        $data = $request->all();
        $colors = [
            'primary' => $request->input('primary_color', '#000000'),
            'secondary' => $request->input('secondary_color', '#FFFFFF'),
        ];

        $preview = $this->qrCodeService->getPreview($type, $data, $colors);

        return response()->json([
            'success' => true,
            'preview' => $preview,
        ]);
    }

    /**
     * Download QR code in specified format
     */
    public function download($id, $format = 'png')
    {
        $qrCode = QrCode::findOrFail($id);

        if ($format === 'svg') {
            $svg = $this->qrCodeService->generateSvg($qrCode);
            return response($svg)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'attachment; filename="qr-code-' . $qrCode->id . '.svg"');
        }

        // PNG format
        $path = storage_path('app/public/' . $qrCode->qr_image_path);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, 'qr-code-' . $qrCode->id . '.png');
    }

    /**
     * Display QR code history
     */
    public function history()
    {
        $qrCodes = QrCode::latest()->paginate(12);
        return view('qr-codes.history', compact('qrCodes'));
    }

    /**
     * Handle file uploads based on QR code type
     */
    protected function handleFileUploads(Request $request, QrCode $qrCode, string $type)
    {
        $fileFields = [
            'pdf' => ['pdf_file' => 'pdf'],
            'menu' => ['menu_file' => 'menu'],
            'coupon' => ['coupon_image' => 'coupon_image', 'logo' => 'logo'],
            'event' => ['event_image' => 'event_image'],
            'app' => ['app_image' => 'app_image'],
        ];

        // Types that need QR code regeneration after file upload
        $typesNeedingRegeneration = ['menu'];

        if (!isset($fileFields[$type])) {
            return;
        }

        $needsRegeneration = false;
        $colors = [
            'primary' => $request->input('primary_color', '#000000'),
            'secondary' => $request->input('secondary_color', '#FFFFFF'),
        ];

        foreach ($fileFields[$type] as $field => $fileType) {
            if ($request->hasFile($field)) {
                $file = $this->qrCodeService->handleFileUpload(
                    $qrCode,
                    $request->file($field),
                    $fileType
                );

                // Update QR code data with file URL
                $data = $qrCode->data;
                $data[$field . '_url'] = asset('storage/' . $file->file_path);
                $qrCode->update(['data' => $data]);
                
                // Mark that regeneration is needed for types that use file URLs in QR content
                if (in_array($type, $typesNeedingRegeneration)) {
                    $needsRegeneration = true;
                }
            }
        }

        // Regenerate QR code if needed
        if ($needsRegeneration) {
            $this->qrCodeService->regenerateQrCode($qrCode, $colors);
        }
    }
}

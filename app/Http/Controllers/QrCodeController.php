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

        // For PDF, MP3, Menu (with file upload), and Coupon, handle file upload first to get the URL
        $hasFileUpload = false;
        $fileField = null;
        $fileType = null;
        $urlField = null;
        
        if ($type === 'pdf' && $request->hasFile('pdf_file')) {
            $hasFileUpload = true;
            $fileField = 'pdf_file';
            $fileType = 'pdf';
            $urlField = 'pdf_url';
        } elseif ($type === 'mp3' && $request->hasFile('mp3_file')) {
            $hasFileUpload = true;
            $fileField = 'mp3_file';
            $fileType = 'mp3';
            $urlField = 'mp3_url';
        } elseif ($type === 'menu' && $request->hasFile('menu_file')) {
            $hasFileUpload = true;
            $fileField = 'menu_file';
            $fileType = 'menu';
            $urlField = 'menu_file_url';
        } elseif ($type === 'coupon' && $request->hasFile('coupon_image')) {
            $hasFileUpload = true;
            $fileField = 'coupon_image';
            $fileType = 'coupon_image';
            $urlField = 'coupon_image_url';
        }
        
        if ($hasFileUpload) {
            // Create a temporary QR code record to associate the file
            $qrCode = QrCode::create([
                'type' => $type,
                'name' => $validated['name'] ?? 'Untitled QR Code',
                'data' => $validated,
                'colors' => $colors,
            ]);

            // Upload the file and get the URL
            $file = $this->qrCodeService->handleFileUpload(
                $qrCode,
                $request->file($fileField),
                $fileType
            );

            // Add file URL to validated data
            $validated[$urlField] = asset('storage/' . $file->file_path);
            
            // Update QR code data with file URL
            $qrCode->update(['data' => $validated]);

            // Now generate the QR code with the file URL
            $this->qrCodeService->regenerateQrCode($qrCode, $colors);
        } else {
            // Generate QR code for other types
            $qrCode = $this->qrCodeService->generate($type, $validated, $colors);

            // Handle file uploads based on type (for menu with URL, event, app)
            // Note: coupon_image is handled above, but logo still needs to be handled here
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
            'menu' => ['menu_file' => 'menu'], // Only used if menu_url is provided (not file upload)
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
            // Skip menu_file if it was already handled in the main store method
            if ($type === 'menu' && $field === 'menu_file' && $request->hasFile('menu_file')) {
                continue;
            }
            
            // Skip coupon_image if it was already handled in the main store method
            if ($type === 'coupon' && $field === 'coupon_image' && $request->hasFile('coupon_image')) {
                continue;
            }
            
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

        // Regenerate QR code if needed (e.g., menu with URL only)
        if ($needsRegeneration) {
            $this->qrCodeService->regenerateQrCode($qrCode, $colors);
        }
    }
}

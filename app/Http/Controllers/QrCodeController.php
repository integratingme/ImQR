<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQrCodeRequest;
use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function index()
    {
        return view('qr-codes.index');
    }

    public function create(string $type)
    {
        $validTypes = ['url', 'email', 'text', 'pdf', 'menu', 'coupon', 'event', 'app', 'location', 'wifi', 'phone', 'mp3'];
        
        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        return view('qr-codes.create', compact('type'));
    }

    public function store(StoreQrCodeRequest $request)
    {
        $type = $request->input('type');
        $validated = $request->validated();

        $colors = [
            'primary' => $validated['primary_color'] ?? '#000000',
            'secondary' => $validated['secondary_color'] ?? '#FFFFFF',
        ];

        $customization = [
            'pattern' => $request->input('pattern', 'square'),
            'corner_style' => $request->input('corner_style', 'square'),
            'corner_dot_style' => $request->input('corner_dot_style', 'square'),
        ];

        $hasFileUpload = false;
        $fileField = null;
        $fileType = null;
        $urlField = null;

        switch ($type) {
            case 'pdf':
                $hasFileUpload = true;
                $fileField = 'pdf_file';
                $fileType = 'pdf';
                $urlField = 'pdf_url';
                break;
            case 'coupon':
                $hasFileUpload = true;
                $fileField = 'coupon_image';
                $fileType = 'image';
                $urlField = 'coupon_image_url';
                break;
            case 'mp3':
                $hasFileUpload = true;
                $fileField = 'mp3_file';
                $fileType = 'audio';
                $urlField = 'mp3_url';
                break;
        }
        
        if ($hasFileUpload) {
            $qrCode = QrCode::create([
                'type' => $type,
                'name' => $validated['name'] ?? 'Untitled QR Code',
                'data' => $validated,
                'colors' => $colors,
                'customization' => $customization,
            ]);

            $file = $this->qrCodeService->handleFileUpload(
                $qrCode,
                $request->file($fileField),
                $fileType
            );

            $validated[$urlField] = asset('storage/' . $file->file_path);
            $qrCode->update(['data' => $validated]);
            $this->qrCodeService->regenerateQrCode($qrCode, $colors, $customization);
        } else {
            $qrCode = $this->qrCodeService->generate($type, $validated, $colors, $customization);
            $this->handleFileUploads($request, $qrCode, $type);
        }

        return response()->json([
            'success' => true,
            'qr_code_id' => $qrCode->id,
            'preview_url' => asset('storage/' . $qrCode->qr_image_path),
        ]);
    }

    public function preview(Request $request)
    {
        $type = $request->input('type');
        $data = $request->all();
        $colors = [
            'primary' => $request->input('primary_color', '#000000'),
            'secondary' => $request->input('secondary_color', '#FFFFFF'),
        ];

        $customization = [
            'pattern' => $request->input('pattern', 'square'),
            'corner_style' => $request->input('corner_style', 'square'),
            'corner_dot_style' => $request->input('corner_dot_style', 'square'),
        ];

        $preview = $this->qrCodeService->getPreview($type, $data, $colors, $customization);

        return response()->json([
            'success' => true,
            'preview' => $preview,
        ]);
    }

    public function download($id, $format = 'png')
    {
        $qrCode = QrCode::findOrFail($id);

        if ($format === 'svg') {
            $svg = $this->qrCodeService->generateSvg($qrCode);
            return response($svg)
                ->header('Content-Type', 'image/svg+xml')
                ->header('Content-Disposition', 'attachment; filename="qr-code-' . $qrCode->id . '.svg"');
        }

        $path = storage_path('app/public/' . $qrCode->qr_image_path);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, 'qr-code-' . $qrCode->id . '.png');
    }

    public function history()
    {
        $qrCodes = QrCode::latest()->paginate(12);
        return view('qr-codes.history', compact('qrCodes'));
    }

    protected function handleFileUploads(Request $request, QrCode $qrCode, string $type)
    {
        $fileFields = [
            'menu' => ['menu_file' => 'menu'],
            'coupon' => ['logo' => 'logo'],
            'event' => ['event_image' => 'image'],
            'app' => ['app_image' => 'image'],
        ];

        if (!isset($fileFields[$type])) {
            return;
        }

        foreach ($fileFields[$type] as $fieldName => $fileType) {
            if ($request->hasFile($fieldName)) {
                $this->qrCodeService->handleFileUpload(
                    $qrCode,
                    $request->file($fieldName),
                    $fileType
                );
            }
        }
    }
}

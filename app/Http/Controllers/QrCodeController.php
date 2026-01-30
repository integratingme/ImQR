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

        // For PDF type, use Step 2 colors for QR code (Step 1 colors are stored in data for page background)
        if ($type === 'pdf') {
            // QR code uses Step 2 colors
            $colors = [
                'primary' => $validated['primary_color'] ?? '#000000',
                'secondary' => $validated['secondary_color'] ?? '#FFFFFF',
            ];
            // Step 1 colors (pdf_primary_color, pdf_secondary_color) are already in $validated and will be stored in data field
        } else {
            $colors = [
                'primary' => $validated['primary_color'] ?? '#000000',
                'secondary' => $validated['secondary_color'] ?? '#FFFFFF',
            ];
        }

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

            // Coupon presentation image is optional; PDF and MP3 always have a file
            $hasMainFile = ($type === 'coupon') ? $request->hasFile($fileField) : true;
            if ($hasMainFile) {
                $file = $this->qrCodeService->handleFileUpload(
                    $qrCode,
                    $request->file($fileField),
                    $fileType
                );
                // For PDF, store the file URL and generate page URL
                if ($type === 'pdf') {
                    $validated[$urlField] = asset('storage/' . $file->file_path);
                    $validated['pdf_page_url'] = route('qr-codes.pdf-page', $qrCode->id);
                } else {
                    $validated[$urlField] = asset('storage/' . $file->file_path);
                }
            }

            $qrCode->update(['data' => $validated]);
            // For coupon, also handle optional logo and barcode uploads, then set coupon page URL and regenerate QR
            if ($type === 'coupon') {
                $this->handleFileUploads($request, $qrCode, $type);
                $validated['coupon_page_url'] = route('qr-codes.coupon-page', $qrCode->id);
                $qrCode->update(['data' => $validated]);
            }
            $this->qrCodeService->regenerateQrCode($qrCode, $colors, $customization);
        } else {
            $qrCode = $this->qrCodeService->generate($type, $validated, $colors, $customization);
            $this->handleFileUploads($request, $qrCode, $type);
            
            // For text type, generate text page URL and regenerate QR code
            if ($type === 'text') {
                $validated['text_page_url'] = route('qr-codes.text-page', $qrCode->id);
                $qrCode->update(['data' => $validated]);
                // Regenerate QR code with text page URL
                $this->qrCodeService->regenerateQrCode($qrCode, $colors, $customization);
            }
            
            // For app type, generate app page URL and regenerate QR code
            if ($type === 'app') {
                $validated['app_page_url'] = route('qr-codes.app-page', $qrCode->id);
                $qrCode->update(['data' => $validated]);
                // Regenerate QR code with app page URL
                $this->qrCodeService->regenerateQrCode($qrCode, $colors, $customization);
            }
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
        
        // For PDF type, use Step 2 colors for QR code preview (Step 1 colors are for page background)
        if ($type === 'pdf') {
            // QR code preview uses Step 2 colors
            $colors = [
                'primary' => $request->input('primary_color', '#000000'),
                'secondary' => $request->input('secondary_color', '#FFFFFF'),
            ];
            
            // For PDF preview, use placeholder URL - actual URL will be set when QR code is saved
            // The generatePdfContent method will handle this
        } else {
            $colors = [
                'primary' => $request->input('primary_color', '#000000'),
                'secondary' => $request->input('secondary_color', '#FFFFFF'),
            ];
        }

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
        $qrCodes = QrCode::whereIn('type', ['text', 'coupon', 'pdf', 'app'])
            ->latest()
            ->paginate(12);
        return view('qr-codes.history', compact('qrCodes'));
    }

    public function showPdfPage($id)
    {
        $qrCode = QrCode::with('files')->findOrFail($id);
        
        // Only allow PDF type QR codes
        if ($qrCode->type !== 'pdf') {
            abort(404);
        }

        // Get PDF file
        $pdfFile = $qrCode->files()->where('file_type', 'pdf')->first();
        
        if (!$pdfFile) {
            abort(404, 'PDF file not found');
        }

        // Get customization data from QR code data
        $data = $qrCode->data ?? [];
        $pdfTitle = $data['pdf_title'] ?? '';
        $pdfWebsite = $data['pdf_website'] ?? '';
        $companyName = $data['company_name'] ?? '';
        $fileDescription = $data['file_description'] ?? '';
        
        // Get page background colors from data field (Step 1 colors)
        // QR code colors are in colors field (Step 2 colors), but page background uses Step 1 colors
        $pdfPrimaryColor = $data['pdf_primary_color'] ?? '#6594FF';
        $pdfSecondaryColor = $data['pdf_secondary_color'] ?? '#FFFFFF';
        $pdfButtonText = $data['pdf_button_text'] ?? 'Download PDF';
        $pdfButtonColor = $data['pdf_button_color'] ?? '#D6D6D6';
        $pdfFontFamily = $data['pdf_font_family'] ?? 'Maven Pro';
        $primaryColor = $pdfPrimaryColor;
        $secondaryColor = $pdfSecondaryColor;

        return view('qr-codes.pdf-page', compact(
            'qrCode',
            'pdfFile',
            'pdfTitle',
            'pdfWebsite',
            'pdfButtonText',
            'pdfButtonColor',
            'pdfFontFamily',
            'companyName',
            'fileDescription',
            'primaryColor',
            'secondaryColor'
        ));
    }

    public function showTextPage($id)
    {
        $qrCode = QrCode::findOrFail($id);
        
        // Only allow text type QR codes
        if ($qrCode->type !== 'text') {
            abort(404);
        }

        // Get customization data from QR code data
        $data = $qrCode->data ?? [];
        $textContent = $data['text'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam efficitur turpis ut massa semper, et venenatis ipsum vulputate. Curabitur ac sem accumsan, accumsan tortor eu, consectetur purus. Proin dignissim eu dui in vehicula. Morbi rhoncus, leo et tristique condimentum, dolor libero porttitor mauris, id dapibus urna erat a purus. Donec porta, augue quis pellentesque mollis, lectus purus laoreet turpis, vel consectetur nisi nibh vitae dolor. Ut in metus ut nulla congue gravida ut a quam. Quisque a lacus non orci malesuada ornare. Curabitur eu tristique ex. Phasellus ultrices non justo vitae fringilla. In consequat mollis nulla, id ullamcorper eros sollicitudin porta. In laoreet ultrices facilisis. Cras auctor nulla eu est facilisis ullamcorper. Maecenas vehicula sem quis ipsum posuere, ut dictum diam dictum.';
        $backgroundColor = $data['text_background_color'] ?? '#FFFFFF';
        $textColor = $data['text_text_color'] ?? '#000000';
        $textFontFamily = $data['text_font_family'] ?? 'Maven Pro';

        return view('qr-codes.text-page', compact(
            'qrCode',
            'textContent',
            'backgroundColor',
            'textColor',
            'textFontFamily'
        ));
    }

    public function showAppPage($id)
    {
        $qrCode = QrCode::findOrFail($id);
        
        // Only allow app type QR codes
        if ($qrCode->type !== 'app') {
            abort(404);
        }

        // Get customization data from QR code data
        $data = $qrCode->data ?? [];
        $appName = $data['app_name'] ?? '';
        $appDescription = $data['app_description'] ?? '';
        $appStoreLink = $data['app_store_link'] ?? '';
        $playStoreLink = $data['play_store_link'] ?? '';
        $appFontFamily = $data['app_font_family'] ?? 'Maven Pro';
        $textColor = $data['app_text_color'] ?? '#000000';
        
        // Get colors from QR code colors field (Step 2 colors)
        $primaryColor = $qrCode->colors['primary'] ?? '#6594FF';
        $secondaryColor = $qrCode->colors['secondary'] ?? '#FFFFFF';
        
        // Get app image if exists
        $appImageFile = $qrCode->files()->where('file_type', 'image')->first();
        $appImageUrl = $appImageFile ? asset('storage/' . $appImageFile->file_path) : null;

        return view('qr-codes.app-page', compact(
            'qrCode',
            'appName',
            'appDescription',
            'appStoreLink',
            'playStoreLink',
            'appFontFamily',
            'textColor',
            'primaryColor',
            'secondaryColor',
            'appImageUrl'
        ));
    }

    public function showCouponPage($id)
    {
        $qrCode = QrCode::with('files')->findOrFail($id);

        if ($qrCode->type !== 'coupon') {
            abort(404);
        }

        $data = $qrCode->data ?? [];
        $primaryColor = $data['coupon_primary_color'] ?? '#6594FF';
        $secondaryColor = $data['coupon_secondary_color'] ?? '#FFFFFF';
        $company = $data['coupon_company'] ?? '';
        $title = $data['coupon_title'] ?? 'Your coupon title';
        $description = $data['coupon_description'] ?? 'Description';
        $salesBadge = $data['coupon_sales_badge'] ?? '25% OFF*';
        $salesBadgeColor = $data['coupon_sales_badge_color'] ?? '#9FE2BF';
        $salesBadgeTextColor = $data['coupon_sales_badge_text_color'] ?? '#1f2937';
        $codeButtonText = $data['coupon_code_button_text'] ?? 'Get code';
        $buttonColor = $data['coupon_button_color'] ?? '#D6D6D6';
        $buttonTextColor = $data['coupon_button_text_color'] ?? '#1f2937';
        $validUntil = $data['coupon_valid_until'] ?? '';
        $viewMoreText = $data['coupon_view_more_text'] ?? 'View more';
        $viewMoreWebsite = $data['coupon_view_more_website'] ?? '';
        $useBarcode = !empty($data['coupon_use_barcode']);
        $fontFamily = $data['coupon_font_family'] ?? 'Maven Pro';

        $promoFile = $qrCode->files()->where('file_type', 'image')->first();
        $promoImageUrl = $promoFile ? asset('storage/' . $promoFile->file_path) : asset('coupon-icons/coupon-promo-image.webp');
        $logoFile = $qrCode->files()->where('file_type', 'logo')->first();
        $logoUrl = $logoFile ? asset('storage/' . $logoFile->file_path) : null;
        $barcodeFile = $qrCode->files()->where('file_type', 'barcode')->first();
        $barcodeImageUrl = ($useBarcode && $barcodeFile) ? asset('storage/' . $barcodeFile->file_path) : null;

        return view('qr-codes.coupon-page', compact(
            'qrCode',
            'primaryColor',
            'secondaryColor',
            'company',
            'title',
            'description',
            'salesBadge',
            'salesBadgeColor',
            'salesBadgeTextColor',
            'codeButtonText',
            'buttonColor',
            'buttonTextColor',
            'validUntil',
            'viewMoreText',
            'viewMoreWebsite',
            'promoImageUrl',
            'logoUrl',
            'barcodeImageUrl',
            'fontFamily'
        ));
    }

    protected function handleFileUploads(Request $request, QrCode $qrCode, string $type)
    {
        $fileFields = [
            'menu' => ['menu_file' => 'menu'],
            'coupon' => ['logo' => 'logo', 'coupon_barcode_image' => 'barcode'],
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

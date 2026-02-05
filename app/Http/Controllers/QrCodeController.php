<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQrCodeRequest;
use App\Models\QrCode;
use App\Services\QrCodeService;
use GuzzleHttp\TransferStats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

        $reviewUsIcons = $this->getReviewUsPredefinedIcons();

        return view('qr-codes.create', compact('type', 'reviewUsIcons'));
    }

    /**
     * List predefined SVG icons from public/frames/review-us-icons/ for the Review us frame.
     *
     * @return array<int, array{url: string, name: string}>
     */
    protected function getReviewUsPredefinedIcons(): array
    {
        $dir = public_path('frames/review-us-icons');
        if (!is_dir($dir)) {
            return [];
        }
        $icons = [];
        foreach (glob($dir . '/*.svg') ?: [] as $path) {
            $basename = basename($path);
            $filename = basename($path, '.svg');
            if (strtolower($basename) === 'default.svg') {
                continue;
            }
            $icons[] = [
                'url' => asset('frames/review-us-icons/' . $basename),
                'name' => ucfirst(str_replace(['-', '_'], ' ', $filename)),
            ];
        }
        usort($icons, fn ($a, $b) => strcasecmp($a['name'], $b['name']));
        return array_values($icons);
    }

    public function store(StoreQrCodeRequest $request)
    {
        try {
            return $this->performStore($request);
        } catch (\Throwable $e) {
            \Log::error('QR code store failed', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Server error. Please check your input and try again.',
            ], 500);
        }
    }

    protected function performStore(StoreQrCodeRequest $request)
    {
        $type = $request->input('type');
        $validated = $request->validated();

        // For PDF and menu types, use Step 2 colors for QR code (Step 1 colors are stored in data for page background)
        if ($type === 'pdf' || $type === 'menu') {
            // QR code uses Step 2 colors
            $colors = [
                'primary' => $validated['primary_color'] ?? '#000000',
                'secondary' => $validated['secondary_color'] ?? '#FFFFFF',
            ];
            // Step 1 colors (pdf_*/menu_* primary, secondary, font) are already in $validated and will be stored in data field
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

            unset($validated['review_frame_logo']);
            $qrCode->update(['data' => $validated]);
            // For coupon, also handle optional logo and barcode uploads, then set coupon page URL and regenerate QR
            if ($type === 'coupon') {
                $this->handleFileUploads($request, $qrCode, $type);
                $validated['coupon_page_url'] = route('qr-codes.coupon-page', $qrCode->id);
                $qrCode->update(['data' => $validated]);
            }
            // Review-us frame: upload custom logo if provided
            if ($request->input('frame') === 'review-us' && $request->hasFile('review_frame_logo')) {
                $file = $this->qrCodeService->handleFileUpload($qrCode, $request->file('review_frame_logo'), 'review_frame_logo');
                $validated['review_frame_logo_url'] = asset('storage/' . $file->file_path);
                $qrCode->update(['data' => array_merge($qrCode->data ?? [], ['review_frame_logo_url' => $validated['review_frame_logo_url']])]);
            }
            $this->qrCodeService->regenerateQrCode($qrCode, $colors, $customization);
        } else {
            // For menu type, strip file inputs from data so we can store JSON (files are handled via handleFileUploads)
            $dataForStorage = $validated;
            unset($dataForStorage['review_frame_logo']); // file handled below
            if ($type === 'menu') {
                unset($dataForStorage['menu_file'], $dataForStorage['menu_restaurant_image']);
                if (!empty($dataForStorage['menu_sections'])) {
                    foreach ($dataForStorage['menu_sections'] as $si => &$section) {
                        if (!empty($section['products'])) {
                            foreach ($section['products'] as $pi => &$product) {
                                unset($product['product_image']);
                            }
                        }
                    }
                    unset($section, $product);
                }
                $dataForStorage = $this->stripFilesFromArray($dataForStorage);
            }

            $qrCode = $this->qrCodeService->generate($type, $dataForStorage, $colors, $customization);
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

            // For phone type, generate phone page URL and regenerate QR code
            if ($type === 'phone') {
                $validated['phone_page_url'] = route('qr-codes.phone-page', $qrCode->id);
                $qrCode->update(['data' => $validated]);
                $this->qrCodeService->regenerateQrCode($qrCode, $colors, $customization);
            }

            // For menu type, process menu_sections product images and store URLs in data
            if ($type === 'menu' && !empty($validated['menu_sections'])) {
                foreach ($validated['menu_sections'] as $si => &$section) {
                    if (empty($section['products'] ?? [])) {
                        continue;
                    }
                    foreach ($section['products'] as $pi => &$product) {
                        if (isset($product['product_image']) && $product['product_image'] instanceof \Illuminate\Http\UploadedFile) {
                            $file = $this->qrCodeService->handleFileUpload(
                                $qrCode,
                                $product['product_image'],
                                'menu_product_' . $si . '_' . $pi
                            );
                            $product['product_image_url'] = asset('storage/' . $file->file_path);
                            unset($product['product_image']);
                        }
                    }
                }
                unset($section, $product);
                $dataForStorage['menu_sections'] = $this->stripFilesFromArray($validated['menu_sections']);
                $qrCode->update(['data' => $dataForStorage]);
            }
            
            // For menu type, generate menu page URL and regenerate QR code
            if ($type === 'menu') {
                $dataForStorage['menu_page_url'] = route('qr-codes.menu-page', $qrCode->id);
                $qrCode->update(['data' => $dataForStorage]);
                // Regenerate QR code with menu page URL
                $this->qrCodeService->regenerateQrCode($qrCode, $colors, $customization);
            }

            // Review-us frame: upload custom logo if provided
            if ($request->input('frame') === 'review-us' && $request->hasFile('review_frame_logo')) {
                $file = $this->qrCodeService->handleFileUpload($qrCode, $request->file('review_frame_logo'), 'review_frame_logo');
                $dataForStorage['review_frame_logo_url'] = asset('storage/' . $file->file_path);
                $qrCode->update(['data' => array_merge($qrCode->data ?? [], ['review_frame_logo_url' => $dataForStorage['review_frame_logo_url']])]);
            }
        }

        $payload = [
            'success' => true,
            'qr_code_id' => $qrCode->id,
            'preview_url' => asset('storage/' . $qrCode->qr_image_path),
        ];
        if ($type === 'menu') {
            $payload['menu_page_url'] = route('qr-codes.menu-page', $qrCode->id);
        }
        return response()->json($payload);
    }

    public function update(StoreQrCodeRequest $request, string $id)
    {
        $qrCode = QrCode::findOrFail($id);
        if ($qrCode->type !== $request->input('type')) {
            return response()->json(['success' => false, 'message' => 'QR code type cannot be changed.'], 422);
        }
        try {
            return $this->performUpdate($request, $qrCode);
        } catch (\Throwable $e) {
            \Log::error('QR code update failed', ['id' => $id, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'Server error. Please try again.',
            ], 500);
        }
    }

    protected function performUpdate(StoreQrCodeRequest $request, QrCode $qrCode)
    {
        $type = $qrCode->type;
        $validated = $request->validated();

        $colors = [
            'primary' => $validated['primary_color'] ?? $qrCode->colors['primary'] ?? '#000000',
            'secondary' => $validated['secondary_color'] ?? $qrCode->colors['secondary'] ?? '#FFFFFF',
        ];
        $customization = [
            'pattern' => $request->input('pattern', $qrCode->customization['pattern'] ?? 'square'),
            'corner_style' => $request->input('corner_style', $qrCode->customization['corner_style'] ?? 'square'),
            'corner_dot_style' => $request->input('corner_dot_style', $qrCode->customization['corner_dot_style'] ?? 'square'),
        ];

        $fileField = null;
        $fileType = null;
        $urlField = null;
        switch ($type) {
            case 'pdf':
                $fileField = 'pdf_file';
                $fileType = 'pdf';
                $urlField = 'pdf_url';
                break;
            case 'coupon':
                $fileField = 'coupon_image';
                $fileType = 'image';
                $urlField = 'coupon_image_url';
                break;
            case 'mp3':
                $fileField = 'mp3_file';
                $fileType = 'audio';
                $urlField = 'mp3_url';
                break;
        }

        $existingData = $qrCode->data ?? [];
        $dataToStore = array_merge($existingData, $validated);

        if ($fileField && $request->hasFile($fileField)) {
            $file = $this->qrCodeService->handleFileUpload($qrCode, $request->file($fileField), $fileType);
            $dataToStore[$urlField] = asset('storage/' . $file->file_path);
            if ($type === 'pdf') {
                $dataToStore['pdf_page_url'] = route('qr-codes.pdf-page', $qrCode->id);
            }
        }
        if ($type === 'coupon' && $request->hasFile($fileField)) {
            $this->handleFileUploads($request, $qrCode, $type);
            $dataToStore['coupon_page_url'] = route('qr-codes.coupon-page', $qrCode->id);
        }

        $dataToStore = $this->stripFilesFromArray($dataToStore);
        if ($type === 'menu') {
            unset($dataToStore['menu_file'], $dataToStore['menu_restaurant_image']);
            $this->handleFileUploads($request, $qrCode, $type);
            if (!empty($dataToStore['menu_sections'])) {
                foreach ($dataToStore['menu_sections'] as $si => &$section) {
                    if (!empty($section['products'])) {
                        foreach ($section['products'] as $pi => &$product) {
                            if (isset($product['product_image']) && $product['product_image'] instanceof \Illuminate\Http\UploadedFile) {
                                $file = $this->qrCodeService->handleFileUpload(
                                    $qrCode,
                                    $product['product_image'],
                                    'menu_product_' . $si . '_' . $pi
                                );
                                $product['product_image_url'] = asset('storage/' . $file->file_path);
                                unset($product['product_image']);
                            }
                        }
                    }
                }
                unset($section, $product);
                $dataToStore['menu_sections'] = $this->stripFilesFromArray($dataToStore['menu_sections'] ?? []);
            }
            $dataToStore['menu_page_url'] = route('qr-codes.menu-page', $qrCode->id);
        }
        if ($type === 'text') {
            $dataToStore['text_page_url'] = route('qr-codes.text-page', $qrCode->id);
        }
        if ($type === 'app') {
            $dataToStore['app_page_url'] = route('qr-codes.app-page', $qrCode->id);
        }
        if ($type === 'phone') {
            $dataToStore['phone_page_url'] = route('qr-codes.phone-page', $qrCode->id);
        }

        $this->handleFileUploads($request, $qrCode, $type);

        $dataToStore = $this->stripFilesFromArray($dataToStore);

        $qrCode->update([
            'name' => $dataToStore['name'] ?? $qrCode->name,
            'data' => $dataToStore,
            'colors' => $colors,
            'customization' => $customization,
        ]);

        $this->qrCodeService->regenerateQrCode($qrCode, $colors, $customization);

        $payload = [
            'success' => true,
            'qr_code_id' => $qrCode->id,
            'preview_url' => asset('storage/' . $qrCode->qr_image_path),
        ];
        if ($type === 'menu') {
            $payload['menu_page_url'] = route('qr-codes.menu-page', $qrCode->id);
        }
        return response()->json($payload);
    }

    /**
     * Resolve a short Google Maps link and return the place name from the final URL.
     */
    public function resolveMapsLink(Request $request)
    {
        $url = $request->get('url');
        if (! is_string($url) || trim($url) === '') {
            return response()->json(['success' => false, 'place_name' => null], 400);
        }
        $url = trim($url);
        $allowed = [
            'https://www.google.com/maps',
            'https://maps.google.com',
            'https://goo.gl/maps',
            'https://maps.app.goo.gl',
        ];
        $isMapsLink = false;
        foreach ($allowed as $prefix) {
            if (str_starts_with(strtolower($url), $prefix)) {
                $isMapsLink = true;
                break;
            }
        }
        if (! $isMapsLink) {
            return response()->json(['success' => false, 'place_name' => null], 400);
        }
        try {
            $finalUrl = $url;
            Http::timeout(10)->withOptions([
                'allow_redirects' => true,
                'on_stats' => function (TransferStats $stats) use (&$finalUrl) {
                    $uri = $stats->getEffectiveUri();
                    if ($uri) {
                        $finalUrl = (string) $uri;
                    }
                },
            ])->get($url);
            $placeName = $this->extractPlaceNameFromMapsUrl($finalUrl);
            return response()->json([
                'success' => true,
                'place_name' => $placeName,
            ]);
        } catch (\Throwable $e) {
            \Log::warning('resolveMapsLink failed', ['url' => $url, 'message' => $e->getMessage()]);
            return response()->json(['success' => false, 'place_name' => null], 422);
        }
    }

    private function extractPlaceNameFromMapsUrl(string $url): ?string
    {
        if (preg_match('#/place/([^/@]+)(?:/|$)#', $url, $m)) {
            $decoded = str_replace('+', ' ', $m[1]);
            return urldecode($decoded) ?: null;
        }
        return null;
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

    public function history(Request $request)
    {
        $historyTypes = ['text', 'coupon', 'pdf', 'app', 'phone', 'menu', 'location'];
        $typeFilter = $request->get('type');

        $query = QrCode::whereIn('type', $historyTypes)->latest();
        if ($typeFilter && in_array($typeFilter, $historyTypes)) {
            $query->where('type', $typeFilter);
        }
        $qrCodes = $query->paginate(12)->withQueryString();

        return view('qr-codes.history', [
            'qrCodes' => $qrCodes,
            'currentType' => $typeFilter,
            'historyTypes' => $historyTypes,
        ]);
    }

    public function destroy(string $id)
    {
        $qrCode = QrCode::with('files')->findOrFail($id);

        if ($qrCode->qr_image_path && Storage::disk('public')->exists($qrCode->qr_image_path)) {
            Storage::disk('public')->delete($qrCode->qr_image_path);
        }

        foreach ($qrCode->files as $file) {
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        }
        $qrCode->files()->delete();
        $qrCode->delete();

        return redirect()->route('qr-codes.history')->with('success', 'QR code deleted.');
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
        // Font size from slider (12–24px), stored in data when QR code is created
        $appTextFontSize = (int) ($data['app_text_font_size'] ?? 16);
        $appTextFontSize = max(12, min(24, $appTextFontSize));
        $appIconSize = (int) ($data['app_icon_size'] ?? 96);
        $appIconSize = max(64, min(128, $appIconSize));
        
        // Get colors from QR code colors field (Step 2 colors)
        $primaryColor = $qrCode->colors['primary'] ?? '#6594FF';
        $secondaryColor = $qrCode->colors['secondary'] ?? '#FFFFFF';
        $appStoreButtonColor = $data['app_store_button_color'] ?? $primaryColor;
        $appStoreButtonTextColor = $data['app_store_button_text_color'] ?? $secondaryColor;
        
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
            'appTextFontSize',
            'appIconSize',
            'primaryColor',
            'secondaryColor',
            'appStoreButtonColor',
            'appStoreButtonTextColor',
            'appImageUrl'
        ));
    }

    public function showPhonePage($id)
    {
        $qrCode = QrCode::findOrFail($id);

        if ($qrCode->type !== 'phone') {
            abort(404);
        }

        $data = $qrCode->data ?? [];
        $phoneNumber = $data['phone_number'] ?? '';
        $fullName = trim($data['full_name'] ?? '');

        // Digits only (for WhatsApp/Viber); tel keeps leading + for international
        $phoneDigits = preg_replace('/\D/', '', $phoneNumber);
        $telUri = (str_starts_with($phoneNumber, '+') ? '+' : '') . $phoneDigits;

        // WhatsApp: https://wa.me/<country_code><number> (digits only)
        $whatsappUri = $phoneDigits ? 'https://wa.me/' . $phoneDigits : '#';

        // Viber: viber://call?number=<digits>
        $viberUri = $phoneDigits ? 'viber://call?number=' . $phoneDigits : '#';

        // Display number as entered (or formatted)
        $phoneNumberDisplay = trim($phoneNumber) ?: '—';

        $backgroundColor = $data['phone_background_color'] ?? '#2d3748';
        $textColor = $data['phone_text_color'] ?? '#ffffff';
        $callButtonColor = $data['phone_call_button_color'] ?? '#22c55e';
        $phoneFontFamily = $data['phone_font_family'] ?? 'Maven Pro';

        return view('qr-codes.phone-page', compact(
            'qrCode',
            'telUri',
            'whatsappUri',
            'viberUri',
            'fullName',
            'phoneNumberDisplay',
            'backgroundColor',
            'textColor',
            'callButtonColor',
            'phoneFontFamily'
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

    public function showMenuPage($id)
    {
        $qrCode = QrCode::with('files')->findOrFail($id);
        
        // Only allow menu type QR codes
        if ($qrCode->type !== 'menu') {
            abort(404);
        }

        $data = $qrCode->data ?? [];
        
        // Restaurant info
        $restaurantName = $data['restaurant_name'] ?? 'Restaurant';
        $restaurantDescription = $data['restaurant_description'] ?? '';
        
        // Get restaurant image
        $restaurantImageFile = $qrCode->files()->where('file_type', 'restaurant_image')->first();
        $restaurantImageUrl = $restaurantImageFile ? asset('storage/' . $restaurantImageFile->file_path) : null;
        
        // Colors and fonts
        $menuPrimaryColor = $data['menu_primary_color'] ?? '#6594FF';
        $menuSecondaryColor = $data['menu_secondary_color'] ?? '#FFFFFF';
        $menuFontFamily = $data['menu_font_family'] ?? 'Maven Pro';
        $restaurantNameFontSize = $data['menu_restaurant_name_font_size'] ?? 18;
        $restaurantDescFontSize = $data['menu_restaurant_description_font_size'] ?? 14;
        $restaurantNameColor = $data['menu_restaurant_name_color'] ?? '#FFFFFF';
        $restaurantDescColor = $data['menu_restaurant_description_color'] ?? '#FFFFFF';
        
        // Determine menu mode (priority: 1. sections, 2. URL, 3. PDF)
        $menuMode = 'sections'; // default
        $menuSections = [];
        $menuUrl = null;
        $pdfUrl = null;
        $pdfFileName = null;
        $hasSections = !empty($data['menu_sections']) && is_array($data['menu_sections']);
        $pdfFile = $qrCode->files()->where('file_type', 'menu')->first();

        if ($hasSections) {
            $menuMode = 'sections';
            $menuSections = $data['menu_sections'];
        } elseif (!empty($data['menu_url'])) {
            $menuMode = 'url';
            $menuUrl = $data['menu_url'];
        } elseif ($pdfFile) {
            $menuMode = 'pdf';
            $pdfUrl = asset('storage/' . $pdfFile->file_path);
            $pdfFileName = $pdfFile->original_name;
        }

        return view('qr-codes.menu-page', compact(
            'qrCode',
            'restaurantName',
            'restaurantDescription',
            'restaurantImageUrl',
            'menuPrimaryColor',
            'menuSecondaryColor',
            'menuFontFamily',
            'restaurantNameFontSize',
            'restaurantDescFontSize',
            'restaurantNameColor',
            'restaurantDescColor',
            'menuMode',
            'menuSections',
            'menuUrl',
            'pdfUrl',
            'pdfFileName'
        ));
    }


    /**
     * Recursively remove UploadedFile instances from array so it can be JSON-encoded for storage.
     */
    protected function stripFilesFromArray(array $data): array
    {
        $out = [];
        foreach ($data as $key => $value) {
            if ($value instanceof \Illuminate\Http\UploadedFile) {
                continue;
            }
            if (is_array($value)) {
                $out[$key] = $this->stripFilesFromArray($value);
            } else {
                $out[$key] = $value;
            }
        }
        return $out;
    }

    protected function handleFileUploads(Request $request, QrCode $qrCode, string $type)
    {
        $fileFields = [
            'menu' => ['menu_file' => 'menu', 'menu_restaurant_image' => 'restaurant_image'],
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

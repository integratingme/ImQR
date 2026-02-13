<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected QrCodeService $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Show the user dashboard with full QR code history.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $historyTypes = ['url', 'email', 'text', 'coupon', 'pdf', 'app', 'phone', 'menu', 'location', 'wifi', 'event', 'business_card', 'personal_vcard'];
        $typeFilter = $request->get('type');

        // Statistics (from all user codes, unfiltered)
        $allCodes = QrCode::where('user_id', $user->id)->get();
        $stats = [
            'total_qr_codes' => $allCodes->count(),
            'total_scans' => $allCodes->sum('scan_count'),
            'dynamic_codes' => $allCodes->where('is_dynamic', true)->count(),
        ];

        // Filtered & paginated QR codes for the grid
        $query = QrCode::where('user_id', $user->id)
            ->whereIn('type', $historyTypes)
            ->latest();

        if ($typeFilter && in_array($typeFilter, $historyTypes)) {
            $query->where('type', $typeFilter);
        }

        $qrCodes = $query->paginate(12)->withQueryString();

        // Frame configuration for QR preview rendering
        $frameConfig = [];
        foreach (['standard-border', 'thick-border', 'speech-bubble', 'menu-qr', 'location', 'wifi', 'chat', 'coupon', 'review-us'] as $frameId) {
            $frameConfig[$frameId] = $this->qrCodeService->getFrameConfig($frameId);
        }

        return view('dashboard', [
            'user' => $user,
            'qrCodes' => $qrCodes,
            'stats' => $stats,
            'historyTypes' => $historyTypes,
            'currentType' => $typeFilter,
            'frameConfig' => $frameConfig,
        ]);
    }
}


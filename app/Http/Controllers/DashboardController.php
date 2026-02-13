<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get user's QR codes, most recent first
        $qrCodes = QrCode::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $stats = [
            'total_qr_codes' => $qrCodes->count(),
            'total_scans' => $qrCodes->sum('scan_count'),
            'dynamic_codes' => $qrCodes->where('is_dynamic', true)->count(),
        ];

        return view('dashboard', compact('user', 'qrCodes', 'stats'));
    }
}

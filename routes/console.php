<?php

use App\Models\QrCode;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('qr-codes:delete-non-page', function () {
    $pageTypes = ['text', 'coupon', 'pdf', 'app'];
    $qrCodes = QrCode::whereNotIn('type', $pageTypes)->get();
    $count = 0;

    foreach ($qrCodes as $qrCode) {
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
        $count++;
    }

    $this->info("Deleted {$count} QR code(s) without a page and their files.");
})->purpose('Delete QR codes that do not have a page (text, coupon, pdf, app) and their storage files');

Artisan::command('qr-codes:delete-pdf', function () {
    $qrCodes = QrCode::with('files')->where('type', 'pdf')->get();
    $count = 0;

    foreach ($qrCodes as $qrCode) {
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
        $count++;
    }

    $this->info("Deleted {$count} PDF QR code(s) and their files.");
})->purpose('Delete all PDF QR codes and their storage files');

Artisan::command('qr-codes:delete-older-than-today', function () {
    $today = Carbon::today();
    $qrCodes = QrCode::with('files')->where('created_at', '<', $today)->get();
    $count = 0;

    foreach ($qrCodes as $qrCode) {
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
        $count++;
    }

    $this->info("Deleted {$count} QR code(s) older than today ({$today->toDateString()}) and their files.");
})->purpose('Delete all QR codes created before today and their storage files');

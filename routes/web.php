<?php

use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// QR Code Routes
Route::get('/', [QrCodeController::class, 'index'])->name('qr-codes.index');
Route::get('/qr-codes/create/{type}', [QrCodeController::class, 'create'])->name('qr-codes.create');
Route::post('/qr-codes', [QrCodeController::class, 'store'])->name('qr-codes.store');
Route::post('/qr-codes/preview', [QrCodeController::class, 'preview'])->name('qr-codes.preview');
Route::get('/qr-codes/{id}/download/{format}', [QrCodeController::class, 'download'])->name('qr-codes.download');
Route::get('/qr-codes/history', [QrCodeController::class, 'history'])->name('qr-codes.history');
Route::get('/pdf/{id}', [QrCodeController::class, 'showPdfPage'])->name('qr-codes.pdf-page');
Route::get('/text/{id}', [QrCodeController::class, 'showTextPage'])->name('qr-codes.text-page');
Route::get('/app/{id}', [QrCodeController::class, 'showAppPage'])->name('qr-codes.app-page');

// Static Pages
Route::get('/terms-and-conditions', [PageController::class, 'termsAndConditions'])->name('pages.terms-and-conditions');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('pages.privacy-policy');
Route::get('/aup', [PageController::class, 'aup'])->name('pages.aup');
Route::get('/cookie-policy', [PageController::class, 'cookiePolicy'])->name('pages.cookie-policy');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('pages.disclaimer');

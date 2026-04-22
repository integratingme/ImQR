<?php

use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FrameDesignController;
use Illuminate\Support\Facades\Route;

// QR Code Routes
Route::get('/', [QrCodeController::class, 'index'])->name('qr-codes.index');
Route::get('/qr-codes/create/{type}', [QrCodeController::class, 'create'])->name('qr-codes.create');
Route::get('/r/{slug}', [QrCodeController::class, 'dynamicRedirect'])->name('qr-codes.dynamic-redirect');

Route::middleware(['throttle:qr-create', 'throttle:qr-create-daily'])->group(function () {
    Route::post('/qr-codes', [QrCodeController::class, 'store'])->name('qr-codes.store');

    // Creation-flow update: allows ALL tiers (guest/free/premium) to update
    // their QR code during the multi-step creation wizard.
    // This is separate from the premium-only edit route below.
    Route::put('/qr-codes/{id}/creation-update', [QrCodeController::class, 'creationUpdate'])->name('qr-codes.creation-update');

    Route::options('/qr-codes', function () {
        return response()->noContent(204);
    });
});

Route::get('/qr-codes/{id}/edit', [QrCodeController::class, 'edit'])->middleware(['auth', 'premium'])->name('qr-codes.edit');
Route::put('/qr-codes/{id}', [QrCodeController::class, 'update'])->middleware(['auth', 'premium'])->name('qr-codes.update');
Route::delete('/qr-codes/{id}', [QrCodeController::class, 'destroy'])->name('qr-codes.destroy');
Route::post('/qr-codes/preview', [QrCodeController::class, 'preview'])->name('qr-codes.preview');
Route::get('/qr-codes/check-logo-limit', [QrCodeController::class, 'checkLogoLimit'])->name('qr-codes.check-logo-limit');
Route::get('/qr-codes/resolve-maps-link', [QrCodeController::class, 'resolveMapsLink'])->name('qr-codes.resolve-maps-link');
Route::get('/qr-codes/{id}/download/{format}', [QrCodeController::class, 'download'])->name('qr-codes.download');
Route::get('/qr-codes/history', [QrCodeController::class, 'history'])->name('qr-codes.history');
Route::get('/pdf/{id}', [QrCodeController::class, 'showPdfPage'])->name('qr-codes.pdf-page');
Route::get('/text/{id}', [QrCodeController::class, 'showTextPage'])->name('qr-codes.text-page');
Route::get('/app/{id}', [QrCodeController::class, 'showAppPage'])->name('qr-codes.app-page');
Route::get('/coupon/{id}', [QrCodeController::class, 'showCouponPage'])->name('qr-codes.coupon-page');
Route::get('/phone/{id}', [QrCodeController::class, 'showPhonePage'])->name('qr-codes.phone-page');
Route::get('/menu/{id}', [QrCodeController::class, 'showMenuPage'])->name('qr-codes.menu-page');
Route::get('/business-card/{id}', [QrCodeController::class, 'showBusinessCardPage'])->name('qr-codes.business-card-page');
Route::get('/vcard/{id}', [QrCodeController::class, 'showPersonalVCardPage'])->name('qr-codes.personal-vcard-page');
Route::get('/event/{id}', [QrCodeController::class, 'showEventPage'])->name('qr-codes.event-page');

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/dashboard/update-plan', [\App\Http\Controllers\DashboardController::class, 'updatePlan'])->name('dashboard.update-plan');
});

Route::middleware(['auth'])->prefix('frames')->name('frames.')->group(function () {
    Route::get('/editor', [FrameDesignController::class, 'editor'])->name('editor');
    Route::get('/{frame}/edit', [FrameDesignController::class, 'edit'])->name('edit');
});

Route::middleware(['auth'])->prefix('frame-designs')->name('frames.')->group(function () {
    Route::get('/', [FrameDesignController::class, 'index'])->name('index');
    Route::post('/', [FrameDesignController::class, 'store'])->name('store');
    Route::delete('/', [FrameDesignController::class, 'destroyAll'])->name('destroy-all');
    Route::put('/{frame}', [FrameDesignController::class, 'update'])->name('update');
    Route::get('/{frame}', [FrameDesignController::class, 'show'])->name('show');
    Route::delete('/{frame}', [FrameDesignController::class, 'destroy'])->name('destroy');
});

// Static Pages
Route::get('/terms-and-conditions', [PageController::class, 'termsAndConditions'])->name('pages.terms-and-conditions');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('pages.privacy-policy');
Route::get('/aup', [PageController::class, 'aup'])->name('pages.aup');
Route::get('/cookie-policy', [PageController::class, 'cookiePolicy'])->name('pages.cookie-policy');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('pages.disclaimer');

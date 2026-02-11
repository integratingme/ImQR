<?php

use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\PageController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Dev-only: simulate login as Free/Premium user (only when APP_ENV=local)
if (app()->environment('local')) {
    Route::get('/dev/login-as/{id}', function ($id) {
        $plan = request()->query('plan'); // 'free' | 'premium' (optional)

        if ($id === 'premium' || $id === 'free') {
            $wantPlan = $id;
            $user = User::where('plan', $wantPlan)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $wantPlan === 'premium' ? 'Premium Test User' : 'Free Test User',
                    'email' => $wantPlan . '-test@imqr.test',
                    'password' => bcrypt('password'),
                    'plan' => $wantPlan,
                    'plan_expires_at' => $wantPlan === 'premium' ? now()->addYear() : null,
                ]);
            }
        } else {
            $user = User::findOrFail((int) $id);
            if ($plan && in_array($plan, ['free', 'premium'])) {
                $user->plan = $plan;
                $user->plan_expires_at = $plan === 'premium' ? now()->addYear() : null;
                $user->save();
            }
        }

        auth()->login($user);

        return redirect()->route('qr-codes.index')->with('message', 'Logged in as ' . $user->email . ' (' . $user->plan . ')');
    })->name('dev.login-as');

    Route::get('/dev/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('qr-codes.index')->with('message', 'Logged out.');
    })->name('dev.logout');
}

// QR Code Routes
Route::get('/', [QrCodeController::class, 'index'])->name('qr-codes.index');
Route::get('/qr-codes/create/{type}', [QrCodeController::class, 'create'])->name('qr-codes.create');
Route::get('/r/{slug}', [QrCodeController::class, 'dynamicRedirect'])->name('qr-codes.dynamic-redirect');

Route::middleware(['throttle:qr-create', 'throttle:qr-create-daily'])->group(function () {
    Route::post('/qr-codes', [QrCodeController::class, 'store'])->name('qr-codes.store');

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

// Static Pages
Route::get('/terms-and-conditions', [PageController::class, 'termsAndConditions'])->name('pages.terms-and-conditions');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('pages.privacy-policy');
Route::get('/aup', [PageController::class, 'aup'])->name('pages.aup');
Route::get('/cookie-policy', [PageController::class, 'cookiePolicy'])->name('pages.cookie-policy');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('pages.disclaimer');

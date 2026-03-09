<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SessionAuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Show the register page.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle Laravel session login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        $this->migrateGuestQrCodes($request, $request->user());

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle user registration for session auth.
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'plan' => 'free',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $this->migrateGuestQrCodes($request, $user);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout the user from the current session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Migrate guest QR codes (stored in session) to the authenticated user.
     */
    protected function migrateGuestQrCodes(Request $request, User $user): void
    {
        $guestQrIds = $request->session()->get('guest_qr_ids', []);

        if (empty($guestQrIds)) {
            return;
        }

        QrCode::whereIn('id', $guestQrIds)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        $request->session()->forget('guest_qr_ids');
    }
}

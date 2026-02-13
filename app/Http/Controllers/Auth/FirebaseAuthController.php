<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Models\User;
use App\Services\FirebaseTokenVerifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FirebaseAuthController extends Controller
{
    protected FirebaseTokenVerifier $tokenVerifier;

    public function __construct(FirebaseTokenVerifier $tokenVerifier)
    {
        $this->tokenVerifier = $tokenVerifier;
    }

    /**
     * Show the login page.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Show the register page.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle Firebase authentication callback.
     *
     * Receives a Firebase ID token from the frontend,
     * verifies it, finds or creates the user, migrates
     * guest QR codes, and establishes a Laravel session.
     */
    public function handleCallback(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'name' => 'nullable|string|max:255',
        ]);

        try {
            // Verify the Firebase ID token
            $decoded = $this->tokenVerifier->verify($request->input('id_token'));
            $userInfo = $this->tokenVerifier->extractUserInfo($decoded);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 401);
        }

        // Find existing user by firebase_uid or email
        $user = User::where('firebase_uid', $userInfo['uid'])->first();

        if (!$user && $userInfo['email']) {
            $user = User::where('email', $userInfo['email'])->first();
        }

        if ($user) {
            // Update firebase_uid if not set (e.g., existing user linking Firebase)
            if (!$user->firebase_uid) {
                $user->firebase_uid = $userInfo['uid'];
            }

            // Update phone if provided and not set
            if ($userInfo['phone'] && !$user->phone) {
                $user->phone = $userInfo['phone'];
            }

            // Update name if provided and not set
            if ($userInfo['name'] && !$user->name) {
                $user->name = $userInfo['name'];
            }

            $user->save();
        } else {
            // Create new user
            $name = $request->input('name')
                ?? $userInfo['name']
                ?? $userInfo['email']
                ?? $userInfo['phone']
                ?? 'User';

            $user = User::create([
                'firebase_uid' => $userInfo['uid'],
                'name' => $name,
                'email' => $userInfo['email'],
                'phone' => $userInfo['phone'],
                'password' => null, // Firebase handles auth
                'plan' => 'free',
                'email_verified_at' => $userInfo['email_verified'] ? now() : null,
            ]);
        }

        // Migrate guest QR codes to this user
        $this->migrateGuestQrCodes($request, $user);

        // Log the user in with Laravel session
        Auth::login($user, remember: true);

        // Regenerate session to prevent fixation
        $request->session()->regenerate();

        // Determine redirect URL
        $redirectUrl = $request->session()->pull('url.intended', route('dashboard'));

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
            'redirect' => $redirectUrl,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'plan' => $user->plan,
            ],
        ]);
    }

    /**
     * Show the logout page and log the user out.
     */
    public function showLogout(Request $request)
    {
        // If user is authenticated, log them out first
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return view('auth.logout');
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

        // Update QR codes that belong to guest (user_id is null) to this user
        QrCode::whereIn('id', $guestQrIds)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        // Clear guest QR IDs from session
        $request->session()->forget('guest_qr_ids');
    }
}

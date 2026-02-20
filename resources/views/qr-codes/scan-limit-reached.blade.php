<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Limit Reached - ImQR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-primary-50 to-primary-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
        <!-- Icon -->
        <div class="mb-6">
            <div class="mx-auto w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>

        <!-- Heading -->
        <h1 class="text-2xl font-bold text-gray-900 mb-3">
            Scan Limit Reached
        </h1>

        <!-- Message -->
        <p class="text-gray-600 mb-6">
            This QR code has reached its scan limit of <strong>10 scans</strong>. 
            @if($qrCode->isGuest())
                Register for a free account or upgrade to Premium for unlimited scans.
            @else
                Upgrade to <strong>Premium</strong> to get unlimited scans, edit anytime, and more!
            @endif
        </p>

        <!-- Stats -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="text-sm text-gray-500 mb-1">Total Scans</div>
            <div class="text-3xl font-bold text-gray-900">{{ $qrCode->scan_count }}</div>
        </div>

        <!-- CTA Buttons -->
        <div class="space-y-3">
            @guest
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="block w-full bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                        Create Free Account
                    </a>
                @endif
                @if(Route::has('login'))
                    <a href="{{ route('login') }}" class="block w-full border-2 border-primary-600 text-primary-600 px-6 py-3 rounded-lg font-semibold hover:bg-primary-50 transition-colors">
                        Log In
                    </a>
                @endif
            @else
                @if(auth()->user()->isFree() && Route::has('premium.upgrade'))
                    <a href="{{ route('premium.upgrade') }}" class="block w-full bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors">
                        Upgrade to Premium
                    </a>
                @endif
            @endguest

            <a href="{{ route('qr-codes.index') }}" class="block w-full text-gray-600 px-6 py-3 rounded-lg font-semibold hover:text-gray-900 transition-colors">
                Create New QR Code
            </a>
        </div>

        <!-- Premium Features -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">Premium Benefits:</h3>
            <ul class="text-left text-sm text-gray-600 space-y-2">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Unlimited scans</strong> per QR code</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Add your logo</strong> to QR codes</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Edit anytime</strong> without reprinting</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Scan tracking</strong> & analytics</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>No branding</strong> on pages</span>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>

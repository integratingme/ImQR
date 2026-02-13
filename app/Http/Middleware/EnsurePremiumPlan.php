<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePremiumPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isPremium()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This feature requires a Premium plan. Upgrade to access.',
                    'upgrade_required' => true,
                ], 403);
            }
            
            return redirect()->route('qr-codes.index')->with('error', 'This feature requires a Premium plan.');
        }

        return $next($request);
    }
}

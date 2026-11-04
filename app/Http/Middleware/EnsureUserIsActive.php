<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Akun yang diblokir (is_active = false) langsung dikeluarkan pada setiap
 * request, sehingga blokir admin berlaku seketika untuk sesi yang sedang aktif.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_active === false) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akun kamu diblokir.'], 403);
            }

            return redirect()->route('login')->withErrors(['email' => 'Akun kamu diblokir. Hubungi admin untuk bantuan.']);
        }

        return $next($request);
    }
}

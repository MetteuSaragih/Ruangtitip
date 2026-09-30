<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && (empty($user->name) || empty($user->phone))) {
            session(['profile_redirect_after' => $request->fullUrl()]);

            return redirect()->route('profile.index')
                ->with('error', 'Lengkapi profil kamu (nama & nomor WhatsApp) terlebih dahulu sebelum melanjutkan pemesanan.');
        }

        return $next($request);
    }
}

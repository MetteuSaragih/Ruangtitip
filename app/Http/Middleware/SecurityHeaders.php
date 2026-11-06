<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Header keamanan standar untuk semua respons (halaman biasa, galat, dan perawatan).
 *
 * - X-Frame-Options dan frame-ancestors: situs tidak bisa dipasang di dalam frame situs lain (anti-clickjacking).
 * - X-Content-Type-Options: browser tidak menebak jenis berkas (anti MIME sniffing).
 * - Referrer-Policy: alamat halaman internal tidak bocor ke situs lain.
 * - Permissions-Policy: kamera, mikrofon, lokasi, dan pembayaran browser dimatikan.
 * - HSTS: hanya dikirim lewat HTTPS, memaksa browser memakai HTTPS.
 * - CSP dibatasi pada direktif yang aman untuk dipasang tanpa menyentuh skrip inline halaman:
 *   frame-ancestors, base-uri, object-src.
 *
 * Header yang sudah diisi oleh controller (mis. file bukti pembayaran) tidak ditimpa.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=(), usb=()',
            'Content-Security-Policy' => "frame-ancestors 'self'; base-uri 'self'; object-src 'none'",
            'Cross-Origin-Opener-Policy' => 'same-origin-allow-popups',
        ];

        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000';
        }

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}

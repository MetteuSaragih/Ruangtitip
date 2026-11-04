<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($appUrl = config('app.url')) {
            URL::forceRootUrl($appUrl);
            URL::forceScheme(parse_url($appUrl, PHP_URL_SCHEME) ?: 'http');
        }

        // Pembatasan OTP: kirim kode (per IP dan per email) dan percobaan verifikasi.
        RateLimiter::for('otp-send', fn (Request $r) => [
            Limit::perMinute(5)->by('ip:'.$r->ip()),
            Limit::perHour(8)->by('email:'.strtolower((string) ($r->input('email') ?: $r->session()->get('otp_email')))),
        ]);
        RateLimiter::for('otp-verify', fn (Request $r) => [
            Limit::perMinute(10)->by('ip:'.$r->ip()),
            Limit::perHour(20)->by('email:'.strtolower((string) $r->session()->get('otp_email'))),
        ]);

        // BookShipmentOnOrderPaid terdaftar otomatis lewat auto-discovery
        // Laravel (handle() di-type-hint ke App\Events\OrderPaid), tidak
        // perlu didaftarkan manual di sini.
    }
}

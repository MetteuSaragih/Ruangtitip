<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Menghitung pengunjung secara anonim: satu baris per pengunjung per hari (tabel site_visits).
 *
 * - Pengunjung dikenali lewat cookie berisi kode acak, bukan IP atau identitas.
 * - Hanya halaman HTML (GET) yang berhasil dimuat. Admin, bot, API, webhook, dan berkas tidak dihitung.
 * - Pencatatan dilakukan setelah respons terkirim, dan galat apa pun tidak pernah memengaruhi pengunjung.
 */
class TrackVisit
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|facebot|whatsapp|telegram|preview|monitor|uptime|pingdom|curl|wget|python|java\/|go-http|headless|lighthouse|gtmetrix|semrush|ahrefs|mj12|dotbot|petalbot|yandex|baidu|duckduck|scanner|httpclient/i';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $this->track($request, $response);
        } catch (Throwable $e) {
            // Statistik tidak boleh mengganggu pengunjung.
        }

        return $response;
    }

    private function track(Request $request, Response $response): void
    {
        if (! config('visits.enabled') || ! $request->isMethod('GET')) {
            return;
        }
        if ($request->ajax() || $request->expectsJson() || $request->header('Purpose') === 'prefetch') {
            return;
        }
        if (! $response->isSuccessful() || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return;
        }

        $path = trim($request->path(), '/');
        if (preg_match('#^(admin|api|tripay|biteship|pembayaran|storage|build|assets|livewire)(/|$)#', $path) || $path === 'up' || str_starts_with($path, '_')) {
            return;
        }

        $agent = (string) $request->userAgent();
        if ($agent === '' || preg_match(self::BOT_PATTERN, $agent)) {
            return;
        }

        $user = $request->user();
        if ($user && $user->role === 'admin') {
            return; // kunjungan admin sendiri tidak ikut dihitung
        }

        $name = (string) config('visits.cookie', 'rt_vid');
        $visitor = (string) $request->cookie($name);
        if (! preg_match('/^[a-f0-9]{32}$/', $visitor)) {
            $visitor = bin2hex(random_bytes(16));
        }
        $response->headers->setCookie(cookie($name, $visitor, 60 * 24 * 365, '/', null, $request->isSecure(), true, false, 'lax'));

        $isLanding = $path === '';
        $userId = $user?->id;

        app()->terminating(function () use ($visitor, $userId, $isLanding) {
            try {
                self::record($visitor, $userId, $isLanding);
            } catch (Throwable $e) {
                // tabel belum dimigrasi atau galat lain: abaikan
            }
        });
    }

    /** Tambah satu kunjungan untuk pengunjung ini pada hari ini. */
    public static function record(string $visitor, ?int $userId, bool $isLanding): void
    {
        $today = now()->toDateString();
        $column = $isLanding ? 'landing_views' : 'other_views';
        $now = now();

        $update = static function () use ($visitor, $today, $userId, $column, $now) {
            $sets = ['last_seen_at' => $now, $column => DB::raw($column . ' + 1')];
            if ($userId) {
                $sets['user_id'] = $userId;
            }

            return DB::table('site_visits')->where('visitor_id', $visitor)->where('visit_date', $today)->update($sets);
        };

        if ($update()) {
            return;
        }

        try {
            DB::table('site_visits')->insert([
                'visitor_id' => $visitor,
                'visit_date' => $today,
                'user_id' => $userId,
                'landing_views' => $isLanding ? 1 : 0,
                'other_views' => $isLanding ? 0 : 1,
                'first_seen_at' => $now,
                'last_seen_at' => $now,
            ]);
        } catch (QueryException $e) {
            $update(); // dua permintaan bersamaan: baris sudah dibuat, cukup tambah hitungan
        }
    }
}

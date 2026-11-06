<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ringkasan statistik kunjungan untuk dashboard admin, dari tabel site_visits.
 *
 * Satu "pengunjung" = satu browser (cookie anonim). Dalam periode tertentu setiap pengunjung masuk
 * tepat satu kelompok:
 *   login        sudah login pada periode itu
 *   landing_only belum login dan hanya membuka halaman depan (landing)
 *   other        belum login tetapi membuka halaman lain (login, katalog, syarat, dst.)
 */
class VisitStats
{
    /** Null bila tabel belum dimigrasi. */
    public static function summary(): ?array
    {
        try {
            if (! Schema::hasTable('site_visits')) {
                return null;
            }

            $today = now()->toDateString();
            $periods = [
                'today' => ['Hari ini', $today],
                'week' => ['7 hari terakhir', now()->subDays(6)->toDateString()],
                'month' => ['30 hari terakhir', now()->subDays(29)->toDateString()],
            ];

            $rows = [];
            foreach ($periods as $key => [$label, $from]) {
                $rows[$key] = ['label' => $label] + self::period($from, $today) + [
                    'new_accounts' => User::where('role', '!=', 'admin')->whereDate('created_at', '>=', $from)->count(),
                ];
            }

            $activeSince = now()->subMinutes((int) config('visits.active_minutes', 5));
            $active = DB::table('site_visits')->where('visit_date', $today)->where('last_seen_at', '>=', $activeSince);
            $activeTotal = (clone $active)->count();
            $activeLogin = (clone $active)->whereNotNull('user_id')->count();

            return [
                'periods' => $rows,
                'active' => ['total' => $activeTotal, 'login' => $activeLogin, 'guest' => $activeTotal - $activeLogin, 'minutes' => (int) config('visits.active_minutes', 5)],
                'chart' => self::chart(14),
                'since' => DB::table('site_visits')->min('visit_date'),
            ];
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    /** Hitung pengunjung unik dan kelompoknya pada rentang tanggal. */
    private static function period(string $from, string $to): array
    {
        $visitors = DB::table('site_visits')
            ->whereBetween('visit_date', [$from, $to])
            ->selectRaw('visitor_id, MAX(CASE WHEN user_id IS NULL THEN 0 ELSE 1 END) AS is_login, SUM(landing_views) AS lv, SUM(other_views) AS ov')
            ->groupBy('visitor_id')
            ->get();

        $login = $landing = $other = 0;
        foreach ($visitors as $v) {
            if ((int) $v->is_login === 1) {
                $login++;
            } elseif ((int) $v->ov === 0 && (int) $v->lv > 0) {
                $landing++;
            } else {
                $other++;
            }
        }
        $total = $visitors->count();

        return [
            'total' => $total,
            'login' => $login,
            'landing_only' => $landing,
            'other' => $other,
            'login_pct' => $total > 0 ? (int) round($login / $total * 100) : 0,
        ];
    }

    /** Pengunjung per hari untuk grafik: total, sudah login, dan hanya landing. */
    private static function chart(int $days): array
    {
        $from = now()->subDays($days - 1)->toDateString();
        $byDate = DB::table('site_visits')
            ->where('visit_date', '>=', $from)
            ->selectRaw("visit_date, COUNT(*) AS total, SUM(CASE WHEN user_id IS NULL THEN 0 ELSE 1 END) AS login, SUM(CASE WHEN user_id IS NULL AND other_views = 0 AND landing_views > 0 THEN 1 ELSE 0 END) AS landing_only")
            ->groupBy('visit_date')
            ->get()
            ->keyBy(fn ($r) => substr((string) $r->visit_date, 0, 10));

        $chart = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $row = $byDate->get($d->toDateString());
            $chart[] = [
                'label' => $d->format('d'),
                'date' => $d->translatedFormat('j M'),
                'total' => (int) ($row->total ?? 0),
                'login' => (int) ($row->login ?? 0),
                'landing_only' => (int) ($row->landing_only ?? 0),
            ];
        }

        return $chart;
    }
}

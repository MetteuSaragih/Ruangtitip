<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BillingFeed;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TagihanController extends Controller
{
    private const TABS = ['semua', 'menunggu', 'berhasil', 'gagal'];

    public function index(Request $request)
    {
        [$tab, $source, $q] = $this->params($request);

        // Hitungan tab dihitung dari data yang sudah terfilter sumber dan pencarian.
        $base = $this->filtered($source, $q, 'semua');
        $counts = [
            'semua' => $base->count(),
            'menunggu' => $base->where('group', 'wait')->count(),
            'berhasil' => $base->where('group', 'ok')->count(),
            'gagal' => $base->where('group', 'fail')->count(),
        ];

        $rows = $this->filtered($source, $q, $tab);

        $all = BillingFeed::records(200);
        $kpi = [
            'menunggu' => $all->where('group', 'wait')->sum('amount'),
            'menunggu_n' => $all->where('group', 'wait')->count(),
            'berhasil' => $all->where('group', 'ok')
                ->filter(fn ($r) => $r['paid'] && $r['paid']->greaterThanOrEqualTo(now()->startOfMonth()))
                ->sum('amount'),
            'gagal' => $all->where('group', 'fail')->count(),
        ];

        $perPage = 15;
        $page = max(1, (int) $request->query('page', 1));
        $paginator = new LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url()]
        );
        $paginator->withQueryString();

        return view('admin.tagihan.index', [
            'rows' => $paginator,
            'tab' => $tab,
            'source' => $source,
            'q' => $q,
            'counts' => $counts,
            'kpi' => $kpi,
            'types' => BillingFeed::TYPES,
        ]);
    }

    public function csv(Request $request)
    {
        [$tab, $source, $q] = $this->params($request);
        $rows = $this->filtered($source, $q, $tab);
        $filename = 'tagihan-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Kode', 'Jenis', 'Pelanggan', 'Email', 'Metode', 'Jumlah', 'Status', 'Status Tripay', 'Referensi Tripay', 'Dibuat', 'Dibayar']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r['code'],
                    $r['type_label'],
                    $r['customer'],
                    $r['email'],
                    $r['method'],
                    $r['amount'],
                    $r['label'],
                    $r['raw'],
                    $r['reference'],
                    $r['created'] ? $r['created']->format('Y-m-d H:i') : '',
                    $r['paid'] ? $r['paid']->format('Y-m-d H:i') : '',
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function show(string $type, int $id)
    {
        abort_unless(array_key_exists($type, BillingFeed::TYPES), 404);
        $bill = BillingFeed::find($type, $id);
        abort_unless($bill, 404);

        return view('admin.tagihan.show', ['bill' => $bill]);
    }

    private function params(Request $request): array
    {
        $tab = (string) $request->query('tab', 'semua');
        if (! in_array($tab, self::TABS, true)) {
            $tab = 'semua';
        }
        $source = (string) $request->query('sumber', 'semua');
        if (! array_key_exists($source, BillingFeed::TYPES)) {
            $source = 'semua';
        }
        $q = trim((string) $request->query('q', ''));

        return [$tab, $source, $q];
    }

    private function filtered(string $source, string $q, string $tab): Collection
    {
        $rows = BillingFeed::records(200, null, $source === 'semua' ? null : $source);

        if ($q !== '') {
            $needle = Str::lower($q);
            $rows = $rows->filter(function ($r) use ($needle) {
                return Str::contains(Str::lower($r['code'] . ' ' . $r['customer'] . ' ' . ($r['email'] ?? '') . ' ' . ($r['reference'] ?? '')), $needle);
            });
        }

        $group = ['menunggu' => 'wait', 'berhasil' => 'ok', 'gagal' => 'fail'][$tab] ?? null;
        if ($group) {
            $rows = $rows->where('group', $group);
        }

        return $rows->sortByDesc(fn ($r) => $r['created'] ? $r['created']->getTimestamp() : 0)->values();
    }
}

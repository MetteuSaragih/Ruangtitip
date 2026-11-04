<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorageRoom;
use App\Services\BillingFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $days = $this->days($request);
        $data = $this->build($days);

        $rooms = collect();
        try {
            if (Schema::hasTable('storage_rooms')) {
                $rooms = StorageRoom::orderBy('name')->get();
            }
        } catch (\Throwable $e) {
            report($e);
        }

        $maxDay = max(1, collect($data['daily'])->max('total'));

        return view('admin.laporan', $data + [
            'days' => $days,
            'rooms' => $rooms,
            'maxDay' => $maxDay,
            'types' => BillingFeed::TYPES,
        ]);
    }

    public function csv(Request $request)
    {
        $days = $this->days($request);
        $data = $this->build($days);
        $filename = 'laporan-harian-' . $days . 'hari-' . now()->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Tanggal', 'Titip', 'Preloved', 'Packing', 'Total', 'Tagihan baru']);
            foreach ($data['daily'] as $d) {
                fputcsv($out, [$d['date'], $d['titip'], $d['preloved'], $d['packing'], $d['total'], $d['new']]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function days(Request $request): int
    {
        $d = (int) $request->query('periode', 7);

        return in_array($d, [7, 14, 30], true) ? $d : 7;
    }

    private function build(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();
        $records = BillingFeed::records(5000, $start);

        $daily = [];
        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            $daily[$day->toDateString()] = [
                'date' => $day->toDateString(),
                'label' => $day->format('d/m'),
                'titip' => 0, 'preloved' => 0, 'packing' => 0, 'total' => 0, 'new' => 0,
            ];
        }

        $paid = $records->filter(fn ($r) => $r['group'] === 'ok' && $r['paid'] && $r['paid']->greaterThanOrEqualTo($start));
        foreach ($paid as $r) {
            $key = $r['paid']->toDateString();
            if (isset($daily[$key])) {
                $daily[$key][$r['type']] += $r['amount'];
                $daily[$key]['total'] += $r['amount'];
            }
        }

        $created = $records->filter(fn ($r) => $r['created'] && $r['created']->greaterThanOrEqualTo($start));
        foreach ($created as $r) {
            $key = $r['created']->toDateString();
            if (isset($daily[$key])) {
                $daily[$key]['new']++;
            }
        }

        $sum = fn (string $type) => (int) $paid->where('type', $type)->sum('amount');
        $kpi = [
            'titip' => $sum('titip'),
            'preloved' => $sum('preloved'),
            'packing' => $sum('packing'),
            'new' => $created->count(),
            'fail' => $created->where('group', 'fail')->count(),
        ];
        $kpi['total'] = $kpi['titip'] + $kpi['preloved'] + $kpi['packing'];

        $perType = [];
        foreach (BillingFeed::TYPES as $type => $label) {
            $c = $created->where('type', $type);
            $perType[$type] = [
                'label' => $label,
                'count' => $c->count(),
                'ok' => $c->where('group', 'ok')->count(),
                'wait' => $c->where('group', 'wait')->count(),
                'fail' => $c->where('group', 'fail')->count(),
                'revenue' => $sum($type),
            ];
        }

        return [
            'daily' => array_values($daily),
            'kpi' => $kpi,
            'perType' => $perType,
            'from' => $start,
        ];
    }
}

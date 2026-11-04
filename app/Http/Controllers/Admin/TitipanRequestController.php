<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TitipanRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TitipanRequestController extends Controller
{
    private const TRANSITIONS = [
        'menunggu' => ['diproses', 'ditolak'],
        'diproses' => ['selesai', 'ditolak'],
    ];

    private const TABS = [
        'aktif' => ['menunggu', 'diproses'],
        'selesai' => ['selesai'],
        'ditolak' => ['ditolak'],
    ];

    public function index(Request $request)
    {
        $type = $request->query('type');
        if (! in_array($type, TitipanRequest::TYPES, true)) {
            $type = 'ambil';
        }
        $tab = $request->query('tab');
        if (! array_key_exists($tab, self::TABS)) {
            $tab = 'aktif';
        }

        $ready = Schema::hasTable('titipan_requests');
        $counts = ['aktif' => 0, 'selesai' => 0, 'ditolak' => 0];
        $requests = null;

        if ($ready) {
            foreach (self::TABS as $key => $statuses) {
                $counts[$key] = TitipanRequest::where('type', $type)->whereIn('status', $statuses)->count();
            }
            $requests = TitipanRequest::with(['order', 'user'])
                ->where('type', $type)
                ->whereIn('status', self::TABS[$tab])
                ->orderByDesc('created_at')
                ->paginate(15)
                ->withQueryString();
        }

        $titles = [
            'ambil' => ['Pengambilan di gudang', 'Pelanggan yang ingin mengambil sendiri barang titipannya.'],
            'kirim' => ['Pengiriman barang titipan', 'Pelanggan yang meminta barang dikirim ke alamat mereka.'],
            'perpanjang' => ['Perpanjangan masa titip', 'Pelanggan yang ingin menambah periode penyimpanan.'],
        ];

        return view('admin.permintaan', [
            'type' => $type,
            'tab' => $tab,
            'ready' => $ready,
            'counts' => $counts,
            'requests' => $requests,
            'title' => $titles[$type][0],
            'subtitle' => $titles[$type][1],
            'transitions' => self::TRANSITIONS,
        ]);
    }

    public function update(Request $request, TitipanRequest $titipanRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:diproses,selesai,ditolak'],
            'catatan' => ['nullable', 'string', 'max:500', 'required_if:status,ditolak'],
        ], [
            'catatan.required_if' => 'Catatan wajib diisi saat menolak permintaan.',
        ]);

        $allowed = self::TRANSITIONS[$titipanRequest->status] ?? [];
        if (! in_array($data['status'], $allowed, true)) {
            return back()->with('error', 'Perubahan status tidak valid untuk permintaan ini.');
        }

        $note = trim((string) ($data['catatan'] ?? ''));
        $extra = '';

        DB::transaction(function () use ($titipanRequest, $data, $note, &$extra) {
            if ($data['status'] === 'selesai' && $titipanRequest->type === 'perpanjang') {
                $order = $titipanRequest->order;
                $newEnd = $titipanRequest->payload['new_date_end'] ?? null;
                if ($order && $newEnd) {
                    $target = \Illuminate\Support\Carbon::parse($newEnd)->startOfDay();
                    if (! $order->date_end || $target->gt($order->date_end)) {
                        $order->date_end = $target->toDateString();
                        $order->save();
                        $extra = 'Tanggal akhir titip diperbarui ke ' . $target->format('d/m/Y') . '.';
                    } else {
                        $extra = 'Tanggal akhir titip tidak diubah (sudah sama atau lebih jauh).';
                    }
                }
            }

            $parts = array_filter([
                $titipanRequest->catatan,
                $note !== '' ? 'Admin: ' . $note : null,
                $extra !== '' ? 'Sistem: ' . $extra : null,
            ]);

            $titipanRequest->status = $data['status'];
            $titipanRequest->catatan = $parts ? implode("\n", $parts) : null;
            $titipanRequest->save();
        });

        return back()->with('success', 'Status permintaan diperbarui menjadi ' . strtolower(TitipanRequest::STATUS_LABELS[$data['status']]) . '.');
    }
}

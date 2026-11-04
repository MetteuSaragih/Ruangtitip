<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PrelovedSellController;
use App\Models\PrelovedItem;
use App\Models\PrelovedSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Admin: pengajuan jual barang dari pelanggan (alur status sesuai PrelovedSubmission::STATUS).
 */
class PrelovedSubmissionController extends Controller
{
    public const GROUPS = [
        'perlu'   => ['seleksi', 'periksa', 'sepakat'],
        'berjalan' => ['jadwal', 'antar', 'tawar', 'titip'],
        'selesai' => ['tolak', 'tidak', 'dibeli', 'batal'],
    ];

    /** Pemetaan kondisi pengajuan ke persen kondisi katalog. */
    private const CONDITION_PCT = ['baru' => 95, 'baik' => 85, 'cukup' => 75];

    public function index(Request $request)
    {
        if (! Schema::hasTable('preloved_submissions')) {
            return view('admin.jual-index', ['missing' => true, 'tab' => 'perlu', 'q' => '', 'counts' => [], 'rows' => null]);
        }

        $tab = $request->query('tab', 'perlu');
        if (! in_array($tab, ['perlu', 'berjalan', 'selesai', 'semua'], true)) {
            $tab = 'perlu';
        }
        $q = trim((string) $request->query('q', ''));

        $counts = ['semua' => PrelovedSubmission::count()];
        foreach (self::GROUPS as $key => $statuses) {
            $counts[$key] = PrelovedSubmission::whereIn('status', $statuses)->count();
        }

        $query = PrelovedSubmission::with('user')->latest();
        if (isset(self::GROUPS[$tab])) {
            $query->whereIn('status', self::GROUPS[$tab]);
        }
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('code', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            });
        }

        return view('admin.jual-index', [
            'missing' => false,
            'tab' => $tab,
            'q' => $q,
            'counts' => $counts,
            'rows' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function show(PrelovedSubmission $submission)
    {
        $submission->loadMissing('user');

        $draft = null;
        if ($submission->status === 'dibeli') {
            try {
                $draft = Schema::hasColumn('preloved_items', 'source_code')
                    ? PrelovedItem::where('source_code', $submission->code)->first()
                    : null;
            } catch (\Throwable $e) {
                $draft = null;
            }
        }

        return view('admin.jual-show', [
            's' => $submission,
            'slots' => PrelovedSellController::SLOTS,
            'draft' => $draft,
            'conditions' => PrelovedSubmission::CONDITIONS,
        ]);
    }

    public function act(Request $request, PrelovedSubmission $submission)
    {
        $aksi = (string) $request->input('aksi');
        $status = $submission->status;

        switch ($aksi) {
            case 'approve':
                $this->requireStatus($status, 'seleksi');
                $submission->status = 'jadwal';
                $submission->reason = null;
                $submission->addLog('Admin', 'Pengajuan disetujui. Menunggu pelanggan memilih jadwal antar.');
                $msg = 'Pengajuan disetujui.';
                break;

            case 'reject':
                $this->requireStatus($status, 'seleksi');
                $data = $request->validate(['reason' => ['required', 'string', 'max:500']], [
                    'reason.required' => 'Isi alasan penolakan.',
                ]);
                $submission->status = 'tolak';
                $submission->reason = $data['reason'];
                $submission->addLog('Admin', 'Pengajuan ditolak: ' . $data['reason']);
                $msg = 'Pengajuan ditolak.';
                break;

            case 'schedule':
                $this->requireStatus($status, 'jadwal');
                $data = $request->validate([
                    'date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(30)->toDateString()],
                    'slot' => ['required', Rule::in(array_keys(PrelovedSellController::SLOTS))],
                ], [
                    'date.required' => 'Pilih tanggal.',
                    'date.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini.',
                    'date.before_or_equal' => 'Maksimal 30 hari dari sekarang.',
                    'slot.required' => 'Pilih jam.',
                    'slot.in' => 'Pilih jam.',
                ]);
                $day = Carbon::parse($data['date']);
                if ($day->isSunday()) {
                    return back()->withErrors(['date' => 'Gudang tutup di hari Minggu.'])->withInput();
                }
                if ($day->isSaturday() && ! in_array($data['slot'], ['s1', 's2'], true)) {
                    return back()->withErrors(['slot' => 'Hari Sabtu hanya tersedia jam pagi.'])->withInput();
                }
                $submission->schedule_date = $day->toDateString();
                $submission->schedule_slot = $data['slot'];
                $submission->status = 'antar';
                $submission->addLog('Admin', 'Mengatur jadwal antar ' . $day->locale('id')->translatedFormat('j F Y') . ', ' . PrelovedSellController::SLOTS[$data['slot']] . '.');
                $msg = 'Jadwal antar disimpan.';
                break;

            case 'arrived':
                $this->requireStatus($status, 'antar');
                $submission->status = 'periksa';
                $submission->addLog('Admin', 'Barang diterima di gudang dan mulai diperiksa.');
                $msg = 'Barang dicatat sudah datang.';
                break;

            case 'not_buy':
                $this->requireStatus($status, 'periksa');
                $data = $request->validate(['reason' => ['required', 'string', 'max:500']], [
                    'reason.required' => 'Isi alasan tidak dibeli.',
                ]);
                $submission->status = 'tidak';
                $submission->reason = $data['reason'];
                $submission->addLog('Admin', 'Barang tidak dibeli: ' . $data['reason']);
                $msg = 'Pengajuan ditutup (tidak dibeli).';
                break;

            case 'offer':
                $this->requireStatus($status, 'periksa');
                $data = $request->validate(['offer' => ['required', 'integer', 'min:1', 'max:100000000']], [
                    'offer.required' => 'Isi harga penawaran.',
                    'offer.integer' => 'Harga penawaran harus angka.',
                    'offer.min' => 'Harga penawaran harus lebih dari 0.',
                ]);
                $submission->offer = (int) $data['offer'];
                $submission->status = 'tawar';
                $submission->addLog('Admin', 'Membuat penawaran harga beli Rp ' . number_format($submission->offer, 0, ',', '.') . '.');
                $msg = 'Penawaran dikirim ke pelanggan.';
                break;

            case 'titip_ok':
                $this->requireStatus($status, 'titip');
                $submission->status = 'sepakat';
                $submission->addLog('Admin', 'Tagihan titipan diselesaikan. Menunggu pembayaran ke penjual.');
                $msg = 'Tagihan titipan dicatat selesai.';
                break;

            case 'paid':
                $this->requireStatus($status, 'sepakat');
                $submission->status = 'dibeli';
                $submission->addLog('Admin', 'Pembayaran ke penjual dicatat. Barang resmi dibeli Ruang Titip.');
                $msg = 'Pembayaran ke penjual dicatat.';
                break;

            default:
                abort(422, 'Aksi tidak dikenal.');
        }

        $submission->save();

        if ($aksi === 'paid') {
            $created = $this->createDraftItem($submission);
            if ($created) {
                $msg .= ' Draft barang dibuat di katalog.';
            }
        }

        return redirect()->route('admin.jual.show', $submission->code)->with('success', $msg);
    }

    private function requireStatus(string $current, string $expected): void
    {
        abort_unless($current === $expected, 409, 'Status pengajuan sudah berubah. Muat ulang halaman.');
    }

    /** Buat PrelovedItem berstatus Draft dari data pengajuan. Gagal diam-diam bila tabel/kolom tidak cocok. */
    private function createDraftItem(PrelovedSubmission $s): bool
    {
        try {
            if (! Schema::hasTable('preloved_items')) {
                return false;
            }
            $hasSource = Schema::hasColumn('preloved_items', 'source_code');
            if ($hasSource && PrelovedItem::where('source_code', $s->code)->exists()) {
                return false;
            }

            // Foto disalin ke folder katalog supaya menghapus barang di katalog tidak merusak data pengajuan.
            $photos = [];
            foreach (array_values(array_filter((array) ($s->photos ?? []))) as $path) {
                if (! Storage::disk('public')->exists($path)) {
                    continue;
                }
                $target = 'preloved/' . Str::random(12) . '-' . basename($path);
                Storage::disk('public')->copy($path, $target);
                $photos[] = $target;
            }

            $data = [
                'name' => $s->name,
                'category' => $s->category,
                'condition' => self::CONDITION_PCT[$s->condition] ?? 85,
                // Harga awal = harga beli. Admin menyesuaikan harga jual sebelum mempublikasikan.
                'price' => max(1000, (int) $s->offer),
                'status' => 'Draft',
                'seller' => optional($s->user)->name,
                'description' => trim((string) $s->description) !== '' ? trim((string) $s->description) : $s->name,
                'completeness' => $s->completeness,
                'flaws' => $s->flaws,
                'photo' => $photos[0] ?? null,
                'photos' => $photos,
            ];
            if ($hasSource) {
                $data['source_code'] = $s->code;
            }

            PrelovedItem::create($data);

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}

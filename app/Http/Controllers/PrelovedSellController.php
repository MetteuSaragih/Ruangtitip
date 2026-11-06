<?php

namespace App\Http\Controllers;

use App\Services\AdminNotifier;
use App\Models\PrelovedSubmission;
use App\Models\TitipanOrder;
use App\Models\UserBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Jual Barang: pengajuan barang ke Ruang Titip (alur prototipe).
 * Daftar pengajuan tampil di halaman preloved.cara-jual (PrelovedController@caraJual).
 */
class PrelovedSellController extends Controller
{
    public const SLOTS = [
        's1' => '08.00–10.00',
        's2' => '10.00–12.00',
        's3' => '13.00–15.00',
        's4' => '15.00–17.00',
    ];

    public static function categories(): array
    {
        return \App\Models\PrelovedItem::CATEGORIES;
    }

    public function create()
    {
        $user = Auth::user();
        $bank = Schema::hasTable('user_bank_accounts')
            ? UserBankAccount::where('user_id', $user->id)->orderByDesc('is_primary')->first()
            : null;

        return view('preloved.sell-new', [
            'titips' => $this->myTitips(),
            'categories' => self::categories(),
            'conditions' => PrelovedSubmission::CONDITIONS,
            'bank' => $bank,
            'holder' => $bank?->holder ?? $user->name,
        ]);
    }

    public function store(Request $request)
    {
        if (! Schema::hasTable('preloved_submissions')) {
            return back()->withInput()->with('error', 'Fitur pengajuan belum aktif di server. Hubungi admin untuk menjalankan pembaruan database (php artisan migrate).');
        }

        $titipCodes = $this->myTitips()->pluck('id')->all();

        $data = $request->validate([
            'source' => ['required', Rule::in(['luar', 'titip'])],
            'titip_ref' => ['nullable', 'required_if:source,titip', Rule::in($titipCodes)],
            'allow_open' => ['nullable', 'accepted_if:source,titip'],
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'category' => ['required', Rule::in(self::categories())],
            'condition' => ['required', Rule::in(array_keys(PrelovedSubmission::CONDITIONS))],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'completeness' => ['required', 'string', 'max:255'],
            'flaws' => ['required', 'string', 'max:255'],
            'photos' => ['required', 'array', 'min:2', 'max:8'],
            'photos.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
            'bank_name' => ['required', 'string', 'max:50'],
            'bank_number' => ['required', 'regex:/^\d{6,20}$/'],
            'bank_holder' => ['required', 'string', 'min:3', 'max:100'],
        ], [
            'source.required' => 'Pilih asal barang.',
            'titip_ref.required_if' => 'Pilih barang titipan.',
            'titip_ref.in' => 'Barang titipan tidak ditemukan.',
            'allow_open.accepted_if' => 'Centang izin membuka kemasan.',
            'name.required' => 'Isi nama barang.',
            'name.min' => 'Nama barang minimal 3 karakter.',
            'category.required' => 'Pilih kategori.',
            'category.in' => 'Pilih kategori.',
            'condition.required' => 'Pilih kondisi.',
            'condition.in' => 'Pilih kondisi.',
            'description.required' => 'Isi deskripsi dan fungsi barang.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'completeness.required' => 'Isi kelengkapan.',
            'flaws.required' => 'Isi kekurangan, atau tulis "Tidak ada".',
            'photos.required' => 'Unggah 2 sampai 8 foto.',
            'photos.min' => 'Unggah minimal 2 foto.',
            'photos.max' => 'Maksimal 8 foto.',
            'photos.*.image' => 'File harus berupa foto.',
            'photos.*.mimes' => 'Foto harus JPG, PNG, atau WEBP.',
            'photos.*.max' => 'Ukuran tiap foto maksimal 4 MB.',
            'bank_name.required' => 'Isi bank atau e-wallet.',
            'bank_number.required' => 'Isi nomor rekening.',
            'bank_number.regex' => 'Nomor rekening 6 sampai 20 angka.',
            'bank_holder.required' => 'Isi nama pemilik rekening.',
            'bank_holder.min' => 'Nama pemilik minimal 3 karakter.',
        ]);

        $paths = [];
        foreach ($request->file('photos', []) as $photo) {
            $paths[] = $photo->store('preloved-submissions', 'public');
        }

        do {
            $code = 'JB-' . strtoupper(Str::random(6));
        } while (PrelovedSubmission::where('code', $code)->exists());

        $submission = new PrelovedSubmission([
            'code' => $code,
            'user_id' => Auth::id(),
            'source' => $data['source'],
            'titip_ref' => $data['source'] === 'titip' ? $data['titip_ref'] : null,
            'allow_open' => $data['source'] === 'titip',
            'name' => $data['name'],
            'category' => $data['category'],
            'condition' => $data['condition'],
            'description' => $data['description'],
            'completeness' => $data['completeness'],
            'flaws' => $data['flaws'],
            'photos' => $paths,
            'bank_name' => $data['bank_name'],
            'bank_number' => $data['bank_number'],
            'bank_holder' => $data['bank_holder'],
            'status' => 'seleksi',
        ]);
        $submission->addLog('Kamu', 'Pengajuan dikirim dan menunggu seleksi admin.');
        $submission->save();

        AdminNotifier::sellSubmitted($submission);

        return redirect()->route('sell.show', $submission->code);
    }

    public function show(PrelovedSubmission $submission)
    {
        $this->authorizeOwner($submission);

        return view('preloved.sell-show', [
            's' => $submission,
            'slots' => self::SLOTS,
        ]);
    }

    public function schedule(Request $request, PrelovedSubmission $submission)
    {
        $this->authorizeOwner($submission);
        abort_unless($submission->status === 'jadwal', 404);

        $data = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:tomorrow', 'before_or_equal:' . now()->addDays(14)->toDateString()],
            'slot' => ['required', Rule::in(array_keys(self::SLOTS))],
        ], [
            'date.required' => 'Pilih tanggal.',
            'date.after_or_equal' => 'Tanggal paling cepat besok.',
            'date.before_or_equal' => 'Maksimal 14 hari dari sekarang.',
            'slot.required' => 'Pilih jam.',
            'slot.in' => 'Pilih jam.',
        ]);

        $day = \Illuminate\Support\Carbon::parse($data['date']);
        if ($day->isSunday()) {
            return back()->withErrors(['date' => 'Gudang tutup di hari Minggu.'])->withInput();
        }
        if ($day->isSaturday() && ! in_array($data['slot'], ['s1', 's2'], true)) {
            return back()->withErrors(['slot' => 'Hari Sabtu hanya tersedia jam pagi.'])->withInput();
        }

        $submission->schedule_date = $day->toDateString();
        $submission->schedule_slot = $data['slot'];
        $submission->status = 'antar';
        $submission->addLog('Kamu', 'Memilih jadwal ' . $day->locale('id')->translatedFormat('j F Y') . ', ' . self::SLOTS[$data['slot']] . '.');
        $submission->save();

        AdminNotifier::sellScheduled($submission);

        return redirect()->route('sell.show', $submission->code);
    }

    public function offer(Request $request, PrelovedSubmission $submission)
    {
        $this->authorizeOwner($submission);
        abort_unless($submission->status === 'tawar', 404);

        $data = $request->validate([
            'decision' => ['required', Rule::in(['accept', 'decline'])],
            'agree' => ['nullable', 'accepted_if:decision,accept'],
        ], [
            'agree.accepted_if' => 'Centang persetujuan sebelum menerima penawaran.',
        ]);

        if ($data['decision'] === 'accept') {
            $submission->status = $submission->source === 'titip' ? 'titip' : 'sepakat';
            $submission->addLog('Kamu', 'Menerima penawaran harga beli.');
        } else {
            $submission->status = 'batal';
            $submission->addLog('Kamu', 'Menolak penawaran harga beli.');
        }
        $submission->save();

        AdminNotifier::sellOfferAnswered($submission, $data['decision'] === 'accept');

        return redirect()->route('sell.show', $submission->code);
    }

    private function authorizeOwner(PrelovedSubmission $submission): void
    {
        abort_unless($submission->user_id === Auth::id(), 404);
    }

    private function myTitips()
    {
        return TitipanOrder::where('user_id', Auth::id())
            ->where('status', '!=', 'selesai')
            ->latest()
            ->get()
            ->map(fn (TitipanOrder $o) => ['id' => $o->code(), 'label' => 'Titipan ' . $o->code()]);
    }
}

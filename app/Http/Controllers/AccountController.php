<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PrelovedSubmission;
use App\Models\TitipanOrder;
use App\Models\UserAddress;
use App\Models\UserBankAccount;
use App\Services\DistanceService;
use App\Support\UserNotifications;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Halaman Akun saya: ringkasan, profil, alamat, rekening, keamanan,
 * notifikasi, dan bantuan.
 */
class AccountController extends Controller
{
    public const BANKS = ['BCA', 'BRI', 'BNI', 'Mandiri', 'BSI', 'CIMB Niaga', 'Permata', 'GoPay', 'OVO', 'DANA', 'ShopeePay'];

    public const NOTIF_CATEGORIES = [
        'bayar' => ['Pembayaran dan tagihan', 'Tagihan baru, batas bayar, dan status pembayaran'],
        'jadwal' => ['Jadwal antar dan ambil', 'Pengingat jadwal dan usulan jadwal baru dari admin'],
        'barang' => ['Status barang titipan', 'Barang diterima, koreksi data, dan akhir masa titip'],
        'preloved' => ['Toko Preloved', 'Status pembelian, penawaran harga, dan komplain'],
        'promo' => ['Info dan promo', 'Kabar layanan baru dan potongan harga'],
    ];

    public const FAQ = [
        ['Bagaimana cara menitipkan barang?', 'Buka Ruang Titip, pilih ruang, isi periode dan barang, lalu bayar tagihan dalam 24 jam. Setelah itu antar barang sesuai jadwal.'],
        ['Kapan sisa tagihan dibayar?', 'Sisa tagihan dilunasi dalam 24 jam sebelum jadwal ambil. Barang tidak bisa dikeluarkan sebelum lunas.'],
        ['Apakah isi kardus diperiksa?', 'Tidak. Ruang Titip memakai skema blind box. Petugas hanya mencatat jumlah, ukuran, kemasan, dan kondisi luar.'],
        ['Bagaimana menjual barang ke Ruang Titip?', 'Ajukan barang di Toko Preloved. Setelah lolos seleksi dan pemeriksaan, kamu menerima penawaran harga dan dibayar penuh kalau setuju.'],
        ['Bisakah orang lain mengambil barangku?', 'Bisa. Pilih pengambilan diwakilkan, isi data perwakilan, dan konfirmasi lewat website.'],
    ];

    public function __construct(private DistanceService $distance)
    {
    }

    /* ===== Ringkasan ===== */

    public function home()
    {
        $user = Auth::user();

        $steps = [
            ['Buat akun', true, null],
            ['Isi nama lengkap', mb_strlen(trim((string) $user->name)) >= 3, route('akun.profil')],
            ['Isi nomor WhatsApp', ! empty($user->phone), route('akun.profil')],
        ];

        $stats = $this->stats($user);
        $mainAddress = UserAddress::where('user_id', $user->id)->orderByDesc('is_primary')->orderByDesc('id')->first();
        $bankCount = UserBankAccount::where('user_id', $user->id)->count();

        return view('akun.home', [
            'user' => $user,
            'steps' => $steps,
            'done' => collect($steps)->where(1, true)->count(),
            'stats' => $stats,
            'mainAddress' => $mainAddress,
            'bankCount' => $bankCount,
        ]);
    }

    /* ===== Profil ===== */

    public function profil()
    {
        return view('akun.profil', ['user' => Auth::user()]);
    }

    public function saveProfil(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^(\+62|62|0)8\d{7,12}$/'],
            'campus' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'cohort_year' => 'nullable|digits:4',
            'hometown' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png|max:1024',
        ], [
            'name.required' => 'Isi nama lengkap.',
            'name.min' => 'Nama minimal 3 huruf.',
            'phone.regex' => 'Nomor tidak valid. Contoh: 081234567890.',
            'cohort_year.digits' => 'Angkatan harus 4 angka, misalnya 2022.',
            'avatar.image' => 'File harus berupa foto JPG atau PNG.',
            'avatar.max' => 'Ukuran foto maksimal 1 MB.',
        ]);

        $user = Auth::user();
        $attrs = collect($data)->except('avatar')->map(fn ($v) => $v === '' ? null : $v)->all();
        $attrs['phone'] = isset($attrs['phone']) ? preg_replace('/[\s-]/', '', $attrs['phone']) : null;

        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $attrs['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $attrs['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($attrs);

        $after = session()->pull('profile_redirect_after');
        if ($after && $user->name && $user->phone) {
            return redirect($after)->with('success', 'Profil tersimpan. Silakan lanjutkan pemesanan.');
        }

        return redirect()->route('akun.profil')->with('success', 'Perubahan profil tersimpan.');
    }

    /* ===== Alamat ===== */

    public function alamat(Request $request)
    {
        $addresses = UserAddress::where('user_id', Auth::id())->orderByDesc('is_primary')->orderByDesc('id')->get();
        $editing = null;

        if ($request->query('ubah')) {
            $editing = $addresses->firstWhere('id', (int) $request->query('ubah'));
        }

        return view('akun.alamat', [
            'user' => Auth::user(),
            'addresses' => $addresses,
            'editing' => $editing,
            'adding' => $request->boolean('baru') || $editing !== null,
        ]);
    }

    public function storeAlamat(Request $request)
    {
        $data = $this->validateAddress($request);
        $user = Auth::user();
        $first = UserAddress::where('user_id', $user->id)->doesntExist();
        $makeMain = $first || $request->boolean('is_primary');

        if ($makeMain) {
            UserAddress::where('user_id', $user->id)->update(['is_primary' => false]);
        }

        $addr = UserAddress::create($data + ['user_id' => $user->id, 'is_primary' => $makeMain]);
        $this->distance->ensureAddressCoords($addr);

        return redirect()->route('akun.alamat')->with('success', 'Alamat disimpan.');
    }

    public function updateAlamat(Request $request, int $id)
    {
        $addr = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $data = $this->validateAddress($request);

        if ($request->boolean('is_primary') && ! $addr->is_primary) {
            UserAddress::where('user_id', Auth::id())->update(['is_primary' => false]);
            $data['is_primary'] = true;
        }

        $addr->update($data);
        $this->distance->ensureAddressCoords($addr);

        return redirect()->route('akun.alamat')->with('success', 'Alamat diperbarui.');
    }

    public function mainAlamat(int $id)
    {
        $addr = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        UserAddress::where('user_id', Auth::id())->update(['is_primary' => false]);
        $addr->update(['is_primary' => true]);

        return redirect()->route('akun.alamat')->with('success', 'Alamat utama diganti.');
    }

    public function destroyAlamat(int $id)
    {
        $addr = UserAddress::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $wasMain = $addr->is_primary;
        $addr->delete();

        if ($wasMain) {
            UserAddress::where('user_id', Auth::id())->latest()->first()?->update(['is_primary' => true]);
        }

        return redirect()->route('akun.alamat')->with('success', 'Alamat dihapus.');
    }

    private function validateAddress(Request $request): array
    {
        $data = $request->validate([
            'label' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'area_id' => 'required|string',
            'area_name' => 'nullable|string',
            'postal_code' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ], [
            'label.required' => 'Isi label alamat, misalnya Rumah atau Kos.',
            'address.required' => 'Isi alamat lengkap.',
            'area_id.required' => 'Pilih kecamatan atau kota dari daftar saran.',
        ]);

        return collect($data)->only(['label', 'address', 'area_id', 'area_name', 'postal_code', 'latitude', 'longitude'])->all();
    }

    /* ===== Rekening ===== */

    public function rekening(Request $request)
    {
        $banks = UserBankAccount::where('user_id', Auth::id())->orderByDesc('is_primary')->orderByDesc('id')->get();
        $editing = $request->query('ubah') ? $banks->firstWhere('id', (int) $request->query('ubah')) : null;

        return view('akun.rekening', [
            'user' => Auth::user(),
            'banks' => $banks,
            'editing' => $editing,
            'adding' => $request->boolean('baru') || $editing !== null,
            'bankNames' => self::BANKS,
        ]);
    }

    public function storeRekening(Request $request)
    {
        $data = $this->validateBank($request);
        $first = UserBankAccount::where('user_id', Auth::id())->doesntExist();
        $makeMain = $first || $request->boolean('is_primary');

        if ($makeMain) {
            UserBankAccount::where('user_id', Auth::id())->update(['is_primary' => false]);
        }

        UserBankAccount::create($data + ['user_id' => Auth::id(), 'is_primary' => $makeMain]);

        return redirect()->route('akun.rekening')->with('success', 'Rekening disimpan.');
    }

    public function updateRekening(Request $request, int $id)
    {
        $bank = UserBankAccount::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $data = $this->validateBank($request);

        if ($request->boolean('is_primary') && ! $bank->is_primary) {
            UserBankAccount::where('user_id', Auth::id())->update(['is_primary' => false]);
            $data['is_primary'] = true;
        }

        $bank->update($data);

        return redirect()->route('akun.rekening')->with('success', 'Rekening diperbarui.');
    }

    public function mainRekening(int $id)
    {
        $bank = UserBankAccount::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        UserBankAccount::where('user_id', Auth::id())->update(['is_primary' => false]);
        $bank->update(['is_primary' => true]);

        return redirect()->route('akun.rekening')->with('success', 'Rekening utama diganti.');
    }

    public function destroyRekening(int $id)
    {
        $bank = UserBankAccount::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $wasMain = $bank->is_primary;
        $bank->delete();

        if ($wasMain) {
            UserBankAccount::where('user_id', Auth::id())->latest()->first()?->update(['is_primary' => true]);
        }

        return redirect()->route('akun.rekening')->with('success', 'Rekening dihapus.');
    }

    private function validateBank(Request $request): array
    {
        $data = $request->validate([
            'bank' => 'required|in:' . implode(',', self::BANKS),
            'account_number' => 'required|string|regex:/^[0-9]{5,20}$/',
            'holder' => 'required|string|max:100',
        ], [
            'bank.required' => 'Pilih bank atau e-wallet.',
            'bank.in' => 'Pilih bank atau e-wallet dari daftar.',
            'account_number.required' => 'Isi nomor rekening atau akun.',
            'account_number.regex' => 'Nomor hanya boleh angka, 5 sampai 20 digit.',
            'holder.required' => 'Isi nama pemilik rekening.',
        ]);

        return $data;
    }

    /* ===== Keamanan ===== */

    public function keamanan(Request $request)
    {
        $user = Auth::user();
        $stats = $this->stats($user);
        $unpaid = UserNotifications::needs($user)->where('pay', true)->count();

        $sessions = collect();
        if (config('session.driver') === 'database') {
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderByDesc('last_activity')
                ->get()
                ->map(fn ($s) => [
                    'id' => $s->id,
                    'device' => $this->deviceLabel((string) $s->user_agent),
                    'ip' => $s->ip_address,
                    'last' => \Illuminate\Support\Carbon::createFromTimestamp($s->last_activity),
                    'current' => $s->id === $request->session()->getId(),
                ]);
        }

        return view('akun.keamanan', [
            'user' => $user,
            'sessions' => $sessions,
            'activeTitip' => $stats['titip'],
            'unpaid' => $unpaid,
        ]);
    }

    public function revokeSession(Request $request, string $id)
    {
        abort_if($id === $request->session()->getId(), 422);

        DB::table('sessions')->where('id', $id)->where('user_id', Auth::id())->delete();

        return redirect()->route('akun.keamanan')->with('success', 'Perangkat dikeluarkan.');
    }

    public function revokeOtherSessions(Request $request)
    {
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return redirect()->route('akun.keamanan')->with('success', 'Semua perangkat lain dikeluarkan.');
    }

    public function destroyAccount(Request $request)
    {
        $user = Auth::user();
        $stats = $this->stats($user);
        $unpaid = UserNotifications::needs($user)->where('pay', true)->count();

        if ($stats['titip'] > 0 || $unpaid > 0) {
            return redirect()->route('akun.keamanan')->with('error', 'Akun belum bisa dihapus. Selesaikan dulu titipan dan tagihan yang masih berjalan.');
        }

        try {
            DB::transaction(function () use ($user) {
                UserAddress::where('user_id', $user->id)->delete();
                UserBankAccount::where('user_id', $user->id)->delete();
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->delete();
            });
        } catch (QueryException $e) {
            report($e);

            return redirect()->route('akun.keamanan')->with('error', 'Akun belum bisa dihapus karena masih punya riwayat pesanan. Hubungi admin untuk bantuan.');
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /* ===== Notifikasi ===== */

    public function notifikasi()
    {
        $user = Auth::user();
        $prefs = $user->notif_prefs ?? [];

        return view('akun.notifikasi', [
            'user' => $user,
            'categories' => self::NOTIF_CATEGORIES,
            'prefs' => $prefs,
        ]);
    }

    public function saveNotifikasi(Request $request)
    {
        $prefs = [];
        foreach (array_keys(self::NOTIF_CATEGORIES) as $key) {
            $prefs[$key] = [
                'email' => $request->boolean("n.$key.email"),
                'wa' => $request->boolean("n.$key.wa"),
            ];
        }

        Auth::user()->update(['notif_prefs' => $prefs]);

        return redirect()->route('akun.notifikasi')->with('success', 'Pengaturan notifikasi tersimpan.');
    }

    /* ===== Bantuan ===== */

    public function bantuan()
    {
        return view('akun.bantuan', [
            'user' => Auth::user(),
            'faq' => self::FAQ,
        ]);
    }

    /* ===== Util ===== */

    private function stats($user): array
    {
        return [
            'titip' => TitipanOrder::where('user_id', $user->id)->where('status', 'dalam_gudang')->count(),
            'beli' => Order::where('user_id', $user->id)->count(),
            'jual' => PrelovedSubmission::where('user_id', $user->id)->count(),
        ];
    }

    private function deviceLabel(string $ua): string
    {
        $browser = match (true) {
            str_contains($ua, 'Edg') => 'Edge',
            str_contains($ua, 'OPR') || str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'Chrome') => 'Chrome',
            str_contains($ua, 'Firefox') => 'Firefox',
            str_contains($ua, 'Safari') => 'Safari',
            default => 'Peramban',
        };

        $os = match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iPhone',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'Mac OS') => 'Mac',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'perangkat tidak dikenal',
        };

        return "$browser di $os";
    }
}

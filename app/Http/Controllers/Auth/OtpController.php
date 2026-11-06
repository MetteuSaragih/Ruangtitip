<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpController extends Controller
{
    /**
     * STEP 1 — Tampilkan halaman login (input email).
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * STEP 2 — Terima email, buat OTP, kirim ke email, lanjut ke form OTP.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $email = strtolower(trim($request->email));

        // Batasi permintaan kode per email (anti spam/bom email).
        $key = 'otp-send:' . $email;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withInput()->withErrors([
                'email' => 'Terlalu banyak permintaan kode. Coba lagi dalam ' . RateLimiter::availableIn($key) . ' detik.',
            ]);
        }
        RateLimiter::hit($key, 600);

        $otp = EmailOtp::generateFor($email);

        // Kirim email (driver "log" akan menulis ke storage/logs/laravel.log)
        Mail::to($email)->send(new OtpMail($otp->plain_code));

        // Simpan email & waktu kirim ke session untuk step berikutnya
        $request->session()->put('otp_email', $email);
        $request->session()->put('otp_last_sent', now()->timestamp);

        return redirect()->route('otp.form');
    }

    /**
     * STEP 3 — Tampilkan form input OTP. Wajib sudah ada email di session.
     */
    public function showOtp(Request $request)
    {
        $email = $request->session()->get('otp_email');

        if (! $email) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi habis. Silakan masukkan email lagi.']);
        }

        return view('auth.otp', ['email' => $email]);
    }

    /**
     * STEP 4 — Verifikasi OTP. Jika valid → cari/buat user → login.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'Kode OTP wajib diisi.',
            'code.digits'   => 'Kode OTP harus 6 digit.',
        ]);

        $email = $request->session()->get('otp_email');

        if (! $email) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi habis. Silakan masukkan email lagi.']);
        }

        $otp = EmailOtp::where('email', $email)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (! $otp || ! $otp->isUsable()) {
            return back()->withErrors(['code' => 'Kode sudah kedaluwarsa. Minta kode baru.']);
        }

        if (! $otp->matches((string) $request->code)) {
            $otp->increment('attempts');
            return back()->withErrors(['code' => 'Kode OTP salah. Coba lagi.']);
        }

        // Kode benar → tandai terpakai
        $otp->update(['used_at' => now()]);

        // Akun nonaktif tidak boleh masuk.
        $existing = User::where('email', $email)->first();
        if ($existing && $existing->is_active === false) {
            $request->session()->forget(['otp_email', 'otp_last_sent']);

            return redirect()->route('login')->withErrors([
                'email' => 'Akun kamu sedang dinonaktifkan. Hubungi tim RuangTitip lewat WhatsApp.',
            ]);
        }

        // AUTO-REGISTER: cari user, buat jika belum ada
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'              => null,            // diisi nanti di halaman profil
                'email_verified_at' => now(),
            ]
        );

        // Tandai email terverifikasi jika user lama belum
        if (is_null($user->email_verified_at)) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();
        $request->session()->forget(['otp_email', 'otp_last_sent']);

        $firstName = $user->name ? explode(' ', trim($user->name))[0] : explode('@', $email)[0];
        $request->session()->flash('welcome_name', $firstName);
        $request->session()->flash('welcome_type', $user->wasRecentlyCreated ? 'new' : 'returning');

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Tampilkan layar "Berhasil masuk" dulu, lalu lanjut ke tujuan semula.
        $intended = (string) $request->session()->pull('url.intended', '');
        $request->session()->put('login_done_to', $this->safeInternalUrl($intended) ?? route('dashboard'));

        return redirect()->route('login.done');
    }

    /**
     * Layar konfirmasi setelah login berhasil (OTP), lalu lanjut otomatis.
     */
    public function done(Request $request)
    {
        $to = $this->safeInternalUrl((string) $request->session()->get('login_done_to', '')) ?? route('dashboard');

        return view('auth.done', ['to' => $to]);
    }

    /**
     * Hanya terima URL internal (host sama) agar tidak jadi open redirect.
     */
    private function safeInternalUrl(string $url): ?string
    {
        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//') && ! str_contains($url, '\\')) {
            return url($url);
        }

        $host = parse_url($url, PHP_URL_HOST);
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if ($host && in_array($scheme, ['http', 'https'], true) && strcasecmp($host, $this->currentHost()) === 0) {
            return $url;
        }

        return null;
    }

    private function currentHost(): string
    {
        return (string) parse_url(url('/'), PHP_URL_HOST);
    }

    /**
     * Kirim ulang OTP — dibatasi 60 detik sekali.
     */
    public function resendOtp(Request $request)
    {
        $email = $request->session()->get('otp_email');

        if (! $email) {
            return redirect()->route('login');
        }

        $lastSent = $request->session()->get('otp_last_sent', 0);
        $elapsed  = now()->timestamp - $lastSent;

        if ($elapsed < 60) {
            return back()->withErrors([
                'code' => 'Tunggu ' . (60 - $elapsed) . ' detik sebelum kirim ulang.',
            ]);
        }

        $key = 'otp-send:' . $email;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'code' => 'Terlalu banyak permintaan kode. Coba lagi dalam ' . RateLimiter::availableIn($key) . ' detik.',
            ]);
        }
        RateLimiter::hit($key, 600);

        $otp = EmailOtp::generateFor($email);
        Mail::to($email)->send(new OtpMail($otp->plain_code));
        $request->session()->put('otp_last_sent', now()->timestamp);

        return back()->with('status', 'Kode baru sudah dikirim ke email kamu.');
    }

    /**
     * Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
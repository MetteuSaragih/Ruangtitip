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

        $otp = EmailOtp::generateFor($email);

        // Kirim email (driver "log" akan menulis ke storage/logs/laravel.log)
        Mail::to($email)->send(new OtpMail($otp->code));

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

        if ($otp->code !== $request->code) {
            $otp->increment('attempts');
            return back()->withErrors(['code' => 'Kode OTP salah. Coba lagi.']);
        }

        // Kode benar → tandai terpakai
        $otp->update(['used_at' => now()]);

        // AUTO-REGISTER: cari user, buat jika belum ada
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'              => null,            // diisi nanti di halaman profil
                'email_verified_at' => now(),
                'role'              => 'penitip',       // <-- SUDAH DIUBAH MENJADI 'penitip'
            ]
        );

        // Tandai email terverifikasi jika user lama belum
        if (is_null($user->email_verified_at)) {
            $user->update(['email_verified_at' => now()]);
        }

        Auth::login($user, remember: true);
        $request->session()->regenerate();
        $request->session()->forget(['otp_email', 'otp_last_sent']);

        // ─── PENAMBAHAN CEK ROLE DI SINI ───
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('dashboard'));
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

        $otp = EmailOtp::generateFor($email);
        Mail::to($email)->send(new OtpMail($otp->code));
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
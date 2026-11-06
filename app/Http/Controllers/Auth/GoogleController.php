<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return $this->fail('Gagal login dengan Google. Silakan coba lagi.');
        }

        $email = strtolower(trim((string) $googleUser->getEmail()));
        $googleId = (string) $googleUser->getId();

        // Hanya email yang sudah diverifikasi Google yang boleh dipakai masuk atau mendaftar.
        $raw = (array) ($googleUser->user ?? []);
        $verified = filter_var($raw['email_verified'] ?? $raw['verified_email'] ?? false, FILTER_VALIDATE_BOOLEAN);
        if ($email === '' || $googleId === '' || ! $verified) {
            return $this->fail('Email Google kamu belum terverifikasi, jadi belum bisa dipakai untuk masuk.');
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            if ($user->is_active === false) {
                return $this->fail('Akun kamu sedang dinonaktifkan. Hubungi tim RuangTitip lewat WhatsApp.');
            }
            // Email yang sama tetapi akun Google berbeda: tolak, supaya akun tidak bisa diambil alih.
            if ($user->google_id && (string) $user->google_id !== $googleId) {
                return $this->fail('Email ini sudah terhubung dengan akun Google yang lain. Masuk dengan kode OTP ke email kamu.');
            }

            // Lengkapi data yang masih kosong; nama dan foto yang sudah kamu atur sendiri tidak ditimpa.
            $user->google_id = $user->google_id ?: $googleId;
            $user->name = $user->name ?: $googleUser->getName();
            $user->avatar = $user->avatar ?: $googleUser->getAvatar();
            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
            }
            $user->save();
        } else {
            $user = User::create([
                'name'      => $googleUser->getName(),
                'email'     => $email,
                'google_id' => $googleId,
                'avatar'    => $googleUser->getAvatar(),
            ]);
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        Auth::login($user, remember: true);
        // Sesi baru setelah login: mencegah session fixation.
        request()->session()->regenerate();

        $firstName = $user->name ? explode(' ', trim($user->name))[0] : explode('@', $email)[0];
        session()->flash('welcome_name', $firstName);
        session()->flash('welcome_type', $user->wasRecentlyCreated ? 'new' : 'returning');

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    private function fail(string $message)
    {
        return redirect()->route('login')->withErrors(['email' => $message]);
    }
}

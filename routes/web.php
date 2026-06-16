
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes — RUTIP (Passwordless: OTP Email + Google OAuth)
|--------------------------------------------------------------------------
*/

// ─── PUBLIC ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('landing.index');
})->name('home');

// ─── GUEST ONLY ──────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {

    // Login via OTP email
    Route::get('/login', [OtpController::class, 'showLogin'])->name('login');
    Route::post('/login', [OtpController::class, 'sendOtp'])->name('otp.send');
    Route::get('/login/otp', [OtpController::class, 'showOtp'])->name('otp.form');
    Route::post('/login/otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/login/otp/resend', [OtpController::class, 'resendOtp'])->name('otp.resend');

    // Login via Google OAuth (asli)
    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
});

// ─── AUTHENTICATED ONLY ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [OtpController::class, 'logout'])->name('logout');
});


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\ProfileController;
/*
|--------------------------------------------------------------------------
| Web Routes — RUTIP (Passwordless: OTP Email + Google OAuth)
|--------------------------------------------------------------------------
*/

// ─── PUBLIC ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('landing.index');
})->name('home');

Route::middleware('auth')->prefix('dashboard/packing')->name('packing.')->group(function () {
    Route::get('/', [PackingController::class, 'index'])->name('index');
    Route::get('/produk/{product}', [PackingController::class, 'show'])->name('show');
    Route::post('/produk/{product}/beli', [PackingController::class, 'buy'])->name('buy');
    Route::get('/logistik',  [PackingController::class, 'logistics'])->name('logistics');
    Route::post('/logistik', [PackingController::class, 'chooseLogistics'])->name('logistics.choose');
    Route::get('/alamat',  [PackingController::class, 'address'])->name('address');
    Route::post('/alamat', [PackingController::class, 'chooseAddress'])->name('address.choose');
    Route::get('/pembayaran',  [PackingController::class, 'payment'])->name('payment');
    Route::post('/pembayaran', [PackingController::class, 'pay'])->name('pay');
    Route::get('/sukses/{orderCode}', [PackingController::class, 'success'])->name('success');
});
Route::post('/midtrans/notification', [PackingPaymentNotificationController::class, 'handle'])
    ->name('midtrans.notification');
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



// Pastikan route ini menggunakan middleware auth jika user harus login
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');
});
// ─── AUTHENTICATED ONLY ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [OtpController::class, 'logout'])->name('logout');
});

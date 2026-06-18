<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
<<<<<<< Updated upstream
use App\Http\Controllers\PackingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuangTitipController;
use App\Http\Controllers\PackingPaymentNotificationController;
use App\Http\Controllers\PesananController;
=======
use App\Http\Controllers\PrelovedController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
>>>>>>> Stashed changes

/*
|--------------------------------------------------------------------------
| Web Routes — RUTIP (Passwordless: OTP Email + Google OAuth)
|--------------------------------------------------------------------------
*/

// ─── PUBLIC ──────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('landing.index');
})->name('home');

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


// ─── AUTHENTICATED ONLY (General) ────────────────────────────────────────
Route::middleware('auth')->group(function () {
    
    // Dashboard & Logout
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [OtpController::class, 'logout'])->name('logout');

    // Profil
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');

    // Pesanan (Tambahan Baru)
    Route::get('/dashboard/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/dashboard/pesanan/{order}', [PesananController::class, 'show'])->name('pesanan.detail');
    
});

<<<<<<< Updated upstream

// ─── PACKING ─────────────────────────────────────────────────────────────
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


// ─── RUANG TITIP (alur 7 langkah) ───────────────────────────────────────
Route::middleware('auth')->prefix('dashboard/ruang-titip')->name('ruang-titip.')->group(function () {
    // Screen 1
    Route::get('/', [RuangTitipController::class, 'index'])->name('index');
    // Screen 2
    Route::get('/{storage}/detail', [RuangTitipController::class, 'show'])->name('detail');
    // Screen 3 — GABUNGAN item + tanggal
    Route::get('/detail-item', [RuangTitipController::class, 'detailForm'])->name('detail-item');
    Route::post('/detail-item', [RuangTitipController::class, 'detailStore'])->name('detail-item.store');
    // Screen 4 — Opsi Logistik
    Route::get('/logistik', [RuangTitipController::class, 'logistikForm'])->name('logistik');
    Route::post('/logistik', [RuangTitipController::class, 'logistikStore'])->name('logistik.store');
    // Screen 5 — Alamat
    Route::get('/alamat', [RuangTitipController::class, 'alamatForm'])->name('alamat');
    Route::post('/alamat', [RuangTitipController::class, 'alamatStore'])->name('alamat.store');
    // Screen 6 — Kurir (instant only)
    Route::get('/kurir', [RuangTitipController::class, 'kurirForm'])->name('kurir');
    Route::post('/kurir', [RuangTitipController::class, 'kurirStore'])->name('kurir.store');
    // Screen 7 — Checkout
    Route::get('/checkout', [RuangTitipController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [RuangTitipController::class, 'place'])->name('place');
    // Sukses
    Route::get('/sukses/{order}', [RuangTitipController::class, 'success'])->name('success');
=======
// Toko Preloved Routes
Route::prefix('toko-preloved')->name('preloved.')->group(function () {
    Route::get('/', [PrelovedController::class, 'index'])->name('index');
    Route::get('/produk/{id}', [PrelovedController::class, 'show'])->name('show');
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/keranjang/hapus', [CartController::class, 'remove'])->name('cart.remove');
});

// Checkout Routes
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/pengiriman', [CheckoutController::class, 'shipping'])->name('shipping');
    Route::post('/pengiriman/simpan', [CheckoutController::class, 'saveShipping'])->name('shipping.save');
    Route::get('/pembayaran', [CheckoutController::class, 'payment'])->name('payment');
    Route::post('/proses', [CheckoutController::class, 'process'])->name('process');
    Route::get('/berhasil/{orderId}', [CheckoutController::class, 'success'])->name('success');
});

// // Biteship API Routes
// Route::prefix('api/biteship')->name('biteship.')->group(function () {
//     Route::post('/rates', [BiteshipController::class, 'getRates'])->name('rates');
//     Route::post('/order', [BiteshipController::class, 'createOrder'])->name('order');
//     Route::get('/track/{trackingId}', [BiteshipController::class, 'track'])->name('track');
// });

// // Midtrans Payment Routes
// Route::prefix('payment')->name('payment.')->group(function () {
//     Route::post('/create-token', [PaymentController::class, 'createToken'])->name('create-token');
//     Route::post('/notification', [PaymentController::class, 'notification'])->name('notification');
//     Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
//     Route::get('/unfinish', [PaymentController::class, 'unfinish'])->name('unfinish');
//     Route::get('/error', [PaymentController::class, 'error'])->name('error');
// });

// Redirect root to preloved
Route::get('/', function () {
    return redirect()->route('preloved.index');
>>>>>>> Stashed changes
});
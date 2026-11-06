<?php

use App\Http\Controllers\Admin\AccountManagementController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RuangTitipController as AdminRuangTitipController;
use App\Http\Controllers\Admin\TokoPackingController;
use App\Http\Controllers\Admin\TokoPrelovedController;
use App\Http\Controllers\Api\BiteshipAreaController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\BiteshipWebhookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingSurveyController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PrelovedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuangTitipController as UserRuangTitipController;
use App\Http\Controllers\TripayCallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('landing.index'))->name('home');
Route::post('/survei', [LandingSurveyController::class, 'store'])->middleware('throttle:10,1')->name('survey.store');
Route::get('/syarat-ketentuan', fn () => view('legal.syarat-ketentuan'))->name('legal.terms');
Route::get('/kebijakan-privasi', fn () => view('legal.kebijakan-privasi'))->name('legal.privacy');
Route::post('/tripay/callback', [TripayCallbackController::class, 'handle'])
    ->name('tripay.callback');
Route::post('/biteship/webhook', [BiteshipWebhookController::class, 'handle'])
    ->name('biteship.webhook');

/*
|--------------------------------------------------------------------------
| Guest routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [OtpController::class, 'showLogin'])->name('login');
    Route::post('/login', [OtpController::class, 'sendOtp'])->middleware('throttle:otp-send')->name('otp.send');
    Route::get('/login/otp', [OtpController::class, 'showOtp'])->name('otp.form');
    Route::post('/login/otp', [OtpController::class, 'verifyOtp'])->middleware('throttle:otp-verify')->name('otp.verify');
    Route::post('/login/otp/resend', [OtpController::class, 'resendOtp'])->middleware('throttle:otp-send')->name('otp.resend');

    Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('auth.google.callback');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [OtpController::class, 'logout'])->name('logout');
    Route::get('/login/berhasil', [OtpController::class, 'done'])->name('login.done');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifikasi', [DashboardController::class, 'notifications'])->name('notifikasi.index');
    Route::post('/notifikasi/baca', [DashboardController::class, 'markNotificationsRead'])->name('notifikasi.read');
    // --- Akun saya (ringkasan, profil, alamat, rekening, keamanan, notifikasi, bantuan) ---
    Route::get('/akun', [\App\Http\Controllers\AccountController::class, 'home'])->name('profile.index');
    Route::redirect('/profil', '/akun');
    Route::prefix('akun')->name('akun.')->controller(\App\Http\Controllers\AccountController::class)->group(function () {
        Route::get('/profil', 'profil')->name('profil');
        Route::post('/profil', 'saveProfil')->name('profil.save');
        Route::get('/alamat', 'alamat')->name('alamat');
        Route::post('/alamat', 'storeAlamat')->name('alamat.store');
        Route::put('/alamat/{id}', 'updateAlamat')->name('alamat.update');
        Route::post('/alamat/{id}/utama', 'mainAlamat')->name('alamat.main');
        Route::delete('/alamat/{id}', 'destroyAlamat')->name('alamat.destroy');
        Route::get('/rekening', 'rekening')->name('rekening');
        Route::post('/rekening', 'storeRekening')->name('rekening.store');
        Route::put('/rekening/{id}', 'updateRekening')->name('rekening.update');
        Route::post('/rekening/{id}/utama', 'mainRekening')->name('rekening.main');
        Route::delete('/rekening/{id}', 'destroyRekening')->name('rekening.destroy');
        Route::get('/keamanan', 'keamanan')->name('keamanan');
        Route::delete('/keamanan/perangkat', 'revokeOtherSessions')->name('sesi.others');
        Route::delete('/keamanan/perangkat/{id}', 'revokeSession')->name('sesi.revoke');
        Route::delete('/keamanan/akun', 'destroyAccount')->name('hapus');
        Route::get('/notifikasi', 'notifikasi')->name('notifikasi');
        Route::post('/notifikasi', 'saveNotifikasi')->name('notifikasi.save');
        Route::get('/bantuan', 'bantuan')->name('bantuan');
    });
    // --- akhir blok akun ---
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profil/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::post('/profil/lewati-banner', [ProfileController::class, 'dismissCompleteBanner'])->name('profile.dismiss-banner');
    Route::post('/profil/alamat', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::delete('/profil/alamat/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');
    Route::post('/profil/alamat/{id}/primary', [ProfileController::class, 'setPrimaryAddress'])->name('profile.address.primary');

    Route::get('/dashboard/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/dashboard/pesanan/{order}', [PesananController::class, 'show'])->name('pesanan.detail');

    // --- Permintaan atas titipan di gudang: tambah periode, kirim barang, ambil di gudang ---
    Route::post('/dashboard/pesanan/{order}/perpanjang', [\App\Http\Controllers\PesananRequestController::class, 'perpanjang'])->name('pesanan.request.perpanjang');
    Route::post('/dashboard/pesanan/{order}/kirim', [\App\Http\Controllers\PesananRequestController::class, 'kirim'])->name('pesanan.request.kirim');
    Route::post('/dashboard/pesanan/{order}/ambil', [\App\Http\Controllers\PesananRequestController::class, 'ambil'])->name('pesanan.request.ambil');
    // --- akhir blok permintaan titipan ---

    // --- Jual barang (pengajuan preloved) dan Pembelian Saya ---
    Route::get('/jual/baru', [\App\Http\Controllers\PrelovedSellController::class, 'create'])->name('sell.create');
    Route::post('/jual', [\App\Http\Controllers\PrelovedSellController::class, 'store'])->name('sell.store');
    Route::get('/jual/{submission}', [\App\Http\Controllers\PrelovedSellController::class, 'show'])->name('sell.show');
    Route::post('/jual/{submission}/jadwal', [\App\Http\Controllers\PrelovedSellController::class, 'schedule'])->name('sell.schedule');
    Route::post('/jual/{submission}/tawaran', [\App\Http\Controllers\PrelovedSellController::class, 'offer'])->name('sell.offer');
    Route::get('/pembelian', [\App\Http\Controllers\PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/pembelian/{order}', [\App\Http\Controllers\PurchaseController::class, 'show'])->name('purchases.show');
    // --- akhir blok jual dan pembelian ---

    Route::get('/api/biteship/areas', [BiteshipAreaController::class, 'search'])->middleware('throttle:60,1')->name('api.biteship.areas');
    // Pembayaran QRIS manual: unggah bukti (pemilik) dan lihat bukti (pemilik atau admin).
    Route::post('/pembayaran/{type}/{id}/bukti', [\App\Http\Controllers\PaymentProofController::class, 'store'])
        ->whereIn('type', ['titip', 'preloved', 'packing'])->whereNumber('id')
        ->middleware('throttle:20,1')->name('payment.proof.store');
    Route::get('/pembayaran/{type}/{id}/bukti', [\App\Http\Controllers\PaymentProofController::class, 'show'])
        ->whereIn('type', ['titip', 'preloved', 'packing'])->whereNumber('id')
        ->name('payment.proof.show');

    Route::prefix('dashboard/packing')->name('packing.')->group(function () {
        Route::get('/', [PackingController::class, 'index'])->name('index');
        Route::get('/produk/{product}', [PackingController::class, 'show'])->name('show');
        Route::post('/produk/{product}/beli', [PackingController::class, 'buy'])->name('buy');
        Route::get('/logistik', [PackingController::class, 'logistics'])->name('logistics')->middleware('profile.complete');
        Route::post('/logistik', [PackingController::class, 'chooseLogistics'])->name('logistics.choose');
        Route::get('/alamat', [PackingController::class, 'address'])->name('address');
        Route::post('/alamat', [PackingController::class, 'saveAddress'])->name('address.save');
        Route::get('/kurir', [PackingController::class, 'courier'])->name('courier');
        Route::post('/kurir/ongkir', [PackingController::class, 'courierRates'])->name('courier.rates');
        Route::post('/kurir', [PackingController::class, 'chooseCourier'])->name('courier.choose');
        Route::get('/pembayaran', [PackingController::class, 'payment'])->name('payment');
        Route::post('/pembayaran', [PackingController::class, 'pay'])->name('pay');
        Route::get('/sukses/{orderCode}', [PackingController::class, 'success'])->name('success');
    });

    Route::prefix('dashboard/ruang-titip')->name('ruang-titip.')->group(function () {
        Route::get('/', [UserRuangTitipController::class, 'index'])->name('index');
        Route::get('/{storage}/detail', [UserRuangTitipController::class, 'show'])->name('detail');
        Route::get('/detail-item', [UserRuangTitipController::class, 'detailForm'])->name('detail-item');
        Route::post('/detail-item', [UserRuangTitipController::class, 'detailStore'])->name('detail-item.store');
        Route::get('/logistik', [UserRuangTitipController::class, 'logistikForm'])->name('logistik')->middleware('profile.complete');
        Route::post('/logistik', [UserRuangTitipController::class, 'logistikStore'])->name('logistik.store');
        Route::get('/alamat', [UserRuangTitipController::class, 'alamatForm'])->name('alamat');
        Route::post('/alamat', [UserRuangTitipController::class, 'alamatStore'])->name('alamat.store');
        Route::get('/kurir', [UserRuangTitipController::class, 'kurirForm'])->name('kurir');
        Route::post('/kurir/ongkir', [UserRuangTitipController::class, 'kurirRates'])->name('kurir.rates');
        Route::post('/kurir', [UserRuangTitipController::class, 'kurirStore'])->name('kurir.store');
        Route::get('/checkout', [UserRuangTitipController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [UserRuangTitipController::class, 'place'])->name('place');
        Route::get('/sukses/{order}', [UserRuangTitipController::class, 'success'])->name('success');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        // Route admin per area (routes/admin/*.php) dimuat di sini, nama otomatis berawalan admin.
        foreach (glob(base_path('routes/admin/*.php')) as $adminRoutes) {
            require $adminRoutes;
        }

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::post('/profil/update', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');

        Route::get('/ruang-titip', [AdminRuangTitipController::class, 'index'])->name('ruang-titip');
        Route::post('/ruang-titip', [AdminRuangTitipController::class, 'store'])->name('ruang-titip.store');
        Route::put('/ruang-titip/{room}', [AdminRuangTitipController::class, 'update'])->name('ruang-titip.update');
        Route::delete('/ruang-titip/{room}', [AdminRuangTitipController::class, 'destroy'])->name('ruang-titip.destroy');
        Route::post('/ruang-titip/{room}/toggle-active', [AdminRuangTitipController::class, 'toggleActive'])
            ->name('ruang-titip.toggle-active');
        Route::post('/ruang-titip/orders/{order}/proof', [AdminRuangTitipController::class, 'uploadProof'])
            ->name('ruang-titip.orders.proof');
        Route::post('/ruang-titip/orders/{order}/status', [AdminRuangTitipController::class, 'updateStatus'])
            ->name('ruang-titip.orders.status');

        Route::get('/preloved', [TokoPrelovedController::class, 'index'])->name('preloved');
        Route::post('/preloved', [TokoPrelovedController::class, 'store'])->name('preloved.store');
        Route::put('/preloved/{item}', [TokoPrelovedController::class, 'update'])->name('preloved.update');
        Route::delete('/preloved/{item}', [TokoPrelovedController::class, 'destroy'])->name('preloved.destroy');
        Route::post('/preloved/{item}/toggle-draft', [TokoPrelovedController::class, 'toggleDraft'])
            ->name('preloved.toggle-draft');
        Route::post('/preloved/orders/{order}/advance', [TokoPrelovedController::class, 'advanceOrder'])
            ->name('preloved.advance-order');

        Route::get('/toko-packing', [TokoPackingController::class, 'index'])->name('packing.index');
        Route::post('/toko-packing', [TokoPackingController::class, 'store'])->name('packing.store');
        Route::put('/toko-packing/{id}', [TokoPackingController::class, 'update'])->name('packing.update');
        Route::delete('/toko-packing/{id}', [TokoPackingController::class, 'destroy'])->name('packing.destroy');
        Route::post('/toko-packing/orders/{id}/status', [TokoPackingController::class, 'updateOrderStatus'])->name('packing.order.status');

        Route::get('/accounts', [AccountManagementController::class, 'index'])->name('accounts');
        Route::post('/accounts/{user}/toggle-active', [AccountManagementController::class, 'toggleActive'])
            ->name('accounts.toggle-active');
    });
});

/*
|--------------------------------------------------------------------------
| Toko preloved routes
|--------------------------------------------------------------------------
*/

Route::prefix('toko-preloved')->name('preloved.')->group(function () {
    Route::get('/', [PrelovedController::class, 'index'])->name('index');
    Route::get('/cara-jual', [PrelovedController::class, 'caraJual'])->name('cara-jual');
    Route::get('/produk/{id}', [PrelovedController::class, 'show'])->name('show');
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/keranjang/hapus', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/keranjang/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/pengiriman', [CheckoutController::class, 'shipping'])->name('shipping')->middleware('profile.complete');
    Route::post('/pengiriman/pilih', [CheckoutController::class, 'chooseShipping'])->name('shipping.choose');
    Route::get('/alamat', [CheckoutController::class, 'address'])->name('address');
    Route::post('/alamat/simpan', [CheckoutController::class, 'saveAddress'])->name('address.save');
    Route::get('/kurir', [CheckoutController::class, 'courier'])->name('courier');
    Route::post('/kurir/ongkir', [CheckoutController::class, 'courierRates'])->name('courier.rates');
    Route::post('/kurir/pilih', [CheckoutController::class, 'chooseCourier'])->name('courier.choose');
    Route::get('/pembayaran', [CheckoutController::class, 'payment'])->name('payment');
    Route::post('/proses', [CheckoutController::class, 'process'])->name('process');
    Route::get('/berhasil/{orderId}', [CheckoutController::class, 'success'])->name('success');
});

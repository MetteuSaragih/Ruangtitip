<?php

/*
|--------------------------------------------------------------------------
| ROUTES TOKO PACKING — tempel ke dalam grup Route::middleware('auth')
| di file routes/web.php (setelah route /dashboard).
|--------------------------------------------------------------------------
|
| Jangan lupa import controller di bagian atas routes/web.php:
|   use App\Http\Controllers\PackingController;
|
*/

use App\Http\Controllers\PackingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('dashboard/packing')->name('packing.')->group(function () {
    // Katalog & detail
    Route::get('/', [PackingController::class, 'index'])->name('index');
    Route::get('/produk/{product}', [PackingController::class, 'show'])->name('show');

    // Mulai checkout (Beli Sekarang)
    Route::post('/produk/{product}/beli', [PackingController::class, 'buy'])->name('buy');

    // Alur checkout
    Route::get('/logistik',  [PackingController::class, 'logistics'])->name('logistics');
    Route::post('/logistik', [PackingController::class, 'chooseLogistics'])->name('logistics.choose');

    Route::get('/alamat',  [PackingController::class, 'address'])->name('address');
    Route::post('/alamat', [PackingController::class, 'chooseAddress'])->name('address.choose');

    Route::get('/pembayaran',  [PackingController::class, 'payment'])->name('payment');
    Route::post('/pembayaran', [PackingController::class, 'pay'])->name('pay');

    Route::get('/sukses/{orderCode}', [PackingController::class, 'success'])->name('success');
});

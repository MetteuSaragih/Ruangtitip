<?php

// Route admin area preloved. Dimuat di dalam grup prefix("admin")->name("admin.")->middleware("admin") di routes/web.php.

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\PrelovedSubmissionController;
use App\Http\Controllers\Admin\TokoPrelovedController;

// Pesanan Toko Preloved (path dibedakan dari /preloved milik katalog).
Route::get('/pesanan-preloved', [TokoPrelovedController::class, 'orders'])->name('preloved.orders');

// Pengajuan jual barang dari pelanggan.
Route::get('/jual', [PrelovedSubmissionController::class, 'index'])->name('jual.index');
Route::get('/jual/{submission}', [PrelovedSubmissionController::class, 'show'])->name('jual.show');
Route::post('/jual/{submission}/aksi', [PrelovedSubmissionController::class, 'act'])->name('jual.act');

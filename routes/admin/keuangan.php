<?php

// Route admin area keuangan. Dimuat di dalam grup prefix("admin")->name("admin.")->middleware("admin") di routes/web.php.

use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\TagihanController;
use Illuminate\Support\Facades\Route;

Route::get('/tagihan', [TagihanController::class, 'index'])->name('tagihan.index');
Route::get('/tagihan/unduh', [TagihanController::class, 'csv'])->name('tagihan.csv');
Route::get('/tagihan/{type}/{id}', [TagihanController::class, 'show'])
    ->whereIn('type', ['titip', 'preloved', 'packing'])
    ->whereNumber('id')
    ->name('tagihan.show');

Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
Route::get('/laporan/unduh', [LaporanController::class, 'csv'])->name('laporan.csv');

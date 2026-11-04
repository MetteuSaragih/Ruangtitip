<?php

// Route admin area titip. Dimuat di dalam grup prefix("admin")->name("admin.")->middleware("admin") di routes/web.php.

use App\Http\Controllers\Admin\RuangTitipController;
use App\Http\Controllers\Admin\TitipanOrderController;
use Illuminate\Support\Facades\Route;

// Harga per ukuran (S, M, L, XL), berlaku di semua ruang.
Route::post('/ruang-titip/sizes', [RuangTitipController::class, 'updateSizes'])->name('ruang-titip.sizes');

Route::get('/titip', [TitipanOrderController::class, 'index'])->name('titip.index');
Route::get('/titip/{order}', [TitipanOrderController::class, 'show'])->name('titip.show');

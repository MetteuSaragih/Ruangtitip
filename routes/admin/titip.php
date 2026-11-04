<?php

// Route admin area titip. Dimuat di dalam grup prefix("admin")->name("admin.")->middleware("admin") di routes/web.php.

use App\Http\Controllers\Admin\TitipanOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/titip', [TitipanOrderController::class, 'index'])->name('titip.index');
Route::get('/titip/{order}', [TitipanOrderController::class, 'show'])->name('titip.show');

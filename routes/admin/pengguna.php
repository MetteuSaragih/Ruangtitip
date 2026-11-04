<?php

// Route admin area pengguna. Dimuat di dalam grup prefix("admin")->name("admin.")->middleware("admin") di routes/web.php.

use App\Http\Controllers\Admin\AccountManagementController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TitipanRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/permintaan', [TitipanRequestController::class, 'index'])->name('permintaan.index');
Route::post('/permintaan/{titipanRequest}', [TitipanRequestController::class, 'update'])->name('permintaan.update');

Route::get('/accounts/{user}', [AccountManagementController::class, 'show'])->whereNumber('user')->name('accounts.show');

Route::get('/tim', [TeamController::class, 'index'])->name('tim');
Route::post('/tim', [TeamController::class, 'store'])->name('tim.store');
Route::post('/tim/{user}/cabut', [TeamController::class, 'revoke'])->whereNumber('user')->name('tim.revoke');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TargetTabunganController;
use App\Http\Controllers\TransaksiCelenganController;
use App\Http\Controllers\LaporanController;

// Auth Routes
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Harus Login)
Route::middleware(['auth'])->group(function () {
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola Target Impian
    Route::resource('target', TargetTabunganController::class)->except(['create', 'edit']);
    Route::post('/target/{id}/complete', [TargetTabunganController::class, 'markCompleted'])->name('target.complete');

    // Mutasi & Transaksi Celengan
    Route::get('/transaksi', [TransaksiCelenganController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi', [TransaksiCelenganController::class, 'store'])->name('transaksi.store');
    Route::delete('/transaksi/{id}', [TransaksiCelenganController::class, 'destroy'])->name('transaksi.destroy');

    // Rekapitulasi Laporan & Export
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
});

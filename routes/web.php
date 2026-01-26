<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KayuController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::prefix('/dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('transaksi')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi');
        Route::post('/', [TransaksiController::class, 'store'])->name('transaksi.store');
        Route::get('print', [TransaksiController::class, 'printFilter'])
            ->name('transaksi.print.filter');
        Route::get('print/{id}', [TransaksiController::class, 'printById'])
            ->name('transaksi.print.id');
        Route::put('/{id}', [TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    });


    Route::prefix('kayu')->group(function () {
        Route::get('/', [KayuController::class, 'index'])->name('kayu');
        Route::get('/print', [KayuController::class, 'print'])->name('kayu.print');
        Route::post('/', [KayuController::class, 'store'])->name('kayu.store');
        Route::put('/{id}', [KayuController::class, 'update'])->name('kayu.update');
        Route::delete('/{id}', [KayuController::class, 'destroy'])->name('kayu.destroy');
    });

    Route::get('/ajax/kayu-by-cabang/{cabang}', function ($cabang) {
        return \App\Models\DataKayu::where('cabang_id', $cabang)
            ->orderBy('jenis_kayu')
            ->get(['id', 'jenis_kayu', 'harga_satuan', 'jumlah', 'cabang_id']);
    })->name('ajax.kayu.by.cabang');



    Route::middleware('role:superadmin')->group(function () {
        Route::prefix('cabang')->group(function () {
            Route::get('/', [CabangController::class, 'index'])->name('cabang');
            Route::post('/', [CabangController::class, 'store'])->name('cabang.store');
            Route::put('{id}', [CabangController::class, 'update'])->name('cabang.update');
            Route::delete('{id}', [CabangController::class, 'destroy'])->name('cabang.destroy');
        });

        Route::prefix('pengguna')->group(function () {
            Route::get('/', [PenggunaController::class, 'index'])->name('pengguna');
            Route::post('/', [PenggunaController::class, 'store'])->name('pengguna.store');
            Route::put('{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
            Route::delete('{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
        });
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', function () {
            return 'HALAMAN ADMIN';
        });
    });
});

<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/transaksi', function () {
        return view('pages.transaksi');
    })->name('transaksi');

    Route::get('/kayu', function () {
        return view('pages.transaksi');
    })->name('kayu');



    Route::middleware('role:superadmin')->group(function () {
        Route::get('/superadmin', function () {
            return 'HALAMAN SUPERADMIN';
        });
        Route::get('/cabang', function () {
            return view('pages.transaksi');
        })->name('cabang');

        Route::get('/pengguna', function () {
            return view('pages.transaksi');
        })->name('pengguna');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', function () {
            return 'HALAMAN ADMIN';
        });
    });
});

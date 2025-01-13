<?php

use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanBarangController;
use App\Http\Controllers\BarangInventarisController;
use App\Http\Controllers\JenisBarangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|-----------------------------------------------------------------------
| API Routes
|-----------------------------------------------------------------------
*/

// Endpoint untuk login
Route::post('login', [AuthController::class, 'login']);

// Gunakan middleware auth:sanctum untuk mengakses rute berikutnya
Route::middleware('auth:sanctum')->group(function () {
    // Rute untuk semua peran
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rute yang hanya bisa diakses oleh super
    Route::middleware('role:super')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index']);
        Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy']);
    });

    // Rute yang hanya bisa diakses oleh admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/barang-inventaris', [BarangInventarisController::class, 'store']);
        Route::post('/jenis-barang', [JenisBarangController::class, 'store']);
    });

    // Rute yang hanya bisa diakses oleh user
    Route::middleware('role:user')->group(function () {
        Route::post('/peminjaman', [PeminjamanController::class, 'store']);
        Route::post('/peminjaman-barang', [PeminjamanBarangController::class, 'store']);
    });
});


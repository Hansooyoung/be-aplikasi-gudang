<?php

use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanBarangController;
use App\Http\Controllers\BarangInventarisController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\VendorController;
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
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

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
        // CRUD barang inventaris
        Route::post('/barang-inventaris', [BarangInventarisController::class, 'store']); // Create
        Route::get('/barang-inventaris', [BarangInventarisController::class, 'index']); // Read (List)
        Route::get('/barang-inventaris/{kode}', [BarangInventarisController::class, 'show']); // Read (Detail)
        Route::put('/barang-inventaris/{kode}', [BarangInventarisController::class, 'update']); // Update
        Route::delete('/barang-inventaris/{kode}', [BarangInventarisController::class, 'destroy']); // Delete

        //CRUD jenis barang
        Route::post('/jenis-barang', [JenisBarangController::class, 'store']); // Create
        Route::get('/jenis-barang', [JenisBarangController::class, 'index']); // Read (List)
        Route::get('/jenis-barang/{kode}', [JenisBarangController::class, 'show']); // Read (Detail)
        Route::put('/jenis-barang/{kode}', [JenisBarangController::class, 'update']); // Update
        Route::delete('/jenis-barang/{kode}', [JenisBarangController::class, 'destroy']); // Delete

        //crud vendor
        Route::get('/vendor', [VendorController::class, 'index']); // Read (List)
        Route::get('/vendor/{id}', [VendorController::class, 'show']); // Read (Detail)
        Route::put('/vendor/{id}', [VendorController::class, 'update']); // Update
        Route::delete('/vendor/{id}', [VendorController::class, 'destroy']); // Delete

    });

    // Rute yang hanya bisa diakses oleh user
    Route::middleware('role:user')->group(function () {
        Route::post('/peminjaman', [PeminjamanController::class, 'store']);
        Route::post('/peminjaman-barang', [PeminjamanBarangController::class, 'store']);
    });
});


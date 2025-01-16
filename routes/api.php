<?php

use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PeminjamanBarangController;
use App\Http\Controllers\BarangInventarisController;
use App\Http\Controllers\JenisBarangController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\SiswaController;
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
Route::post('/create', [AuthController::class, 'store']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Gunakan middleware auth:sanctum untuk mengakses rute berikutnya
Route::middleware('auth:sanctum')->group(function () {
    // Rute untuk semua peran
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    // Rute yang hanya bisa diakses oleh admin
    Route::middleware('role:admin')->group(function () {
        // CRUD barang inventaris
        Route::get('/barang-inventaris', [BarangInventarisController::class, 'index']);
        Route::get('/barang-inventaris/{kode}', [BarangInventarisController::class, 'show']);
        Route::post('/barang-inventaris', [BarangInventarisController::class, 'store']);
        Route::put('/barang-inventaris/{kode}', [BarangInventarisController::class, 'update']);
        Route::delete('/barang-inventaris/{kode}', [BarangInventarisController::class, 'destroy']);
        Route::get('/barang-inventaris/trashed', [BarangInventarisController::class, 'trashed']);
        Route::post('/barang-inventaris/restore/{kode}', [BarangInventarisController::class, 'restore']);

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

        //crud kelas
        Route::get('/kelas', [KelasController::class, 'index']);
        Route::post('/kelas', [KelasController::class, 'store']);
        Route::get('/kelas/{id}', [KelasController::class, 'show']);
        Route::put('/kelas/{id}', [KelasController::class, 'update']);
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy']);

        //crud jurusan
        Route::get('/jurusan', [JurusanController::class, 'index']);
        Route::post('/jurusan', [JurusanController::class, 'store']);
        Route::get('/jurusan/{id}', [JurusanController::class, 'show']);
        Route::put('/jurusan/{id}', [JurusanController::class, 'update']);
        Route::delete('/jurusan/{id}', [JurusanController::class, 'destroy']);

        //crud siswa
        Route::get('/siswa', [SiswaController::class, 'index']);
        Route::get('/siswa/{nisn}', [SiswaController::class, 'show']);
        Route::post('/siswa', [SiswaController::class, 'store']);
        Route::put('/siswa/{nisn}', [SiswaController::class, 'update']);
        Route::delete('/siswa/{nisn}', [SiswaController::class, 'destroy']);
        Route::post('/siswa/restore/{nisn}', [SiswaController::class, 'restore']);
        Route::get('/siswa/trashed', [SiswaController::class, 'trashed']);

        Route::post('/peminjaman', [PeminjamanController::class, 'store']); // Menyimpan peminjaman
        Route::get('/peminjaman', [PeminjamanController::class, 'getPeminjaman']); // Mendapatkan daftar peminjaman dengan filter
        Route::get('/peminjaman/{id}', [PeminjamanController::class, 'showPeminjaman']); // Menampilkan detail peminjaman

        // Pengembalian Routes
        Route::put('/pengembalian/{pengembalianId}', [PeminjamanController::class, 'updatePengembalian']); // Mengupdate pengembalian
        Route::get('/pengembalian', [PeminjamanController::class, 'getPengembalian']); // Mendapatkan daftar pengembalian
        Route::get('/pengembalian/{id}', [PeminjamanController::class, 'showPengembalian']); // Menampilkan detail pengembalian

    });

    // Rute yang hanya bisa diakses oleh user
    Route::middleware('role:user')->group(function () {

    });
});


<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBarang;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class PeminjamanBarangController extends Controller
{
    /**
     * Menyimpan peminjaman barang baru ke dalam database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|string|max:20|exists:peminjaman,id',
            'kode_barang' => 'required|string|max:20|exists:barang_inventaris,kode_barang',
        ]);

        $tahunSekarang = Carbon::now()->year;
        $bulanSekarang = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);

        $noUrutTerakhir = PeminjamanBarang::where('id', 'like', 'PJD' . $tahunSekarang . $bulanSekarang . '%')
            ->max(DB::raw("CAST(SUBSTRING(id, 10) AS UNSIGNED)")) ?? 0;

        $noUrutBaru = str_pad($noUrutTerakhir + 1, 3, '0', STR_PAD_LEFT);
        $idPeminjamanBarang = 'PJD' . $tahunSekarang . $bulanSekarang . $noUrutBaru;

        $peminjamanBarang = PeminjamanBarang::create([
            'id' => $idPeminjamanBarang,
            'peminjaman_id' => $request->peminjaman_id,
            'kode_barang' => $request->kode_barang,
            'tanggal_peminjaman' => Carbon::now(),
            'status' => 1,
        ]);

        return response()->json(['message' => 'Peminjaman barang berhasil dibuat.', 'data' => $peminjamanBarang], Response::HTTP_CREATED);
    }

    /**
     * Memperbarui status peminjaman barang.
     */
// PeminjamanBarangController.php

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0', // Status 0 = dikembalikan, 1 = dipinjam
        ]);

        $peminjamanBarang = PeminjamanBarang::find($id);

        if (!$peminjamanBarang) {
            return response()->json(['message' => 'Peminjaman barang tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        if ($peminjamanBarang->status == 0 && $request->status == 1) {
            return response()->json(['message' => 'Barang sudah dikembalikan dan tidak bisa diperbarui ke status dipinjam.'], Response::HTTP_BAD_REQUEST);
        }

        // Perbarui status peminjaman barang
        $peminjamanBarang->status = $request->status;
        $peminjamanBarang->save();

        return response()->json(['message' => 'Status peminjaman barang berhasil diperbarui.', 'data' => $peminjamanBarang], Response::HTTP_OK);
    }

}

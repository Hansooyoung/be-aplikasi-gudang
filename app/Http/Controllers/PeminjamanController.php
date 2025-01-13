<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\PeminjamanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Generate pbd_id (ID detail peminjaman) baru.
     */
    public static function generatePbdId($id)
    {
        $maxUrut = DB::table('peminjaman_barang')
            ->select(DB::raw("IFNULL(MAX(CAST(SUBSTRING(id, -3) AS UNSIGNED)), 0) + 1 AS next_urut"))
            ->where('id', 'like', "{$id}%")
            ->value('next_urut');

        return sprintf("%s%03d", $id, $maxUrut);
    }


    /**
     * Menyimpan peminjaman baru beserta detail barang ke dalam database.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'no_siswa' => 'required|string|max:20',
                'nama_siswa' => 'required|string|max:50',
                'status' => 'required|string|max:2',
                'kode_barang' => 'required|array',
                'kode_barang.*' => 'required|string|max:20',
            ]);

            $tanggalPeminjaman = Carbon::now();
            $harusKembaliTanggal = $tanggalPeminjaman->copy()->addDays(7);

            $user = Auth::user();
            $userId = $user->id;
            $id = self::generatePbId();

            $peminjaman = Peminjaman::create([
                'id' => $id,
                'user_id' => $userId,
                'no_siswa' => $validatedData['no_siswa'],
                'nama_siswa' => $validatedData['nama_siswa'],
                'harus_kembali_tanggal' => $harusKembaliTanggal,
                'status' => $validatedData['status'],
            ]);

            foreach ($validatedData['kode_barang'] as $kode_barang) {
                PeminjamanBarang::create([
                    'id' => self::generatePbdId($id),
                    'peminjaman_id' => $id,
                    'kode_barang' => $kode_barang,
                    'tanggal_peminjaman' => $tanggalPeminjaman,
                    'status' => '0',
                ]);
            }

            return response()->json(['peminjaman' => $peminjaman], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan data.', 'message' => $e->getMessage()], 500);
        }
    }



    /**
     * Menampilkan detail peminjaman beserta barang yang dipinjam.
     */
    public function show($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman')->find($id);

        if (!$peminjaman) {
            return response()->json(['message' => 'Peminjaman tidak ditemukan.'], 404);
        }

        return response()->json(['peminjaman' => $peminjaman], 200);
    }

    /**
     * Mengupdate status detail peminjaman barang.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'pdb_status' => 'required|string|max:2',
            ]);

            $detailPeminjaman = PeminjamanBarang::find($id);

            if (!$detailPeminjaman) {
                return response()->json(['message' => 'Detail peminjaman tidak ditemukan.'], 404);
            }

            $detailPeminjaman->update(['pdb_status' => $validatedData['pdb_status']]);

            return response()->json(['message' => 'Status peminjaman barang berhasil diperbarui.', 'data' => $detailPeminjaman], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat memperbarui data.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Generate pb_id (ID peminjaman) baru.
     */
    private function generatePbId()
    {
        $year = Carbon::now()->year;
        $month = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);

        $maxUrut = Peminjaman::where('id', 'like', "PB{$year}{$month}%")
            ->max(DB::raw("CAST(SUBSTRING(id, 9) AS UNSIGNED)")) ?? 0;

        $newUrut = str_pad($maxUrut + 1, 3, '0', STR_PAD_LEFT);
        return "PB{$year}{$month}{$newUrut}";
    }
}

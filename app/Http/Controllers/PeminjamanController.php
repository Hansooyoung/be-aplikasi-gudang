<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Pengembalian;
use App\Models\BarangInventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Fungsi untuk menyimpan peminjaman dan peminjaman_detail
    public function store(Request $request)
    {
        $this->validate($request, [
            'siswa_id' => 'required|exists:siswa,id',
            'kode_barang' => 'required|array',
        ]);

        $errors = [];
        DB::beginTransaction();
        try {
            // Generate Peminjaman ID
            $peminjamanId = $this->generatePeminjamanId();

            // Menyimpan data peminjaman
            $peminjaman = new Peminjaman();
            $peminjaman->id = $peminjamanId;
            $peminjaman->user_id = auth()->user()->id;
            $peminjaman->siswa_id = $request->siswa_id;
            $peminjaman->tanggal_peminjaman = now();
            $peminjaman->tanggal_pengembalian = now()->addDays(7); // Contoh 7 hari pengembalian
            $peminjaman->save();

            // Menyimpan peminjaman_detail untuk setiap barang yang dipinjam dan pengembalian terkait
            foreach ($request->kode_barang as $kode_barang) {
                // Validasi apakah barang ada dan tersedia
                $barang = BarangInventaris::where('kode_barang', $kode_barang)->first();

                // Jika barang tidak ditemukan di database
                if (!$barang) {
                    $errors[] = "Barang dengan kode {$kode_barang} tidak ditemukan di database.";
                    continue; // Lewatkan ke barang berikutnya
                }

                // Jika barang tidak tersedia
                if ($barang->status_tersedia != 'tersedia') {
                    $errors[] = "Barang dengan kode {$kode_barang} tidak tersedia.";
                    continue; // Lewatkan ke barang berikutnya
                }

                // Update status barang menjadi tidak_tersedia
                $barang->status_tersedia = 'tidak_tersedia';
                $barang->save();

                // Menyimpan data peminjaman_detail
                $peminjamanDetail = new PeminjamanDetail();
                $peminjamanDetail->id = $this->generatePeminjamanDetailId($peminjamanId);
                $peminjamanDetail->peminjaman_id = $peminjamanId;
                $peminjamanDetail->kode_barang = $kode_barang;
                $peminjamanDetail->save();

                // Membuat pengembalian setelah peminjaman_detail disimpan
                $pengembalian = new Pengembalian();
                $pengembalian->id = $this->generatePengembalianId(); // Generate pengembalian ID
                $pengembalian->peminjaman_detail_id = $peminjamanDetail->id; // Menggunakan ID yang benar
                $pengembalian->user_id = null; // Akan diisi saat pengembalian dilakukan
                $pengembalian->tanggal_kembali = null;
                $pengembalian->status_barang = null;
                $pengembalian->status_kembali = "dipinjam";
                $pengembalian->save();
            }

            // Jika ada error, rollback dan kirim error yang ditemukan
            if (!empty($errors)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Gagal membuat peminjaman, beberapa barang tidak tersedia atau tidak ditemukan.',
                    'errors' => $errors,
                ], 400);
            }

            DB::commit();

            return response()->json([
                'message' => 'Peminjaman berhasil dibuat',
                'data' => $peminjaman,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat peminjaman',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // Fungsi untuk mengupdate pengembalian
    public function updatePengembalian(Request $request, $pengembalianId)
    {
        $this->validate($request, [
            'status_barang' => 'required|in:1,2,3', // Define your status (e.g., good, damaged, lost)
            'status_kembali' => 'required|in:dipinjam,dikembalikan', // Status pengembalian
        ]);

        DB::beginTransaction();
        try {
            $pengembalian = Pengembalian::findOrFail($pengembalianId);

            // Cek jika status pengembalian sudah dikembalikan
            if ($pengembalian->status_kembali == "dikembalikan") {
                throw new \Exception('Barang sudah dikembalikan sebelumnya');
            }

            // Update pengembalian status
            $pengembalian->user_id = auth()->user()->id; // User yang mengembalikan barang
            $pengembalian->tanggal_kembali = now(); // Waktu pengembalian
            $pengembalian->status_barang = $request->status_barang; // Status barang saat dikembalikan
            $pengembalian->status_kembali = 'dikembalikan'; // Set status_kembali menjadi "dikembalikan"
            $pengembalian->save();

            // Update status barang di peminjaman_detail
            $peminjamanDetail = PeminjamanDetail::where('id', $pengembalian->peminjaman_detail_id)->first();
            $peminjamanDetail->status_kembali = 'dikembalikan'; // Set status kembali pada peminjaman_detail
            $peminjamanDetail->save();

            // Update status barang di tabel barang_inventaris menjadi tersedia
            $barang = BarangInventaris::where('kode_barang', $peminjamanDetail->kode_barang)->first();
            if ($barang) {
                $barang->status_tersedia = 'tersedia'; // Set status barang menjadi tersedia
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'message' => 'Pengembalian berhasil',
                'data' => $pengembalian,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memproses pengembalian',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fungsi untuk mendapatkan daftar peminjaman dengan filter status
// Fungsi untuk mendapatkan daftar peminjaman dengan filter status
    public function getPeminjaman(Request $request)
    {
        $status = $request->query('status');
        $peminjamanQuery = Peminjaman::query();

        if ($status) {
            $peminjamanQuery->whereHas('peminjamanDetails.pengembalian', function ($query) use ($status) {
                $query->where('status_kembali', $status);
            });
        }

        $peminjaman = $peminjamanQuery->with(['peminjamanDetails.pengembalian'])->get();

        return response()->json([
            'message' => 'Data peminjaman berhasil diambil',
            'data' => $peminjaman,
        ]);
    }


    // Fungsi untuk mendapatkan detail peminjaman
    // Fungsi untuk mendapatkan detail peminjaman
    public function showPeminjaman($id)
    {
        $peminjaman = Peminjaman::with(['peminjamanDetails.pengembalian'])->findOrFail($id);

        return response()->json([
            'message' => 'Data peminjaman berhasil diambil',
            'data' => $peminjaman,
        ]);
    }


    // Fungsi untuk mendapatkan daftar pengembalian
    public function getPengembalian()
    {
        $pengembalian = Pengembalian::with(['peminjamanDetail.peminjaman'])->get();

        return response()->json([
            'message' => 'Data pengembalian berhasil diambil',
            'data' => $pengembalian,
        ]);
    }

    // Fungsi untuk mendapatkan detail pengembalian
    public function showPengembalian($id)
    {
        $pengembalian = Pengembalian::with(['peminjamanDetail.peminjaman'])->findOrFail($id);

        return response()->json([
            'message' => 'Data pengembalian berhasil diambil',
            'data' => $pengembalian,
        ]);
    }

    // Fungsi untuk generate ID peminjaman
    public function generatePeminjamanId()
    {
        $tahun_sekarang = date('Y');
        $lastTransaction = Peminjaman::where('id', 'like', 'PJ' . $tahun_sekarang . '%')
            ->orderBy('id', 'desc')
            ->first();
        $no_urut = $lastTransaction ? (intval(substr($lastTransaction->id, -3)) + 1) : 1;
        $newId = 'PJ' . $tahun_sekarang . str_pad($no_urut, 3, '0', STR_PAD_LEFT);

        while (Peminjaman::where('id', $newId)->exists()) {
            $no_urut++;
            $newId = 'PJ' . $tahun_sekarang . str_pad($no_urut, 3, '0', STR_PAD_LEFT);
        }

        return $newId;
    }

    // Fungsi untuk generate ID peminjaman detail
    public function generatePeminjamanDetailId($peminjamanId)
    {
        $maxUrut = DB::table('peminjaman_detail')
            ->select(DB::raw("IFNULL(MAX(CAST(SUBSTRING(id, -3) AS UNSIGNED)), 0) + 1 AS next_urut"))
            ->where('peminjaman_id', $peminjamanId)
            ->value('next_urut');
        return sprintf("%s%03d", $peminjamanId, $maxUrut);
    }

    // Fungsi untuk generate ID pengembalian
    public function generatePengembalianId()
    {
        $tahun_sekarang = date('Y');
        $bulan_sekarang = date('m');
        $lastTransaction = Pengembalian::where('id', 'like', 'PB' . $tahun_sekarang . $bulan_sekarang . '%')
            ->orderBy('id', 'desc')
            ->first();
        $no_urut = $lastTransaction ? (intval(substr($lastTransaction->id, -3)) + 1) : 1;
        $newId = 'PB' . $tahun_sekarang . $bulan_sekarang . str_pad($no_urut, 3, '0', STR_PAD_LEFT);

        while (Pengembalian::where('id', $newId)->exists()) {
            $no_urut++;
            $newId = 'PB' . $tahun_sekarang . $bulan_sekarang . str_pad($no_urut, 3, '0', STR_PAD_LEFT);
        }

        return $newId;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BarangInventaris;
use Illuminate\Http\Request;

class BarangInventarisController extends Controller
{
    // Pastikan hanya user yang terautentikasi yang bisa mengakses fungsi ini
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data tanpa perlu user_id di request, karena akan diambil dari pengguna yang terautentikasi
        $request->validate([
            'jenis_barang_kode' => 'required|string|max:20',
            'nama_barang' => 'required|string|max:50',
            'tanggal_terima' => 'required|date',
            'status' => 'required|in:0,1',
        ]);

        $tahunSekarang = date('Y');

        // Cari no urut terakhir untuk tahun sekarang
        $noUrutTerakhir = (int) BarangInventaris::whereRaw("SUBSTRING(kode_barang, 4, 4) = ?", [$tahunSekarang])
            ->max(\DB::raw("IFNULL(SUBSTRING(kode_barang, 8, 5), 0)"));

        $noUrutBaru = str_pad($noUrutTerakhir + 1, 5, '0', STR_PAD_LEFT);
        $kodeBarangBaru = 'INV' . $tahunSekarang . $noUrutBaru;

        // Simpan data barang baru dengan kode yang baru
        $barangInventaris = new BarangInventaris();
        $barangInventaris->kode_barang = $kodeBarangBaru;
        $barangInventaris->jenis_barang_kode = $request->jenis_barang_kode;
        $barangInventaris->user_id = auth()->user()->id; // Ambil user_id dari pengguna yang login
        $barangInventaris->nama_barang = $request->nama_barang;
        $barangInventaris->tanggal_terima = $request->tanggal_terima;
        $barangInventaris->tanggal_entry = now();
        $barangInventaris->status = $request->status;
        $barangInventaris->save();

        return response()->json([
            'message' => 'Barang berhasil ditambahkan.',
            'barang' => $barangInventaris
        ], 201);
    }
}

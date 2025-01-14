<?php
namespace App\Http\Controllers;

use App\Models\BarangInventaris;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $request->validate([
            'jenis_barang_kode' => 'required|string|max:20',
            'nama_barang' => 'required|string|max:50',
            'tanggal_terima' => 'required|date',
            'status' => 'required|in:0,1',
            'vendor_id' => 'required|exists:vendor,id', // Validasi vendor_id
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
        $barangInventaris->status_barang = $request->status;
        $barangInventaris->status_tersedia = 'tersedia'; // Default status tersedia
        $barangInventaris->vendor_id = $request->vendor_id; // Menyimpan vendor_id
        $barangInventaris->save();

        // Ambil nama vendor dengan relasi
        $vendorNama = $barangInventaris->vendor->nama_vendor;
        $jenisBarangNama = $barangInventaris->jenisBarang->jenis_barang_nama;

        return response()->json([
            'message' => 'Barang berhasil ditambahkan.',
            'barang' => $barangInventaris,
            'vendor_nama' => $vendorNama, // Menambahkan nama vendor ke response
            'jenis_barang_nama' => $jenisBarangNama, // Menambahkan nama jenis barang ke response
        ], 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangInventaris = BarangInventaris::with(['vendor', 'jenisBarang'])->get(); // Mengambil semua data barang inventaris dengan relasi vendor dan jenis barang
        return response()->json(['data' => $barangInventaris], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show($kode)
    {
        $barangInventaris = BarangInventaris::with(['vendor', 'jenisBarang'])->where('kode_barang', $kode)->first();

        if (!$barangInventaris) {
            return response()->json(['message' => 'Barang tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => $barangInventaris,
            'vendor_nama' => $barangInventaris->vendor->nama_vendor,
            'jenis_barang_nama' => $barangInventaris->jenisBarang->jenis_barang_nama,
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode)
    {
        $request->validate([
            'jenis_barang_kode' => 'required|string|max:20',
            'nama_barang' => 'required|string|max:50',
            'tanggal_terima' => 'required|date',
            'status' => 'required|in:0,1',
            'vendor_id' => 'required|exists:vendor,id', // Validasi vendor_id
        ]);

        $barangInventaris = BarangInventaris::where('kode_barang', $kode)->first();

        if (!$barangInventaris) {
            return response()->json(['message' => 'Barang tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        // Update data barang
        $barangInventaris->jenis_barang_kode = $request->jenis_barang_kode;
        $barangInventaris->nama_barang = $request->nama_barang;
        $barangInventaris->tanggal_terima = $request->tanggal_terima;
        $barangInventaris->status_barang = $request->status;
        $barangInventaris->vendor_id = $request->vendor_id;
        $barangInventaris->save();

        return response()->json([
            'message' => 'Barang berhasil diperbarui.',
            'barang' => $barangInventaris,
            'vendor_nama' => $barangInventaris->vendor->nama_vendor,
            'jenis_barang_nama' => $barangInventaris->jenisBarang->jenis_barang_nama,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode)
    {
        $barangInventaris = BarangInventaris::where('kode_barang', $kode)->first();

        if (!$barangInventaris) {
            return response()->json(['message' => 'Barang tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        $barangInventaris->delete();

        return response()->json(['message' => 'Barang berhasil dihapus.'], Response::HTTP_OK);
    }

    public function restore($kode)
{
    $barangInventaris = BarangInventaris::withTrashed()->where('kode_barang', $kode)->first();

    if (!$barangInventaris) {
        return response()->json(['message' => 'Barang tidak ditemukan.'], Response::HTTP_NOT_FOUND);
    }

    $barangInventaris->restore();

    return response()->json(['message' => 'Barang berhasil dipulihkan.'], Response::HTTP_OK);
}

}

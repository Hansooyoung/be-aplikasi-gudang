<?php
namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JenisBarangController extends Controller
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
            'jenis_barang_nama' => 'required|string|max:50',
        ]);

        // Hitung tahun saat ini
        $tahunSekarang = Carbon::now()->year;

        // Cari no urut terakhir untuk tahun sekarang
        $noUrutTerakhir = JenisBarang::where('jenis_barang_kode', 'like', 'JNS' . $tahunSekarang . '%')
            ->max(\DB::raw("CAST(SUBSTRING(jenis_barang_kode, 8) AS UNSIGNED)"));

        // Tentukan no urut baru
        $noUrutBaru = str_pad($noUrutTerakhir + 1, 3, '0', STR_PAD_LEFT);

        // Membuat kode baru dengan format 'JNS' + Tahun + No_urut
        $jenisBarangKode = 'JNS' . $tahunSekarang . $noUrutBaru;

        // Menyimpan data jenis barang
        $jenisBarang = JenisBarang::create([
            'jenis_barang_kode' => $jenisBarangKode,
            'jenis_barang_nama' => $request->jenis_barang_nama,
        ]);

        return response()->json(['data' => $jenisBarang], Response::HTTP_CREATED);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisBarang = JenisBarang::all(); // Ambil semua data jenis barang
        return response()->json(['data' => $jenisBarang], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show($kode)
    {
        $jenisBarang = JenisBarang::where('jenis_barang_kode', $kode)->first();

        if (!$jenisBarang) {
            return response()->json(['message' => 'Jenis barang tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $jenisBarang], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode)
    {
        $request->validate([
            'jenis_barang_nama' => 'required|string|max:50',
        ]);

        $jenisBarang = JenisBarang::where('jenis_barang_kode', $kode)->first();

        if (!$jenisBarang) {
            return response()->json(['message' => 'Jenis barang tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        // Update nama jenis barang
        $jenisBarang->jenis_barang_nama = $request->jenis_barang_nama;
        $jenisBarang->save();

        return response()->json(['data' => $jenisBarang], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode)
    {
        $jenisBarang = JenisBarang::where('jenis_barang_kode', $kode)->first();

        if (!$jenisBarang) {
            return response()->json(['message' => 'Jenis barang tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        $jenisBarang->delete();

        return response()->json(['message' => 'Jenis barang berhasil dihapus.'], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Models\JenisBarang;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisBarang = JenisBarang::all();
        return view('jenis_barang.index', compact('jenisBarang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_barang.create');
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
     * Display the specified resource.
     */
    public function show(JenisBarang $jenisBarang)
    {
        return view('jenis_barang.show', compact('jenisBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisBarang $jenisBarang)
    {
        return view('jenis_barang.edit', compact('jenisBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisBarang $jenisBarang)
    {
        $request->validate([
            'jenis_barang_nama' => 'required|string|max:50',
        ]);

        $jenisBarang->update($request->all());

        return redirect()->route('jenis_barang.index')->with('success', 'Jenis Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisBarang $jenisBarang)
    {
        $jenisBarang->delete();

        return redirect()->route('jenis_barang.index')->with('success', 'Jenis Barang berhasil dihapus.');
    }
}

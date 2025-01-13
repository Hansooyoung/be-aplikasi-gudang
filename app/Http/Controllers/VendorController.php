<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    // Pastikan hanya user yang terautentikasi yang bisa mengakses fungsi ini
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Menampilkan semua vendor.
     */
    public function index()
    {
        $vendors = Vendor::all(); // Ambil semua data vendor
        return response()->json($vendors);
    }

    /**
     * Menyimpan vendor baru.
     */
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
        ]);

        // Simpan vendor baru
        $vendor = Vendor::create([
            'nama_vendor' => $request->nama_vendor,
        ]);

        return response()->json([
            'message' => 'Vendor berhasil ditambahkan.',
            'vendor' => $vendor
        ], 201);
    }

    /**
     * Menampilkan detail vendor.
     */
    public function show($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor tidak ditemukan'], 404);
        }

        return response()->json($vendor);
    }

    /**
     * Memperbarui data vendor.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor tidak ditemukan'], 404);
        }

        // Validasi dan update data
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
        ]);

        $vendor->update([
            'nama_vendor' => $request->nama_vendor,
        ]);

        return response()->json([
            'message' => 'Vendor berhasil diperbarui.',
            'vendor' => $vendor
        ]);
    }

    /**
     * Menghapus data vendor.
     */
    public function destroy($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor tidak ditemukan'], 404);
        }

        // Hapus vendor
        $vendor->delete();

        return response()->json(['message' => 'Vendor berhasil dihapus']);
    }
}

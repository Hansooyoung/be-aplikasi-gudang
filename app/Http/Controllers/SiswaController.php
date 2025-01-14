<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Menampilkan daftar siswa
     */
    public function index()
    {
        $siswa = Siswa::all();
        return response()->json(['data' => $siswa], Response::HTTP_OK);
    }

    /**
     * Menampilkan detail siswa berdasarkan NISN
     */
    public function show($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $siswa], Response::HTTP_OK);
    }

    /**
     * Menyimpan data siswa baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|unique:siswa,nisn',
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jurusan_id' => 'required|exists:jurusan,id',
            'no_hp' => 'required|string|max:15',
        ]);

        $siswa = Siswa::create($request->all());

        return response()->json([
            'message' => 'Siswa berhasil ditambahkan.',
            'data' => $siswa
        ], Response::HTTP_CREATED);
    }

    /**
     * Mengupdate data siswa berdasarkan NISN
     */
    public function update(Request $request, $nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        $siswa->update($request->all());

        return response()->json([
            'message' => 'Data siswa berhasil diperbarui.',
            'data' => $siswa
        ], Response::HTTP_OK);
    }

    /**
     * Menghapus data siswa (soft delete)
     */
    public function destroy($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        $siswa->delete();

        return response()->json(['message' => 'Siswa berhasil dihapus.'], Response::HTTP_OK);
    }

    /**
     * Mengembalikan data siswa yang telah di-soft delete
     */
    public function restore($nisn)
    {
        $siswa = Siswa::onlyTrashed()->where('nisn', $nisn)->first();

        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan di arsip.'], Response::HTTP_NOT_FOUND);
        }

        $siswa->restore();

        return response()->json(['message' => 'Siswa berhasil dipulihkan.'], Response::HTTP_OK);
    }

    /**
     * Menampilkan daftar siswa yang sudah di-soft delete
     */
    public function trashed()
    {
        $siswa = Siswa::onlyTrashed()->get();

        return response()->json(['data' => $siswa], Response::HTTP_OK);
    }
}

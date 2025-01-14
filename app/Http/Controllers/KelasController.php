<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::all();
        return response()->json(['data' => $kelas], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        $kelas = Kelas::create($request->all());

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan.',
            'data' => $kelas,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json(['message' => 'Kelas tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $kelas], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
        ]);

        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json(['message' => 'Kelas tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        $kelas->update($request->all());

        return response()->json([
            'message' => 'Kelas berhasil diperbarui.',
            'data' => $kelas,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return response()->json(['message' => 'Kelas tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        $kelas->delete();

        return response()->json(['message' => 'Kelas berhasil dihapus.'], Response::HTTP_OK);
    }
}
    
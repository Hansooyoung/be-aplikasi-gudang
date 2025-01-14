<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusan = Jurusan::all();
        return response()->json(['data' => $jurusan], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:30',
        ]);

        $jurusan = Jurusan::create($request->all());

        return response()->json([
            'message' => 'Jurusan berhasil ditambahkan.',
            'data' => $jurusan,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json(['message' => 'Jurusan tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $jurusan], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:30',
        ]);

        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json(['message' => 'Jurusan tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        $jurusan->update($request->all());

        return response()->json([
            'message' => 'Jurusan berhasil diperbarui.',
            'data' => $jurusan,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jurusan = Jurusan::find($id);

        if (!$jurusan) {
            return response()->json(['message' => 'Jurusan tidak ditemukan.'], Response::HTTP_NOT_FOUND);
        }

        $jurusan->delete();

        return response()->json(['message' => 'Jurusan berhasil dihapus.'], Response::HTTP_OK);
    }
}

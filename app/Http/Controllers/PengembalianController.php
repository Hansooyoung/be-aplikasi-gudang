<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengembalian;
use App\Models\BarangInventaris;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function update(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        $pengembalian->update([
            'user_id' => $request->user_id,
            'tanggal_kembali' => Carbon::now(),
            'status_barang' => $request->status_barang,
            'status_kembali' => 1,
        ]);

        $status_tersedia = $request->status_barang == 3 ? 'tidak_tersedia' : 'tersedia';
        BarangInventaris::where('kode_barang', $pengembalian->peminjamanDetail->kode_barang)
            ->update(['status_tersedia' => $status_tersedia]);

        return response()->json([
            'message' => 'Pengembalian berhasil diperbarui',
            'pengembalian' => $pengembalian,
        ]);
    }
}

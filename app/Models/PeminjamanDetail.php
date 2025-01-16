<?php

// app/Models/PeminjamanDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_detail';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['id', 'peminjaman_id', 'kode_barang'];

    // Relasi ke BarangInventaris
    public function barangInventaris()
    {
        return $this->belongsTo(BarangInventaris::class, 'kode_barang', 'kode_barang');
    }

    // Relasi ke Pengembalian
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_detail_id');
    }

    // Relasi ke Peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id');
    }
}

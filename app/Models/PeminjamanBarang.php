<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanBarang extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_barang';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'peminjaman_id',
        'kode_barang',
        'tanggal_peminjaman',
        'status'
    ];

    // Relasi ke model Peminjaman
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // Relasi ke model BarangInventaris
    public function barangInventaris()
    {
        return $this->belongsTo(BarangInventaris::class, 'kode_barang');
    }
}

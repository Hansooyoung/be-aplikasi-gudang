<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalian';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['id', 'peminjaman_detail_id', 'user_id', 'tanggal_kembali', 'status_barang', 'status_kembali'];

    public function peminjamanDetail()
    {
        return $this->belongsTo(PeminjamanDetail::class, 'peminjaman_detail_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = ['id', 'user_id', 'tanggal_peminjaman', 'siswa_id', 'tanggal_pengembalian', 'status_kembali'];

    public function peminjamanDetails()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }
}

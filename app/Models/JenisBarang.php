<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'jenis_barang';

    // Primary key dari tabel
    protected $primaryKey = 'jenis_barang_kode';

    // Menggunakan tipe data string untuk primary key
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang dapat diisi
    protected $fillable = [
        'jenis_barang_kode',
        'jenis_barang_nama',
    ];

    // Kolom yang dikelola secara otomatis oleh Eloquent
    public $timestamps = true;

    // Relasi ke model 'BarangInventaris' (jika ada)
    public function barangInventaris()
    {
        return $this->hasMany(BarangInventaris::class, 'jenis_barang_kode', 'jenis_barang_kode');
    }
}

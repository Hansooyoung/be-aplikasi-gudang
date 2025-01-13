<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangInventaris extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'barang_inventaris';

    // Primary key dari tabel
    protected $primaryKey = 'kode_barang';

    // Menggunakan tipe data string untuk primary key
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang dapat diisi
    protected $fillable = [
        'kode_barang',
        'jenis_barang_kode',
        'user_id',
        'nama_barang',
        'tanggal_terima',
        'tanggal_entry',
        'status_barang',
        'status_tersedia',
        'vendor_id', // Tambahkan vendor_id di fillable
    ];

    // Kolom yang dikelola secara otomatis oleh Eloquent
    public $timestamps = true;

    // Relasi ke model 'JenisBarang'
    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'jenis_barang_kode', 'jenis_barang_kode');
    }

    // Relasi ke model 'User'
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke model 'Vendor'
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'peminjaman';

    // Primary key dari tabel
    protected $primaryKey = 'id';

    // Tidak menggunakan auto increment karena 'id' adalah string
    public $incrementing = false;

    // Menentukan kolom yang dapat diisi (fillable)
    protected $fillable = [
        'id',
        'user_id',
        'no_siswa',
        'nama_siswa',
        'harus_kembali_tanggal',
        'status',
    ];

    // Kolom yang tidak akan diubah oleh Mass Assignment
    protected $guarded = [];

    // Menyembunyikan kolom tertentu ketika mengubah menjadi array atau JSON
    protected $hidden = [];

    // Menentukan format tanggal yang digunakan dalam model
    protected $dates = ['tanggal_peminjaman', 'harus_kembali_tanggal'];

    // Mendefinisikan relasi dengan model User (satu peminjaman memiliki satu user)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

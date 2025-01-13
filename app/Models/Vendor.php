<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'vendor';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'nama_vendor',
    ];

    // Kolom yang dikelola oleh Eloquent secara otomatis
    public $timestamps = true;
}

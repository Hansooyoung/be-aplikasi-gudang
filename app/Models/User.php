<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; // Tambahkan ini
class User extends Authenticatable
{

    use HasFactory,HasApiTokens;

    // Nama tabel di database
    protected $table = 'user';

    // Primary key dari tabel
    protected $primaryKey = 'id';

    // Menggunakan tipe data string untuk primary key
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang dapat diisi
    protected $fillable = [
        'id',
        'nama',
        'password',
        'hak',
        'status',
    ];

    // Kolom yang dikelola secara otomatis oleh Eloquent
    public $timestamps = true;

    // Menyembunyikan kolom tertentu dari array atau JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Setter untuk mengenkripsi password
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = md5($password);
    }
}

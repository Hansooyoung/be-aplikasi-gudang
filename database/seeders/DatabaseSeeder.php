<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed data untuk tabel user
        DB::table('user')->insert([
            [
                'id' => 'USR001',
                'email' => 'superadmin@gmail.com',
                'nama' => 'SuperAdmin',
                'password' => md5('superadmin123'),
                'role' => 'super',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'USR002',
                'email' => 'admin@gmail.com',
                'nama' => 'AdminUser',
                'password' => md5('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'USR003',
                'email' => 'user@gmail.com',
                'nama' => 'RegularUser',
                'password' => md5('user123'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed data untuk tabel vendor
        DB::table('vendor')->insert([
            [
                'nama_vendor' => 'Vendor A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_vendor' => 'Vendor B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_vendor' => 'Vendor C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed data untuk tabel jenis_barang
        DB::table('jenis_barang')->insert([
            [
                'jenis_barang_kode' => 'JNS2025001',
                'jenis_barang_nama' => 'Elektronik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_barang_kode' => 'JNS2025002',
                'jenis_barang_nama' => 'Furniture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_barang_kode' => 'JNS2025003',
                'jenis_barang_nama' => 'Alat Tulis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

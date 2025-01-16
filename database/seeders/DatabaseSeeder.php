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

        // Seed data untuk tabel kelas
        DB::table('kelas')->insert([
            [
                'nama_kelas' => 'X-A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => 'X-B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => 'XI-A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => 'XI-B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => 'XII-A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kelas' => 'XII-B',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed data untuk tabel jurusan
        DB::table('jurusan')->insert([
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jurusan' => 'Teknik Komputer dan Jaringan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_jurusan' => 'Animasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seed data untuk tabel siswa
        DB::table('siswa')->insert([
            [
                'nisn' => '1234567890',
                'nama' => 'Siswa A',
                'kelas_id' => 1, // X-A
                'jurusan_id' => 1, // Rekayasa Perangkat Lunak
                'no_hp' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '1234567891',
                'nama' => 'Siswa B',
                'kelas_id' => 2, // X-B
                'jurusan_id' => 2, // Teknik Komputer dan Jaringan
                'no_hp' => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '1234567892',
                'nama' => 'Siswa C',
                'kelas_id' => 3, // XI-A
                'jurusan_id' => 3, // Animasi
                'no_hp' => '081234567892',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '1234567893',
                'nama' => 'Siswa D',
                'kelas_id' => 4, // XI-B
                'jurusan_id' => 1, // Rekayasa Perangkat Lunak
                'no_hp' => '081234567893',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '1234567894',
                'nama' => 'Siswa E',
                'kelas_id' => 5, // XII-A
                'jurusan_id' => 2, // Teknik Komputer dan Jaringan
                'no_hp' => '081234567894',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nisn' => '1234567895',
                'nama' => 'Siswa F',
                'kelas_id' => 6, // XII-B
                'jurusan_id' => 3, // Animasi
                'no_hp' => '081234567895',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

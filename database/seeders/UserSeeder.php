<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'id' => 'USR001',
                'nama' => 'SuperAdmin',
                'password' => md5('superadmin123'),
                'role' => 'super',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'USR002',
                'nama' => 'AdminUser',
                'password' => md5('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'USR003',
                'nama' => 'RegularUser',
                'password' => md5('user123'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

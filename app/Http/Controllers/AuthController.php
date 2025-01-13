<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan nama
        $user = User::where('nama', $request->nama)->first();

        // Periksa apakah user ditemukan dan password cocok menggunakan md5
        if ($user && md5($request->password) == $user->password) {
            // Membuat token baru menggunakan id user sebagai nama token
            $token = $user->createToken($user->id)->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'message' => 'Nama atau password salah'
            ], 401);
        }
    }
}

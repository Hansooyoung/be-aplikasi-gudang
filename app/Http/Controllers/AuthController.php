<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Periksa apakah user ditemukan dan password cocok
        if ($user && md5($request->password, $user->password)) {
            // Membuat token baru menggunakan id user sebagai nama token
            $token = $user->createToken($user->id)->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        // Hapus token autentikasi pengguna yang sedang login
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ], 200);
    }
}

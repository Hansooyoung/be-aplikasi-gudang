<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{       public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|string|max:10|unique:user',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:user',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super,admin,user',
        ]);

        // Membuat user baru dengan MD5 hash
        $user = User::create([
            'id' => $request->id,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => md5($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:user,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => 'sometimes|in:super,admin,user',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $user->update([
            'nama' => $request->nama ?? $user->nama,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? md5($request->password) : $user->password,
            'role' => $request->role ?? $user->role,
        ]);

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'user' => $user
        ], 200);
    }

    public function destroy($id)
{
    // Temukan user berdasarkan ID
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    // Hapus user
    $user->delete();

    return response()->json([
        'message' => 'User berhasil dihapus'
    ], 200);
}
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

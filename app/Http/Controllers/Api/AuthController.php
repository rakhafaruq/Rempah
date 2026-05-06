<?php

// app/Http/Controllers/Api/AuthController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users,email',
            'phone'     => 'required|string|unique:users,phone',
            'password'  => 'required|string|min:6',
            'role'      => 'required|in:donatur,relawan',
            'nama_toko' => 'required_if:role,donatur|string|max:255',
            'alamat'    => 'nullable|string',
        ], [
            'phone.unique'         => 'Nomor HP sudah terdaftar.',
            'nama_toko.required_if' => 'Nama toko wajib diisi untuk Donatur.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Buat profil donor jika role adalah donatur
        if ($request->role === 'donatur') {
            Donor::create([
                'user_id'   => $user->id,
                'nama_toko' => $request->nama_toko,
                'alamat'    => $request->alamat,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token'   => $token,
            'user'    => $user->load('donor'),
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone'    => 'required',
            'password' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Nomor HP atau password salah.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => $user->load('donor'),
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('donor'));
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }
}
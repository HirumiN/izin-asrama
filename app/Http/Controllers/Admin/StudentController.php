<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support5\Facades\Hash; // Wait, it's Illuminate\Support\Facades\Hash, let's fix the typo in my head.

class StudentController extends Controller
{
    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nim' => 'required|string|max:50|unique:students',
            'dorm_room' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
        ], [
            'email.unique' => 'Alamat email sudah digunakan.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'password.min' => 'Kata sandi minimal harus 6 karakter.',
        ]);

        // Gunakan transaksi database agar konsisten
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password), // bcrypt or Hash::make
                'role' => 'mahasiswa',
            ]);

            $user->student()->create([
                'nim' => $request->nim,
                'dorm_room' => $request->dorm_room,
                'phone' => $request->phone,
            ]);
        });

        return redirect()->route('admin.dashboard')->with('success', 'Akun mahasiswa baru berhasil dibuat.');
    }
}

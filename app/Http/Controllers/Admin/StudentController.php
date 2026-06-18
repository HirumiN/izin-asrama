<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        return redirect()->route('admin.students.index')->with('success', 'Akun mahasiswa baru berhasil dibuat.');
    }

    public function index(Request $request)
    {
        $query = Student::with('user');

        // Filter pencarian: hanya nama atau NIM
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where(['nim' => $search])
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter status akun
        if ($request->filled('status')) {
            $query->where(['is_suspended' => $request->status === 'ditangguhkan']);
        }

        // Filter terdaftar sejak
        if ($request->filled('registered_since')) {
            $query->whereDate('created_at', '>=', $request->registered_since);
        }

        // Sorting
        $sort = $request->input('sort', 'terbaru');
        $sortMap = [
            'az'      => ['join' => true, 'column' => 'name', 'dir' => 'asc'],
            'za'      => ['join' => true, 'column' => 'name', 'dir' => 'desc'],
            'terlama' => ['join' => false, 'column' => 'created_at', 'dir' => 'asc'],
            'terbaru' => ['join' => false, 'column' => 'created_at', 'dir' => 'desc'],
        ];

        $sortConfig = $sortMap[$sort] ?? $sortMap['terbaru'];

        if ($sortConfig['join']) {
            $query->join('users', function ($join) {
                      $join->on('students.user_id', '=', 'users.id');
                  })
                  ->orderByRaw("users.{$sortConfig['column']} {$sortConfig['dir']}")
                  ->select('students.*');
        } else {
            $query->orderByRaw("students.{$sortConfig['column']} {$sortConfig['dir']}");
        }

        $students = $query->paginate(10)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    /**
     * Mencabut status penangguhan mahasiswa.
     */
    public function liftSuspension(Student $student)
    {
        if (!$student->isSuspended()) {
            return back()->with('error', 'Mahasiswa ini tidak sedang dalam status ditangguhkan.');
        }

        $student->is_suspended = false;
        $student->suspended_at = null;
        $student->save();

        return back()->with('success', "Penangguhan untuk {$student->user->name} berhasil dicabut. Mahasiswa kini dapat mengajukan izin kembali.");
    }
}

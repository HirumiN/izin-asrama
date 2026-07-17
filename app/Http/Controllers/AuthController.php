<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'pengelola'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');
        $remember = $request->filled('remember');

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $login, 'password' => $password];
            $attempt = Auth::attempt($credentials, $remember);
        } else {
            $student = \App\Models\Student::with('user')->where('nim', $login)->first();
            if ($student && $student->user) {
                $credentials = ['email' => $student->user->email, 'password' => $password];
                $attempt = Auth::attempt($credentials, $remember);
            } else {
                $attempt = false;
            }
        }

        if ($attempt) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'pengelola') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors([
            'login' => 'NIM/Email atau sandi salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}

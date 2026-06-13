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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role === 'pengelola') {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Selamat datang kembali, Pengelola!');
            }

            return redirect()->intended(route('student.dashboard'))
                ->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Email atau sandi salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}

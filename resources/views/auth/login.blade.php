@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-[75vh]">
    <div class="w-full max-w-md p-8 glass-card glow-blue transition duration-300 hover:border-slate-300/60">
        <!-- Logo / Title -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-slate-800">
                Masuk Sistem
            </h2>
            <p class="mt-2 text-sm text-slate-500">
                Gunakan NIM & Sandi untuk Mahasiswa atau Email untuk Pengelola
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-emerald-600 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- NIM / Email Address -->
            <div>
                <label for="login" class="block text-sm font-semibold text-slate-700">
                    NIM atau Alamat Email
                </label>
                <div class="mt-1">
                    <input id="login" name="login" type="text" required 
                        value="{{ old('login') }}"
                        class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="NIM atau email@asrama.com">
                </div>
                @error('login')
                    <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700">
                    Kata Sandi
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600">
                    <label for="remember" class="ml-2 block text-sm text-slate-600 font-medium">
                        Ingat saya
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition duration-250 transform active:scale-[0.98]">
                    Masuk
                </button>
            </div>

            <!-- Tombol Cek Aktivitas Mahasiswa (Publik) -->
            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('public.student-info') }}"
                    class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-blue-200 rounded-xl shadow-sm text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-250 transform active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    Informasi Aktivitas Mahasiswa
                </a>
            </div>
        </form>

        <!-- Akun Demo Box -->
        <div class="mt-8 pt-6 border-t border-slate-200">
            <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Akun Demo Default</h3>
            <div class="space-y-2 text-xs text-slate-600 font-medium">
                <div class="flex justify-between p-2 bg-slate-50/80 rounded-lg border border-slate-200/80 shadow-sm">
                    <span><strong>Pengelola:</strong> admin@asrama.com</span>
                    <span class="text-blue-600">Sandi: password</span>
                </div>
                <div class="flex justify-between p-2 bg-slate-50/80 rounded-lg border border-slate-200/80 shadow-sm">
                    <span><strong>Mahasiswa:</strong> andi@asrama.com</span>
                    <span class="text-blue-600">Sandi: password</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

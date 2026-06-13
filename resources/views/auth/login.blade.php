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

            <!-- Email Address / NIM -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">
                    Alamat Email (atau Akun Terdaftar)
                </label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="nama@asrama.com">
                </div>
                @error('email')
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

@extends('layouts.app')

@section('title', 'Ganti Password')
@section('page_title', 'Ganti Password')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <!-- Header Card -->
    <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg border border-white/10" 
         style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 50%, #7c3aed 100%);">
        <!-- Dekorasi Latar Belakang -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative flex items-center gap-4">
            <div class="p-3 bg-white/15 backdrop-blur-md rounded-xl border border-white/20 shadow-inner flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold tracking-tight m-0 text-white">Ganti Kata Sandi</h2>
                <p class="text-white/80 text-xs font-medium mt-1">Perbarui kata sandi akun E-Asrama Anda demi menjaga keamanan akses secara mandiri.</p>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="p-8 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('student.password.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Password Saat Ini -->
            <div class="space-y-2">
                <label for="current_password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password" id="current_password" required
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-600 transition text-sm shadow-sm"
                    placeholder="Masukkan kata sandi lama Anda">
                @error('current_password')
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-slate-200 my-2">

            <!-- Password Baru -->
            <div class="space-y-2">
                <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Kata Sandi Baru</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-600 transition text-sm shadow-sm"
                    placeholder="Masukkan kata sandi baru (min. 6 karakter)">
                @error('password')
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-600 transition text-sm shadow-sm"
                    placeholder="Ulangi kata sandi baru">
            </div>

            <!-- Submit Button -->
            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('student.dashboard') }}" 
                   class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-sm font-bold transition text-center shadow-sm">
                    Kembali
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-150 transform active:scale-[0.98]">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

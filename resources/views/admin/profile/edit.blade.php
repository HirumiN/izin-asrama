@extends('layouts.app')

@section('title', 'Pengaturan Profil')
@section('page_title', 'Pengaturan Profil')

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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.936 6.936 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.645-.869L9.59 3.94Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold tracking-tight m-0 text-white">Kelola Kredensial Akun</h2>
                <p class="text-white/80 text-xs font-medium mt-1">Perbarui nama, alamat email, atau kata sandi pengelola Anda secara berkala.</p>
            </div>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="p-8 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Nama Lengkap -->
            <div class="space-y-2">
                <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-600 transition text-sm shadow-sm font-medium"
                    placeholder="Masukkan nama lengkap Anda">
                @error('name')
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-600 transition text-sm shadow-sm font-medium"
                    placeholder="nama@email.com">
                @error('email')
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-slate-200 my-2">

            <!-- Password Info Alert -->
            <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-600 font-medium">
                <p>💡 **Tips Kata Sandi**: Jika Anda tidak ingin mengganti kata sandi saat ini, silakan **kosongkan** kolom kata sandi di bawah.</p>
            </div>

            <!-- Password Baru -->
            <div class="space-y-2">
                <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Kata Sandi Baru</label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-600 transition text-sm shadow-sm"
                    placeholder="Masukkan kata sandi baru (min. 6 karakter)">
                @error('password')
                    <p class="text-xs text-rose-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600/25 focus:border-blue-600 transition text-sm shadow-sm"
                    placeholder="Ulangi kata sandi baru">
            </div>

            <!-- Submit Button -->
            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-sm font-bold transition text-center shadow-sm">
                    Kembali
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-150 transform active:scale-[0.98]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

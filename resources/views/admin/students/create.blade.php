@extends('layouts.app')

@section('title', 'Tambah Mahasiswa Baru')
@section('page_title', 'Tambah Mahasiswa Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Tombol Kembali -->
    <div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 font-semibold transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Card Form -->
    <div class="p-8 glass-card glow-blue">
        <div class="pb-5 border-b border-slate-200">
            <h1 class="text-2xl font-bold text-slate-900">Tambah Akun Mahasiswa Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan akun dan profil asrama mahasiswa baru.</p>
        </div>

        <form action="{{ route('admin.students.store') }}" method="POST" class="mt-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="Contoh: Andi Pratama">
                    @error('name')
                        <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Alamat Email</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="andi@asrama.com">
                    @error('email')
                        <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi Awal</label>
                    <input type="text" name="password" id="password" required value="{{ old('password', Str::random(8)) }}"
                        class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="Minimal 6 karakter">
                    @error('password')
                        <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- NIM -->
                <div>
                    <label for="nim" class="block text-sm font-semibold text-slate-700">NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" name="nim" id="nim" required value="{{ old('nim') }}"
                        class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="Contoh: 1234567890">
                    @error('nim')
                        <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kamar Asrama -->
                <div>
                    <label for="dorm_room" class="block text-sm font-semibold text-slate-700">Nomor Kamar</label>
                    <input type="text" name="dorm_room" id="dorm_room" required value="{{ old('dorm_room') }}"
                        class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="Contoh: A-102">
                    @error('dorm_room')
                        <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div class="md:col-span-2">
                    <label for="phone" class="block text-sm font-semibold text-slate-700">Nomor Telepon (WhatsApp)</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                        placeholder="Contoh: 08123456789">
                    @error('phone')
                        <span class="text-rose-600 text-xs mt-1 block font-semibold">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition duration-250 transform active:scale-[0.98]">
                    Buat Akun & Profil Mahasiswa
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

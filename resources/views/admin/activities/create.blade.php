@extends('layouts.app')

@section('title', 'Buat Kegiatan Baru')
@section('page_title', 'Buat Kegiatan Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Back button -->
    <a href="{{ route('admin.activities.index') }}" 
        class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Daftar Kegiatan
    </a>

    <!-- Form Card -->
    <div class="glass-card p-6 border-slate-200/80 shadow-md">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
            <div class="p-2 bg-blue-50 border border-blue-100 text-blue-600 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Form Kegiatan Baru</h3>
                <p class="text-xs text-slate-500 font-medium">Buat kegiatan asrama kustom baru dan jadwalkan pengisian absensinya.</p>
            </div>
        </div>

        <form action="{{ route('admin.activities.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Kegiatan -->
            <div class="space-y-2">
                <label for="name" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Nama Kegiatan</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Apel Malam Minggu, Gotong Royong Asrama..."
                    class="w-full px-4 py-2.5 bg-white border @error('name') border-red-400 focus:ring-red-150 focus:border-red-655 @else border-slate-200 focus:ring-blue-150 focus:border-blue-600 @enderror rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 transition-all text-sm font-medium shadow-sm">
                @error('name')
                    <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Kegiatan -->
            <div class="space-y-2">
                <label for="date" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Tanggal Kegiatan</label>
                <input type="date" name="date" id="date" value="{{ old('date', today()->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2.5 bg-white border @error('date') border-red-400 focus:ring-red-150 focus:border-red-655 @else border-slate-200 focus:ring-blue-150 focus:border-blue-600 @enderror rounded-xl text-slate-900 focus:outline-none focus:ring-4 transition-all text-sm font-medium shadow-sm">
                @error('date')
                    <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jam Mulai & Selesai Absensi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="start_time" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Jam Mulai Absensi</label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time', '19:00') }}" required
                        class="w-full px-4 py-2.5 bg-white border @error('start_time') border-red-400 focus:ring-red-150 focus:border-red-655 @else border-slate-200 focus:ring-blue-150 focus:border-blue-600 @enderror rounded-xl text-slate-900 focus:outline-none focus:ring-4 transition-all text-sm font-medium shadow-sm">
                    @error('start_time')
                        <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="end_time" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Jam Selesai Absensi</label>
                    <input type="time" name="end_time" id="end_time" value="{{ old('end_time', '20:00') }}" required
                        class="w-full px-4 py-2.5 bg-white border @error('end_time') border-red-400 focus:ring-red-150 focus:border-red-655 @else border-slate-200 focus:ring-blue-150 focus:border-blue-600 @enderror rounded-xl text-slate-900 focus:outline-none focus:ring-4 transition-all text-sm font-medium shadow-sm">
                    @error('end_time')
                        <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi Kegiatan -->
            <div class="space-y-2">
                <label for="description" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Deskripsi / Keterangan (Opsional)</label>
                <textarea name="description" id="description" rows="4" 
                    placeholder="Masukkan detail tambahan tentang kegiatan ini..."
                    class="w-full px-4 py-2.5 bg-white border @error('description') border-red-400 focus:ring-red-150 focus:border-red-655 @else border-slate-200 focus:ring-blue-150 focus:border-blue-600 @enderror rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 transition-all text-sm font-medium shadow-sm">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                <button type="submit"
                    class="flex-1 py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition transform active:scale-[0.98] cursor-pointer text-center">
                    Simpan Kegiatan
                </button>
                <a href="{{ route('admin.activities.index') }}"
                    class="py-2.5 px-6 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 text-slate-700 rounded-xl text-sm font-bold transition text-center cursor-pointer">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

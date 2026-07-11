@extends('layouts.app')

@section('title', 'Informasi Mahasiswa')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-extrabold text-slate-800">Cek Aktivitas Mahasiswa</h1>
        <p class="text-slate-500 text-sm font-medium">Masukkan Nomor Induk Mahasiswa (NIM) untuk melihat riwayat izin dan absensi kegiatan.</p>
    </div>

    <!-- Form Pencarian -->
    <div class="p-8 glass-card glow-blue w-full max-w-2xl mx-auto">
        <form action="{{ route('public.student-info') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="nim" class="sr-only">NIM Mahasiswa</label>
                <input type="text" name="nim" id="nim" required value="{{ request('nim') }}"
                    placeholder="Masukkan NIM Mahasiswa..."
                    class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm">
            </div>
            <button type="submit"
                class="py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-150 transform active:scale-[0.98] whitespace-nowrap">
                Cari Riwayat
            </button>
        </form>
    </div>

    <!-- Hasil Pencarian -->
    @if(request()->filled('nim'))
        @if($student)
            <!-- Info Profil Singkat -->
            <div class="p-6 glass-card border-blue-200/60 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-4 bg-blue-50 border border-blue-100 rounded-full text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 uppercase">{{ $maskedName }}</h2>
                        <div class="text-sm font-medium text-slate-500 mt-1 flex flex-wrap items-center gap-x-4 gap-y-1">
                            <span>NIM: <strong class="text-slate-700">{{ $student->nim }}</strong></span>
                            <span>Kamar: <strong class="text-slate-700">{{ $student->dorm_room }}</strong></span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-2">
                    @if($activePermit)
                        <span class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold rounded-full uppercase tracking-wider text-center flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            Sedang Di Luar: {{ str_replace('_', ' ', $activePermit->type) }} ke {{ $activePermit->destination }}
                        </span>
                    @else
                        <span class="px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wider text-center flex items-center justify-center gap-1.5 shadow-sm">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Berada di Asrama
                        </span>
                    @endif

                    @if($student->isSuspended())
                        <span class="px-4 py-2 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-full uppercase tracking-wider text-center shadow-sm">
                            Izin Ditangguhkan
                        </span>
                    @endif
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="glass-card border-slate-200/80 shadow-sm overflow-hidden">
                <!-- Tab Bar -->
                <div class="flex border-b border-slate-200 bg-slate-50/60">
                    <button type="button" id="tab-btn-permit"
                        onclick="switchTab('permit')"
                        class="flex items-center gap-2 px-6 py-3.5 text-sm font-bold border-b-2 transition duration-150 tab-active"
                        data-tab="permit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l-3-3m3 3h7.5" />
                        </svg>
                        Izin Keluar / Masuk
                        <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-md">
                            {{ $historyPermits->total() }}
                        </span>
                    </button>
                    <button type="button" id="tab-btn-activity"
                        onclick="switchTab('activity')"
                        class="flex items-center gap-2 px-6 py-3.5 text-sm font-bold border-b-2 transition duration-150 tab-inactive"
                        data-tab="activity">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                        Absen Kegiatan
                        <span class="px-1.5 py-0.5 bg-slate-200 text-slate-600 text-[10px] font-bold rounded-md" id="activity-count-badge">
                            {{ $activityAttendances->total() }}
                        </span>
                    </button>
                </div>

                <!-- Tab 1: Izin Keluar / Masuk -->
                <div id="tab-permit" class="tab-pane">
                    @if($historyPermits->isEmpty())
                        <div class="text-center py-12 text-slate-400 text-sm font-medium">
                            Belum ada riwayat pengajuan izin sebelumnya.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-slate-650">
                                <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                                    <tr>
                                        <th class="px-6 py-3.5">Jenis Izin</th>
                                        <th class="px-6 py-3.5">Tujuan</th>
                                        <th class="px-6 py-3.5">Keluar</th>
                                        <th class="px-6 py-3.5">Kembali</th>
                                        <th class="px-6 py-3.5">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200/80 font-medium">
                                    @foreach($historyPermits as $history)
                                        <tr class="hover:bg-slate-50/50 transition duration-150">
                                            <td class="px-6 py-4 font-bold text-slate-800 capitalize">
                                                {{ str_replace('_', ' ', $history->type) }}
                                            </td>
                                            <td class="px-6 py-4 text-slate-700">{{ $history->destination }}</td>
                                            <td class="px-6 py-4 text-slate-600 text-xs">
                                                {{ $history->start_time->format('d/m/Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 text-xs">
                                                @if($history->status === 'returned_on_time' || $history->status === 'returned_late')
                                                    {{ $history->actual_return_time ? $history->actual_return_time->format('d/m/Y, H:i') : '-' }}
                                                @elseif($history->status === 'approved')
                                                    <span class="text-blue-600 font-bold italic">Sedang Keluar</span>
                                                @else
                                                    <span class="text-slate-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($history->status === 'approved')
                                                    <span class="px-2 py-1 bg-blue-50 border border-blue-100 text-blue-700 rounded-md text-[11px] font-bold uppercase">Aktif Keluar</span>
                                                @elseif($history->status === 'returned_on_time')
                                                    <span class="px-2 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-md text-[11px] font-bold uppercase">Tepat Waktu</span>
                                                @elseif($history->status === 'returned_late')
                                                    <span class="px-2 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-md text-[11px] font-bold uppercase">Terlambat ({{ $history->lateness_duration }} Menit)</span>
                                                @elseif($history->status === 'pending')
                                                    <span class="px-2 py-1 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-md text-[11px] font-bold uppercase">Menunggu</span>
                                                @elseif($history->status === 'rejected')
                                                    <span class="px-2 py-1 bg-slate-100 border border-slate-200 text-slate-500 rounded-md text-[11px] font-bold uppercase">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-slate-100">
                            {{ $historyPermits->links() }}
                        </div>
                    @endif
                </div>

                <!-- Tab 2: Absen Kegiatan -->
                <div id="tab-activity" class="tab-pane hidden">
                    @if($activityAttendances->isEmpty())
                        <div class="text-center py-12 text-slate-400 text-sm font-medium">
                            Belum ada riwayat absensi kegiatan.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-slate-650">
                                <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                                    <tr>
                                        <th class="px-6 py-3.5">Nama Kegiatan</th>
                                        <th class="px-6 py-3.5">Tanggal</th>
                                        <th class="px-6 py-3.5">Status</th>
                                        <th class="px-6 py-3.5">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200/80 font-medium">
                                    @foreach($activityAttendances as $rec)
                                        <tr class="hover:bg-slate-50/50 transition duration-150">
                                            <td class="px-6 py-4 font-bold text-slate-800">{{ $rec->activity->name }}</td>
                                            <td class="px-6 py-4 text-xs text-slate-600">
                                                {{ $rec->activity->date->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($rec->status === 'hadir')
                                                    <span class="px-2 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-md text-[11px] font-bold uppercase">Hadir</span>
                                                @elseif($rec->status === 'sakit')
                                                    <span class="px-2 py-1 bg-amber-50 border border-amber-100 text-amber-700 rounded-md text-[11px] font-bold uppercase">Sakit</span>
                                                @elseif($rec->status === 'izin')
                                                    <span class="px-2 py-1 bg-slate-100 border border-slate-200 text-slate-600 rounded-md text-[11px] font-bold uppercase">Izin</span>
                                                @elseif($rec->status === 'alpa')
                                                    <span class="px-2 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-md text-[11px] font-bold uppercase">Alpa</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-xs text-slate-500">{{ $rec->notes ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-slate-100">
                            {{ $activityAttendances->links() }}
                        </div>
                    @endif
                </div>
            </div>

        @else
            <!-- Peringatan Tidak Ditemukan -->
            <div class="p-8 glass-card border-rose-200/60 bg-rose-50/30 text-center space-y-3 w-full max-w-2xl mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-rose-400 mx-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-lg font-bold text-rose-900">Mahasiswa Tidak Ditemukan</h3>
                <p class="text-sm text-rose-700">Tidak ada data mahasiswa dengan NIM <strong class="text-rose-900">{{ request('nim') }}</strong>. Pastikan penulisan NIM sudah benar.</p>
            </div>
        @endif
    @endif

    <div class="text-center pt-4">
        <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition flex items-center justify-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Halaman Login
        </a>
    </div>

</div>

<style>
    .tab-active  { color: #2563eb; border-color: #2563eb; background: white; }
    .tab-inactive { color: #64748b; border-color: transparent; }
    .tab-inactive:hover { color: #1e293b; background: #f1f5f9; }
</style>

<script>
function switchTab(tab) {
    // Sembunyikan semua pane
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
    // Reset semua button
    document.querySelectorAll('[data-tab]').forEach(function(btn) {
        btn.classList.remove('tab-active');
        btn.classList.add('tab-inactive');
    });

    // Tampilkan pane & aktifkan button yang dipilih
    document.getElementById('tab-' + tab).classList.remove('hidden');
    var activeBtn = document.getElementById('tab-btn-' + tab);
    activeBtn.classList.add('tab-active');
    activeBtn.classList.remove('tab-inactive');
}

// Aktifkan tab dari URL hash jika ada
(function() {
    var hash = window.location.hash.replace('#', '');
    if (hash === 'activity') switchTab('activity');
})();
</script>
@endsection

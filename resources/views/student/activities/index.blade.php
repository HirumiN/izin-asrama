@extends('layouts.app')

@section('title', 'Absen Kegiatan Kustom')
@section('page_title', 'Absen Kegiatan Kustom')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">

    <!-- Header Info Card -->
    <div class="glass-card p-5 border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="p-2.5 bg-blue-50 border border-blue-100 text-blue-600 rounded-xl shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
            </svg>
        </div>
        <div>
            <h2 class="text-sm font-bold text-slate-800">Absensi Kegiatan Kustom</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Catat kehadiran Anda pada kegiatan asrama hari ini,
                <span class="font-semibold text-slate-700">{{ today()->translatedFormat('d F Y') }}</span>.
            </p>
        </div>
    </div>

    <!-- Kegiatan Hari Ini -->
    <div class="glass-card border-slate-200/80 shadow-sm overflow-hidden">
        <!-- Section Header -->
        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
            <h3 class="text-sm font-bold text-slate-700">Kegiatan Hari Ini</h3>
        </div>

        @if($todayActivities->isEmpty())
            <div class="px-5 py-12 text-center">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-500">Tidak ada kegiatan hari ini.</p>
                <p class="text-xs text-slate-400 mt-1">Cek kembali esok hari atau lihat riwayat di bawah.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($todayActivities as $activity)
                    @php
                        $now        = \Carbon\Carbon::now();
                        $startTime  = \Carbon\Carbon::parse(today()->format('Y-m-d') . ' ' . $activity->start_time);
                        $endTime    = \Carbon\Carbon::parse(today()->format('Y-m-d') . ' ' . $activity->end_time);
                        $isOpen     = $now->between($startTime, $endTime);
                        $isUpcoming = $now->lessThan($startTime);
                        $isClosed   = $now->greaterThan($endTime);
                        $attendance = $todayAttendances->get($activity->id);
                    @endphp

                    <div class="px-5 py-4 flex items-center justify-between gap-4 {{ $attendance ? 'bg-emerald-50/50' : ($isOpen ? 'bg-blue-50/40' : '') }}">
                        <!-- Kiri: Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="font-bold text-slate-900 text-sm">{{ $activity->name }}</span>

                                @if($attendance)
                                    <span class="px-2 py-0.5 bg-emerald-100 border border-emerald-200 text-emerald-700 text-[10px] font-bold rounded-md uppercase tracking-wide">
                                        ✓ Sudah Absen
                                    </span>
                                @elseif($isOpen)
                                    <span class="px-2 py-0.5 bg-blue-100 border border-blue-200 text-blue-700 text-[10px] font-bold rounded-md uppercase tracking-wide">
                                        Buka
                                    </span>
                                @elseif($isUpcoming)
                                    <span class="px-2 py-0.5 bg-amber-100 border border-amber-200 text-amber-700 text-[10px] font-bold rounded-md uppercase tracking-wide">
                                        Belum Mulai
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-500 text-[10px] font-bold rounded-md uppercase tracking-wide">
                                        Tutup
                                    </span>
                                @endif
                            </div>

                            @if($activity->description)
                                <p class="text-xs text-slate-500 mb-1.5">{{ $activity->description }}</p>
                            @endif

                            <div class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $startTime->format('H:i') }} – {{ $endTime->format('H:i') }} WIB
                            </div>

                            @if($attendance)
                                <p class="text-[11px] text-emerald-700 font-semibold mt-1">
                                    Tercatat pukul {{ $attendance->updated_at->format('H:i') }} WIB
                                    @if($attendance->notes) · {{ $attendance->notes }} @endif
                                </p>
                            @elseif($isUpcoming)
                                <p class="text-[11px] text-amber-600 font-semibold mt-1"
                                   id="countdown-{{ $activity->id }}"
                                   data-target="{{ $startTime->toIso8601String() }}">
                                    Menghitung waktu...
                                </p>
                            @elseif($isClosed)
                                <p class="text-[11px] text-slate-400 font-semibold mt-1">Waktu absensi telah berakhir.</p>
                            @endif
                        </div>

                        <!-- Kanan: Aksi -->
                        <div class="shrink-0">
                            @if($attendance)
                                <div class="w-9 h-9 rounded-full bg-emerald-100 border-2 border-emerald-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-emerald-600">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                            @elseif($isOpen)
                                <form action="{{ route('student.activities.attendance', $activity->id) }}" method="POST"
                                      onsubmit="return confirm('Konfirmasi absensi untuk: {{ addslashes($activity->name) }}?')">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition duration-150 active:scale-95 cursor-pointer">
                                        Absen Sekarang
                                    </button>
                                </form>
                            @else
                                <button disabled
                                    class="px-4 py-2 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed border border-slate-200">
                                    {{ $isUpcoming ? 'Belum Buka' : 'Tutup' }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Riwayat Absensi Kegiatan -->
    <div class="glass-card border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-800">Riwayat Kehadiran Kegiatan</h3>
            <p class="text-xs text-slate-500 mt-0.5">Rekaman absensi kegiatan kustom Anda sebelumnya.</p>
        </div>

        @if($historyAttendances->isEmpty())
            <div class="px-5 py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mx-auto mb-2 text-slate-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                </svg>
                <p class="text-sm font-semibold text-slate-400">Belum ada riwayat absensi kegiatan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                        <tr>
                            <th class="px-5 py-3.5">Nama Kegiatan</th>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($historyAttendances as $rec)
                            <tr class="hover:bg-slate-50/40 transition duration-150">
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $rec->activity->name }}</td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">
                                    {{ $rec->activity->date->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($rec->status === 'hadir')
                                        <span class="inline-flex px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-lg text-[11px] font-bold uppercase tracking-wider">Hadir</span>
                                    @elseif($rec->status === 'sakit')
                                        <span class="inline-flex px-2.5 py-1 bg-amber-50 border border-amber-100 text-amber-700 rounded-lg text-[11px] font-bold uppercase tracking-wider">Sakit</span>
                                    @elseif($rec->status === 'izin')
                                        <span class="inline-flex px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-600 rounded-lg text-[11px] font-bold uppercase tracking-wider">Izin</span>
                                    @elseif($rec->status === 'alpa')
                                        <span class="inline-flex px-2.5 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-lg text-[11px] font-bold uppercase tracking-wider">Alpa</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">{{ $rec->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 border-t border-slate-100">
                {{ $historyAttendances->links() }}
            </div>
        @endif
    </div>

</div>

<script>
document.querySelectorAll('[data-target]').forEach(function(el) {
    var target = new Date(el.dataset.target).getTime();
    var elId = el.id;

    var interval = setInterval(function() {
        var diff = target - Date.now();
        if (diff <= 0) {
            clearInterval(interval);
            setTimeout(function() { window.location.reload(); }, 1500);
            return;
        }
        var h = Math.floor(diff / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        var parts = [];
        if (h > 0) parts.push(h + ' jam');
        if (m > 0 || h > 0) parts.push(m + ' menit');
        parts.push(s + ' detik');
        var el2 = document.getElementById(elId);
        if (el2) el2.textContent = 'Dimulai dalam ' + parts.join(' ');
    }, 1000);
});
</script>
@endsection

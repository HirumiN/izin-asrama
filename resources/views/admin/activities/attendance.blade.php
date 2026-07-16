@extends('layouts.app')

@section('title', 'Monitor Absen Kegiatan')
@section('page_title', 'Monitor Absen Kegiatan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Back button & Header Info -->
    <div class="flex flex-col gap-3">
        <a href="{{ route('admin.activities.index') }}" 
            class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Kegiatan
        </a>

        <!-- Detail Card -->
        <div class="glass-card p-6 border-slate-200/80 shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-full uppercase tracking-wider">
                        {{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d F Y') }}
                    </span>
                    <h3 class="text-xl font-bold text-slate-900 mt-2">{{ $activity->name }}</h3>
                    @if($activity->description)
                        <p class="text-xs text-slate-500 mt-1 max-w-2xl">{{ $activity->description }}</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-2 font-semibold">
                        🕐 {{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }} WIB
                        &nbsp;·&nbsp; Mahasiswa absen mandiri dari akun masing-masing
                    </p>
                </div>

                {{-- Status Kegiatan --}}
                @php
                    $activityDate = \Carbon\Carbon::parse($activity->date);
                    $now = \Carbon\Carbon::now();
                    $startTime = \Carbon\Carbon::parse($activity->date->format('Y-m-d') . ' ' . $activity->start_time);
                    $endTime   = \Carbon\Carbon::parse($activity->date->format('Y-m-d') . ' ' . $activity->end_time);
                @endphp
                @if($now->lessThan($startTime))
                    <span class="px-3 py-1.5 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold rounded-lg uppercase tracking-wide shrink-0">
                        Belum Dimulai
                    </span>
                @elseif($now->between($startTime, $endTime))
                    <span class="px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-lg uppercase tracking-wide shrink-0 animate-pulse">
                        Sedang Berlangsung
                    </span>
                @else
                    <span class="px-3 py-1.5 bg-slate-100 border border-slate-200 text-slate-600 text-xs font-bold rounded-lg uppercase tracking-wide shrink-0">
                        Selesai
                    </span>
                @endif
            </div>

            <!-- Statistik Ringkasan -->
            <div class="mt-5 pt-5 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-5 gap-3">
                <div class="text-center p-3 bg-emerald-50 border border-emerald-100 rounded-xl min-w-0">
                    <div class="text-2xl font-extrabold text-emerald-700">{{ $stats['hadir'] }}</div>
                    <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mt-0.5">Hadir</div>
                </div>
                <div class="text-center p-3 bg-amber-50 border border-amber-100 rounded-xl min-w-0">
                    <div class="text-2xl font-extrabold text-amber-700">{{ $stats['sakit'] }}</div>
                    <div class="text-[10px] font-bold text-amber-600 uppercase tracking-wider mt-0.5">Sakit</div>
                </div>
                <div class="text-center p-3 bg-slate-100 border border-slate-200 rounded-xl min-w-0">
                    <div class="text-2xl font-extrabold text-slate-700">{{ $stats['izin'] }}</div>
                    <div class="text-[10px] font-bold text-slate-600 uppercase tracking-wider mt-0.5">Izin</div>
                </div>
                <div class="text-center p-3 bg-rose-50 border border-rose-100 rounded-xl min-w-0">
                    <div class="text-2xl font-extrabold text-rose-700">{{ $stats['alpa'] }}</div>
                    <div class="text-[10px] font-bold text-rose-600 uppercase tracking-wider mt-0.5">Alpa</div>
                </div>
                <div class="text-center p-3 bg-slate-50 border border-dashed border-slate-300 rounded-xl col-span-2 sm:col-span-1 min-w-0">
                    <div class="text-2xl font-extrabold text-slate-500">{{ $stats['belum_absen'] }}</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Belum Absen</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Monitoring (Read-Only) -->
    <div class="glass-card overflow-hidden border-slate-200/80 shadow-md">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
            <span class="text-sm font-bold text-slate-700">Rekap Kehadiran Mahasiswa</span>
            <span class="ml-auto text-xs text-slate-400 font-medium">Total: {{ $stats['total'] }} mahasiswa</span>
        </div>
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-sm text-left text-slate-650">
                <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold whitespace-nowrap">
                    <tr>
                        <th class="px-6 py-4">Mahasiswa</th>
                        <th class="px-6 py-4">Kamar</th>
                        <th class="px-6 py-4">Status Kehadiran</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4">Waktu Absen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 font-medium">
                    @foreach($students as $student)
                        @php
                            $att = $attendances->get($student->id);
                        @endphp
                        <tr class="hover:bg-slate-50/30 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ $student->user->name }}</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">NIM: {{ $student->nim }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold rounded-lg">
                                    {{ $student->dorm_room }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($att)
                                    @if($att->status === 'hadir')
                                        <span class="inline-flex px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                            ✓ Hadir
                                        </span>
                                    @elseif($att->status === 'sakit')
                                        <span class="inline-flex px-2.5 py-1 bg-amber-50 border border-amber-100 text-amber-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                            Sakit
                                        </span>
                                    @elseif($att->status === 'izin')
                                        <span class="inline-flex px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                            Izin
                                        </span>
                                    @elseif($att->status === 'alpa')
                                        <span class="inline-flex px-2.5 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                            Alpa
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex px-2.5 py-1 bg-slate-50 border border-dashed border-slate-300 text-slate-400 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                        Belum Absen
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 min-w-[150px]">
                                {{ $att?->notes ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 whitespace-nowrap">
                                @if($att)
                                    {{ $att->updated_at->translatedFormat('d M Y, H:i') }} WIB
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Monitoring Absen Shalat')
@section('page_title', 'Monitoring Absen Shalat')

@section('content')
<style>
    .prayer-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1.5rem;
    }
    .search-form-grid {
        display: grid;
        grid-template-columns: 2fr 1fr auto;
        gap: 1rem;
        align-items: flex-end;
    }
    @media (max-width: 1024px) {
        .prayer-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 768px) {
        .prayer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .search-form-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .prayer-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="space-y-8 max-w-7xl mx-auto">
    <!-- Ringkasan Statistik -->
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                Rekap Absensi Hari Ini
                <span class="text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200/60 px-2 py-0.5 rounded-full">
                    {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                </span>
            </h2>
        </div>
        
        <div class="prayer-grid">
            @php
                $prayerIcons = [
                    'subuh' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-indigo-550 text-indigo-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m.386-6.364 1.591 1.591M12 18.75a6.75 6.75 0 1 1 0-13.5 6.75 6.75 0 0 1 0 13.5Z" /></svg>',
                    'dzuhur' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-amber-550 text-amber-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m.386-6.364 1.591 1.591M12 7.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z" /></svg>',
                    'ashar' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-sky-550 text-sky-500"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 0 0 4.5 4.5H18a3.75 3.75 0 0 0 1.332-7.257 3 3 0 0 0-3.758-3.848 5.25 5.25 0 0 0-10.233 2.33A4.502 4.502 0 0 0 2.25 15Z" /></svg>',
                    'maghrib' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-rose-550 text-rose-500"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m.386-6.364 1.591 1.591M12 18.75a6.75 6.75 0 1 1 0-13.5 6.75 6.75 0 0 1 0 13.5Z" /></svg>',
                    'isya' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-indigo-700"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" /></svg>'
                ];
            @endphp
            @foreach($prayers as $prayer)
                @php
                    $s = $stats[$prayer];
                    $totalFilled = $s['berjamaah'] + $s['munfarid'] + $s['sakit'] + $s['izin'];
                    $fillPercent = $totalStudents > 0 ? ($totalFilled / $totalStudents) * 100 : 0;
                @endphp
                <div class="glass-card p-5 border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition duration-300">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div class="p-1.5 bg-slate-100/80 rounded-lg">
                                    {!! $prayerIcons[$prayer] ?? '' !!}
                                </div>
                                <span class="text-sm font-bold text-slate-800 capitalize font-semibold">{{ $prayer }}</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">{{ $totalFilled }}/{{ $totalStudents }}</span>
                        </div>

                        <!-- Progress Bar Pengisian -->
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mb-4 overflow-hidden">
                            <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $fillPercent }}%"></div>
                        </div>

                        <!-- List Status -->
                        <div class="space-y-2 text-[11px]">
                            <div class="flex justify-between items-center py-0.5 border-b border-slate-100/50">
                                <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Berjamaah
                                </span>
                                <span class="font-bold text-slate-800 bg-emerald-50 px-1.5 py-0.5 rounded">{{ $s['berjamaah'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-0.5 border-b border-slate-100/50">
                                <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    Munfarid
                                </span>
                                <span class="font-bold text-slate-800 bg-blue-50 px-1.5 py-0.5 rounded">{{ $s['munfarid'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-0.5 border-b border-slate-100/50">
                                <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Sakit
                                </span>
                                <span class="font-bold text-slate-800 bg-amber-50 px-1.5 py-0.5 rounded">{{ $s['sakit'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-0.5 border-b border-slate-100/50">
                                <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Izin / Luar
                                </span>
                                <span class="font-bold text-slate-800 bg-slate-100 px-1.5 py-0.5 rounded">{{ $s['izin'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-0.5">
                                <span class="flex items-center gap-1.5 text-slate-500 font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Alpa
                                </span>
                                <span class="font-bold text-slate-800 bg-rose-50 px-1.5 py-0.5 rounded">{{ $s['alpa'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="glass-card p-6 border-slate-200/80 shadow-sm">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
            <div class="p-2 bg-blue-50 border border-blue-100 text-blue-600 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Pencarian & Filter Pemantauan</h3>
                <p class="text-xs text-slate-500">Cari mahasiswa berdasarkan nama/NIM atau saring data berdasarkan tanggal sholat.</p>
            </div>
        </div>

        <form action="{{ route('admin.sholat.index') }}" method="GET" class="search-form-grid">
            <!-- Cari Mahasiswa Input -->
            <div class="w-full">
                <label for="search" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Cari Mahasiswa</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors" style="padding-left: 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" id="search" value="{{ $search }}"
                        placeholder="Masukkan nama atau NIM mahasiswa..."
                        style="padding-left: 2.25rem;"
                        class="w-full pr-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-150 focus:border-blue-600 transition-all text-sm shadow-sm font-medium">
                </div>
            </div>

            <!-- Pilih Tanggal Input -->
            <div class="w-full">
                <label for="date" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Tanggal Pemantauan</label>
                <input type="date" name="date" id="date" value="{{ $selectedDate }}"
                    class="w-full px-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-150 focus:border-blue-600 transition-all text-sm shadow-sm font-medium">
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 w-full">
                <button type="submit"
                    class="flex-1 py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition-all duration-150 transform active:scale-[0.98] whitespace-nowrap flex items-center justify-center gap-2 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                    </svg>
                    Tampilkan
                </button>
                @if($search || $selectedDate !== today()->format('Y-m-d'))
                    <a href="{{ route('admin.sholat.index') }}"
                        class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 text-slate-700 rounded-xl text-sm font-bold transition-all duration-150 whitespace-nowrap flex items-center justify-center cursor-pointer">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Rekapitulasi Mahasiswa -->
    <div class="glass-card p-6" id="container-admin-sholat-monitoring">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Rekap Absensi Mahasiswa</h3>
                <p class="text-xs text-slate-500 mt-0.5">Menampilkan status absen shalat seluruh mahasiswa tanggal <strong>{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</strong>.</p>
            </div>
        </div>

        @if($students->isEmpty())
            <div class="text-center py-12 text-slate-400 text-sm font-medium">
                Tidak ada data mahasiswa ditemukan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-650">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                        <tr>
                            <th class="px-6 py-3">Nama Mahasiswa</th>
                            <th class="px-6 py-3">Kamar</th>
                            <th class="px-6 py-3 text-center">Subuh</th>
                            <th class="px-6 py-3 text-center">Dzuhur</th>
                            <th class="px-6 py-3 text-center">Ashar</th>
                            <th class="px-6 py-3 text-center">Maghrib</th>
                            <th class="px-6 py-3 text-center">Isya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 font-medium">
                        @foreach($students as $student)
                            @php
                                $dayAttendances = $student->prayerAttendances->keyBy('prayer_time');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800">{{ $student->user->name }}</span>
                                        <span class="text-[10px] text-slate-400 mt-0.5">NIM: {{ $student->nim }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    {{ $student->dorm_room }}
                                </td>
                                @foreach($prayers as $prayer)
                                    @php
                                        $att = $dayAttendances->get($prayer);
                                    @endphp
                                    <td class="px-6 py-4 text-center">
                                        @if($att)
                                            @if($att->status === 'berjamaah')
                                                <span class="inline-flex px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                                    Berjamaah
                                                </span>
                                            @elseif($att->status === 'munfarid')
                                                <span class="inline-flex px-2.5 py-1 bg-blue-50 border border-blue-100 text-blue-700 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                                    Munfarid
                                                </span>
                                            @elseif($att->status === 'sakit')
                                                <span class="inline-flex px-2.5 py-1 bg-amber-50 border border-amber-100 text-amber-700 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                                    Sakit
                                                </span>
                                            @elseif($att->status === 'izin')
                                                <span class="inline-flex px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-600 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                                    Izin
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex px-2.5 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                                Alpa
                                            </span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

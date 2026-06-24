@extends('layouts.app')

@section('title', 'Monitoring Absen Shalat')
@section('page_title', 'Monitoring Absen Shalat')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    <!-- Filter Bar -->
    <div class="glass-card p-6 border-slate-200/80 shadow-sm">
        <form action="{{ route('admin.sholat.index') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 w-full md:w-auto">
                <label for="search" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Cari Mahasiswa</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" id="search" value="{{ $search }}"
                        placeholder="Cari nama atau NIM..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-sm shadow-sm">
                </div>
            </div>

            <div class="w-full md:w-64">
                <label for="date" class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Pilih Tanggal</label>
                <input type="date" name="date" id="date" value="{{ $selectedDate }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-sm shadow-sm">
            </div>

            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit"
                    class="flex-1 md:flex-initial py-2.5 px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-150 whitespace-nowrap">
                    Terapkan Filter
                </button>
                @if($search || $selectedDate !== today()->format('Y-m-d'))
                    <a href="{{ route('admin.sholat.index') }}"
                        class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-650 rounded-xl text-sm font-bold transition whitespace-nowrap">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        @foreach($prayers as $prayer)
            @php
                $s = $stats[$prayer];
                $totalFilled = $s['berjamaah'] + $s['munfarid'] + $s['sakit'] + $s['izin'];
                $fillPercent = $totalStudents > 0 ? ($totalFilled / $totalStudents) * 100 : 0;
            @endphp
            <div class="glass-card p-5 border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-slate-800 capitalize">{{ $prayer }}</span>
                        <span class="text-xs font-semibold text-slate-400">{{ $totalFilled }}/{{ $totalStudents }} Terisi</span>
                    </div>

                    <!-- Progress Bar Pengisian -->
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mb-4">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $fillPercent }}%"></div>
                    </div>

                    <!-- List Status -->
                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between font-semibold text-emerald-650">
                            <span>Berjamaah:</span>
                            <span>{{ $s['berjamaah'] }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-blue-650">
                            <span>Munfarid:</span>
                            <span>{{ $s['munfarid'] }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-amber-650">
                            <span>Sakit:</span>
                            <span>{{ $s['sakit'] }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-slate-600">
                            <span>Izin / Luar:</span>
                            <span>{{ $s['izin'] }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-rose-650">
                            <span>Alpa:</span>
                            <span>{{ $s['alpa'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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

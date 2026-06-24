@extends('layouts.app')

@section('title', 'Absen Shalat')
@section('page_title', 'Absen Shalat')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header info -->
    <div class="glass-card p-6 border-blue-100/80 bg-blue-50/20 shadow-sm flex items-center gap-4">
        <div class="p-3 bg-blue-600 rounded-xl text-white shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Absen Shalat Harian</h2>
            <p class="text-slate-500 text-xs mt-0.5">Silakan laporkan pelaksanaan shalat wajib 5 waktu Anda untuk tanggal hari ini: <strong>{{ today()->translatedFormat('d F Y') }}</strong>.</p>
        </div>
    </div>

    <!-- Grid Shalat 5 Waktu -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        @foreach($prayers as $prayer)
            @php
                $attendance = $todayAttendances->get($prayer);
            @endphp
            <div class="glass-card overflow-hidden border border-slate-200 shadow-sm flex flex-col justify-between h-full hover:shadow-md transition duration-200">
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <span class="text-base font-bold text-slate-800 capitalize">{{ $prayer }}</span>
                    <span class="text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-1 flex flex-col justify-center">
                    @if($attendance)
                        <!-- Jika sudah absen -->
                        <div class="text-center py-4 space-y-3" id="status-display-{{ $prayer }}">
                            @if($attendance->status === 'berjamaah')
                                <div class="inline-flex p-2 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-emerald-700">Berjamaah di Masjid</p>
                            @elseif($attendance->status === 'munfarid')
                                <div class="inline-flex p-2 bg-blue-50 text-blue-600 rounded-full border border-blue-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-blue-700">Munfarid (Sendiri)</p>
                            @elseif($attendance->status === 'sakit')
                                <div class="inline-flex p-2 bg-amber-50 text-amber-600 rounded-full border border-amber-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-amber-700">Sakit</p>
                            @elseif($attendance->status === 'izin')
                                <div class="inline-flex p-2 bg-slate-100 text-slate-600 rounded-full border border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0l-3-3m3 3h-7.5" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Izin / Bermalam</p>
                            @endif

                            <div class="pt-2">
                                <button type="button" onclick="showEditForm('{{ $prayer }}')"
                                    class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                                    Ubah Absen
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Form Input Absen (Hidden jika sudah absen, unless diedit) -->
                    <form action="{{ route('student.sholat.store') }}" method="POST" 
                          id="form-{{ $prayer }}" 
                          class="{{ $attendance ? 'hidden' : '' }} space-y-4">
                        @csrf
                        <input type="hidden" name="prayer_time" value="{{ $prayer }}">
                        
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Status</label>
                            <div class="grid grid-cols-1 gap-2">
                                <label class="border border-slate-200 rounded-xl p-2.5 flex items-center gap-2 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" name="status" value="berjamaah" required 
                                        {{ $attendance && $attendance->status === 'berjamaah' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs font-semibold text-slate-700">Berjamaah</span>
                                </label>
                                <label class="border border-slate-200 rounded-xl p-2.5 flex items-center gap-2 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" name="status" value="munfarid" 
                                        {{ $attendance && $attendance->status === 'munfarid' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs font-semibold text-slate-700">Munfarid</span>
                                </label>
                                <label class="border border-slate-200 rounded-xl p-2.5 flex items-center gap-2 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" name="status" value="sakit" 
                                        {{ $attendance && $attendance->status === 'sakit' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs font-semibold text-slate-700">Sakit</span>
                                </label>
                                <label class="border border-slate-200 rounded-xl p-2.5 flex items-center gap-2 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" name="status" value="izin" 
                                        {{ $attendance && $attendance->status === 'izin' ? 'checked' : '' }}
                                        class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-xs font-semibold text-slate-700">Izin / Luar</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            @if($attendance)
                                <button type="button" onclick="cancelEdit('{{ $prayer }}')"
                                    class="flex-1 py-2 px-3 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">
                                    Batal
                                </button>
                            @endif
                            <button type="submit" 
                                class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Riwayat Absensi Shalat -->
    <div class="glass-card p-6" id="container-sholat-history">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Riwayat Absen Shalat</h3>

        @if($historyDates->isEmpty())
            <div class="text-center py-8 text-slate-400 text-sm font-medium">
                Belum ada data riwayat shalat sebelum hari ini.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3 text-center">Subuh</th>
                            <th class="px-6 py-3 text-center">Dzuhur</th>
                            <th class="px-6 py-3 text-center">Ashar</th>
                            <th class="px-6 py-3 text-center">Maghrib</th>
                            <th class="px-6 py-3 text-center">Isya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 font-medium">
                        @foreach($historyDates as $dateObj)
                            @php
                                $dateStr = $dateObj->date->format('Y-m-d');
                                $dayAttendances = $historyAttendances->get($dateStr) ?? collect();
                                $dayAttendances = $dayAttendances->keyBy('prayer_time');
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $dateObj->date->translatedFormat('d F Y') }}
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
                {{ $historyDates->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function showEditForm(prayer) {
        document.getElementById('status-display-' + prayer).classList.add('hidden');
        document.getElementById('form-' + prayer).classList.remove('hidden');
    }

    function cancelEdit(prayer) {
        document.getElementById('status-display-' + prayer).classList.remove('hidden');
        document.getElementById('form-' + prayer).classList.add('hidden');
    }
</script>
@endsection

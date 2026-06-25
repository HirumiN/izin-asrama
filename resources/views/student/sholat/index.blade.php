@extends('layouts.app')

@section('title', 'Absen Shalat')
@section('page_title', 'Absen Shalat')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header Info & Desain Banner Premium -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-700 p-8 text-white shadow-lg border border-white/10">
        <!-- Dekorasi Ornamen Latar Belakang -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/25 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row items-center gap-6">
            <div class="p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-inner flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-blue-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div class="text-center sm:text-left space-y-1">
                <h2 class="text-2xl font-black tracking-tight">Absensi Shalat Wajib</h2>
                <p class="text-blue-100/90 text-sm font-medium">
                    Silakan laporkan pelaksanaan shalat wajib 5 waktu Anda untuk hari ini: 
                    <span class="px-2.5 py-0.5 bg-white/15 rounded-md font-bold text-white ml-1 border border-white/10">
                        {{ today()->translatedFormat('d F Y') }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Grid Shalat 5 Waktu -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        @php
            $prayerThemes = [
                'subuh' => [
                    'bg' => 'from-amber-500/10 to-orange-500/5',
                    'border' => 'border-amber-200/60',
                    'icon' => '<svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>',
                    'title' => 'Subuh'
                ],
                'dzuhur' => [
                    'bg' => 'from-sky-500/10 to-blue-500/5',
                    'border' => 'border-sky-200/60',
                    'icon' => '<svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m6.364.364-1.06 1.06M21 12h-1.5m-.364 6.364-1.06-1.06M12 21v-1.5m-6.364-.364 1.06-1.06M3 12h1.5m.364-6.364 1.06 1.06M12 7.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z"/></svg>',
                    'title' => 'Dzuhur'
                ],
                'ashar' => [
                    'bg' => 'from-indigo-500/10 to-violet-500/5',
                    'border' => 'border-indigo-200/60',
                    'icon' => '<svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>',
                    'title' => 'Ashar'
                ],
                'maghrib' => [
                    'bg' => 'from-violet-500/10 to-fuchsia-500/5',
                    'border' => 'border-violet-200/60',
                    'icon' => '<svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>',
                    'title' => 'Maghrib'
                ],
                'isya' => [
                    'bg' => 'from-slate-650/10 to-slate-900/5',
                    'border' => 'border-slate-300/65',
                    'icon' => '<svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>',
                    'title' => 'Isya'
                ],
            ];
        @endphp

        @foreach($prayers as $prayer)
            @php
                $attendance = $todayAttendances->get($prayer);
                $theme = $prayerThemes[$prayer];
            @endphp
            <div class="glass-card overflow-hidden border {{ $theme['border'] }} bg-gradient-to-br {{ $theme['bg'] }} shadow-sm flex flex-col justify-between h-full hover:shadow-md transition-all duration-200 rounded-2xl group">
                <!-- Card Header -->
                <div class="p-5 border-b border-slate-100/80 flex items-center justify-between bg-white/40">
                    <span class="text-base font-bold text-slate-800 tracking-tight">{{ $theme['title'] }}</span>
                    <div class="p-1.5 bg-white rounded-lg shadow-sm group-hover:scale-105 transition-transform">
                        {!! $theme['icon'] !!}
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-1 flex flex-col justify-center">
                    @if($attendance)
                        <!-- Tampilan Jika Sudah Absen -->
                        <div class="text-center py-4 space-y-4" id="status-display-{{ $prayer }}">
                            @if($attendance->status === 'berjamaah')
                                <div class="inline-flex p-3 bg-emerald-55/15 text-emerald-600 rounded-2xl border border-emerald-200/50 shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                </div>
                                <p class="text-sm font-extrabold text-emerald-800">Berjamaah di Masjid</p>
                            @elseif($attendance->status === 'munfarid')
                                <div class="inline-flex p-3 bg-blue-55/15 text-blue-600 rounded-2xl border border-blue-200/50 shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                </div>
                                <p class="text-sm font-extrabold text-blue-800">Munfarid (Sendiri)</p>
                            @elseif($attendance->status === 'sakit')
                                <div class="inline-flex p-3 bg-amber-55/15 text-amber-600 rounded-2xl border border-amber-200/50 shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                                </div>
                                <p class="text-sm font-extrabold text-amber-800">Sakit</p>
                            @elseif($attendance->status === 'izin')
                                <div class="inline-flex p-3 bg-slate-100/80 text-slate-655 rounded-2xl border border-slate-250 shadow-sm">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0l-3-3m3 3h-7.5"/></svg>
                                </div>
                                <p class="text-sm font-extrabold text-slate-800">Izin / Bermalam</p>
                            @endif

                            <div class="pt-2">
                                <button type="button" onclick="showEditForm('{{ $prayer }}')"
                                    class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    Ubah Absen
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Form Input Absen (Gaya Custom Cards yang Sangat Responsif) -->
                    <form action="{{ route('student.sholat.store') }}" method="POST" 
                          id="form-{{ $prayer }}" 
                          class="{{ $attendance ? 'hidden' : '' }} space-y-4">
                        @csrf
                        <input type="hidden" name="prayer_time" value="{{ $prayer }}">
                        
                        <div class="space-y-3">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Pilih Status</label>
                            
                            <div class="grid grid-cols-1 gap-2">
                                <!-- Berjamaah Option -->
                                <label class="relative flex items-center p-2.5 rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 cursor-pointer transition select-none group">
                                    <input type="radio" name="status" value="berjamaah" required 
                                        {{ $attendance && $attendance->status === 'berjamaah' ? 'checked' : '' }}
                                        class="peer sr-only">
                                    <div class="peer-checked:border-emerald-500 peer-checked:bg-emerald-50/15 absolute inset-0 rounded-xl border border-transparent transition-all pointer-events-none"></div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-emerald-600 peer-checked:bg-emerald-600 flex items-center justify-center shrink-0 transition-colors">
                                        <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 ml-2.5 group-hover:text-slate-900 transition-colors">Berjamaah</span>
                                </label>

                                <!-- Munfarid Option -->
                                <label class="relative flex items-center p-2.5 rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 cursor-pointer transition select-none group">
                                    <input type="radio" name="status" value="munfarid" 
                                        {{ $attendance && $attendance->status === 'munfarid' ? 'checked' : '' }}
                                        class="peer sr-only">
                                    <div class="peer-checked:border-blue-500 peer-checked:bg-blue-50/15 absolute inset-0 rounded-xl border border-transparent transition-all pointer-events-none"></div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center shrink-0 transition-colors">
                                        <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 ml-2.5 group-hover:text-slate-900 transition-colors">Munfarid</span>
                                </label>

                                <!-- Sakit Option -->
                                <label class="relative flex items-center p-2.5 rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 cursor-pointer transition select-none group">
                                    <input type="radio" name="status" value="sakit" 
                                        {{ $attendance && $attendance->status === 'sakit' ? 'checked' : '' }}
                                        class="peer sr-only">
                                    <div class="peer-checked:border-amber-500 peer-checked:bg-amber-50/15 absolute inset-0 rounded-xl border border-transparent transition-all pointer-events-none"></div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-amber-600 peer-checked:bg-amber-600 flex items-center justify-center shrink-0 transition-colors">
                                        <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 ml-2.5 group-hover:text-slate-900 transition-colors">Sakit</span>
                                </label>

                                <!-- Izin Option -->
                                <label class="relative flex items-center p-2.5 rounded-xl border border-slate-200/80 bg-white hover:bg-slate-50 cursor-pointer transition select-none group">
                                    <input type="radio" name="status" value="izin" 
                                        {{ $attendance && $attendance->status === 'izin' ? 'checked' : '' }}
                                        class="peer sr-only">
                                    <div class="peer-checked:border-slate-550 peer-checked:bg-slate-100 absolute inset-0 rounded-xl border border-transparent transition-all pointer-events-none"></div>
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-slate-600 peer-checked:bg-slate-600 flex items-center justify-center shrink-0 transition-colors">
                                        <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 ml-2.5 group-hover:text-slate-900 transition-colors">Izin / Luar</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-1.5">
                            @if($attendance)
                                <button type="button" onclick="cancelEdit('{{ $prayer }}')"
                                    class="flex-1 py-2 px-3 border border-slate-250 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-105 transition-all">
                                    Batal
                                </button>
                            @endif
                            <button type="submit" 
                                class="flex-1 py-2 px-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md active:scale-95 transform">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Riwayat Absensi Shalat -->
    <div class="p-6 glass-card border border-slate-200 shadow-sm space-y-6" id="container-sholat-history">
        <div class="pb-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Riwayat Kehadiran Shalat</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar rekaman absensi ibadah harian Anda sebelumnya.</p>
            </div>
        </div>

        @if($historyDates->isEmpty())
            <div class="text-center py-12 text-slate-400 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto mb-3 text-slate-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                Belum ada data riwayat shalat sebelum hari ini.
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-sm text-left text-slate-650">
                    <thead class="text-xs uppercase bg-slate-50/80 text-slate-500 border-b border-slate-200 font-extrabold tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Subuh</th>
                            <th class="px-6 py-4 text-center">Dzuhur</th>
                            <th class="px-6 py-4 text-center">Ashar</th>
                            <th class="px-6 py-4 text-center">Maghrib</th>
                            <th class="px-6 py-4 text-center">Isya</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 font-medium">
                        @foreach($historyDates as $dateObj)
                            @php
                                $dateStr = $dateObj->date->format('Y-m-d');
                                $dayAttendances = $historyAttendances->get($dateStr) ?? collect();
                                $dayAttendances = $dayAttendances->keyBy('prayer_time');
                            @endphp
                            <tr class="hover:bg-slate-50/40 transition duration-150">
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
                                                <span class="inline-flex px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                                    Berjamaah
                                                </span>
                                            @elseif($att->status === 'munfarid')
                                                <span class="inline-flex px-2.5 py-1 bg-blue-50 border border-blue-100 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                                    Munfarid
                                                </span>
                                            @elseif($att->status === 'sakit')
                                                <span class="inline-flex px-2.5 py-1 bg-amber-50 border border-amber-100 text-amber-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                                    Sakit
                                                </span>
                                            @elseif($att->status === 'izin')
                                                <span class="inline-flex px-2.5 py-1 bg-slate-105 border border-slate-200 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                                                    Izin
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex px-2.5 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-lg text-[10px] font-bold uppercase tracking-wider animate-pulse">
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

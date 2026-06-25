@extends('layouts.app')

@section('title', 'Absen Shalat')
@section('page_title', 'Absen Shalat')

@section('content')
<!-- Style Murni CSS untuk Menjamin Interaksi & Grid 100% Bekerja Tanpa Cache Tailwind -->
<style>
    /* Grid layout utama untuk 5 waktu shalat */
    .prayer-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
        gap: 16px;
    }
    
    /* Grid untuk 4 opsi di dalam masing-masing shalat (2 Kolom, 2 Baris) */
    .status-options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 6px;
    }
    
    /* Desain Card Status Opsi */
    .status-card {
        position: relative !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 10px 6px !important;
        border-radius: 12px !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        cursor: pointer !important;
        transition: all 0.15s ease-in-out !important;
        min-height: 74px !important;
        text-align: center !important;
        user-select: none !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04) !important;
    }
    
    .status-card:hover {
        background-color: #f8fafc !important;
        border-color: #94a3b8 !important;
    }
    
    /* Sembunyikan radio input secara fisik & aman */
    .status-radio-hidden {
        position: absolute !important;
        opacity: 0 !important;
        width: 0 !important;
        height: 0 !important;
        pointer-events: none !important;
        z-index: -1 !important;
    }
    
    /* Bulatan Indikator Pilihan (Top Right) */
    .status-indicator-dot {
        position: absolute !important;
        top: 6px !important;
        right: 6px !important;
        width: 12px !important;
        height: 12px !important;
        border-radius: 50% !important;
        border: 1.5px solid #94a3b8 !important;
        background-color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.15s ease !important;
    }
    
    .status-indicator-dot-inner {
        width: 5px !important;
        height: 5px !important;
        border-radius: 50% !important;
        background-color: #ffffff !important;
        opacity: 0 !important;
        transition: all 0.15s ease !important;
    }
    
    /* Efek Ketika Terpilih (Checked) dengan selector :has */
    
    /* 1. Berjamaah (Emerald Green) */
    .status-card-berjamaah:has(.status-radio-hidden:checked) {
        border-color: #10b981 !important;
        background-color: #ecfdf5 !important;
        box-shadow: 0 0 0 1px #10b981 !important;
    }
    .status-card-berjamaah:has(.status-radio-hidden:checked) .status-indicator-dot {
        border-color: #059669 !important;
        background-color: #059669 !important;
    }
    .status-card-berjamaah:has(.status-radio-hidden:checked) .status-indicator-dot-inner {
        opacity: 1 !important;
    }
    .status-card-berjamaah:has(.status-radio-hidden:checked) .status-option-icon {
        color: #059669 !important;
    }
    .status-card-berjamaah:has(.status-radio-hidden:checked) .status-option-text {
        color: #047857 !important;
    }
    
    /* 2. Munfarid (Blue/Indigo) */
    .status-card-munfarid:has(.status-radio-hidden:checked) {
        border-color: #3b82f6 !important;
        background-color: #eff6ff !important;
        box-shadow: 0 0 0 1px #3b82f6 !important;
    }
    .status-card-munfarid:has(.status-radio-hidden:checked) .status-indicator-dot {
        border-color: #2563eb !important;
        background-color: #2563eb !important;
    }
    .status-card-munfarid:has(.status-radio-hidden:checked) .status-indicator-dot-inner {
        opacity: 1 !important;
    }
    .status-card-munfarid:has(.status-radio-hidden:checked) .status-option-icon {
        color: #2563eb !important;
    }
    .status-card-munfarid:has(.status-radio-hidden:checked) .status-option-text {
        color: #1d4ed8 !important;
    }
    
    /* 3. Sakit (Amber) */
    .status-card-sakit:has(.status-radio-hidden:checked) {
        border-color: #f59e0b !important;
        background-color: #fffbeb !important;
        box-shadow: 0 0 0 1px #f59e0b !important;
    }
    .status-card-sakit:has(.status-radio-hidden:checked) .status-indicator-dot {
        border-color: #d97706 !important;
        background-color: #d97706 !important;
    }
    .status-card-sakit:has(.status-radio-hidden:checked) .status-indicator-dot-inner {
        opacity: 1 !important;
    }
    .status-card-sakit:has(.status-radio-hidden:checked) .status-option-icon {
        color: #d97706 !important;
    }
    .status-card-sakit:has(.status-radio-hidden:checked) .status-option-text {
        color: #b45309 !important;
    }
    
    /* 4. Izin (Slate) */
    .status-card-izin:has(.status-radio-hidden:checked) {
        border-color: #64748b !important;
        background-color: #f8fafc !important;
        box-shadow: 0 0 0 1px #64748b !important;
    }
    .status-card-izin:has(.status-radio-hidden:checked) .status-indicator-dot {
        border-color: #475569 !important;
        background-color: #475569 !important;
    }
    .status-card-izin:has(.status-radio-hidden:checked) .status-indicator-dot-inner {
        opacity: 1 !important;
    }
    .status-card-izin:has(.status-radio-hidden:checked) .status-option-icon {
        color: #475569 !important;
    }
    .status-card-izin:has(.status-radio-hidden:checked) .status-option-text {
        color: #334155 !important;
    }

    /* Style Tombol Aksi */
    .btn-submit {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 6px 16px !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2), 0 2px 4px -1px rgba(16, 185, 129, 0.1) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .btn-submit:hover {
        background: linear-gradient(135deg, #059669, #047857) !important;
        box-shadow: 0 6px 10px rgba(16, 185, 129, 0.3) !important;
        transform: translateY(-1px) !important;
    }
    
    .btn-submit:active {
        transform: translateY(0) !important;
    }

    .btn-cancel {
        background-color: #ffffff !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
        padding: 6px 16px !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    
    .btn-cancel:hover {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        border-color: #94a3b8 !important;
    }

    /* Modal transition styles */
    #confirm-modal {
        transition: opacity 0.2s ease-in-out;
    }
    #confirm-modal > div {
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
</style>

<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Judul Halaman Lokal -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Absen Shalat Harian</h1>
    </div>

    <!-- Header Info & Desain Banner Premium -->
    <div class="relative overflow-hidden rounded-2xl p-6 text-white shadow-lg border border-white/10" 
         style="background: linear-gradient(135deg, #2563eb 0%, #4f46e5 50%, #7c3aed 100%);">
        <!-- Dekorasi Latar Belakang -->
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row items-center gap-5">
            <div class="p-3.5 bg-white/15 backdrop-blur-md rounded-xl border border-white/20 shadow-inner flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div class="text-center sm:text-left space-y-1">
                <h2 class="text-xl font-extrabold tracking-tight" style="color: #ffffff; margin: 0;">Pelaporan Kehadiran Shalat</h2>
                <p class="text-white/90 text-xs font-medium" style="margin: 4px 0 0 0;">
                    Silakan laporkan pelaksanaan shalat wajib 5 waktu Anda untuk hari ini: 
                    <span class="px-2 py-0.5 bg-white/20 rounded font-bold text-white ml-1 border border-white/10">
                        {{ today()->translatedFormat('d F Y') }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Grid Shalat 5 Waktu (Horizontal Auto-Fit) -->
    <div class="prayer-cards-grid">
        @php
            $prayerThemes = [
                'subuh' => [
                    'bg' => 'from-amber-500/10 to-orange-500/5',
                    'border' => 'border-amber-200/60',
                    'icon' => '<svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>',
                    'title' => 'Subuh'
                ],
                'dzuhur' => [
                    'bg' => 'from-sky-500/10 to-blue-500/5',
                    'border' => 'border-sky-200/60',
                    'icon' => '<svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m6.364.364-1.06 1.06M21 12h-1.5m-.364 6.364-1.06-1.06M12 21v-1.5m-6.364-.364 1.06-1.06M3 12h1.5m.364-6.364 1.06 1.06M12 7.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z"/></svg>',
                    'title' => 'Dzuhur'
                ],
                'ashar' => [
                    'bg' => 'from-indigo-500/10 to-violet-500/5',
                    'border' => 'border-indigo-200/60',
                    'icon' => '<svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>',
                    'title' => 'Ashar'
                ],
                'maghrib' => [
                    'bg' => 'from-violet-500/10 to-fuchsia-500/5',
                    'border' => 'border-violet-200/60',
                    'icon' => '<svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>',
                    'title' => 'Maghrib'
                ],
                'isya' => [
                    'bg' => 'from-slate-500/10 to-slate-800/5',
                    'border' => 'border-slate-300',
                    'icon' => '<svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>',
                    'title' => 'Isya'
                ],
            ];
        @endphp

        @foreach($prayers as $prayer)
            @php
                $attendance = $todayAttendances->get($prayer);
                $theme = $prayerThemes[$prayer];
            @endphp
            <div class="glass-card overflow-hidden border {{ $theme['border'] }} bg-gradient-to-br {{ $theme['bg'] }} shadow-sm flex flex-col justify-between h-full hover:shadow-md transition-all duration-200 rounded-xl group">
                <!-- Card Header -->
                <div class="p-4 border-b border-slate-100/80 flex items-center justify-between bg-white/40">
                    <span class="text-sm font-extrabold text-slate-800 tracking-tight">{{ $theme['title'] }}</span>
                    <div class="p-1 bg-white rounded-lg shadow-sm group-hover:scale-105 transition-transform">
                        {!! $theme['icon'] !!}
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 flex-1 flex flex-col justify-center">
                    @if($attendance)
                        <!-- Tampilan Jika Sudah Absen (Tidak Bisa Diubah) -->
                        <div class="text-center py-4 space-y-3" id="status-display-{{ $prayer }}">
                            @if($attendance->status === 'berjamaah')
                                <div class="inline-flex p-2.5 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-200/50 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                </div>
                                <p class="text-xs font-extrabold text-emerald-800">Berjamaah</p>
                            @elseif($attendance->status === 'munfarid')
                                <div class="inline-flex p-2.5 bg-blue-50 text-blue-600 rounded-xl border border-blue-200/50 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                </div>
                                <p class="text-xs font-extrabold text-blue-800">Munfarid</p>
                            @elseif($attendance->status === 'sakit')
                                <div class="inline-flex p-2.5 bg-amber-50 text-amber-600 rounded-xl border border-amber-200/50 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                                </div>
                                <p class="text-xs font-extrabold text-amber-800">Sakit</p>
                            @elseif($attendance->status === 'izin')
                                <div class="inline-flex p-2.5 bg-slate-100 text-slate-655 rounded-xl border border-slate-200 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0l-3-3m3 3h-7.5"/></svg>
                                </div>
                                <p class="text-xs font-extrabold text-slate-800">Izin / Luar</p>
                            @endif
                        </div>
                    @endif

                    <!-- Form Input Absen -->
                    <form action="{{ route('student.sholat.store') }}" method="POST" 
                          id="form-{{ $prayer }}" 
                          class="{{ $attendance ? 'hidden' : '' }} space-y-3"
                          onsubmit="handleFormSubmit(event, this)">
                        @csrf
                        <input type="hidden" name="prayer_time" value="{{ $prayer }}">
                        
                        <div class="space-y-1.5">
                            <!-- Grid Pilihan 2x2 Diatur Secara Presisi via CSS -->
                            <div class="status-options-grid">
                                <!-- Berjamaah Option -->
                                <label class="status-card status-card-berjamaah">
                                    <input type="radio" name="status" value="berjamaah" required 
                                        {{ $attendance && $attendance->status === 'berjamaah' ? 'checked' : '' }}
                                        class="status-radio-hidden">
                                    
                                    <!-- Indicator dot on top-right -->
                                    <div class="status-indicator-dot">
                                        <div class="status-indicator-dot-inner"></div>
                                    </div>

                                    <!-- Option Icon -->
                                    <div class="status-option-icon text-slate-400 group-hover:text-slate-500 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M12 12.75a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/></svg>
                                    </div>
                                    
                                    <span class="status-option-text text-[10px] font-extrabold text-slate-655 mt-1 block">Masjid</span>
                                </label>

                                <!-- Munfarid Option -->
                                <label class="status-card status-card-munfarid">
                                    <input type="radio" name="status" value="munfarid" 
                                        {{ $attendance && $attendance->status === 'munfarid' ? 'checked' : '' }}
                                        class="status-radio-hidden">
                                    
                                    <!-- Indicator dot on top-right -->
                                    <div class="status-indicator-dot">
                                        <div class="status-indicator-dot-inner"></div>
                                    </div>

                                    <!-- Option Icon -->
                                    <div class="status-option-icon text-slate-400 group-hover:text-slate-500 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0zM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    </div>
                                    
                                    <span class="status-option-text text-[10px] font-extrabold text-slate-655 mt-1 block">Sendiri</span>
                                </label>

                                <!-- Sakit Option -->
                                <label class="status-card status-card-sakit">
                                    <input type="radio" name="status" value="sakit" 
                                        {{ $attendance && $attendance->status === 'sakit' ? 'checked' : '' }}
                                        class="status-radio-hidden">
                                    
                                    <!-- Indicator dot on top-right -->
                                    <div class="status-indicator-dot">
                                        <div class="status-indicator-dot-inner"></div>
                                    </div>

                                    <!-- Option Icon -->
                                    <div class="status-option-icon text-slate-400 group-hover:text-slate-500 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                                    </div>
                                    
                                    <span class="status-option-text text-[10px] font-extrabold text-slate-655 mt-1 block">Sakit</span>
                                </label>

                                <!-- Izin Option -->
                                <label class="status-card status-card-izin">
                                    <input type="radio" name="status" value="izin" 
                                        {{ $attendance && $attendance->status === 'izin' ? 'checked' : '' }}
                                        class="status-radio-hidden">
                                    
                                    <!-- Indicator dot on top-right -->
                                    <div class="status-indicator-dot">
                                        <div class="status-indicator-dot-inner"></div>
                                    </div>

                                    <!-- Option Icon -->
                                    <div class="status-option-icon text-slate-400 group-hover:text-slate-500 transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0l-3-3m3 3h-7.5"/></svg>
                                    </div>
                                    
                                    <span class="status-option-text text-[10px] font-extrabold text-slate-655 mt-1 block">Izin</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tombol Aksi Kanan Bawah -->
                        <div class="flex justify-end gap-1.5 pt-1">
                            <button type="submit" class="btn-submit">
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
                                                <span class="inline-flex px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-655 rounded-lg text-[10px] font-bold uppercase tracking-wider">
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

<!-- Custom Confirmation Modal Wrapper -->
<div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden opacity-0">
    <div class="bg-white rounded-2xl max-w-xs w-full p-6 shadow-2xl border border-slate-100 transform scale-95 flex flex-col items-center text-center space-y-4">
        <!-- Icon Warning Peringatan -->
        <div class="p-3 bg-amber-50 text-amber-550 rounded-full border border-amber-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-amber-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>
        <!-- Title & Message -->
        <div class="space-y-1.5">
            <h3 class="text-base font-extrabold text-slate-900">Simpan Absen Shalat?</h3>
            <p class="text-xs text-slate-500 font-semibold leading-relaxed">
                Data absensi shalat yang sudah disimpan <span class="text-rose-600 font-extrabold">tidak dapat diubah kembali</span>. Apakah Anda yakin pilihan Anda sudah benar?
            </p>
        </div>
        <!-- Action Buttons -->
        <div class="flex gap-2 w-full pt-2">
            <button id="modal-cancel-btn" type="button" class="btn-cancel flex-1 py-2 text-xs">
                Batal
            </button>
            <button id="modal-confirm-btn" type="button" class="btn-submit flex-1 py-2 text-xs">
                Ya, Simpan
            </button>
        </div>
    </div>
</div>

<script>
    let formToSubmit = null;

    function handleFormSubmit(event, form) {
        event.preventDefault();
        formToSubmit = form;
        
        const modal = document.getElementById('confirm-modal');
        const modalContent = modal.querySelector('div');
        
        // Tampilkan Wrapper Modal
        modal.classList.remove('hidden');
        
        // Paksa reflow browser agar animasi transisi opacity & scale terpicu
        void modal.offsetWidth;
        
        // Aktifkan visual opacity & scaling
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }

    function closeModal() {
        const modal = document.getElementById('confirm-modal');
        const modalContent = modal.querySelector('div');
        
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            formToSubmit = null;
        }, 200);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('modal-cancel-btn').addEventListener('click', closeModal);
        document.getElementById('modal-confirm-btn').addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });
    });
</script>
@endsection

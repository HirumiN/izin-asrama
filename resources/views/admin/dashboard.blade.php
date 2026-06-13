@extends('layouts.app')

@section('title', 'Dashboard Pengelola')
@section('page_title', 'Dashboard Pengelola Asrama')

@section('content')
@php
    $pendingPesiar = $pendingPermits->where('type', 'pesiar');
    $pendingBermalam = $pendingPermits->where('type', 'bermalam');
    $activePesiar = $activePermits->where('type', 'pesiar');
    $activeBermalam = $activePermits->where('type', 'bermalam');
@endphp

<div class="space-y-10">

    <!-- SECTION 1: PENGAJUAN MASUK / MENUNGGU PERSETUJUAN -->
    <div class="p-6 glass-card border-slate-200/80 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-yellow-500 rounded-full animate-pulse"></span>
                Pengajuan Masuk / Menunggu ACC
                <span class="ml-2 px-2.5 py-0.5 bg-yellow-50 border border-yellow-250 text-yellow-700 text-xs font-bold rounded-full">
                    {{ $pendingPermits->count() }}
                </span>
            </h2>

            <!-- Switch Tab Jenis Izin Pending -->
            <div class="flex p-1 bg-slate-100 rounded-xl border border-slate-200/60 w-full sm:w-72">
                <button type="button" onclick="switchPendingTab('pesiar')" id="tab-pending-pesiar"
                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition duration-200 text-white bg-blue-600 shadow-sm">
                    Izin Pesiar ({{ $pendingPesiar->count() }})
                </button>
                <button type="button" onclick="switchPendingTab('bermalam')" id="tab-pending-bermalam"
                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition duration-200 text-slate-500 hover:text-slate-800">
                    Izin Bermalam ({{ $pendingBermalam->count() }})
                </button>
            </div>
        </div>

        <!-- CONTAINER IZIN PESIAR PENDING -->
        <div id="container-pending-pesiar" class="space-y-4">
            @if($pendingPesiar->isEmpty())
                <div class="text-center py-8 text-slate-400 text-sm font-medium">
                    Tidak ada pengajuan masuk untuk Izin Pesiar.
                </div>
            @else
                <form action="{{ route('admin.permits.bulk') }}" method="POST" id="bulk-form-pending-pesiar" class="space-y-4">
                    @csrf
                    <input type="hidden" name="action" id="bulk-action-type-pesiar" value="">

                    <!-- Bulk Action Controls -->
                    <div id="bulk-controls-pesiar" class="hidden flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-blue-50 border border-blue-150 rounded-xl transition duration-300">
                        <span class="text-xs font-bold text-blue-800" id="bulk-selected-count-pesiar">0 terpilih</span>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="submitBulk('pesiar', 'approve')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-md transition duration-150 transform active:scale-[0.98]">
                                Setujui Terpilih
                            </button>
                            <button type="button" onclick="submitBulk('pesiar', 'reject')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow-md transition duration-150 transform active:scale-[0.98]">
                                Tolak Terpilih
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-650">
                            <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                                <tr>
                                    <th class="px-4 py-3 w-10">
                                        <input type="checkbox" id="select-all-pending-pesiar" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                                    </th>
                                    <th class="px-6 py-3">Mahasiswa</th>
                                    <th class="px-6 py-3">Kamar</th>
                                    <th class="px-6 py-3">Tujuan</th>
                                    <th class="px-6 py-3">Waktu Keluar</th>
                                    <th class="px-6 py-3 text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/80 font-medium">
                                @foreach($pendingPesiar as $permit)
                                    <tr class="hover:bg-slate-50/50 transition duration-150">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" name="permit_ids[]" value="{{ $permit->id }}" class="pending-checkbox-pesiar h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{ $permit->student->user->name }}</div>
                                            <div class="text-xs text-slate-500 font-medium">NIM: {{ $permit->student->nim }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-800">{{ $permit->student->dorm_room }}</td>
                                        <td class="px-6 py-4 text-slate-800">{{ $permit->destination }}</td>
                                        <td class="px-6 py-4">{{ $permit->start_time->format('d/m/Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" onclick="singleAction('approve', {{ $permit->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition duration-150 shadow-md transform active:scale-[0.95]">
                                                    Setujui / ACC
                                                </button>
                                                <button type="button" onclick="singleAction('reject', {{ $permit->id }})" class="px-3 py-1.5 bg-white hover:bg-rose-50 hover:text-rose-600 text-slate-700 border border-slate-350 hover:border-rose-200 rounded-lg text-xs font-bold transition duration-150 transform active:scale-[0.95]">
                                                    Tolak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            @endif
        </div>

        <!-- CONTAINER IZIN BERMALAM PENDING -->
        <div id="container-pending-bermalam" class="space-y-4 hidden">
            @if($pendingBermalam->isEmpty())
                <div class="text-center py-8 text-slate-400 text-sm font-medium">
                    Tidak ada pengajuan masuk untuk Izin Bermalam.
                </div>
            @else
                <form action="{{ route('admin.permits.bulk') }}" method="POST" id="bulk-form-pending-bermalam" class="space-y-4">
                    @csrf
                    <input type="hidden" name="action" id="bulk-action-type-bermalam" value="">

                    <!-- Bulk Action Controls -->
                    <div id="bulk-controls-bermalam" class="hidden flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-blue-50 border border-blue-150 rounded-xl transition duration-300">
                        <span class="text-xs font-bold text-blue-800" id="bulk-selected-count-bermalam">0 terpilih</span>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="submitBulk('bermalam', 'approve')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shadow-md transition duration-150 transform active:scale-[0.98]">
                                Setujui Terpilih
                            </button>
                            <button type="button" onclick="submitBulk('bermalam', 'reject')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow-md transition duration-150 transform active:scale-[0.98]">
                                Tolak Terpilih
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-650">
                            <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                                <tr>
                                    <th class="px-4 py-3 w-10">
                                        <input type="checkbox" id="select-all-pending-bermalam" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                                    </th>
                                    <th class="px-6 py-3">Mahasiswa</th>
                                    <th class="px-6 py-3">Kamar</th>
                                    <th class="px-6 py-3">Tujuan</th>
                                    <th class="px-6 py-3">Mulai Bermalam</th>
                                    <th class="px-6 py-3">Rencana Kembali</th>
                                    <th class="px-6 py-3">Alasan Bermalam</th>
                                    <th class="px-6 py-3 text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200/80 font-medium">
                                @foreach($pendingBermalam as $permit)
                                    <tr class="hover:bg-slate-50/50 transition duration-150">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" name="permit_ids[]" value="{{ $permit->id }}" class="pending-checkbox-bermalam h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900">{{ $permit->student->user->name }}</div>
                                            <div class="text-xs text-slate-500 font-medium">NIM: {{ $permit->student->nim }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-800">{{ $permit->student->dorm_room }}</td>
                                        <td class="px-6 py-4 text-slate-800">{{ $permit->destination }}</td>
                                        <td class="px-6 py-4">{{ $permit->start_time->format('d/m/Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-slate-800">
                                            {{ $permit->end_time ? $permit->end_time->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 max-w-xs truncate text-slate-500" title="{{ $permit->reason }}">
                                            {{ $permit->reason ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" onclick="singleAction('approve', {{ $permit->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition duration-150 shadow-md transform active:scale-[0.95]">
                                                    Setujui / ACC
                                                </button>
                                                <button type="button" onclick="singleAction('reject', {{ $permit->id }})" class="px-3 py-1.5 bg-white hover:bg-rose-50 hover:text-rose-600 text-slate-700 border border-slate-350 hover:border-rose-200 rounded-lg text-xs font-bold transition duration-150 transform active:scale-[0.95]">
                                                    Tolak
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- SECTION 2: DAFTAR MAHASISWA SEDANG KELUAR -->
    <div class="p-6 glass-card border-slate-200/80 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-blue-500 rounded-full animate-pulse"></span>
                Mahasiswa Sedang Keluar (Izin Aktif)
                <span class="ml-2 px-2.5 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold rounded-full">
                    {{ $activePermits->count() }}
                </span>
            </h2>

            <!-- Switch Tab Jenis Izin Aktif -->
            <div class="flex p-1 bg-slate-100 rounded-xl border border-slate-200/60 w-full sm:w-72">
                <button type="button" onclick="switchActiveTab('pesiar')" id="tab-active-pesiar"
                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition duration-200 text-white bg-blue-600 shadow-sm">
                    Izin Pesiar ({{ $activePesiar->count() }})
                </button>
                <button type="button" onclick="switchActiveTab('bermalam')" id="tab-active-bermalam"
                    class="flex-1 py-1.5 text-xs font-bold rounded-lg transition duration-200 text-slate-500 hover:text-slate-800">
                    Izin Bermalam ({{ $activeBermalam->count() }})
                </button>
            </div>
        </div>

        <!-- CONTAINER ACTIVE PESIAR -->
        <div id="container-active-pesiar" class="space-y-4">
            @if($activePesiar->isEmpty())
                <div class="text-center py-8 text-slate-400 text-sm font-medium">
                    Tidak ada mahasiswa pesiar yang sedang keluar saat ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-650">
                        <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                            <tr>
                                <th class="px-6 py-3">Mahasiswa</th>
                                <th class="px-6 py-3">Kamar</th>
                                <th class="px-6 py-3">Tujuan</th>
                                <th class="px-6 py-3">Keluar Sejak</th>
                                <th class="px-6 py-3">Batas Kembali</th>
                                <th class="px-6 py-3">Status Terkini</th>
                                <th class="px-6 py-3 text-right">Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/80 font-medium">
                            @foreach($activePesiar as $permit)
                                @php
                                    $isOverdue = \Carbon\Carbon::now()->greaterThan($permit->end_time);
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition duration-150 {{ $isOverdue ? 'bg-rose-50/50 border-l-4 border-l-rose-500' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $permit->student->user->name }}</div>
                                        <div class="text-xs text-slate-500">NIM: {{ $permit->student->nim }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-800">{{ $permit->student->dorm_room }}</td>
                                    <td class="px-6 py-4 text-slate-850">{{ $permit->destination }}</td>
                                    <td class="px-6 py-4">{{ $permit->start_time->format('d/m/Y, H:i') }}</td>
                                    <td class="px-6 py-4 {{ $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-850' }}">
                                        {{ $permit->end_time->format('d/m/Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($isOverdue)
                                            <span class="px-2.5 py-0.5 bg-rose-50 border border-rose-100 text-rose-700 rounded-full text-xs font-bold animate-pulse">
                                                Terlambat {{ \Carbon\Carbon::now()->diffInMinutes($permit->end_time) }}m+
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-blue-50 border border-blue-100 text-blue-700 rounded-full text-xs font-bold">
                                                Di Luar Asrama
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.permits.return', $permit) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition duration-150 shadow-md">
                                                Lapor Kembali / Scan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- CONTAINER ACTIVE BERMALAM -->
        <div id="container-active-bermalam" class="space-y-4 hidden">
            @if($activeBermalam->isEmpty())
                <div class="text-center py-8 text-slate-400 text-sm font-medium">
                    Tidak ada mahasiswa bermalam yang sedang keluar saat ini.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-650">
                        <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                            <tr>
                                <th class="px-6 py-3">Mahasiswa</th>
                                <th class="px-6 py-3">Kamar</th>
                                <th class="px-6 py-3">Tujuan</th>
                                <th class="px-6 py-3">Mulai Bermalam</th>
                                <th class="px-6 py-3">Batas Kembali</th>
                                <th class="px-6 py-3">Status Terkini</th>
                                <th class="px-6 py-3 text-right">Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/80 font-medium">
                            @foreach($activeBermalam as $permit)
                                @php
                                    $isOverdue = \Carbon\Carbon::now()->greaterThan($permit->end_time);
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition duration-150 {{ $isOverdue ? 'bg-rose-50/50 border-l-4 border-l-rose-500' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $permit->student->user->name }}</div>
                                        <div class="text-xs text-slate-500">NIM: {{ $permit->student->nim }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-800">{{ $permit->student->dorm_room }}</td>
                                    <td class="px-6 py-4 text-slate-850">{{ $permit->destination }}</td>
                                    <td class="px-6 py-4">{{ $permit->start_time->format('d/m/Y, H:i') }}</td>
                                    <td class="px-6 py-4 {{ $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-850' }}">
                                        {{ $permit->end_time->format('d/m/Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($isOverdue)
                                            <span class="px-2.5 py-0.5 bg-rose-50 border border-rose-100 text-rose-700 rounded-full text-xs font-bold animate-pulse">
                                                Terlambat {{ \Carbon\Carbon::now()->diffInMinutes($permit->end_time) }}m+
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 bg-blue-50 border border-blue-100 text-blue-700 rounded-full text-xs font-bold">
                                                Di Luar Asrama
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('admin.permits.return', $permit) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition duration-150 shadow-md">
                                                Lapor Kembali / Scan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- SECTION 3: TABEL RIWAYAT SEMUA IZIN -->
    <div class="p-6 glass-card border-slate-200/80 space-y-6">
        <div class="pb-3 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Seluruh Izin</h2>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-50 border border-slate-200 rounded-xl shadow-sm">
            <!-- Pencarian -->
            <div>
                <label for="search" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari Nama/NIM</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Contoh: Andi"
                    class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm">
            </div>

            <!-- Tanggal -->
            <div>
                <label for="date" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal Keluar</label>
                <input type="date" name="date" id="date" value="{{ request('date') }}"
                    class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-955 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm">
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status Izin</label>
                <select name="status" id="status"
                    class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-950 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 text-sm shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="returned_on_time" {{ request('status') === 'returned_on_time' ? 'selected' : '' }}>Tepat Waktu</option>
                    <option value="returned_late" {{ request('status') === 'returned_late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition duration-150 text-center shadow-sm">
                    Terapkan Filter
                </button>
                @if(request()->anyFilled(['search', 'date', 'status']))
                    <a href="{{ route('admin.dashboard') }}" class="py-2 px-3 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-lg text-sm font-semibold transition duration-150 text-center shadow-sm">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Tabel Riwayat -->
        @if($historyPermits->isEmpty())
            <div class="text-center py-6 text-slate-400 text-sm font-medium">
                Tidak ada data riwayat yang cocok dengan filter.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-650">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                        <tr>
                            <th class="px-6 py-3">Mahasiswa</th>
                            <th class="px-6 py-3">Jenis</th>
                            <th class="px-6 py-3">Tujuan</th>
                            <th class="px-6 py-3">Keluar</th>
                            <th class="px-6 py-3">Batas Kembali</th>
                            <th class="px-6 py-3">Aktual Kembali</th>
                            <th class="px-6 py-3">Terlambat</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Di-ACC Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 font-medium">
                        @foreach($historyPermits as $history)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $history->student->user->name }}</div>
                                    <div class="text-xs text-slate-500 font-medium">NIM: {{ $history->student->nim }}</div>
                                </td>
                                <td class="px-6 py-4 capitalize text-slate-800">{{ $history->type }}</td>
                                <td class="px-6 py-4 text-slate-800">{{ $history->destination }}</td>
                                <td class="px-6 py-4">{{ $history->start_time->format('d/m/Y, H:i') }}</td>
                                <td class="px-6 py-4 text-slate-800">
                                    {{ $history->end_time ? $history->end_time->format('d/m/Y, H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-800">
                                    {{ $history->actual_return_time ? $history->actual_return_time->format('d/m/Y, H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($history->status === 'returned_late')
                                        <span class="text-rose-600 font-bold">{{ $history->lateness_duration }} Menit</span>
                                    @else
                                        <span class="text-slate-400 font-medium">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($history->status === 'returned_on_time')
                                        <span class="px-2 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded text-[11px] font-bold uppercase">
                                            Tepat Waktu
                                        </span>
                                    @elseif($history->status === 'returned_late')
                                        <span class="px-2 py-0.5 bg-rose-50 border border-rose-100 text-rose-700 rounded text-[11px] font-bold uppercase">
                                            Terlambat
                                        </span>
                                    @elseif($history->status === 'rejected')
                                        <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 text-slate-500 rounded text-[11px] font-bold uppercase">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                                    {{ $history->actionBy ? $history->actionBy->name : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="pt-4">
                {{ $historyPermits->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Script Switcher & Action Handlers -->
<script>
    // Tab switcher untuk Pengajuan Pending
    function switchPendingTab(type) {
        const tabPesiar = document.getElementById('tab-pending-pesiar');
        const tabBermalam = document.getElementById('tab-pending-bermalam');
        const containerPesiar = document.getElementById('container-pending-pesiar');
        const containerBermalam = document.getElementById('container-pending-bermalam');

        if (type === 'pesiar') {
            tabPesiar.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
            tabPesiar.classList.remove('text-slate-500');
            tabBermalam.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            tabBermalam.classList.add('text-slate-500');

            containerPesiar.classList.remove('hidden');
            containerBermalam.classList.add('hidden');
        } else {
            tabBermalam.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
            tabBermalam.classList.remove('text-slate-500');
            tabPesiar.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            tabPesiar.classList.add('text-slate-500');

            containerBermalam.classList.remove('hidden');
            containerPesiar.classList.add('hidden');
        }
    }

    // Tab switcher untuk Mahasiswa Sedang Keluar (Izin Aktif)
    function switchActiveTab(type) {
        const tabPesiar = document.getElementById('tab-active-pesiar');
        const tabBermalam = document.getElementById('tab-active-bermalam');
        const containerPesiar = document.getElementById('container-active-pesiar');
        const containerBermalam = document.getElementById('container-active-bermalam');

        if (type === 'pesiar') {
            tabPesiar.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
            tabPesiar.classList.remove('text-slate-500');
            tabBermalam.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            tabBermalam.classList.add('text-slate-500');

            containerPesiar.classList.remove('hidden');
            containerBermalam.classList.add('hidden');
        } else {
            tabBermalam.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
            tabBermalam.classList.remove('text-slate-500');
            tabPesiar.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            tabPesiar.classList.add('text-slate-500');

            containerBermalam.classList.remove('hidden');
            containerPesiar.classList.add('hidden');
        }
    }

    // Handler untuk checkbox massal / bulk action
    document.addEventListener('DOMContentLoaded', function() {
        // Setup bulk action untuk Pesiar Pending
        setupBulkHandlers('pesiar');
        
        // Setup bulk action untuk Bermalam Pending
        setupBulkHandlers('bermalam');
    });

    function setupBulkHandlers(type) {
        const selectAll = document.getElementById(`select-all-pending-${type}`);
        const checkboxes = document.querySelectorAll(`.pending-checkbox-${type}`);
        const bulkControls = document.getElementById(`bulk-controls-${type}`);
        const countText = document.getElementById(`bulk-selected-count-${type}`);

        if (!selectAll) return;

        function updateControls() {
            const checkedCount = document.querySelectorAll(`.pending-checkbox-${type}:checked`).length;
            if (checkedCount > 0) {
                bulkControls.classList.remove('hidden');
                countText.textContent = checkedCount + ' pengajuan terpilih';
            } else {
                bulkControls.classList.add('hidden');
            }
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateControls();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateControls();
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                selectAll.checked = allChecked;
            });
        });
    }

    function submitBulk(type, actionType) {
        const actionInput = document.getElementById(`bulk-action-type-${type}`);
        const form = document.getElementById(`bulk-form-pending-${type}`);
        actionInput.value = actionType;
        form.submit();
    }

    function singleAction(actionType, id) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = actionType === 'approve' 
            ? `/admin/permits/${id}/approve` 
            : `/admin/permits/${id}/reject`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = "{{ csrf_token() }}";
        form.appendChild(csrfInput);
        
        document.body.appendChild(form);
        form.submit();
    }
</script>
 q@endsection

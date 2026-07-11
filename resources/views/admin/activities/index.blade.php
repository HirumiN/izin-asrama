@extends('layouts.app')

@section('title', 'Daftar Kegiatan Asrama')
@section('page_title', 'Daftar Kegiatan Asrama')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    <!-- Header Card -->
    <div class="glass-card p-6 border-slate-200/80 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-blue-50 border border-blue-100 text-blue-600 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Kegiatan & Absensi Kustom</h3>
                <p class="text-xs text-slate-500">Buat dan kelola kegiatan asrama. Mahasiswa absen secara mandiri dari akun masing-masing.</p>
            </div>
        </div>
        <a href="{{ route('admin.activities.create') }}"
            class="py-2.5 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all duration-150 transform active:scale-[0.98] flex items-center gap-2 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Buat Kegiatan Baru
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-emerald-600 flex-shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Table Card -->
    <div class="glass-card p-6 border-slate-200/80 shadow-sm">
        @if($activities->isEmpty())
            <div class="text-center py-16 text-slate-400 text-sm font-medium">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                </div>
                Belum ada kegiatan kustom yang dibuat.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-650">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama Kegiatan</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Status Absensi</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 font-medium">
                        @foreach($activities as $activity)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-sm">{{ $activity->name }}</span>
                                        @if($activity->description)
                                            <span class="text-xs text-slate-400 mt-1 max-w-md truncate">{{ $activity->description }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-650">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d F Y') }}</span>
                                        <span class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($activity->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($activity->end_time)->format('H:i') }} WIB</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $totalAttendances = $activity->attendances()->count();
                                        $hadirCount = $activity->attendances()->where('status', 'hadir')->count();
                                    @endphp
                                    @if($totalAttendances > 0)
                                        <span class="inline-flex px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-md text-xs font-bold uppercase tracking-wider">
                                            {{ $hadirCount }}/{{ $totalAttendances }} Hadir
                                        </span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-md text-xs font-bold uppercase tracking-wider">
                                            Belum Diabsen
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.activities.attendance.show', $activity->id) }}"
                                        class="inline-flex items-center gap-1.5 py-1.5 px-4 bg-white border border-slate-200 text-slate-700 hover:text-blue-600 hover:border-blue-300 rounded-xl text-xs font-bold shadow-sm transition hover:shadow-md cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                        Monitor Absen
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

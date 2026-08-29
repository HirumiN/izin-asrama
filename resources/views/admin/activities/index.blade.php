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
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.activities.export-csv') }}" download
                class="py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all duration-150 transform active:scale-[0.98] flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.activities.create') }}"
                class="py-2.5 px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all duration-150 transform active:scale-[0.98] flex items-center gap-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buat Kegiatan Baru
            </a>
        </div>
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
            <!-- Bulk Action Form & Controls -->
            <form action="{{ route('admin.activities.bulk-delete') }}" method="POST" id="form-bulk-activities">
                @csrf
                <div id="activity-bulk-bar" class="hidden mb-4 p-3.5 bg-rose-50 border border-rose-200 rounded-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="p-1.5 bg-rose-100 text-rose-700 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </span>
                        <div>
                            <span class="text-sm font-bold text-rose-900" id="activity-selected-count">0 kegiatan terpilih</span>
                            <p class="text-xs text-rose-700">Penghapusan akan menghapus kegiatan terpilih beserta seluruh riwayat presensinya secara permanen.</p>
                        </div>
                    </div>
                    <button type="button" onclick="confirmBulkDeleteActivities()" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow transition flex items-center gap-1.5 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        Hapus Kegiatan Terpilih
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-650">
                        <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                            <tr>
                                <th class="px-4 py-4 text-center w-10">
                                    <input type="checkbox" id="select-all-activities" onclick="toggleSelectAllActivities(this)" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="px-6 py-4">Nama Kegiatan</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Status Absensi</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/80 font-medium">
                            @foreach($activities as $activity)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-4 py-4 text-center">
                                        <input type="checkbox" name="activity_ids[]" value="{{ $activity->id }}" onchange="updateActivityBulkBar()" class="activity-checkbox w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                                    </td>
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
                                            $hadirCount = $activity->attendances()->where('status', 'hadir')->count();
                                        @endphp
                                        @if($hadirCount > 0)
                                            <span class="inline-flex px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-md text-xs font-bold uppercase tracking-wider">
                                                {{ $hadirCount }}/{{ $totalStudents }} Hadir
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-md text-xs font-bold uppercase tracking-wider">
                                                0/{{ $totalStudents }} Hadir
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
            </form>

            <!-- Pagination -->
            <div class="pt-4">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function toggleSelectAllActivities(master) {
    const checkboxes = document.querySelectorAll('.activity-checkbox');
    checkboxes.forEach(cb => cb.checked = master.checked);
    updateActivityBulkBar();
}

function updateActivityBulkBar() {
    const checked = document.querySelectorAll('.activity-checkbox:checked');
    const bulkBar = document.getElementById('activity-bulk-bar');
    const countSpan = document.getElementById('activity-selected-count');
    const master = document.getElementById('select-all-activities');

    if (checked.length > 0) {
        bulkBar.classList.remove('hidden');
        countSpan.textContent = `${checked.length} kegiatan terpilih`;
    } else {
        bulkBar.classList.add('hidden');
    }

    const allCheckboxes = document.querySelectorAll('.activity-checkbox');
    if (master && allCheckboxes.length > 0) {
        master.checked = checked.length === allCheckboxes.length;
    }
}

function confirmBulkDeleteActivities() {
    const checkedCount = document.querySelectorAll('.activity-checkbox:checked').length;
    if (checkedCount === 0) return;

    if (confirm(`⚠️ PERINGATAN HAPUS KEGIATAN MASAL:\nApakah Anda yakin ingin menghapus ${checkedCount} kegiatan terpilih beserta seluruh riwayat absensinya secara permanen?\nData kegiatan yang dihapus tidak dapat dikembalikan!`)) {
        document.getElementById('form-bulk-activities').submit();
    }
}
</script>
@endsection

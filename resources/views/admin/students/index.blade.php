@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')
@section('page_title', 'Daftar Mahasiswa Asrama')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Semua Data Mahasiswa</h2>
            <p class="text-sm text-slate-500 mt-0.5">Kelola akun dan informasi asrama bagi seluruh mahasiswa terdaftar.</p>
        </div>
        <div class="shrink-0">
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-150 transform active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Mahasiswa
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="p-4 bg-white border border-slate-200 rounded-xl shadow-sm">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-450">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.608 10.608Z" />
                    </svg>
                </div>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-150 text-sm"
                    placeholder="Cari Nama, NIM, Kamar, atau Email...">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-sm transition duration-150 shrink-0">
                    Cari Data
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-350 rounded-xl text-sm font-semibold transition duration-150 text-center shrink-0">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Student List Card -->
    <div class="p-6 glass-card border-slate-200/80 space-y-6">
        @if($students->isEmpty())
            <div class="text-center py-12 text-slate-400 font-medium">
                Tidak ada data mahasiswa ditemukan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-650">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                        <tr>
                            <th class="px-6 py-3.5">Mahasiswa</th>
                            <th class="px-6 py-3.5">NIM</th>
                            <th class="px-6 py-3.5">Kamar</th>
                            <th class="px-6 py-3.5">No. Telepon</th>
                            <th class="px-6 py-3.5">Terdaftar Sejak</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 font-medium">
                        @foreach($students as $student)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $student->user->name }}</div>
                                    <div class="text-xs text-slate-500 font-medium">{{ $student->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-slate-800 font-semibold">
                                    {{ $student->nim }}
                                </td>
                                <td class="px-6 py-4 text-slate-800">
                                    <span class="px-2 py-1 bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold rounded-lg">
                                        {{ $student->dorm_room }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-800">
                                    {{ $student->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $student->created_at->format('d/m/Y, H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="pt-4 border-t border-slate-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

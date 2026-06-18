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

    <!-- Search & Filters Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        
        <!-- Pencarian Realtime -->
        <div class="p-4 border-b border-slate-100">
            <div class="relative flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.608 10.608Z" />
                </svg>
                <input type="text" id="student-search-input" value="{{ request('search') }}"
                    class="w-full pl-12 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition duration-200"
                    placeholder="Ketik nama atau NIM untuk mencari..." autocomplete="off">
                <button type="button" id="search-clear-btn" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition {{ request('search') ? '' : 'hidden' }}" title="Hapus pencarian">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Filter Dropdowns -->
        <div class="p-4 bg-slate-50/50 flex flex-col sm:flex-row items-end gap-3">
            <!-- Urut -->
            <div class="w-full sm:flex-1">
                <label for="filter-sort" class="block text-[11px] font-bold uppercase text-slate-500 mb-1.5 tracking-wider">Urut Berdasarkan</label>
                <select id="filter-sort" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 cursor-pointer">
                    <option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru Terdaftar</option>
                    <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Terlama Terdaftar</option>
                    <option value="az" {{ request('sort') === 'az' ? 'selected' : '' }}>Nama A → Z</option>
                    <option value="za" {{ request('sort') === 'za' ? 'selected' : '' }}>Nama Z → A</option>
                </select>
            </div>

            <!-- Status Akun -->
            <div class="w-full sm:flex-1">
                <label for="filter-status" class="block text-[11px] font-bold uppercase text-slate-500 mb-1.5 tracking-wider">Status Akun</label>
                <select id="filter-status" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 cursor-pointer">
                    <option value="" {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="ditangguhkan" {{ request('status') === 'ditangguhkan' ? 'selected' : '' }}>Ditangguhkan</option>
                </select>
            </div>

            <!-- Terdaftar Sejak -->
            <div class="w-full sm:flex-1">
                <label for="filter-date" class="block text-[11px] font-bold uppercase text-slate-500 mb-1.5 tracking-wider">Terdaftar Sejak</label>
                <input type="date" id="filter-date" value="{{ request('registered_since') }}"
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 cursor-pointer">
            </div>

            <!-- Reset Filters -->
            <div class="w-full sm:w-auto shrink-0">
                <label class="block text-[11px] font-bold uppercase text-transparent mb-1.5 tracking-wider sm:block hidden">&nbsp;</label>
                <button type="button" id="reset-filters-btn" class="w-full sm:w-auto px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-600 border border-slate-200 rounded-lg text-sm font-semibold transition duration-150 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182" />
                    </svg>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Student List Card -->
    <div class="p-6 glass-card border-slate-200/80 space-y-6" id="container-students">
        @if($students->isEmpty())
            <div class="text-center py-12 text-slate-400 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto mb-3 text-slate-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                </svg>
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
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Terdaftar Sejak</th>
                            <th class="px-6 py-3.5">Aksi</th>
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
                                <td class="px-6 py-4">
                                    @if($student->isSuspended())
                                        <span class="px-2 py-1 bg-rose-50 border border-rose-100 text-rose-700 text-[11px] font-bold uppercase rounded-md">
                                            Ditangguhkan
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[11px] font-bold uppercase rounded-md">
                                            Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $student->created_at->format('d/m/Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($student->isSuspended())
                                        <form action="{{ route('admin.students.liftSuspension', $student) }}" method="POST" onsubmit="return confirm('Yakin ingin mencabut penangguhan untuk {{ $student->user->name }}?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 border border-emerald-700 text-emerald-100 rounded-lg text-xs font-bold hover:bg-emerald-700 transition duration-150 cursor-pointer shadow-sm">
                                                Cabut Penangguhan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
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

@push('scripts')
<script>
(function() {
    const searchInput = document.getElementById('student-search-input');
    const clearBtn = document.getElementById('search-clear-btn');
    const sortSelect = document.getElementById('filter-sort');
    const statusSelect = document.getElementById('filter-status');
    const dateInput = document.getElementById('filter-date');
    const resetBtn = document.getElementById('reset-filters-btn');
    const container = document.getElementById('container-students');

    let debounceTimer = null;

    function buildUrl() {
        const params = new URLSearchParams();
        const search = searchInput.value.trim();
        const sort = sortSelect.value;
        const status = statusSelect.value;
        const date = dateInput.value;

        if (search) params.set('search', search);
        if (sort && sort !== 'terbaru') params.set('sort', sort);
        if (status) params.set('status', status);
        if (date) params.set('registered_since', date);

        const base = "{{ route('admin.students.index') }}";
        const qs = params.toString();
        return qs ? base + '?' + qs : base;
    }

    function fetchResults() {
        const url = buildUrl();

        container.style.opacity = '0.5';
        container.style.transition = 'opacity 0.15s ease';

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('container-students');

                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                }

                container.style.opacity = '1';
                window.history.replaceState({}, '', url);
            })
            .catch(err => {
                console.error('AJAX fetch failed:', err);
                container.style.opacity = '1';
                window.location.href = url;
            });
    }

    // Realtime search dengan debounce
    searchInput.addEventListener('input', function() {
        clearBtn.classList.toggle('hidden', !this.value);
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchResults, 300);
    });

    // Clear search
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        clearBtn.classList.add('hidden');
        fetchResults();
        searchInput.focus();
    });

    // Filter dropdowns: langsung fetch saat berubah
    sortSelect.addEventListener('change', fetchResults);
    statusSelect.addEventListener('change', fetchResults);
    dateInput.addEventListener('change', fetchResults);

    // Reset semua filter
    resetBtn.addEventListener('click', function() {
        searchInput.value = '';
        sortSelect.value = 'terbaru';
        statusSelect.value = '';
        dateInput.value = '';
        clearBtn.classList.add('hidden');
        fetchResults();
    });
})();
</script>
@endpush

@endsection

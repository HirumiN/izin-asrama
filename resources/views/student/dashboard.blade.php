@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page_title', 'Dashboard Mahasiswa')

@section('content')
<div class="space-y-8">

    <!-- Konten Utama: Pengajuan & Status Izin Aktif -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Form atau Status Aktif -->
        <div class="lg:col-span-2 space-y-6">
            
            @if($activePermit)
                <!-- KARTU IZIN AKTIF -->
                <div class="p-8 glass-card glow-blue border-blue-200/60 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200/80">
                        <div>
                            <span class="px-3 py-1 bg-blue-50 border border-blue-100 rounded-full text-blue-600 text-xs font-bold uppercase tracking-wider">
                                Izin Izin Aktif / Sedang Keluar
                            </span>
                            <h2 class="text-xl font-bold text-slate-900 mt-3">
                                Izin {{ ucfirst($activePermit->type) }} ke {{ $activePermit->destination }}
                            </h2>
                        </div>
                        <div class="p-3 bg-blue-50 border border-blue-100 rounded-2xl text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 15l3-3m0 0l-3-3m3 3h-7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Waktu Keluar</p>
                            <p class="text-base font-bold text-slate-800 mt-1">
                                {{ $activePermit->start_time->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Batas Waktu Kembali</p>
                            <p class="text-base font-bold text-rose-600 mt-1">
                                {{ $activePermit->end_time->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>

                    @if($activePermit->type === 'pesiar')
                        <!-- Countdown Timer Interaktif untuk Pesiar -->
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200/60 flex flex-col items-center justify-center text-center">
                            <span class="text-xs text-slate-500 uppercase tracking-wider mb-2 font-semibold">Sisa Waktu Kembali</span>
                            <div class="flex gap-4 text-slate-800 font-bold" id="countdown-wrapper">
                                <div class="flex flex-col">
                                    <span class="text-3xl md:text-4xl" id="cd-hours">00</span>
                                    <span class="text-[10px] text-slate-400 uppercase mt-1">Jam</span>
                                </div>
                                <span class="text-3xl md:text-4xl text-slate-300">:</span>
                                <div class="flex flex-col">
                                    <span class="text-3xl md:text-4xl" id="cd-minutes">00</span>
                                    <span class="text-[10px] text-slate-400 uppercase mt-1">Menit</span>
                                </div>
                                <span class="text-3xl md:text-4xl text-slate-300">:</span>
                                <div class="flex flex-col">
                                    <span class="text-3xl md:text-4xl" id="cd-seconds">00</span>
                                    <span class="text-[10px] text-slate-400 uppercase mt-1">Detik</span>
                                </div>
                            </div>
                            <p class="text-xs text-blue-600 mt-4 font-semibold" id="curfew-notice">
                                *Harap kembali dan melapor sebelum pukul 21:00 WIB.
                            </p>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const endTime = new Date("{{ $activePermit->end_time->toIso8601String() }}").getTime();
                                
                                function updateCountdown() {
                                    const now = new Date().getTime();
                                    const distance = endTime - now;
                                    
                                    if (distance < 0) {
                                        document.getElementById('countdown-wrapper').innerHTML = "<span class='text-2xl text-rose-600 font-extrabold'>WAKTU TELAH HABIS / TERLAMBAT</span>";
                                        document.getElementById('curfew-notice').innerHTML = "<span class='text-rose-500 font-semibold'>Segera lapor kembali ke pengelola asrama untuk meminimalkan sanksi!</span>";
                                        return;
                                    }
                                    
                                    const hours = Math.floor(distance / (1000 * 60 * 60));
                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    
                                    document.getElementById('cd-hours').innerText = String(hours).padStart(2, '0');
                                    document.getElementById('cd-minutes').innerText = String(minutes).padStart(2, '0');
                                    document.getElementById('cd-seconds').innerText = String(seconds).padStart(2, '0');
                                }
                                
                                updateCountdown();
                                setInterval(updateCountdown, 1000);
                            });
                        </script>
                    @else
                        <!-- Catatan Bermalam -->
                        <div class="p-4 bg-blue-50 border border-blue-150 rounded-xl text-xs text-blue-850 font-medium">
                            <strong>Izin Bermalam Aktif:</strong> Anda diizinkan untuk menginap di luar asrama hingga tanggal pengembalian di atas. Pastikan membawa berkas izin jika sewaktu-waktu diperlukan dan lapor kembali saat tiba di asrama.
                        </div>
                    @endif
                </div>

            @elseif($pendingPermit)
                <!-- KARTU MENUNGGU PERSETUJUAN -->
                <div class="p-8 glass-card glow-blue border-blue-200/60 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200/80">
                        <div>
                            <span class="px-3 py-1 bg-blue-50 border border-blue-100 rounded-full text-blue-600 text-xs font-bold uppercase tracking-wider">
                                Menunggu Persetujuan
                            </span>
                            <h2 class="text-xl font-bold text-slate-900 mt-3">
                                Pengajuan Izin {{ ucfirst($pendingPermit->type) }}
                            </h2>
                        </div>
                        <div class="p-3 bg-blue-50 border border-blue-100 rounded-2xl text-blue-600 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-slate-500 font-semibold">Tujuan</p>
                            <p class="font-bold text-slate-800 mt-1">{{ $pendingPermit->destination }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 font-semibold">Waktu Keluar</p>
                            <p class="font-bold text-slate-800 mt-1">
                                {{ $pendingPermit->start_time->translatedFormat('d F Y, H:i') }} WIB
                            </p>
                        </div>
                        @if($pendingPermit->type === 'bermalam')
                            <div>
                                <p class="text-slate-500 font-semibold">Rencana Kembali</p>
                                <p class="font-bold text-slate-800 mt-1">
                                    {{ $pendingPermit->end_time->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-slate-500 font-semibold">Alasan</p>
                                <p class="font-bold text-slate-800 mt-1">{{ $pendingPermit->reason }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 bg-slate-50 border border-slate-200/60 rounded-xl text-xs text-slate-600 flex items-center gap-2 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-blue-600 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pengajuan Anda telah masuk ke sistem pengelola. Harap tunggu konfirmasi atau hubungi pembina asrama untuk persetujuan.</span>
                    </div>
                </div>

            @else
                <!-- FORM PENGAJUAN IZIN BARU -->
                <div class="p-8 glass-card glow-blue space-y-6">
                    <div class="pb-4 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-900">Ajukan Izin Keluar Asrama</h2>
                        <p class="text-sm text-slate-500 mt-1">Pilih jenis izin dan lengkapi detail formulir di bawah ini.</p>
                    </div>

                    <!-- Pilihan Tab Jenis Izin -->
                    <div class="flex p-1 bg-slate-100 rounded-xl border border-slate-200">
                        <button type="button" onclick="setIzinType('pesiar')" id="tab-pesiar"
                            class="flex-1 py-2.5 text-sm font-bold rounded-lg transition duration-200 text-white bg-blue-600 shadow-sm">
                            Izin Pesiar (Hari yang Sama)
                        </button>
                        <button type="button" onclick="setIzinType('bermalam')" id="tab-bermalam"
                            class="flex-1 py-2.5 text-sm font-bold rounded-lg transition duration-200 text-slate-500 hover:text-slate-800">
                            Izin Bermalam (Menginap)
                        </button>
                    </div>

                    <!-- Form Input -->
                    <form action="{{ route('student.permits.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="type" id="input-type" value="pesiar">

                        <!-- Tujuan -->
                        <div>
                            <label for="destination" class="block text-sm font-semibold text-slate-700">Tujuan Kepergian</label>
                            <input type="text" name="destination" id="destination" required
                                value="{{ old('destination') }}"
                                class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                                placeholder="Contoh: Toko Buku, Stasiun, Rumah Keluarga">
                        </div>

                        <!-- Grid Tanggal & Jam Keluar -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_time" class="block text-sm font-semibold text-slate-700" id="label-start-time">
                                    Waktu / Jam Keluar
                                </label>
                                <input type="datetime-local" name="start_time" id="start_time" required
                                    value="{{ old('start_time', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}"
                                    class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm">
                            </div>

                            <!-- Bidang khusus Bermalam: Tanggal Kembali -->
                            <div id="wrapper-end-time" class="hidden">
                                <label for="end_time" class="block text-sm font-semibold text-slate-700">Rencana Kembali (Maks. Jam 17:00)</label>
                                <input type="date" name="end_time" id="end_time"
                                    value="{{ old('end_time') }}"
                                    class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm">
                            </div>
                        </div>

                        <!-- Bidang khusus Bermalam: Alasan -->
                        <div id="wrapper-reason" class="hidden">
                            <label for="reason" class="block text-sm font-semibold text-slate-700">Alasan Bermalam</label>
                            <textarea name="reason" id="reason" rows="3"
                                class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm"
                                placeholder="Sebutkan alasan penting Anda harus bermalam di luar asrama...">{{ old('reason') }}</textarea>
                        </div>

                        <!-- Button Kirim -->
                        <div>
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition duration-250 transform active:scale-[0.98]">
                                Kirim Pengajuan Izin
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                    function setIzinType(type) {
                        const tabPesiar = document.getElementById('tab-pesiar');
                        const tabBermalam = document.getElementById('tab-bermalam');
                        const inputType = document.getElementById('input-type');
                        const labelStartTime = document.getElementById('label-start-time');
                        const wrapperEndTime = document.getElementById('wrapper-end-time');
                        const wrapperReason = document.getElementById('wrapper-reason');

                        inputType.value = type;

                        if (type === 'pesiar') {
                            tabPesiar.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                            tabPesiar.classList.remove('text-slate-500');
                            
                            tabBermalam.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                            tabBermalam.classList.add('text-slate-500');

                            labelStartTime.innerText = "Waktu / Jam Keluar";
                            wrapperEndTime.classList.add('hidden');
                            wrapperReason.classList.add('hidden');
                            
                            document.getElementById('end_time').required = false;
                            document.getElementById('reason').required = false;
                        } else {
                            tabBermalam.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                            tabBermalam.classList.remove('text-slate-500');
                            
                            tabPesiar.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                            tabPesiar.classList.add('text-slate-500');

                            labelStartTime.innerText = "Tanggal & Jam Keluar";
                            wrapperEndTime.classList.remove('hidden');
                            wrapperReason.classList.remove('hidden');

                            document.getElementById('end_time').required = true;
                            document.getElementById('reason').required = true;
                        }
                    }

                    // Menjaga input tipe jika terjadi validasi error
                    @if(old('type') === 'bermalam')
                        setIzinType('bermalam');
                    @endif
                </script>
            @endif

        </div>

        <!-- Kolom Kanan: Panduan Aturan Kedisiplinan -->
        <div class="space-y-6">
            <div class="p-6 glass-card border-blue-100/80 space-y-4">
                <h3 class="text-lg font-bold text-blue-900 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-blue-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    Aturan Keluar Asrama
                </h3>
                <ul class="space-y-3 text-xs text-slate-700 list-disc list-inside font-medium">
                    <li>Izin pesiar wajib kembali pada hari yang sama paling lambat pukul <strong class="text-slate-900">21:00 WIB</strong>.</li>
                    <li>Izin bermalam wajib kembali paling lambat pukul <strong class="text-slate-900">17:00 WIB</strong> pada tanggal kepulangan yang disetujui.</li>
                    <li>Pelanggaran batas waktu (keterlambatan) akan <strong class="text-rose-600">tercatat otomatis oleh sistem</strong> dan mempengaruhi sanksi asrama.</li>
                    <li>Pastikan melapor kembali ke Pos Asrama dan meminta pengelola untuk <strong class="text-blue-600">melakukan Scan/Lapor Kembali</strong> untuk menyelesaikan izin.</li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Tabel Riwayat Izin Terdahulu -->
    <div class="p-6 glass-card">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Riwayat Izin Keluar Anda</h3>
        
        @if($historyPermits->isEmpty())
            <div class="text-center py-8 text-slate-400 text-sm font-medium">
                Belum ada riwayat pengajuan izin sebelumnya.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-650">
                    <thead class="text-xs uppercase bg-slate-50 text-slate-500 border-b border-slate-200 font-bold">
                        <tr>
                            <th class="px-6 py-3">Jenis</th>
                            <th class="px-6 py-3">Tujuan</th>
                            <th class="px-6 py-3">Keluar</th>
                            <th class="px-6 py-3">Batas Kembali</th>
                            <th class="px-6 py-3">Aktual Kembali</th>
                            <th class="px-6 py-3">Durasi Telat</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/80 font-medium">
                        @foreach($historyPermits as $history)
                            <tr class="hover:bg-slate-50/50 transition duration-150">
                                <td class="px-6 py-4 font-bold text-slate-800 capitalize">
                                    {{ $history->type }}
                                </td>
                                <td class="px-6 py-4 text-slate-850">
                                    {{ $history->destination }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $history->start_time->format('d/m/Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $history->end_time ? $history->end_time->format('d/m/Y, H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $history->actual_return_time ? $history->actual_return_time->format('d/m/Y, H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($history->status === 'returned_late')
                                        <span class="text-rose-600 font-bold">{{ $history->lateness_duration }} Menit</span>
                                    @elseif($history->status === 'returned_on_time')
                                        <span class="text-emerald-600 font-medium">-</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($history->status === 'returned_on_time')
                                        <span class="px-2 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-md text-[11px] font-bold uppercase">
                                            Tepat Waktu
                                        </span>
                                    @elseif($history->status === 'returned_late')
                                        <span class="px-2 py-1 bg-rose-50 border border-rose-100 text-rose-700 rounded-md text-[11px] font-bold uppercase">
                                            Terlambat
                                        </span>
                                    @elseif($history->status === 'rejected')
                                        <span class="px-2 py-1 bg-slate-100 border border-slate-200 text-slate-500 rounded-md text-[11px] font-bold uppercase">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection

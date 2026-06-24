@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page_title', 'Dashboard Mahasiswa')

@section('content')
<div class="space-y-8">

    <!-- Konten Utama: Pengajuan & Status Izin Aktif -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Form atau Status Aktif -->
        <div class="lg:col-span-2 space-y-6">
            
            @if($student->isSuspended())
                <!-- KARTU PENANGGUHAN -->
                <div class="p-8 glass-card border-rose-200/60 space-y-6" style="box-shadow: 0 10px 30px rgba(239, 68, 68, 0.08);">
                    <div class="flex items-center justify-between pb-4 border-b border-rose-100">
                        <div>
                            <span class="px-3 py-1 bg-rose-50 border border-rose-100 rounded-full text-rose-600 text-xs font-bold uppercase tracking-wider">
                                Hak Izin Ditangguhkan
                            </span>
                            <h2 class="text-xl font-bold text-slate-900 mt-3">
                                Anda Tidak Dapat Mengajukan Izin
                            </h2>
                        </div>
                        <div class="p-3 bg-rose-50 border border-rose-100 rounded-2xl text-rose-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                    </div>

                    <div class="p-5 bg-rose-50 border border-rose-100 rounded-xl space-y-3">
                        <p class="text-sm text-rose-800 font-semibold leading-relaxed">
                            Hak izin keluar asrama Anda telah <strong>ditangguhkan</strong> oleh sistem karena riwayat keterlambatan kepulangan.
                        </p>
                        <p class="text-xs text-rose-700 font-medium">
                            Ditangguhkan sejak: <strong>{{ $student->suspended_at ? $student->suspended_at->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}</strong>
                        </p>
                    </div>

                    <div class="p-4 bg-slate-50 border border-slate-200/60 rounded-xl text-xs text-slate-600 flex items-start gap-2.5 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Untuk mencabut penangguhan, silakan hubungi <strong>pengelola asrama</strong> secara langsung. Pengelola memiliki wewenang untuk mengaktifkan kembali hak izin Anda setelah evaluasi.</span>
                    </div>
                </div>

            @elseif($activePermit)
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

                    @if($activePermit->admin_note)
                        <div class="p-4 bg-emerald-50 border border-emerald-150 rounded-xl text-xs text-emerald-850 font-medium">
                            <strong>Catatan Pengelola:</strong> "{{ $activePermit->admin_note }}"
                        </div>
                    @endif

                    <!-- Tombol Lapor Kembali Mandiri -->
                    <div class="pt-6 border-t border-slate-100 flex justify-center">
                        <button type="button" onclick="openReturnModal()" class="w-full px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-150 transform active:scale-[0.98] flex items-center justify-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                            Lapor Kembali ke Asrama (Ambil Foto Selfie)
                        </button>
                    </div>
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

                        <!-- Bidang khusus Bermalam: Tipe Bermalam -->
                        <div id="wrapper-bermalam-type" class="hidden">
                            <label for="bermalam_type" class="block text-sm font-semibold text-slate-700">Jenis Izin Bermalam</label>
                            <select id="bermalam_type" onchange="updateBermalamType(this.value)"
                                class="w-full mt-1 px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-955 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 text-sm shadow-sm cursor-pointer">
                                <option value="bermalam_biasa">Reguler (Jumat Sore s/d Senin Pagi)</option>
                                <option value="bermalam_urgensi">Urgent (Kepentingan Mendesak)</option>
                            </select>
                        </div>

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
                                <label for="end_time" class="block text-sm font-semibold text-slate-700">Rencana Kembali (Maks. Jam 06:30)</label>
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
                        const wrapperBermalamType = document.getElementById('wrapper-bermalam-type');
                        const bermalamTypeSelect = document.getElementById('bermalam_type');

                        if (type === 'pesiar') {
                            inputType.value = 'pesiar';

                            tabPesiar.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                            tabPesiar.classList.remove('text-slate-500');
                            
                            tabBermalam.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                            tabBermalam.classList.add('text-slate-500');

                            labelStartTime.innerText = "Waktu / Jam Keluar";
                            wrapperBermalamType.classList.add('hidden');
                            wrapperEndTime.classList.add('hidden');
                            wrapperReason.classList.add('hidden');
                            
                            document.getElementById('end_time').required = false;
                            document.getElementById('reason').required = false;
                        } else {
                            tabBermalam.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                            tabBermalam.classList.remove('text-slate-500');
                            
                            tabPesiar.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                            tabPesiar.classList.add('text-slate-500');

                            wrapperBermalamType.classList.remove('hidden');
                            updateBermalamType(bermalamTypeSelect.value);
                        }
                    }

                    function updateBermalamType(val) {
                        const inputType = document.getElementById('input-type');
                        const labelStartTime = document.getElementById('label-start-time');
                        const wrapperEndTime = document.getElementById('wrapper-end-time');
                        const wrapperReason = document.getElementById('wrapper-reason');

                        inputType.value = val;

                        if (val === 'bermalam_biasa') {
                            labelStartTime.innerText = "Tanggal & Jam Keluar (Mulai Jumat)";
                            wrapperEndTime.classList.add('hidden'); // Disembunyikan karena dikunci ke Senin otomatis
                            wrapperReason.classList.remove('hidden');

                            document.getElementById('end_time').required = false;
                            document.getElementById('reason').required = true;
                        } else if (val === 'bermalam_urgensi') {
                            labelStartTime.innerText = "Tanggal & Jam Keluar";
                            wrapperEndTime.classList.remove('hidden');
                            wrapperReason.classList.remove('hidden');

                            document.getElementById('end_time').required = true;
                            document.getElementById('reason').required = true;
                        }
                    }

                    // Menjaga input tipe jika terjadi validasi error
                    @if(old('type') === 'bermalam_biasa' || old('type') === 'bermalam_urgensi')
                        document.getElementById('bermalam_type').value = '{{ old('type') }}';
                        setIzinType('bermalam');
                    @else
                        setIzinType('pesiar');
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
                    <li>Izin bermalam wajib kembali paling lambat pukul <strong class="text-slate-900">06:30 WIB</strong> pada tanggal kepulangan yang disetujui (Atau hari Senin untuk Bermalam Biasa).</li>
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
                                    {{ str_replace('_', ' ', $history->type) }}
                                </td>
                                <td class="px-6 py-4 text-slate-850">
                                    <div>{{ $history->destination }}</div>
                                    @if($history->admin_note)
                                        <div class="text-[11px] text-slate-500 mt-1 italic flex items-center gap-1 font-medium bg-slate-100/50 border border-slate-200/50 rounded px-1.5 py-0.5 w-max max-w-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 text-slate-400 shrink-0">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                            </svg>
                                            <span class="truncate" title="{{ $history->admin_note }}">{{ $history->admin_note }}</span>
                                        </div>
                                    @endif
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

<!-- MODAL LAPOR KEMBALI DENGAN FOTO & GPS -->
<div id="return-modal" class="hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999; display: none; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
    <div id="return-modal-card" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.95); opacity: 0; transition: transform 0.3s ease, opacity 0.3s ease; width: 92%; max-width: 512px; background: #fff; border-radius: 16px; border: 1px solid rgba(226,232,240,0.8); box-shadow: 0 25px 50px rgba(0,0,0,0.25); overflow: hidden;">
        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-slate-150 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Lapor Kembali ke Asrama</h3>
            <button type="button" onclick="closeReturnModal()" class="text-slate-400 hover:text-slate-650 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content Modal -->
        <div class="p-6 space-y-6">
            <!-- Status GPS -->
            <div id="gps-status" class="p-3 rounded-xl border flex items-center gap-2.5 text-sm font-semibold transition duration-150 bg-amber-50 border-amber-100 text-amber-800">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </span>
                <span id="gps-text">Mencari Lokasi GPS...</span>
            </div>

            <!-- Video / Kamera Preview Container -->
            <div class="relative w-full aspect-video rounded-2xl bg-black overflow-hidden shadow-inner border border-slate-200">
                <!-- Video Element -->
                <video id="webcam-preview" autoplay playsinline class="w-full h-full object-cover"></video>
                <!-- Canvas Element (Tersembunyi) -->
                <canvas id="photo-canvas" class="hidden"></canvas>
                <!-- Captured Image Preview -->
                <img id="captured-preview" class="hidden w-full h-full object-cover" alt="Captured Preview">

                <!-- Overlay Loading/Status -->
                <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 text-white gap-2">
                    <svg class="animate-spin h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-350">Memulai Kamera...</span>
                </div>
            </div>

            <!-- Form data & Submit -->
            <form id="return-report-form" action="{{ route('student.permits.return', $activePermit ? $activePermit->id : 0) }}" method="POST">
                @csrf
                <input type="hidden" name="return_photo" id="input-return-photo">
                <input type="hidden" name="return_location" id="input-return-location">

                <!-- Buttons Group -->
                <div class="flex items-center gap-3">
                    <button type="button" id="btn-capture" onclick="capturePhoto()" class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-150 transform active:scale-[0.98] flex items-center justify-center gap-2" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        </svg>
                        Ambil Foto
                    </button>
                    <button type="button" id="btn-retake" onclick="retakePhoto()" class="hidden flex-1 py-3 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-350 rounded-xl text-sm font-bold transition duration-150 transform active:scale-[0.98] flex items-center justify-center gap-2">
                        Foto Ulang
                    </button>
                    <button type="submit" id="btn-submit" class="hidden flex-1 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-md transition duration-150 transform active:scale-[0.98] flex items-center justify-center gap-2">
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let webcamStream = null;

    function openReturnModal() {
        const modal = document.getElementById('return-modal');
        const card = document.getElementById('return-modal-card');
        
        // Pindahkan modal ke body agar di atas semua elemen (sidebar, navbar)
        document.body.appendChild(modal);

        modal.classList.remove('hidden');
        modal.style.display = 'block';

        setTimeout(() => {
            card.style.transform = 'translate(-50%, -50%) scale(1)';
            card.style.opacity = '1';
        }, 10);

        // Mulai Kamera
        const video = document.getElementById('webcam-preview');
        const cameraLoading = document.getElementById('camera-loading');
        const btnCapture = document.getElementById('btn-capture');

        navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
            audio: false 
        })
        .then(stream => {
            webcamStream = stream;
            video.srcObject = stream;
            cameraLoading.classList.add('hidden');
            btnCapture.removeAttribute('disabled');
        })
        .catch(err => {
            console.error('Kamera gagal dimuat:', err);
            cameraLoading.innerHTML = `
                <div class="text-center p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-rose-500 mx-auto mb-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                    </svg>
                    <span class="text-rose-400 font-bold text-xs">Akses Kamera Ditolak / Tidak Ada Kamera</span>
                </div>
            `;
        });

        // Mulai Geolocation
        const gpsStatus = document.getElementById('gps-status');
        const gpsText = document.getElementById('gps-text');
        const inputLocation = document.getElementById('input-return-location');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude.toFixed(6);
                    const lng = position.coords.longitude.toFixed(6);
                    inputLocation.value = `Lat: ${lat}, Lng: ${lng}`;

                    gpsStatus.classList.remove('bg-amber-50', 'border-amber-100', 'text-amber-800');
                    gpsStatus.classList.add('bg-emerald-50', 'border-emerald-100', 'text-emerald-800');
                    gpsStatus.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-emerald-600 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span class="text-xs">GPS Terdeteksi: Lat: ${lat}, Lng: ${lng}</span>
                    `;
                },
                (error) => {
                    console.error('GPS gagal:', error);
                    inputLocation.value = 'GPS Tidak Diizinkan / Mati';
                    gpsStatus.classList.remove('bg-amber-50', 'border-amber-100', 'text-amber-800');
                    gpsStatus.classList.add('bg-rose-50', 'border-rose-100', 'text-rose-800');
                    gpsStatus.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-rose-600 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        <span class="text-xs">GPS Mati / Izin Ditolak</span>
                    `;
                },
                { enableHighAccuracy: true, timeout: 8000 }
            );
        } else {
            inputLocation.value = 'GPS Tidak Didukung Browser';
            gpsStatus.classList.remove('bg-amber-50', 'border-amber-100', 'text-amber-800');
            gpsStatus.classList.add('bg-rose-50', 'border-rose-100', 'text-rose-850');
            gpsStatus.innerHTML = `<span class="text-xs">Geolocation Tidak Didukung</span>`;
        }
    }

    function closeReturnModal() {
        const modal = document.getElementById('return-modal');
        const card = document.getElementById('return-modal-card');
        
        card.style.transform = 'translate(-50%, -50%) scale(0.95)';
        card.style.opacity = '0';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            retakePhoto();
        }, 300);

        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
            webcamStream = null;
        }
    }

    function capturePhoto() {
        const video = document.getElementById('webcam-preview');
        const canvas = document.getElementById('photo-canvas');
        const preview = document.getElementById('captured-preview');
        const inputPhoto = document.getElementById('input-return-photo');
        const inputLocation = document.getElementById('input-return-location');
        
        const btnCapture = document.getElementById('btn-capture');
        const btnRetake = document.getElementById('btn-retake');
        const btnSubmit = document.getElementById('btn-submit');

        // Set Resolusi Canvas sesuai Resolusi Video
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;

        const ctx = canvas.getContext('2d');
        
        // Mirroring selfie photo jika perlu (kamera depan biasanya mirror)
        // Di sini kita gambar langsung apa adanya agar tidak membingungkan
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Watermark overlay
        const barHeight = Math.round(canvas.height * 0.16);
        ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
        ctx.fillRect(0, canvas.height - barHeight, canvas.width, barHeight);

        // Teks Watermark
        ctx.fillStyle = '#ffffff';
        ctx.textBaseline = 'middle';

        const padding = Math.round(canvas.width * 0.03);
        const fontSizeLarge = Math.max(12, Math.round(canvas.height * 0.04));
        const fontSizeSmall = Math.max(10, Math.round(canvas.height * 0.03));

        // Line 1: Nama dan NIM
        ctx.font = `bold ${fontSizeLarge}px Arial, sans-serif`;
        const name = @js($student->user->name);
        const nim = @js($student->nim);
        ctx.fillText(`${name} (${nim})`, padding, canvas.height - barHeight + padding * 0.7);

        // Line 2: Waktu & GPS
        ctx.font = `${fontSizeSmall}px Arial, sans-serif`;
        
        // Format waktu local
        const now = new Date();
        const dateStr = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0') + ' WIB';
        const locationStr = inputLocation.value || 'GPS Tidak Terdeteksi';

        ctx.fillText(`${dateStr}, ${timeStr} | ${locationStr}`, padding, canvas.height - padding * 0.7);

        // Konversi ke base64
        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
        inputPhoto.value = dataUrl;
        
        // Update preview & switch tampilan
        preview.src = dataUrl;
        preview.classList.remove('hidden');
        video.classList.add('hidden');
        
        btnCapture.classList.add('hidden');
        btnRetake.classList.remove('hidden');
        btnSubmit.classList.remove('hidden');

        // Hentikan webcam
        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
            webcamStream = null;
        }
    }

    function retakePhoto() {
        const video = document.getElementById('webcam-preview');
        const preview = document.getElementById('captured-preview');
        const inputPhoto = document.getElementById('input-return-photo');
        
        const btnCapture = document.getElementById('btn-capture');
        const btnRetake = document.getElementById('btn-retake');
        const btnSubmit = document.getElementById('btn-submit');
        const cameraLoading = document.getElementById('camera-loading');

        inputPhoto.value = '';
        preview.src = '';
        preview.classList.add('hidden');
        video.classList.remove('hidden');
        cameraLoading.classList.remove('hidden');
        
        btnCapture.classList.remove('hidden');
        btnCapture.setAttribute('disabled', 'true');
        btnRetake.classList.add('hidden');
        btnSubmit.classList.add('hidden');

        // Jalankan kembali webcam
        navigator.mediaDevices.getUserMedia({ 
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
            audio: false 
        })
        .then(stream => {
            webcamStream = stream;
            video.srcObject = stream;
            cameraLoading.classList.add('hidden');
            btnCapture.removeAttribute('disabled');
        })
        .catch(err => {
            console.error('Kamera gagal dimuat saat retake:', err);
            cameraLoading.innerHTML = `<span class="text-rose-400 font-bold text-xs p-4">Kamera Gagal Dimuat</span>`;
        });
    }
</script>
@endsection

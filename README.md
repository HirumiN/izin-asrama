# 🚪 Sistem Izin Keluar-Masuk Asrama (E-Izin Asrama)

Sistem Informasi Manajemen Izin Keluar-Masuk Asrama adalah platform berbasis web modern untuk mendigitalisasi proses pengajuan, persetujuan, dan pencatatan izin mahasiswa asrama secara real-time.

---

## 🌟 Fitur Utama

### 1. 🧑‍🎓 Dashboard Mahasiswa
* **Form Pengajuan Izin**: Mendukung dua jenis izin keluar:
  * **Pesiar**: Keluar dan kembali pada hari yang sama dengan batas kembali otomatis pukul 21:00.
  * **Bermalam**: Keluar untuk menginap di luar asrama dengan input tanggal kembali (batas kembali otomatis pukul 17:00).
* **Riwayat Izin Personal**: Memantau status permohonan aktif, disetujui, ditolak, atau riwayat kembali.

### 2. 🛡️ Dashboard Pengelola (Admin)
* **Tab Switcher Jenis Izin**: Mengelompokkan tabel pengajuan pending dan izin aktif menjadi **Izin Pesiar** & **Izin Bermalam** demi penyajian informasi yang bersih dan rapi.
* **Bulk Action (Aksi Massal)**: Memilih beberapa permohonan sekaligus untuk disetujui (**Setujui Terpilih**) atau ditolak (**Tolak Terpilih**) secara massal dalam satu kali klik.
* **Konfirmasi Lapor Kembali**: Pencatatan kepulangan mahasiswa asrama secara real-time dengan penghitungan otomatis durasi keterlambatan jika melewati batas waktu.
* **Filter & Riwayat Menyeluruh**: Pencarian riwayat izin mahasiswa berdasarkan Nama/NIM, tanggal keluar, dan status izin.
* **Registrasi Siswa Baru**: Form input khusus bagi admin untuk membuat akun mahasiswa baru lengkap dengan data NIM, kamar, dan nomor telepon.



---

## 🛠️ Tech Stack

* **Backend**: Laravel 11 (PHP 8.2+)
* **Frontend Logic**: Blade Templates & JavaScript (Vanilla JS)
* **Styling**: Tailwind CSS
* **Asset Bundler**: Vite
* **Database**: SQLite / MySQL

---


## 📂 Struktur File Utama (Kustomisasi)

* `app/Http/Controllers/`
  * `Admin/PermitController.php`: Logika manajemen persetujuan izin, lapor kembali, dan aksi massal pengelola.
  * `Student/PermitController.php`: Logika pengajuan izin dari sisi mahasiswa.
* `resources/views/`
  * `layouts/app.blade.php`: Layout dasar aplikasi dengan Sidebar Sticky & Global Top Bar.
  * `admin/dashboard.blade.php`: Tampilan dashboard pengelola lengkap dengan filter, tab switcher, dan bulk action.
  * `student/dashboard.blade.php`: Tampilan dashboard mahasiswa lengkap dengan form pengajuan dinamis.
* `routes/web.php`: Rute routing web dengan middleware role.

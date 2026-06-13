<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Permit;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Admin / Pengelola Asrama
        $admin = User::create([
            'name' => 'Admin Asrama',
            'email' => 'admin@asrama.com',
            'password' => Hash::make('password'),
            'role' => 'pengelola',
        ]);

        // 2. Daftar 80 Mahasiswa dengan Nama & Kamar Realistis
        $studentsData = [
            ['name' => 'Andi Pratama', 'email' => 'andi@asrama.com', 'nim' => '1234567890', 'room' => 'A-101', 'phone' => '081234567801'],
            ['name' => 'Budi Santoso', 'email' => 'budi@asrama.com', 'nim' => '1234567891', 'room' => 'A-102', 'phone' => '081234567802'],
            ['name' => 'Citra Lestari', 'email' => 'citra@asrama.com', 'nim' => '1234567892', 'room' => 'A-103', 'phone' => '081234567803'],
            ['name' => 'Dedi Wijaya', 'email' => 'dedi@asrama.com', 'nim' => '1234567893', 'room' => 'A-104', 'phone' => '081234567804'],
            ['name' => 'Eka Saputra', 'email' => 'eka@asrama.com', 'nim' => '1234567894', 'room' => 'A-201', 'phone' => '081234567805'],
            ['name' => 'Fajar Ramadhan', 'email' => 'fajar@asrama.com', 'nim' => '1234567895', 'room' => 'A-202', 'phone' => '081234567806'],
            ['name' => 'Gita Rahmawati', 'email' => 'gita@asrama.com', 'nim' => '1234567896', 'room' => 'A-203', 'phone' => '081234567807'],
            ['name' => 'Hendra Wijaya', 'email' => 'hendra@asrama.com', 'nim' => '1234567897', 'room' => 'A-204', 'phone' => '081234567808'],
            ['name' => 'Indah Permatasari', 'email' => 'indah@asrama.com', 'nim' => '1234567898', 'room' => 'B-101', 'phone' => '081234567809'],
            ['name' => 'Joko Susilo', 'email' => 'joko@asrama.com', 'nim' => '1234567899', 'room' => 'B-102', 'phone' => '081234567810'],
            ['name' => 'Kartika Sari', 'email' => 'kartika@asrama.com', 'nim' => '1234567900', 'room' => 'B-103', 'phone' => '081234567811'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman@asrama.com', 'nim' => '1234567901', 'room' => 'B-104', 'phone' => '081234567812'],
            ['name' => 'Megawati Putri', 'email' => 'megawati@asrama.com', 'nim' => '1234567902', 'room' => 'B-201', 'phone' => '081234567813'],
            ['name' => 'Nugroho Adhi', 'email' => 'nugroho@asrama.com', 'nim' => '1234567903', 'room' => 'B-202', 'phone' => '081234567814'],
            ['name' => 'Olivia Natalia', 'email' => 'olivia@asrama.com', 'nim' => '1234567904', 'room' => 'B-203', 'phone' => '081234567815'],
            ['name' => 'Putu Gede', 'email' => 'putu@asrama.com', 'nim' => '1234567905', 'room' => 'B-204', 'phone' => '081234567816'],
            ['name' => 'Qori Hidayat', 'email' => 'qori@asrama.com', 'nim' => '1234567906', 'room' => 'C-101', 'phone' => '081234567817'],
            ['name' => 'Rian Hidayat', 'email' => 'rian@asrama.com', 'nim' => '1234567907', 'room' => 'C-102', 'phone' => '081234567818'],
            ['name' => 'Siti Aminah', 'email' => 'siti@asrama.com', 'nim' => '1234567908', 'room' => 'C-103', 'phone' => '081234567819'],
            ['name' => 'Taufik Hidayat', 'email' => 'taufik@asrama.com', 'nim' => '1234567909', 'room' => 'C-104', 'phone' => '081234567820'],
            ['name' => 'Utami Ningsih', 'email' => 'utami@asrama.com', 'nim' => '1234567910', 'room' => 'C-201', 'phone' => '081234567821'],
            ['name' => 'Vina Panduwinata', 'email' => 'vina@asrama.com', 'nim' => '1234567911', 'room' => 'C-202', 'phone' => '081234567822'],
            ['name' => 'Wahyu Setiawan', 'email' => 'wahyu@asrama.com', 'nim' => '1234567912', 'room' => 'C-203', 'phone' => '081234567823'],
            ['name' => 'Yeni Astuti', 'email' => 'yeni@asrama.com', 'nim' => '1234567913', 'room' => 'C-204', 'phone' => '081234567824'],
            ['name' => 'Zulkifli Hasan', 'email' => 'zulkifli@asrama.com', 'nim' => '1234567914', 'room' => 'D-101', 'phone' => '081234567825'],
            ['name' => 'Aditya Permana', 'email' => 'aditya@asrama.com', 'nim' => '1234567915', 'room' => 'D-102', 'phone' => '081234567826'],
            ['name' => 'Bella Safira', 'email' => 'bella@asrama.com', 'nim' => '1234567916', 'room' => 'D-103', 'phone' => '081234567827'],
            ['name' => 'Candra Kirana', 'email' => 'candra@asrama.com', 'nim' => '1234567917', 'room' => 'D-104', 'phone' => '081234567828'],
            ['name' => 'Doni Tata', 'email' => 'doni@asrama.com', 'nim' => '1234567918', 'room' => 'D-201', 'phone' => '081234567829'],
            ['name' => 'Elisa Fitri', 'email' => 'elisa@asrama.com', 'nim' => '1234567919', 'room' => 'D-202', 'phone' => '081234567830'],
            ['name' => 'Farhan Aditya', 'email' => 'farhan@asrama.com', 'nim' => '1234567920', 'room' => 'D-203', 'phone' => '081234567831'],
            ['name' => 'Fina Lestari', 'email' => 'fina@asrama.com', 'nim' => '1234567921', 'room' => 'D-204', 'phone' => '081234567832'],
            ['name' => 'Gilang Permana', 'email' => 'gilang@asrama.com', 'nim' => '1234567922', 'room' => 'E-101', 'phone' => '081234567833'],
            ['name' => 'Hana Yuniar', 'email' => 'hana@asrama.com', 'nim' => '1234567923', 'room' => 'E-102', 'phone' => '081234567834'],
            ['name' => 'Irfan Hakim', 'email' => 'irfan@asrama.com', 'nim' => '1234567924', 'room' => 'E-103', 'phone' => '081234567835'],
            ['name' => 'Julia Perez', 'email' => 'julia@asrama.com', 'nim' => '1234567925', 'room' => 'E-104', 'phone' => '081234567836'],
            ['name' => 'Kevin Sanjaya', 'email' => 'kevin@asrama.com', 'nim' => '1234567926', 'room' => 'E-201', 'phone' => '081234567837'],
            ['name' => 'Lilis Karlina', 'email' => 'lilis@asrama.com', 'nim' => '1234567927', 'room' => 'E-202', 'phone' => '081234567838'],
            ['name' => 'Mamat Alkatiri', 'email' => 'mamat@asrama.com', 'nim' => '1234567928', 'room' => 'E-203', 'phone' => '081234567839'],
            ['name' => 'Nina Karlina', 'email' => 'nina@asrama.com', 'nim' => '1234567929', 'room' => 'E-204', 'phone' => '081234567840'],
            ['name' => 'Oscar Lawalata', 'email' => 'oscar@asrama.com', 'nim' => '1234567930', 'room' => 'F-101', 'phone' => '081234567841'],
            ['name' => 'Prita Laura', 'email' => 'prita@asrama.com', 'nim' => '1234567931', 'room' => 'F-102', 'phone' => '081234567842'],
            ['name' => 'Rahmat Kartolo', 'email' => 'rahmat@asrama.com', 'nim' => '1234567932', 'room' => 'F-103', 'phone' => '081234567843'],
            ['name' => 'Susi Pudjiastuti', 'email' => 'susi@asrama.com', 'nim' => '1234567933', 'room' => 'F-104', 'phone' => '081234567844'],
            ['name' => 'Tono Wijaya', 'email' => 'tono@asrama.com', 'nim' => '1234567934', 'room' => 'F-201', 'phone' => '081234567845'],
            ['name' => 'Udin Sedunia', 'email' => 'udin@asrama.com', 'nim' => '1234567935', 'room' => 'F-202', 'phone' => '081234567846'],
            ['name' => 'Vicky Prasetyo', 'email' => 'vicky@asrama.com', 'nim' => '1234567936', 'room' => 'F-203', 'phone' => '081234567847'],
            ['name' => 'Wawan Setiawan', 'email' => 'wawan@asrama.com', 'nim' => '1234567937', 'room' => 'F-204', 'phone' => '081234567848'],
            ['name' => 'Yayan Ruhian', 'email' => 'yayan@asrama.com', 'nim' => '1234567938', 'room' => 'G-101', 'phone' => '081234567849'],
            ['name' => 'Zainal Abidin', 'email' => 'zainal@asrama.com', 'nim' => '1234567939', 'room' => 'G-102', 'phone' => '081234567850'],
            ['name' => 'Ahmad Yani', 'email' => 'ahmad@asrama.com', 'nim' => '1234567940', 'room' => 'G-103', 'phone' => '081234567851'],
            ['name' => 'Bambang Pamungkas', 'email' => 'bambang.p@asrama.com', 'nim' => '1234567941', 'room' => 'G-104', 'phone' => '081234567852'],
            ['name' => 'Chairul Tanjung', 'email' => 'chairul@asrama.com', 'nim' => '1234567942', 'room' => 'G-201', 'phone' => '081234567853'],
            ['name' => 'Dian Sastrowardoyo', 'email' => 'dian@asrama.com', 'nim' => '1234567943', 'room' => 'G-202', 'phone' => '081234567854'],
            ['name' => 'Erick Thohir', 'email' => 'erick@asrama.com', 'nim' => '1234567944', 'room' => 'G-203', 'phone' => '081234567855'],
            ['name' => 'Fatin Shidqia', 'email' => 'fatin@asrama.com', 'nim' => '1234567945', 'room' => 'G-204', 'phone' => '081234567856'],
            ['name' => 'Gading Marten', 'email' => 'gading@asrama.com', 'nim' => '1234567946', 'room' => 'H-101', 'phone' => '081234567857'],
            ['name' => 'Hesti Purwadinata', 'email' => 'hesti@asrama.com', 'nim' => '1234567947', 'room' => 'H-102', 'phone' => '081234567858'],
            ['name' => 'Isyana Sarasvati', 'email' => 'isyana@asrama.com', 'nim' => '1234567948', 'room' => 'H-103', 'phone' => '081234567859'],
            ['name' => 'Judika Sihotang', 'email' => 'judika@asrama.com', 'nim' => '1234567949', 'room' => 'H-104', 'phone' => '081234567860'],
            ['name' => 'Krisdayanti', 'email' => 'krisdayanti@asrama.com', 'nim' => '1234567950', 'room' => 'H-201', 'phone' => '081234567861'],
            ['name' => 'Luna Maya', 'email' => 'luna@asrama.com', 'nim' => '1234567951', 'room' => 'H-202', 'phone' => '081234567862'],
            ['name' => 'Maudy Ayunda', 'email' => 'maudy@asrama.com', 'nim' => '1234567952', 'room' => 'H-203', 'phone' => '081234567863'],
            ['name' => 'Najwa Shihab', 'email' => 'najwa@asrama.com', 'nim' => '1234567953', 'room' => 'H-204', 'phone' => '081234567864'],
            ['name' => 'Olga Syahputra', 'email' => 'olga@asrama.com', 'nim' => '1234567954', 'room' => 'I-101', 'phone' => '081234567865'],
            ['name' => 'Pevita Pearce', 'email' => 'pevita@asrama.com', 'nim' => '1234567955', 'room' => 'I-102', 'phone' => '081234567866'],
            ['name' => 'Raditya Dika', 'email' => 'raditya@asrama.com', 'nim' => '1234567956', 'room' => 'I-103', 'phone' => '081234567867'],
            ['name' => 'Sule Sutisna', 'email' => 'sule@asrama.com', 'nim' => '1234567957', 'room' => 'I-104', 'phone' => '081234567868'],
            ['name' => 'Tulus Prasetyo', 'email' => 'tulus@asrama.com', 'nim' => '1234567958', 'room' => 'I-201', 'phone' => '081234567869'],
            ['name' => 'Uus Rizky', 'email' => 'uus@asrama.com', 'nim' => '1234567959', 'room' => 'I-202', 'phone' => '081234567870'],
            ['name' => 'Via Vallen', 'email' => 'via@asrama.com', 'nim' => '1234567960', 'room' => 'I-203', 'phone' => '081234567871'],
            ['name' => 'Wulan Guritno', 'email' => 'wulan@asrama.com', 'nim' => '1234567961', 'room' => 'I-204', 'phone' => '081234567872'],
            ['name' => 'Yuni Shara', 'email' => 'yuni@asrama.com', 'nim' => '1234567962', 'room' => 'J-101', 'phone' => '081234567873'],
            ['name' => 'Zaskia Adya', 'email' => 'zaskia@asrama.com', 'nim' => '1234567963', 'room' => 'J-102', 'phone' => '081234567874'],
            ['name' => 'Ari Lasso', 'email' => 'ari@asrama.com', 'nim' => '1234567964', 'room' => 'J-103', 'phone' => '081234567875'],
            ['name' => 'Bunga Citra', 'email' => 'bunga@asrama.com', 'nim' => '1234567965', 'room' => 'J-104', 'phone' => '081234567876'],
            ['name' => 'Cita Citata', 'email' => 'cita@asrama.com', 'nim' => '1234567966', 'room' => 'J-201', 'phone' => '081234567877'],
            ['name' => 'Deddy Corbuzier', 'email' => 'deddy@asrama.com', 'nim' => '1234567967', 'room' => 'J-202', 'phone' => '081234567878'],
            ['name' => 'Ello Tahitoe', 'email' => 'ello@asrama.com', 'nim' => '1234567968', 'room' => 'J-203', 'phone' => '081234567879'],
            ['name' => 'Fitri Carlina', 'email' => 'fitri@asrama.com', 'nim' => '1234567969', 'room' => 'J-204', 'phone' => '081234567880'],
        ];

        $students = [];
        foreach ($studentsData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);

            $student = $user->student()->create([
                'nim' => $data['nim'],
                'dorm_room' => $data['room'],
                'phone' => $data['phone'],
            ]);

            $students[] = $student;
        }

        // Variabel pembantu untuk seeder
        $destinations = [
            'Toko Buku Gramedia', 'Pasar Tradisional', 'Rumah Orang Tua', 
            'Stasiun Kereta Api', 'Terminal Bus Damri', 'Warnet Cyber', 
            'Rumah Sakit Daerah', 'Klinik Medika', 'Mall Metropolitan', 
            'Masjid Raya Kota', 'Rumah Keluarga', 'Apotek Kimia Farma',
            'Supermarket Indomaret', 'Tempat Les Bahasa Inggris'
        ];

        $reasons = [
            'Menjenguk nenek yang sedang sakit keras di kampung halaman.',
            'Acara pernikahan kakak kandung pada hari Minggu.',
            'Pulang kampung akhir pekan untuk mengambil berkas beasiswa.',
            'Mengikuti seminar nasional teknologi dan robotika.',
            'Kegiatan bakti sosial mahasiswa di desa binaan.',
            'Menghadiri reuni keluarga besar tahunan.'
        ];

        $now = Carbon::now();

        // 3. SEEDING PERMITS (Riwayat Masa Lalu)
        // Kita berikan 1-3 riwayat masa lalu (returned_on_time, returned_late, rejected) untuk setiap mahasiswa
        foreach ($students as $student) {
            $numPastPermits = rand(1, 3);
            for ($i = 0; $i < $numPastPermits; $i++) {
                $type = rand(0, 1) ? 'pesiar' : 'bermalam';
                $dest = $destinations[array_rand($destinations)];
                $reason = $type === 'bermalam' ? $reasons[array_rand($reasons)] : null;
                
                // Hari ke belakang (misal: 15 hari lalu sampai 3 hari lalu)
                $daysAgo = rand(3, 15);
                $startTime = Carbon::parse($now)->subDays($daysAgo)->setTime(rand(7, 12), 0, 0);

                $statusRoll = rand(1, 10);
                if ($statusRoll <= 6) {
                    // returned_on_time
                    $status = 'returned_on_time';
                    if ($type === 'pesiar') {
                        $endTime = Carbon::parse($startTime)->setTime(21, 0, 0);
                        // Kembali sebelum jam 21:00
                        $actualReturn = Carbon::parse($startTime)->setTime(rand(18, 20), rand(0, 59), 0);
                    } else {
                        $durationDays = rand(1, 3);
                        $endTime = Carbon::parse($startTime)->addDays($durationDays)->setTime(17, 0, 0);
                        // Kembali sebelum jam 17:00 pada tanggal rencana kembali
                        $actualReturn = Carbon::parse($startTime)->addDays($durationDays)->setTime(rand(13, 16), rand(0, 59), 0);
                    }
                    $lateness = 0;
                } elseif ($statusRoll <= 9) {
                    // returned_late
                    $status = 'returned_late';
                    if ($type === 'pesiar') {
                        $endTime = Carbon::parse($startTime)->setTime(21, 0, 0);
                        // Kembali setelah jam 21:00 (misal: 21:30 - 23:30)
                        $actualReturn = Carbon::parse($startTime)->setTime(rand(21, 23), rand(10, 59), 0);
                        $lateness = $endTime->diffInMinutes($actualReturn);
                    } else {
                        $durationDays = rand(1, 3);
                        $endTime = Carbon::parse($startTime)->addDays($durationDays)->setTime(17, 0, 0);
                        // Kembali setelah jam 17:00 (misal: 18:00 - 22:00)
                        $actualReturn = Carbon::parse($startTime)->addDays($durationDays)->setTime(rand(18, 22), rand(0, 59), 0);
                        $lateness = $endTime->diffInMinutes($actualReturn);
                    }
                } else {
                    // rejected
                    $status = 'rejected';
                    $endTime = null;
                    $actualReturn = null;
                    $lateness = null;
                }

                Permit::create([
                    'student_id' => $student->id,
                    'type' => $type,
                    'destination' => $dest,
                    'reason' => $reason,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => $status,
                    'actual_return_time' => $actualReturn,
                    'lateness_duration' => $lateness,
                    'action_by' => $admin->id,
                    'action_at' => Carbon::parse($startTime)->subMinutes(rand(10, 60)),
                ]);
            }
        }

        // 4. SEEDING ACTIVE & PENDING PERMITS (Status Terkini)
        // Kita batasi agar tiap mahasiswa maksimal punya 1 permit aktif atau pending (sesuai aturan validasi)

        // ==========================================================
        // A. 15 MAHASISWA DENGAN IZIN AKTIF PESIAR (APPROVED) - MHS 0-14
        // ==========================================================
        for ($i = 0; $i < 15; $i++) {
            $isOverdue = ($i >= 10); // 10 normal, 5 overdue
            $dayOffset = $isOverdue ? 1 : 0;
            $dest = $destinations[array_rand($destinations)];

            Permit::create([
                'student_id' => $students[$i]->id,
                'type' => 'pesiar',
                'destination' => $dest,
                'reason' => null,
                'start_time' => Carbon::parse($now)->subDays($dayOffset)->setTime(9 + ($i % 3), 0, 0),
                'end_time' => Carbon::parse($now)->subDays($dayOffset)->setTime(21, 0, 0),
                'status' => 'approved',
                'action_by' => $admin->id,
                'action_at' => Carbon::parse($now)->subDays($dayOffset)->setTime(8, 30, 0),
            ]);
        }

        // ===========================================================
        // B. 15 MAHASISWA DENGAN IZIN AKTIF BERMALAM (APPROVED) - MHS 15-29
        // ===========================================================
        for ($i = 15; $i < 30; $i++) {
            $isOverdue = ($i >= 25); // 10 normal, 5 overdue
            $dest = $destinations[array_rand($destinations)];
            $reason = $reasons[array_rand($reasons)];

            if ($isOverdue) {
                // Batas kembali kemarin jam 17:00 sore
                $startTime = Carbon::parse($now)->subDays(3)->setTime(10, 0, 0);
                $endTime = Carbon::parse($now)->subDay()->setTime(17, 0, 0);
            } else {
                // Batas kembali besok atau lusa jam 17:00 sore
                $startTime = Carbon::parse($now)->subDay()->setTime(14, 0, 0);
                $endTime = Carbon::parse($now)->addDays($i % 2 === 0 ? 1 : 2)->setTime(17, 0, 0);
            }

            Permit::create([
                'student_id' => $students[$i]->id,
                'type' => 'bermalam',
                'destination' => $dest,
                'reason' => $reason,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'approved',
                'action_by' => $admin->id,
                'action_at' => Carbon::parse($startTime)->subHours(2),
            ]);
        }

        // ==========================================================
        // C. 15 MAHASISWA DENGAN PENGAJUAN PESIAR PENDING - MHS 30-44
        // ==========================================================
        for ($i = 30; $i < 45; $i++) {
            $dest = $destinations[array_rand($destinations)];
            Permit::create([
                'student_id' => $students[$i]->id,
                'type' => 'pesiar',
                'destination' => $dest,
                'reason' => null,
                'start_time' => Carbon::parse($now)->setTime(10 + ($i % 5), 0, 0),
                'status' => 'pending',
            ]);
        }

        // ===========================================================
        // D. 15 MAHASISWA DENGAN PENGAJUAN BERMALAM PENDING - MHS 45-59
        // ===========================================================
        for ($i = 45; $i < 60; $i++) {
            $dest = $destinations[array_rand($destinations)];
            $reason = $reasons[array_rand($reasons)];
            $startTime = Carbon::parse($now)->addDays(1 + ($i % 3))->setTime(8, 0, 0);
            $endTime = Carbon::parse($startTime)->addDays(2)->setTime(17, 0, 0);

            Permit::create([
                'student_id' => $students[$i]->id,
                'type' => 'bermalam',
                'destination' => $dest,
                'reason' => $reason,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'pending',
            ]);
        }
    }
}

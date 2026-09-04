<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Permit;
use App\Models\PrayerAttendance;
use App\Models\Activity;
use App\Models\ActivityAttendance;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with realistic and diverse dorm management data.
     */
    public function run(): void
    {
        // 1. Membuat Admin & Pembina Asrama
        $admin = User::create([
            'name' => 'Admin Utama Asrama',
            'email' => 'admin@asrama.com',
            'password' => Hash::make('password'),
            'role' => 'pengelola',
        ]);

        $pembina = User::create([
            'name' => 'Pembina Asrama',
            'email' => 'pembina@asrama.com',
            'password' => Hash::make('password'),
            'role' => 'pengelola',
        ]);

        // 2. Data Mahasiswa & Skenario Variasi Status
        $studentsData = [
            // --- MAHASISWI (ASTRI) ---
            [
                'name' => "SITI KOMA'INAH",
                'nim' => 'P17230251001',
                'dorm' => 'ASTRI B1',
                'phone' => '081234567001',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Perpustakaan Kota Blitar',
                    'reason' => 'Pinjam buku referensi tugas akhir',
                    'start_time' => Carbon::now()->subHour(),
                    'end_time' => null,
                    'status' => 'pending',
                ]
            ],
            [
                'name' => 'DEVI PERMATASARI',
                'nim' => 'P17230251002',
                'dorm' => 'ASTRI B1',
                'phone' => '081234567002',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Pasar Besar Blitar',
                    'reason' => 'Beli perlengkapan mandi & laundry',
                    'start_time' => Carbon::now()->subMinutes(30),
                    'end_time' => null,
                    'status' => 'pending',
                ]
            ],
            [
                'name' => 'RINA AMALIA',
                'nim' => 'P17230251003',
                'dorm' => 'ASTRI B2',
                'phone' => '081234567003',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Apotek & Minimarket',
                    'reason' => 'Beli obat vitamin',
                    'start_time' => Carbon::now()->subMinutes(15),
                    'end_time' => null,
                    'status' => 'pending',
                ]
            ],
            [
                'name' => 'ULFY AGUSTINA HERLAMBANG',
                'nim' => 'P17230251025',
                'dorm' => 'ASTRI B1',
                'phone' => '081234567004',
                'suspended' => false,
                'permit' => [
                    'type' => 'bermalam_biasa',
                    'destination' => 'Rumah Orang Tua (Kediri)',
                    'reason' => 'Acara syukuran keluarga',
                    'start_time' => Carbon::now()->subDay(),
                    'end_time' => Carbon::now()->addDays(2)->setTime(6, 30),
                    'status' => 'approved',
                    'action_by' => $admin->id,
                    'action_at' => Carbon::now()->subDay()->subHours(2),
                ]
            ],
            [
                'name' => 'DESI WULANDARI',
                'nim' => 'P17230251005',
                'dorm' => 'ASTRI C1',
                'phone' => '081234567005',
                'suspended' => false,
                'permit' => [
                    'type' => 'bermalam_biasa',
                    'destination' => 'Rumah Orang Tua (Tulungagung)',
                    'reason' => 'Pulang kampung akhir pekan',
                    'start_time' => Carbon::now()->addHours(3),
                    'end_time' => Carbon::now()->addDays(2)->setTime(6, 30),
                    'status' => 'pending',
                ]
            ],
            [
                'name' => 'FIFI ALFIAH',
                'nim' => 'P17230251006',
                'dorm' => 'ASTRI C1',
                'phone' => '081234567006',
                'suspended' => false,
                'permit' => [
                    'type' => 'bermalam_urgensi',
                    'destination' => 'RSUD Mardi Waluyo Blitar',
                    'reason' => 'Mendampingi anggota keluarga yang dirawat',
                    'start_time' => Carbon::now()->subHours(2),
                    'end_time' => Carbon::now()->addDay()->setTime(6, 30),
                    'status' => 'pending',
                ]
            ],
            [
                'name' => 'NADYA RAMADHANTI',
                'nim' => 'P17230253056',
                'dorm' => 'ASTRI B1',
                'phone' => '081234567007',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Blitar Town Square',
                    'reason' => 'Nonton bioskop',
                    'start_time' => Carbon::now()->subDays(2),
                    'end_time' => null,
                    'status' => 'rejected',
                    'admin_note' => 'Izin ditolak karena masih dalam jam aktivitas perkuliahan.',
                    'action_by' => $pembina->id,
                    'action_at' => Carbon::now()->subDays(2)->addMinutes(10),
                ]
            ],
            [
                'name' => 'SELLA PERMATASARI',
                'nim' => 'P17230251035',
                'dorm' => 'ASTRI B1',
                'phone' => '081234567008',
                'suspended' => true,
                'suspended_at' => Carbon::now()->subHours(12),
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Supermarket Blitar',
                    'reason' => 'Belanja bulanan',
                    'start_time' => Carbon::now()->subHours(20),
                    'end_time' => Carbon::now()->subHours(14),
                    'actual_return_time' => Carbon::now()->subHours(12),
                    'lateness_duration' => 120, // 2 jam telat
                    'status' => 'returned_late',
                    'action_by' => $admin->id,
                    'action_at' => Carbon::now()->subHours(21),
                ]
            ],
            [
                'name' => 'FANI RAHMAWATI',
                'nim' => 'P17230251008',
                'dorm' => 'ASTRI B2',
                'phone' => '081234567009',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Toko Buku Togamas',
                    'reason' => 'Beli alat tulis',
                    'start_time' => Carbon::now()->subDays(1)->setTime(14, 0),
                    'end_time' => Carbon::now()->subDays(1)->setTime(22, 0),
                    'actual_return_time' => Carbon::now()->subDays(1)->setTime(20, 45),
                    'lateness_duration' => 0,
                    'status' => 'returned_on_time',
                    'action_by' => $admin->id,
                    'action_at' => Carbon::now()->subDays(1)->setTime(13, 50),
                ]
            ],
            [
                'name' => 'HANIFAH NUR',
                'nim' => 'P17230251009',
                'dorm' => 'ASTRI C1',
                'phone' => '081234567010',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Alun-alun Blitar',
                    'reason' => 'Cari makan malam',
                    'start_time' => Carbon::now()->subDays(3)->setTime(18, 0),
                    'end_time' => Carbon::now()->subDays(3)->setTime(21, 30),
                    'actual_return_time' => Carbon::now()->subDays(3)->setTime(22, 10),
                    'lateness_duration' => 40,
                    'status' => 'returned_late',
                    'action_by' => $pembina->id,
                    'action_at' => Carbon::now()->subDays(3)->setTime(17, 45),
                ]
            ],
            [
                'name' => 'KIKI ANDRIANI',
                'nim' => 'P17230251011',
                'dorm' => 'ASTRI C1',
                'phone' => '081234567011',
                'suspended' => false,
                'permit' => [
                    'type' => 'bermalam_biasa',
                    'destination' => 'Rumah Nenek (Malang)',
                    'reason' => 'Silaturahmi keluarga',
                    'start_time' => Carbon::now()->addHours(1),
                    'end_time' => Carbon::now()->addDays(3)->setTime(6, 30),
                    'status' => 'pending',
                ]
            ],

            // --- MAHASISWA (ASTRO) ---
            [
                'name' => 'AHMAD FAUZI',
                'nim' => 'P17230252001',
                'dorm' => 'ASTRO A1',
                'phone' => '081234567012',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Lapangan Olahraga Kanigoro',
                    'reason' => 'Latihan futsal rutin',
                    'start_time' => Carbon::now()->subHours(3),
                    'end_time' => Carbon::now()->today()->setTime(22, 0),
                    'status' => 'approved',
                    'action_by' => $admin->id,
                    'action_at' => Carbon::now()->subHours(3)->subMinutes(15),
                ]
            ],
            [
                'name' => 'BIMA SAKTI',
                'nim' => 'P17230252002',
                'dorm' => 'ASTRO A1',
                'phone' => '081234567013',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Warkop & Resto Blitar',
                    'reason' => 'Kerja kelompok skripsi',
                    'start_time' => Carbon::now()->subHours(6),
                    'end_time' => Carbon::now()->subHours(1), // TERLAMBAT / OVERDUE AKTIF
                    'status' => 'approved',
                    'action_by' => $admin->id,
                    'action_at' => Carbon::now()->subHours(6)->subMinutes(10),
                ]
            ],
            [
                'name' => 'CHOIRUL ANAM',
                'nim' => 'P17230252003',
                'dorm' => 'ASTRO A2',
                'phone' => '081234567014',
                'suspended' => false,
                'permit' => [
                    'type' => 'bermalam_urgensi',
                    'destination' => 'Rumah Sakit Syuhada Haji',
                    'reason' => 'Menjaga kerabat sakit',
                    'start_time' => Carbon::now()->subHours(5),
                    'end_time' => Carbon::now()->addDay()->setTime(6, 30),
                    'status' => 'approved',
                    'action_by' => $pembina->id,
                    'action_at' => Carbon::now()->subHours(5)->subMinutes(30),
                ]
            ],
            [
                'name' => 'DANI PRASETYO',
                'nim' => 'P17230252004',
                'dorm' => 'ASTRO A2',
                'phone' => '081234567015',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Toko Olahraga Winner',
                    'reason' => 'Beli sepatu bola',
                    'start_time' => Carbon::now()->subMinutes(40),
                    'end_time' => null,
                    'status' => 'pending',
                ]
            ],
            [
                'name' => 'EKO PURWANTO',
                'nim' => 'P17230252005',
                'dorm' => 'ASTRO B1',
                'phone' => '081234567016',
                'suspended' => true,
                'suspended_at' => Carbon::now()->subDays(1),
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Rental Komputer',
                    'reason' => 'Print berkas laporan',
                    'start_time' => Carbon::now()->subDays(1)->setTime(16, 0),
                    'end_time' => Carbon::now()->subDays(1)->setTime(21, 0),
                    'actual_return_time' => Carbon::now()->subDays(1)->setTime(22, 15),
                    'lateness_duration' => 75,
                    'status' => 'returned_late',
                    'action_by' => $admin->id,
                    'action_at' => Carbon::now()->subDays(1)->setTime(15, 30),
                ]
            ],
            [
                'name' => 'GILANG RAMADHAN',
                'nim' => 'P17230252006',
                'dorm' => 'ASTRO B1',
                'phone' => '081234567017',
                'suspended' => false,
                'permit' => [
                    'type' => 'bermalam_biasa',
                    'destination' => 'Rumah Orang Tua (Blitar Selatan)',
                    'reason' => 'Bantu panen di rumah',
                    'start_time' => Carbon::now()->subDays(5)->setTime(16, 0),
                    'end_time' => Carbon::now()->subDays(3)->setTime(6, 30),
                    'actual_return_time' => Carbon::now()->subDays(3)->setTime(06, 10),
                    'lateness_duration' => 0,
                    'status' => 'returned_on_time',
                    'action_by' => $pembina->id,
                    'action_at' => Carbon::now()->subDays(5)->setTime(15, 0),
                ]
            ],
            [
                'name' => 'INDRA WIJAYA',
                'nim' => 'P17230252007',
                'dorm' => 'ASTRO A1',
                'phone' => '081234567018',
                'suspended' => false,
                'permit' => null,
            ],
            [
                'name' => 'JULIA PUTRI',
                'nim' => 'P17230251010',
                'dorm' => 'ASTRI B2',
                'phone' => '081234567019',
                'suspended' => false,
                'permit' => null,
            ],
            [
                'name' => 'LUKMAN HAKIM',
                'nim' => 'P17230252008',
                'dorm' => 'ASTRO A2',
                'phone' => '081234567020',
                'suspended' => false,
                'permit' => [
                    'type' => 'pesiar',
                    'destination' => 'Bengkel Motor Utama',
                    'reason' => 'Servis sepeda motor',
                    'start_time' => Carbon::now()->subMinutes(10),
                    'end_time' => null,
                    'status' => 'pending',
                ]
            ],
        ];

        $createdStudents = [];

        foreach ($studentsData as $data) {
            $emailName = strtolower(str_replace([' ', "'"], ['', ''], $data['name']));
            $user = User::create([
                'name' => $data['name'],
                'email' => $emailName . '@asrama.com',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);

            $student = $user->student()->create([
                'nim' => $data['nim'],
                'dorm_room' => $data['dorm'],
                'phone' => $data['phone'],
                'is_suspended' => $data['suspended'],
                'suspended_at' => $data['suspended_at'] ?? null,
            ]);

            $createdStudents[] = $student;

            if ($data['permit']) {
                $pData = $data['permit'];
                Permit::create([
                    'student_id' => $student->id,
                    'type' => $pData['type'],
                    'destination' => $pData['destination'],
                    'reason' => $pData['reason'] ?? null,
                    'start_time' => $pData['start_time'],
                    'end_time' => $pData['end_time'] ?? null,
                    'actual_return_time' => $pData['actual_return_time'] ?? null,
                    'lateness_duration' => $pData['lateness_duration'] ?? 0,
                    'status' => $pData['status'],
                    'action_by' => $pData['action_by'] ?? null,
                    'action_at' => $pData['action_at'] ?? null,
                    'admin_note' => $pData['admin_note'] ?? null,
                ]);
            }
        }

        // 3. SEEDING PRESENSI SHOLAT (Past 3 Days + Today)
        $prayerTimes = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        $statuses = ['berjamaah', 'berjamaah', 'berjamaah', 'munfarid', 'alpa'];

        for ($i = 0; $i < 4; $i++) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            foreach ($createdStudents as $idx => $student) {
                foreach ($prayerTimes as $pTime) {
                    // Variasi status presensi berdasar index
                    $status = $statuses[($idx + strlen($pTime)) % count($statuses)];
                    PrayerAttendance::create([
                        'student_id' => $student->id,
                        'date' => $date,
                        'prayer_time' => $pTime,
                        'status' => $status,
                    ]);
                }
            }
        }

        // 4. SEEDING KEGIATAN KUSTOM & ABSENSI KEGIATAN
        $activity1 = Activity::create([
            'name' => 'Kajian Rutin Malam Jumat',
            'date' => Carbon::today()->subDays(1),
            'start_time' => '19:30:00',
            'end_time' => '21:00:00',
            'description' => 'Wajib bagi seluruh mahasiswa penghuni asrama.',
        ]);

        $activity2 = Activity::create([
            'name' => 'Gotong Royong Kebersihan Asrama',
            'date' => Carbon::today()->subDays(3),
            'start_time' => '06:00:00',
            'end_time' => '08:30:00',
            'description' => 'Kerja bakti pembersihan area halaman dan lorong.',
        ]);

        $activity3 = Activity::create([
            'name' => 'Senam Pagi Asrama',
            'date' => Carbon::today(),
            'start_time' => '06:00:00',
            'end_time' => '07:00:00',
            'description' => 'Olahraga pagi bersama pengurus asrama.',
        ]);

        $actStatuses = ['hadir', 'hadir', 'hadir', 'izin', 'alpa'];
        foreach ($createdStudents as $idx => $student) {
            ActivityAttendance::create([
                'activity_id' => $activity1->id,
                'student_id' => $student->id,
                'status' => $actStatuses[$idx % count($actStatuses)],
                'notes' => $actStatuses[$idx % count($actStatuses)] === 'izin' ? 'Pulang rumah' : null,
            ]);

            ActivityAttendance::create([
                'activity_id' => $activity2->id,
                'student_id' => $student->id,
                'status' => $actStatuses[($idx + 1) % count($actStatuses)],
            ]);

            ActivityAttendance::create([
                'activity_id' => $activity3->id,
                'student_id' => $student->id,
                'status' => $actStatuses[($idx + 2) % count($actStatuses)],
            ]);
        }
    }
}

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

     
   
       

        // ==========================================
        // 5. SEEDING ASTRI B1 STUDENTS & SCENARIOS
        // ==========================================

        // Student 1: SITI KOMA'INAH - Akun yang sedang minta izin (pending)
        $user1 = User::create([
            'name' => "SITI KOMA'INAH",
            'email' => 'siti.k@asrama.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
        $student1 = $user1->student()->create([
            'nim' => 'P17230251001',
            'dorm_room' => 'ASTRI B1',
            'phone' => '081234567001',
        ]);
        Permit::create([
            'student_id' => $student1->id,
            'type' => 'pesiar',
            'destination' => 'Perpustakaan Kota',
            'start_time' => Carbon::now()->addHours(2),
            'status' => 'pending',
        ]);

        // Student 2: ULFY AGUSTINA HERLAMBANG - Akun yang sedang izin (approved)
        $user2 = User::create([
            'name' => 'ULFY AGUSTINA HERLAMBANG',
            'email' => 'ulfy@asrama.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
        $student2 = $user2->student()->create([
            'nim' => 'P17230251025',
            'dorm_room' => 'ASTRI B1',
            'phone' => '081234567002',
        ]);
        Permit::create([
            'student_id' => $student2->id,
            'type' => 'bermalam_biasa',
            'destination' => 'Rumah Orang Tua',
            'reason' => 'Acara keluarga penting',
            'start_time' => Carbon::now()->subDay(),
            'end_time' => Carbon::now()->addDays(2),
            'status' => 'approved',
            'action_by' => $admin->id,
            'action_at' => Carbon::now()->subDay()->subHours(2),
        ]);

        // Student 3: NADYA RAMADHANTI - Akun yang izinnya ditolak (rejected)
        $user3 = User::create([
            'name' => 'NADYA RAMADHANTI',
            'email' => 'nadya@asrama.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
        $student3 = $user3->student()->create([
            'nim' => 'P17230253056',
            'dorm_room' => 'ASTRI B1',
            'phone' => '081234567003',
        ]);
        Permit::create([
            'student_id' => $student3->id,
            'type' => 'pesiar',
            'destination' => 'Mall',
            'start_time' => Carbon::now()->subDays(2),
            'status' => 'rejected',
            'action_by' => $admin->id,
            'action_at' => Carbon::now()->subDays(2)->subHours(1),
        ]);

        // Student 4: SELLA PERMATASARI - Akun ditangguhkan karena telat (suspended)
        $user4 = User::create([
            'name' => 'SELLA PERMATASARI',
            'email' => 'sella@asrama.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
        $student4 = $user4->student()->create([
            'nim' => 'P17230251035',
            'dorm_room' => 'ASTRI B1',
            'phone' => '081234567004',
            'is_suspended' => true,
            'suspended_at' => Carbon::now()->subHours(12),
        ]);
        // Izin terdahulu yang terlambat yang memicu penangguhan
        Permit::create([
            'student_id' => $student4->id,
            'type' => 'pesiar',
            'destination' => 'Supermarket',
            'start_time' => Carbon::now()->subHours(20),
            'end_time' => Carbon::now()->subHours(14),
            'actual_return_time' => Carbon::now()->subHours(12),
            'lateness_duration' => 120, // 2 jam terlambat
            'status' => 'returned_late',
            'action_by' => $admin->id,
            'action_at' => Carbon::now()->subHours(21),
        ]);
    }
}

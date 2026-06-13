<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat Admin / Pengelola Asrama
        User::factory()->create([
            'name' => 'Admin Asrama',
            'email' => 'admin@asrama.com',
            'password' => bcrypt('password'),
            'role' => 'pengelola',
        ]);

        // Daftar mahasiswa dummy
        $studentsData = [
            ['name' => 'Andi Pratama', 'email' => 'andi@asrama.com', 'nim' => '1234567890', 'room' => 'A-101', 'phone' => '081234567890'],
            ['name' => 'Budi Santoso', 'email' => 'budi@asrama.com', 'nim' => '1234567891', 'room' => 'A-102', 'phone' => '081234567891'],
            ['name' => 'Citra Lestari', 'email' => 'citra@asrama.com', 'nim' => '1234567892', 'room' => 'B-201', 'phone' => '081234567892'],
            ['name' => 'Dedi Wijaya', 'email' => 'dedi@asrama.com', 'nim' => '1234567893', 'room' => 'B-202', 'phone' => '081234567893'],
            ['name' => 'Eka Saputra', 'email' => 'eka@asrama.com', 'nim' => '1234567894', 'room' => 'C-301', 'phone' => '081234567894'],
        ];

        foreach ($studentsData as $data) {
            $user = User::factory()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role' => 'mahasiswa',
            ]);

            $user->student()->create([
                'nim' => $data['nim'],
                'dorm_room' => $data['room'],
                'phone' => $data['phone'],
            ]);
        }
    }
}

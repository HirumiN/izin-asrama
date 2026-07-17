<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_authenticated_users_cannot_access_student_list()
    {
        $response = $this->get(route('admin.students.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_students_cannot_access_student_list()
    {
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($studentUser)->get(route('admin.students.index'));
        $response->assertStatus(403);
    }

    public function test_pengelola_can_access_student_list_and_search()
    {
        $pengelolaUser = User::factory()->create(['role' => 'pengelola']);
        
        $student1 = User::factory()->create(['name' => 'Budi Santoso', 'role' => 'mahasiswa']);
        $student1->student()->create(['nim' => 'NIM001', 'dorm_room' => 'A-101']);

        $student2 = User::factory()->create(['name' => 'Siti Aminah', 'role' => 'mahasiswa']);
        $student2->student()->create(['nim' => 'NIM002', 'dorm_room' => 'B-202']);

        // View index
        $response = $this->actingAs($pengelolaUser)->get(route('admin.students.index'));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Siti Aminah');

        // Search search
        $responseSearch = $this->actingAs($pengelolaUser)->get(route('admin.students.index', ['search' => 'Budi']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Budi Santoso');
        $responseSearch->assertDontSee('Siti Aminah');
    }

    public function test_pengelola_can_reset_student_password_to_nim()
    {
        $pengelolaUser = User::factory()->create(['role' => 'pengelola']);
        
        $user = User::factory()->create([
            'name' => 'Ahmad Roni',
            'email' => 'ahmad@example.com',
            'password' => bcrypt('oldpassword123'),
            'role' => 'mahasiswa',
        ]);
        $student = $user->student()->create([
            'nim' => 'P17230259999',
            'dorm_room' => 'A-102',
        ]);

        $response = $this->actingAs($pengelolaUser)->post(route('admin.students.resetPassword', $student));
        
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('P17230259999', $user->password));
    }

    public function test_pengelola_can_delete_student_and_wipe_history()
    {
        $pengelolaUser = User::factory()->create(['role' => 'pengelola']);
        
        $user = User::factory()->create([
            'name' => 'Ahmad Roni',
            'email' => 'ahmad@example.com',
            'role' => 'mahasiswa',
        ]);
        $student = $user->student()->create([
            'nim' => 'P17230259999',
            'dorm_room' => 'A-102',
        ]);

        // Tambah riwayat sholat, izin, dan kegiatan kustom
        $permit = $student->permits()->create([
            'type' => 'pulang',
            'destination' => 'Rumah Orang Tua',
            'reason' => 'Acara keluarga',
            'out_time' => now(),
            'eta_return' => now()->addDays(2),
            'status' => 'pending',
        ]);

        $prayerAttendance = $student->prayerAttendances()->create([
            'date' => today(),
            'prayer_time' => 'subuh',
            'status' => 'berjamaah',
        ]);

        $activity = \App\Models\Activity::create([
            'name' => 'Rapat',
            'date' => today(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
        ]);

        $activityAttendance = $student->activityAttendances()->create([
            'activity_id' => $activity->id,
            'status' => 'hadir',
        ]);

        // Panggil rute destroy
        $response = $this->actingAs($pengelolaUser)->delete(route('admin.students.destroy', $student));
        
        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        // Pastikan user dan student dihapus
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('students', ['id' => $student->id]);

        // Pastikan semua riwayat dihapus (cascade)
        $this->assertDatabaseMissing('permits', ['id' => $permit->id]);
        $this->assertDatabaseMissing('prayer_attendances', ['id' => $prayerAttendance->id]);
        $this->assertDatabaseMissing('activity_attendances', ['id' => $activityAttendance->id]);
    }
}

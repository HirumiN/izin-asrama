<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\PrayerAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrayerAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_prayer_attendance_page()
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $student = $user->student()->create([
            'nim' => '123456',
            'dorm_room' => 'A-101',
        ]);

        $response = $this->actingAs($user)->get(route('student.sholat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('student.sholat.index');
    }

    public function test_student_can_submit_prayer_attendance()
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $student = $user->student()->create([
            'nim' => '123456',
            'dorm_room' => 'A-101',
        ]);

        $response = $this->actingAs($user)->post(route('student.sholat.store'), [
            'prayer_time' => 'subuh',
            'status' => 'berjamaah',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('prayer_attendances', [
            'student_id' => $student->id,
            'prayer_time' => 'subuh',
            'status' => 'berjamaah',
            'date' => today()->format('Y-m-d'),
        ]);
    }

    public function test_student_cannot_submit_duplicate_prayer_attendance()
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $student = $user->student()->create([
            'nim' => '123456',
            'dorm_room' => 'A-101',
        ]);

        // Submit pertama
        $student->prayerAttendances()->create([
            'date' => today()->format('Y-m-d'),
            'prayer_time' => 'subuh',
            'status' => 'berjamaah',
        ]);

        // Submit kedua lewat rute (seharusnya melakukan update/timpa status alih-alih melempar error duplikat SQL)
        $response = $this->actingAs($user)->post(route('student.sholat.store'), [
            'prayer_time' => 'subuh',
            'status' => 'munfarid',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('prayer_attendances', [
            'student_id' => $student->id,
            'prayer_time' => 'subuh',
            'status' => 'munfarid',
        ]);
        $this->assertEquals(1, $student->prayerAttendances()->count());
    }

    public function test_admin_can_view_prayer_monitoring()
    {
        $admin = User::factory()->create(['role' => 'pengelola']);
        
        $response = $this->actingAs($admin)->get(route('admin.sholat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sholat.index');
    }
}

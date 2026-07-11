<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Activity;
use App\Models\ActivityAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class ActivityAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_activities_list()
    {
        $admin = User::factory()->create(['role' => 'pengelola']);
        
        $response = $this->actingAs($admin)->get(route('admin.activities.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.activities.index');
    }

    public function test_admin_can_create_new_activity()
    {
        $admin = User::factory()->create(['role' => 'pengelola']);
        
        $response = $this->actingAs($admin)->post(route('admin.activities.store'), [
            'name' => 'Apel Malam Minggu',
            'date' => today()->format('Y-m-d'),
            'start_time' => '19:00',
            'end_time' => '20:00',
            'description' => 'Apel wajib berkala.',
        ]);

        $response->assertRedirect(route('admin.activities.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('activities', [
            'name' => 'Apel Malam Minggu',
            'date' => today()->format('Y-m-d 00:00:00'),
            'start_time' => '19:00',
            'end_time' => '20:00',
            'description' => 'Apel wajib berkala.',
        ]);
    }

    public function test_admin_can_view_attendance_monitoring_page()
    {
        $admin = User::factory()->create(['role' => 'pengelola']);
        $activity = Activity::create([
            'name' => 'Apel Malam',
            'date' => today()->format('Y-m-d'),
            'start_time' => '19:00',
            'end_time' => '20:00',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.activities.attendance.show', $activity->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.activities.attendance');
    }

    public function test_admin_cannot_submit_bulk_attendance()
    {
        // Admin tidak lagi memiliki akses POST untuk edit absensi massal.
        // Mahasiswa absen mandiri dari akun masing-masing.
        $admin = User::factory()->create(['role' => 'pengelola']);

        $user1 = User::factory()->create(['role' => 'mahasiswa']);
        $student1 = $user1->student()->create([
            'nim' => '100001',
            'dorm_room' => 'A-101',
        ]);

        $activity = Activity::create([
            'name' => 'Apel Malam',
            'date' => today()->format('Y-m-d'),
            'start_time' => '19:00',
            'end_time' => '20:00',
        ]);

        // Route POST attendance.update sudah tidak ada — harus return 405 Method Not Allowed
        $response = $this->actingAs($admin)->post(
            "/admin/activities/{$activity->id}/attendance",
            ['attendance' => [$student1->id => ['status' => 'hadir', 'notes' => '']]]
        );

        // Tidak ada route yang cocok → 405 Method Not Allowed
        $response->assertStatus(405);

        // Dipastikan tidak ada data absensi yang tersimpan dari aksi admin
        $this->assertDatabaseMissing('activity_attendances', [
            'activity_id' => $activity->id,
            'student_id'  => $student1->id,
        ]);
    }

    public function test_student_can_view_activity_attendances_on_dashboard()
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $student = $user->student()->create([
            'nim' => '100001',
            'dorm_room' => 'A-101',
        ]);

        $activity = Activity::create([
            'name' => 'Apel Malam',
            'date' => today()->format('Y-m-d'),
            'start_time' => '19:00',
            'end_time' => '20:00',
        ]);

        ActivityAttendance::create([
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'status' => 'hadir',
            'notes' => 'Mengikuti apel',
        ]);

        $response = $this->actingAs($user)->get(route('student.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Apel Malam');
        $response->assertSee('Hadir');
        $response->assertSee('Mengikuti apel');
    }

    public function test_student_can_submit_self_attendance_on_time()
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $student = $user->student()->create([
            'nim' => '100001',
            'dorm_room' => 'A-101',
        ]);

        $activity = Activity::create([
            'name' => 'Apel Malam',
            'date' => today()->format('Y-m-d'),
            'start_time' => '19:00',
            'end_time' => '20:00',
        ]);

        // Simulasikan waktu saat ini berada di dalam rentang absensi (19:30)
        Carbon::setTestNow(today()->setTime(19, 30, 0));

        $response = $this->actingAs($user)->post(route('student.activities.attendance', $activity->id));

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('activity_attendances', [
            'activity_id' => $activity->id,
            'student_id' => $student->id,
            'status' => 'hadir',
            'notes' => 'Absen Mandiri',
        ]);

        Carbon::setTestNow(); // Reset test time
    }

    public function test_student_cannot_submit_self_attendance_when_late()
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $student = $user->student()->create([
            'nim' => '100001',
            'dorm_room' => 'A-101',
        ]);

        $activity = Activity::create([
            'name' => 'Apel Malam',
            'date' => today()->format('Y-m-d'),
            'start_time' => '19:00',
            'end_time' => '20:00',
        ]);

        // Simulasikan waktu saat ini terlambat (20:01)
        Carbon::setTestNow(today()->setTime(20, 1, 0));

        $response = $this->actingAs($user)->post(route('student.activities.attendance', $activity->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('activity_attendances', [
            'activity_id' => $activity->id,
            'student_id' => $student->id,
        ]);

        Carbon::setTestNow(); // Reset test time
    }
}

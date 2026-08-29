<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExcelExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'pengelola',
        ]);

        $this->studentUser = User::factory()->create([
            'role' => 'mahasiswa',
        ]);

        Student::create([
            'user_id' => $this->studentUser->id,
            'nim' => '2024001',
            'dorm_room' => 'A101',
            'phone' => '08123456789',
        ]);
    }

    public function test_guest_cannot_access_export_routes()
    {
        $this->get(route('admin.permits.export-csv'))->assertRedirect(route('login'));
        $this->get(route('admin.students.export-csv'))->assertRedirect(route('login'));
        $this->get(route('admin.sholat.export-csv'))->assertRedirect(route('login'));
        $this->get(route('admin.activities.export-csv'))->assertRedirect(route('login'));
    }

    public function test_student_cannot_access_admin_export_routes()
    {
        $this->actingAs($this->studentUser)->get(route('admin.permits.export-csv'))->assertStatus(403);
        $this->actingAs($this->studentUser)->get(route('admin.students.export-csv'))->assertStatus(403);
        $this->actingAs($this->studentUser)->get(route('admin.sholat.export-csv'))->assertStatus(403);
        $this->actingAs($this->studentUser)->get(route('admin.activities.export-csv'))->assertStatus(403);
    }

    public function test_admin_can_export_permits_csv()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.permits.export-csv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('.csv', $response->headers->get('content-disposition', ''));
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type', ''));
    }

    public function test_admin_can_export_students_csv()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.students.export-csv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('.csv', $response->headers->get('content-disposition', ''));
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type', ''));
    }

    public function test_admin_can_export_prayer_attendance_csv()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.sholat.export-csv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('.csv', $response->headers->get('content-disposition', ''));
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type', ''));
    }

    public function test_admin_can_export_activities_csv()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.activities.export-csv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('.csv', $response->headers->get('content-disposition', ''));
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type', ''));
    }

    public function test_admin_can_export_activity_attendance_csv()
    {
        $activity = Activity::create([
            'name' => 'Gotong Royong Asrama',
            'date' => now()->toDateString(),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'description' => 'Membersihkan halaman asrama',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.activities.attendance.export-csv', $activity));

        $response->assertStatus(200);
        $this->assertStringContainsString('.csv', $response->headers->get('content-disposition', ''));
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type', ''));
    }
}

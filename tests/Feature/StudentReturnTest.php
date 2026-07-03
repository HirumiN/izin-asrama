<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Permit;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentReturnTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_student_can_report_return_on_time()
    {
        $studentUser = User::factory()->create(['name' => 'Budi', 'role' => 'mahasiswa']);
        $student = $studentUser->student()->create(['nim' => 'NIM001', 'dorm_room' => 'A-101']);

        $permit = Permit::create([
            'student_id' => $student->id,
            'type' => 'pesiar',
            'destination' => 'Toko Buku',
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now()->addHours(2),
            'status' => 'approved',
        ]);

        $base64Photo = 'data:image/jpeg;base64,' . base64_encode('fake image content');

        $response = $this->actingAs($studentUser)->post(route('student.permits.return', $permit), [
            'return_photo' => $base64Photo,
            'return_location' => 'Lat: -8.096776, Lng: 112.179115',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('success');

        $permit->refresh();
        $this->assertEquals('returned_on_time', $permit->status);
        $this->assertNotNull($permit->return_photo);
        $this->assertEquals('Lat: -8.096776, Lng: 112.179115', $permit->return_location);
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($permit->return_photo);
    }

    public function test_student_can_report_return_late()
    {
        $studentUser = User::factory()->create(['name' => 'Budi', 'role' => 'mahasiswa']);
        $student = $studentUser->student()->create(['nim' => 'NIM001', 'dorm_room' => 'A-101']);

        $endTime = Carbon::now()->subMinutes(45); // Curfew was 45 mins ago

        $permit = Permit::create([
            'student_id' => $student->id,
            'type' => 'pesiar',
            'destination' => 'Toko Buku',
            'start_time' => Carbon::now()->subHours(4),
            'end_time' => $endTime,
            'status' => 'approved',
        ]);

        $base64Photo = 'data:image/jpeg;base64,' . base64_encode('fake image content');

        $response = $this->actingAs($studentUser)->post(route('student.permits.return', $permit), [
            'return_photo' => $base64Photo,
            'return_location' => 'Lat: -8.096776, Lng: 112.179115',
        ]);

        $response->assertRedirect(route('student.dashboard'));

        $permit->refresh();
        $this->assertEquals('returned_late', $permit->status);
        $this->assertTrue($permit->lateness_duration >= 45); // Lateness calculated correctly

        // Verifikasi mahasiswa otomatis ditangguhkan
        $student->refresh();
        $this->assertTrue($student->is_suspended);
        $this->assertNotNull($student->suspended_at);
    }

    public function test_on_time_return_does_not_suspend_student()
    {
        $studentUser = User::factory()->create(['name' => 'Citra', 'role' => 'mahasiswa']);
        $student = $studentUser->student()->create(['nim' => 'NIM003', 'dorm_room' => 'B-101']);

        $permit = Permit::create([
            'student_id' => $student->id,
            'type' => 'pesiar',
            'destination' => 'Stasiun',
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now()->addHours(2),
            'status' => 'approved',
        ]);

        $base64Photo = 'data:image/jpeg;base64,' . base64_encode('fake image content');

        $this->actingAs($studentUser)->post(route('student.permits.return', $permit), [
            'return_photo' => $base64Photo,
            'return_location' => 'Lat: -8.096776, Lng: 112.179115',
        ]);

        $student->refresh();
        $this->assertFalse($student->is_suspended);
        $this->assertNull($student->suspended_at);
    }

    public function test_suspended_student_cannot_submit_new_permit()
    {
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $student = $studentUser->student()->create([
            'nim' => 'NIM004',
            'dorm_room' => 'C-101',
            'is_suspended' => true,
            'suspended_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($studentUser)->post(route('student.permits.store'), [
            'type' => 'pesiar',
            'destination' => 'Mall',
            'start_time' => Carbon::now()->addHour(),
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('ditangguhkan', session('error'));
        $this->assertEquals(0, $student->permits()->count());
    }

    public function test_admin_can_lift_suspension()
    {
        $adminUser = User::factory()->create(['role' => 'pengelola']);

        $studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $student = $studentUser->student()->create([
            'nim' => 'NIM005',
            'dorm_room' => 'D-101',
            'is_suspended' => true,
            'suspended_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($adminUser)->post(route('admin.students.liftSuspension', $student));

        $response->assertSessionHas('success');

        $student->refresh();
        $this->assertFalse($student->is_suspended);
        $this->assertNull($student->suspended_at);
    }

    public function test_cannot_report_return_on_other_students_permit()
    {
        $studentUser1 = User::factory()->create(['role' => 'mahasiswa']);
        $student1 = $studentUser1->student()->create(['nim' => 'NIM001', 'dorm_room' => 'A-101']);

        $studentUser2 = User::factory()->create(['role' => 'mahasiswa']);
        $student2 = $studentUser2->student()->create(['nim' => 'NIM002', 'dorm_room' => 'A-102']);

        $permit = Permit::create([
            'student_id' => $student1->id,
            'type' => 'pesiar',
            'destination' => 'Toko Buku',
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now()->addHours(2),
            'status' => 'approved',
        ]);

        $base64Photo = 'data:image/jpeg;base64,' . base64_encode('fake image content');

        $response = $this->actingAs($studentUser2)->post(route('student.permits.return', $permit), [
            'return_photo' => $base64Photo,
            'return_location' => 'Lat: -8.096776, Lng: 112.179115',
        ]);

        $response->assertSessionHas('error', 'Akses ditolak.');
        $this->assertEquals('approved', $permit->refresh()->status);
    }

    public function test_manager_can_approve_permit_with_note()
    {
        $adminUser = User::factory()->create(['role' => 'pengelola']);
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $student = $studentUser->student()->create(['nim' => 'NIM006', 'dorm_room' => 'A-102']);

        $permit = Permit::create([
            'student_id' => $student->id,
            'type' => 'pesiar',
            'destination' => 'Pasar',
            'start_time' => Carbon::now()->addHour(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($adminUser)->post(route('admin.permits.approve', $permit), [
            'admin_note' => 'Hati-hati di jalan ya.',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $permit->refresh();
        $this->assertEquals('approved', $permit->status);
        $this->assertEquals('Hati-hati di jalan ya.', $permit->admin_note);
    }

    public function test_manager_can_reject_permit_with_note()
    {
        $adminUser = User::factory()->create(['role' => 'pengelola']);
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $student = $studentUser->student()->create(['nim' => 'NIM007', 'dorm_room' => 'A-103']);

        $permit = Permit::create([
            'student_id' => $student->id,
            'type' => 'pesiar',
            'destination' => 'Pasar',
            'start_time' => Carbon::now()->addHour(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($adminUser)->post(route('admin.permits.reject', $permit), [
            'admin_note' => 'Izin ditolak karena masih jam kuliah.',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $permit->refresh();
        $this->assertEquals('rejected', $permit->status);
        $this->assertEquals('Izin ditolak karena masih jam kuliah.', $permit->admin_note);
    }

    public function test_student_can_check_latest_permit_status()
    {
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);
        $student = $studentUser->student()->create(['nim' => 'NIM008', 'dorm_room' => 'A-104']);

        $permit = Permit::create([
            'student_id' => $student->id,
            'type' => 'pesiar',
            'destination' => 'Apotek',
            'start_time' => Carbon::now()->addHour(),
            'status' => 'approved',
            'admin_note' => 'Bawa struk pembelian obat.',
        ]);

        $response = $this->actingAs($studentUser)->get(route('student.permits.latest-status'));

        $response->assertStatus(200);
        $response->assertJson([
            'latest' => [
                'id' => $permit->id,
                'status' => 'approved',
                'destination' => 'Apotek',
                'admin_note' => 'Bawa struk pembelian obat.',
            ]
        ]);
    }

    public function test_student_cannot_report_return_outside_allowed_range()
    {
        $studentUser = User::factory()->create(['name' => 'Budi', 'role' => 'mahasiswa']);
        $student = $studentUser->student()->create(['nim' => 'NIM001', 'dorm_room' => 'A-101']);

        $permit = Permit::create([
            'student_id' => $student->id,
            'type' => 'pesiar',
            'destination' => 'Toko Buku',
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now()->addHours(2),
            'status' => 'approved',
        ]);

        $base64Photo = 'data:image/jpeg;base64,' . base64_encode('fake image content');

        // Koordinat Jakarta (-6.200000, 106.816666) berada jauh di luar radius 200m dari PRODITA Blitar
        $response = $this->actingAs($studentUser)->post(route('student.permits.return', $permit), [
            'return_photo' => $base64Photo,
            'return_location' => 'Lat: -6.200000, Lng: 106.816666',
        ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('di luar area asrama PRODITA', session('error'));

        $permit->refresh();
        $this->assertEquals('approved', $permit->status); // Status tetap approved, tidak berubah
    }
}

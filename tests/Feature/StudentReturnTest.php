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
            'return_location' => 'Lat: -6.200000, Lng: 106.816666',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $response->assertSessionHas('success');

        $permit->refresh();
        $this->assertEquals('returned_on_time', $permit->status);
        $this->assertNotNull($permit->return_photo);
        $this->assertEquals('Lat: -6.200000, Lng: 106.816666', $permit->return_location);
        $this->assertEquals(0, $permit->lateness_duration);

        Storage::disk('public')->assertExists($permit->return_photo);
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
            'return_location' => 'Lat: -6.200000, Lng: 106.816666',
        ]);

        $response->assertRedirect(route('student.dashboard'));

        $permit->refresh();
        $this->assertEquals('returned_late', $permit->status);
        $this->assertTrue($permit->lateness_duration >= 45); // Lateness calculated correctly
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
            'return_location' => 'Lat: -6.200000, Lng: 106.816666',
        ]);

        $response->assertSessionHas('error', 'Akses ditolak.');
        $this->assertEquals('approved', $permit->refresh()->status);
    }
}

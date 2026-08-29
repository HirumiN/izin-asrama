<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Permit;
use App\Models\PrayerAttendance;
use App\Models\Activity;
use App\Models\ActivityAttendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_perform_bulk_actions()
    {
        $studentUser = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($studentUser)->post(route('admin.students.bulk-delete'), [
            'student_ids' => [1, 2],
        ]);
        $response->assertStatus(403);

        $response2 = $this->actingAs($studentUser)->post(route('admin.permits.bulk'), [
            'action' => 'delete',
            'permit_ids' => [1, 2],
        ]);
        $response2->assertStatus(403);
    }

    public function test_bulk_delete_students_deletes_users_and_cascades_history()
    {
        $pengelolaUser = User::factory()->create(['role' => 'pengelola']);

        $user1 = User::factory()->create(['name' => 'Mahasiswa One', 'role' => 'mahasiswa']);
        $student1 = $user1->student()->create(['nim' => 'NIM101', 'dorm_room' => 'A-101']);

        $user2 = User::factory()->create(['name' => 'Mahasiswa Two', 'role' => 'mahasiswa']);
        $student2 = $user2->student()->create(['nim' => 'NIM102', 'dorm_room' => 'A-102']);

        $user3 = User::factory()->create(['name' => 'Mahasiswa Three', 'role' => 'mahasiswa']);
        $student3 = $user3->student()->create(['nim' => 'NIM103', 'dorm_room' => 'A-103']);

        $permit1 = $student1->permits()->create([
            'type' => 'pesiar',
            'destination' => 'Kota',
            'reason' => 'Belanja',
            'start_time' => now(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pengelolaUser)->post(route('admin.students.bulk-delete'), [
            'student_ids' => [$student1->id, $student2->id],
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseMissing('students', ['id' => $student1->id]);
        $this->assertDatabaseMissing('users', ['id' => $user2->id]);
        $this->assertDatabaseMissing('students', ['id' => $student2->id]);
        $this->assertDatabaseMissing('permits', ['id' => $permit1->id]);

        // Student 3 must still exist
        $this->assertDatabaseHas('students', ['id' => $student3->id]);
    }

    public function test_bulk_delete_permits_deletes_selected_permits()
    {
        $pengelolaUser = User::factory()->create(['role' => 'pengelola']);

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $student = $user->student()->create(['nim' => 'NIM201', 'dorm_room' => 'B-201']);

        $permit1 = $student->permits()->create([
            'type' => 'pesiar',
            'destination' => 'Toko',
            'start_time' => now(),
            'status' => 'pending',
        ]);

        $permit2 = $student->permits()->create([
            'type' => 'bermalam_biasa',
            'destination' => 'Rumah',
            'start_time' => now(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($pengelolaUser)->post(route('admin.permits.bulk'), [
            'action' => 'delete',
            'permit_ids' => [$permit1->id, $permit2->id],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('permits', ['id' => $permit1->id]);
        $this->assertDatabaseMissing('permits', ['id' => $permit2->id]);
    }

    public function test_bulk_delete_prayer_attendances_deletes_selected_records()
    {
        $pengelolaUser = User::factory()->create(['role' => 'pengelola']);

        $user1 = User::factory()->create(['role' => 'mahasiswa']);
        $student1 = $user1->student()->create(['nim' => 'NIM301', 'dorm_room' => 'C-301']);

        $user2 = User::factory()->create(['role' => 'mahasiswa']);
        $student2 = $user2->student()->create(['nim' => 'NIM302', 'dorm_room' => 'C-302']);

        $today = today()->toDateString();

        $att1 = PrayerAttendance::create([
            'student_id' => $student1->id,
            'date' => $today,
            'prayer_time' => 'subuh',
            'status' => 'berjamaah',
        ]);

        $att2 = PrayerAttendance::create([
            'student_id' => $student2->id,
            'date' => $today,
            'prayer_time' => 'subuh',
            'status' => 'munfarid',
        ]);

        $response = $this->actingAs($pengelolaUser)->post(route('admin.sholat.bulk-delete'), [
            'student_ids' => [$student1->id],
            'date' => $today,
        ]);

        $response->assertRedirect(route('admin.sholat.index', ['date' => $today]));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('prayer_attendances', ['id' => $att1->id]);
        $this->assertDatabaseHas('prayer_attendances', ['id' => $att2->id]);
    }

    public function test_bulk_delete_activities_deletes_activities_and_their_attendances()
    {
        $pengelolaUser = User::factory()->create(['role' => 'pengelola']);

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $student = $user->student()->create(['nim' => 'NIM401', 'dorm_room' => 'D-401']);

        $activity1 = Activity::create([
            'name' => 'Gotong Royong',
            'date' => today(),
            'start_time' => '07:00:00',
            'end_time' => '09:00:00',
        ]);

        $activity2 = Activity::create([
            'name' => 'Kajian Asrama',
            'date' => today(),
            'start_time' => '19:30:00',
            'end_time' => '21:00:00',
        ]);

        $att1 = ActivityAttendance::create([
            'activity_id' => $activity1->id,
            'student_id' => $student->id,
            'status' => 'hadir',
        ]);

        $response = $this->actingAs($pengelolaUser)->post(route('admin.activities.bulk-delete'), [
            'activity_ids' => [$activity1->id],
        ]);

        $response->assertRedirect(route('admin.activities.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('activities', ['id' => $activity1->id]);
        $this->assertDatabaseMissing('activity_attendances', ['id' => $att1->id]);
        $this->assertDatabaseHas('activities', ['id' => $activity2->id]);
    }
}

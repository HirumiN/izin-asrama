<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tamu (Guest) tidak boleh mengakses halaman ganti password mahasiswa.
     */
    public function test_guest_cannot_access_password_page()
    {
        $response = $this->get(route('student.password.edit'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Pengelola (Admin) tidak boleh mengakses halaman ganti password mahasiswa.
     */
    public function test_admin_cannot_access_student_password_page()
    {
        $admin = User::factory()->create([
            'role' => 'pengelola',
        ]);

        $response = $this->actingAs($admin)->get(route('student.password.edit'));
        $response->assertStatus(403);
    }

    /**
     * Mahasiswa dapat mengakses halaman ganti password.
     */
    public function test_student_can_access_password_page()
    {
        $student = User::factory()->create([
            'role' => 'mahasiswa',
        ]);

        // Mock mahasiswa student profile
        $student->student()->create([
            'nim' => 'P12345',
            'dorm_room' => 'ASTRI A1',
        ]);

        $response = $this->actingAs($student)->get(route('student.password.edit'));
        $response->assertStatus(200);
        $response->assertSee('Ganti Kata Sandi');
    }

    /**
     * Mahasiswa berhasil mengubah password jika password lama benar dan valid.
     */
    public function test_student_can_update_password()
    {
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'password' => Hash::make('password_lama'),
        ]);

        $student->student()->create([
            'nim' => 'P12345',
            'dorm_room' => 'ASTRI A1',
        ]);

        $response = $this->actingAs($student)->post(route('student.password.update'), [
            'current_password' => 'password_lama',
            'password' => 'password_baru',
            'password_confirmation' => 'password_baru',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $student->refresh();
        $this->assertTrue(Hash::check('password_baru', $student->password));
    }

    /**
     * Mahasiswa gagal mengubah password jika password lama salah.
     */
    public function test_student_cannot_update_password_with_incorrect_current_password()
    {
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'password' => Hash::make('password_lama'),
        ]);

        $student->student()->create([
            'nim' => 'P12345',
            'dorm_room' => 'ASTRI A1',
        ]);

        $response = $this->actingAs($student)->post(route('student.password.update'), [
            'current_password' => 'password_salah',
            'password' => 'password_baru',
            'password_confirmation' => 'password_baru',
        ]);

        $response->assertSessionHasErrors('current_password');

        $student->refresh();
        $this->assertTrue(Hash::check('password_lama', $student->password));
    }

    /**
     * Validasi minimal karakter kata sandi baru dan konfirmasinya harus berjalan.
     */
    public function test_student_password_validation_rules()
    {
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'password' => Hash::make('password_lama'),
        ]);

        $student->student()->create([
            'nim' => 'P12345',
            'dorm_room' => 'ASTRI A1',
        ]);

        // Kasus 1: Password baru tidak cocok dengan konfirmasi
        $response1 = $this->actingAs($student)->post(route('student.password.update'), [
            'current_password' => 'password_lama',
            'password' => 'passwordbaru123',
            'password_confirmation' => 'differentpassword',
        ]);
        $response1->assertSessionHasErrors('password');

        // Kasus 2: Password terlalu pendek (kurang dari 6 karakter)
        $response2 = $this->actingAs($student)->post(route('student.password.update'), [
            'current_password' => 'password_lama',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);
        $response2->assertSessionHasErrors('password');
    }
}

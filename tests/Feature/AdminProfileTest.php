<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tamu (Guest) tidak boleh mengakses halaman profil admin.
     */
    public function test_guest_cannot_access_profile_page()
    {
        $response = $this->get(route('admin.profile.edit'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Mahasiswa tidak boleh mengakses halaman profil admin.
     */
    public function test_student_cannot_access_admin_profile_page()
    {
        $student = User::factory()->create([
            'role' => 'mahasiswa',
        ]);

        $response = $this->actingAs($student)->get(route('admin.profile.edit'));
        $response->assertStatus(403);
    }

    /**
     * Admin/Pengelola dapat mengakses halaman profil.
     */
    public function test_admin_can_access_profile_page()
    {
        $admin = User::factory()->create([
            'role' => 'pengelola',
            'name' => 'Admin Utama',
            'email' => 'admin@asrama.com',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.profile.edit'));
        
        $response->assertStatus(200);
        $response->assertSee('Admin Utama');
        $response->assertSee('admin@asrama.com');
    }

    /**
     * Admin dapat mengubah nama dan email mereka.
     */
    public function test_admin_can_update_profile_info()
    {
        $admin = User::factory()->create([
            'role' => 'pengelola',
            'name' => 'Nama Lama',
            'email' => 'lama@asrama.com',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => 'Nama Baru',
            'email' => 'baru@asrama.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Nama Baru',
            'email' => 'baru@asrama.com',
        ]);
    }

    /**
     * Admin dapat mengubah password mereka.
     */
    public function test_admin_can_update_password()
    {
        $admin = User::factory()->create([
            'role' => 'pengelola',
            'password' => Hash::make('password_lama'),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => 'password_baru',
            'password_confirmation' => 'password_baru',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue(Hash::check('password_baru', $admin->password));
    }

    /**
     * Validasi keunikan email harus berjalan dengan benar.
     */
    public function test_admin_cannot_use_existing_email()
    {
        User::factory()->create([
            'email' => 'user_lain@asrama.com',
        ]);

        $admin = User::factory()->create([
            'role' => 'pengelola',
            'email' => 'admin@asrama.com',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => $admin->name,
            'email' => 'user_lain@asrama.com',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'email' => 'admin@asrama.com',
        ]);
    }

    /**
     * Admin bisa menyimpan profil tanpa mengganti password.
     */
    public function test_admin_can_update_without_changing_password()
    {
        $admin = User::factory()->create([
            'role' => 'pengelola',
            'password' => Hash::make('password_rahasia'),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => 'Admin Baru',
            'email' => $admin->email,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue(Hash::check('password_rahasia', $admin->password));
    }

    /**
     * Validasi password minimal dan konfirmasi harus berjalan.
     */
    public function test_admin_password_validation_rules()
    {
        $admin = User::factory()->create([
            'role' => 'pengelola',
        ]);

        // Password mismatch
        $response1 = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);
        $response1->assertSessionHasErrors('password');

        // Password too short
        $response2 = $this->actingAs($admin)->post(route('admin.profile.update'), [
            'name' => $admin->name,
            'email' => $admin->email,
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);
        $response2->assertSessionHasErrors('password');
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_email()
    {
        $user = User::factory()->create([
            'email' => 'admin@asrama.com',
            'password' => bcrypt('password'),
            'role' => 'pengelola',
        ]);

        $response = $this->post(route('login'), [
            'login' => 'admin@asrama.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_student_can_login_with_alphanumeric_nim()
    {
        $user = User::factory()->create([
            'email' => 'mahasiswa@asrama.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);

        $student = $user->student()->create([
            'nim' => 'NIM123ABC', // Alphanumeric NIM
            'dorm_room' => 'A-101',
        ]);

        $response = $this->post(route('login'), [
            'login' => 'NIM123ABC',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('student.dashboard'));
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->post(route('login'), [
            'login' => 'invalid@asrama.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('login');
    }
}

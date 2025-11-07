<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_a_new_user(): void
    {
        $data = [
            'surname' => 'Іваненко',
            'name' => 'Олег',
            'patronymic' => 'Миколайович',
            'email' => 'oleg@example.com',
            'phone' => '+380931112233',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
        ];

        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'oleg@example.com',
            'role' => 'student',
        ]);
    }

    public function test_it_logs_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'login@test.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@test.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['user', 'token', 'message']);
    }

    public function test_it_rejects_login_with_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'fail@test.com',
            'password' => bcrypt('realpass'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'fail@test.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Невірні дані для входу']);
    }

    public function test_it_returns_user_data_when_authenticated(): void
    {
        $user = User::factory()->create(['email' => 'auth@test.com']);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');
        $response->assertStatus(200)
            ->assertJsonFragment(['email' => 'auth@test.com']);
    }

    public function test_it_logs_out_and_invalidates_session(): void
    {
        $user = User::factory()->create(['email' => 'logout@test.com']);
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');
        $response->assertStatus(200)
            ->assertJson(['message' => 'Вихід виконано']);
    }
}

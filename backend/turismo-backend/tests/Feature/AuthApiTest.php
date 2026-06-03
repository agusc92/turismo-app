<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\InfoUsuario;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a new user can register.
     */
    public function test_user_can_register(): void
    {
        $userData = [
            'name' => 'Register User',
            'email' => 'register@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123', // Laravel validation often requires this
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email', 'rol'],
                     'token'
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'register@example.com', 'name' => 'Register User', 'rol' => 'turista']);
        $this->assertDatabaseHas('info_usuarios', ['user_id' => $response->json('user.id')]);
    }

    /**
     * Test that a user can log in.
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => 'login@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => ['id', 'name', 'email', 'rol'],
                     'token'
                 ]);
    }

    /**
     * Test that a logged in user can log out.
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Sesión cerrada correctamente']);


        $this->assertCount(0, $user->tokens);
    }

    /**
     * Test that registration fails with invalid data.
     */
    public function test_register_validation_fails(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '', // Missing name
            'email' => 'invalid-email', // Invalid email
            'password' => 'short', // Too short password
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /**
     * Test that login fails with incorrect credentials.
     */
    public function test_login_fails_with_incorrect_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'wrong@example.com',
            'password' => bcrypt('correct_password'),
        ]);

        $loginData = [
            'email' => 'wrong@example.com',
            'password' => 'incorrect_password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Credenciales incorrectas']);
    }

    /**
     * Test that logout fails for unauthenticated user.
     */
    public function test_logout_fails_for_unauthenticated_user(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401); // Unauthorized
    }
}

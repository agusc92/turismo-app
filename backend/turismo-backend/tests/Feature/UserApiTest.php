<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\InfoUsuario;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of users can be retrieved.
     */
    public function test_can_retrieve_list_of_users(): void
    {
        User::factory()->count(3)->create()->each(function ($user) {
            InfoUsuario::factory()->create(['user_id' => $user->id]);
        });

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'email', 'rol', 'created_at', 'updated_at', 'info_usuario']
                 ]);
    }

    /**
     * Test that a single user can be retrieved by ID.
     */
    public function test_can_retrieve_single_user(): void
    {
        $user = User::factory()->create();
        InfoUsuario::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/users/' . $user->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $user->id,
                     'name' => $user->name,
                 ])
                 ->assertJsonStructure([
                     'id', 'name', 'email', 'rol', 'created_at', 'updated_at', 'info_usuario'
                 ]);
    }

    /**
     * Test that a user can be updated.
     */
    public function test_can_update_user(): void
    {
        $user = User::factory()->create();

        $updatedData = [
            'name' => 'Updated User Name',
            'rol' => 'admin',
        ];

        $response = $this->putJson('/api/users/' . $user->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'name' => 'Updated User Name',
                     'rol' => 'admin',
                 ]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated User Name', 'rol' => 'admin']);
    }

    /**
     * Test that a user can be deleted.
     */
    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();
        InfoUsuario::factory()->create(['user_id' => $user->id]); // InfoUsuario se elimina en cascada

        $response = $this->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Usuario eliminado correctamente']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('info_usuarios', ['user_id' => $user->id]);
    }

    /**
     * Test that creating a user via /api/users returns 400.
     */
    public function test_cannot_create_user_via_users_endpoint(): void
    {
        $userData = [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password123',
            'rol' => 'turista',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Use /register instead']);
    }

    /**
     * Test that validation works for updating a user.
     */
    public function test_update_user_validation_fails_with_invalid_email(): void
    {
        $user = User::factory()->create();

        $response = $this->putJson('/api/users/' . $user->id, ['email' => 'invalid-email']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}

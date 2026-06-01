<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\InfoUsuario;
use App\Models\User;
use App\Models\Tipo;

class InfoUsuarioApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of info_usuarios can be retrieved.
     */
    public function test_can_retrieve_list_of_info_usuarios(): void
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/info-usuarios');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'ciudad', 'edad', 'estadia', 'integrantes', 'user_id', 'created_at', 'updated_at', 'intereses']
                 ]);
    }

    /**
     * Test that a single info_usuario can be retrieved by ID.
     */
    public function test_can_retrieve_single_info_usuario(): void
    {
        $user = User::factory()->create();
        $infoUsuario = InfoUsuario::where('user_id', $user->id)->first();
        $tipo = Tipo::factory()->create();
        $infoUsuario->intereses()->attach($tipo->id);

        $response = $this->getJson('/api/info-usuarios/' . $infoUsuario->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $infoUsuario->id,
                     'ciudad' => $infoUsuario->ciudad,
                     'user_id' => $user->id,
                 ])
                 ->assertJsonFragment(['tipo' => $tipo->tipo]);
    }

    /**
     * Test that an info_usuario can be updated.
     */
    public function test_can_update_info_usuario(): void
    {
        $user = User::factory()->create();
        $infoUsuario = InfoUsuario::where('user_id', $user->id)->first();
        $tipo1 = Tipo::factory()->create();
        $tipo2 = Tipo::factory()->create();
        $infoUsuario->intereses()->attach($tipo1->id);

        $updatedData = [
            'ciudad' => 'Ciudad Actualizada',
            'edad' => 35,
            'intereses' => [$tipo2->id], // Sincronizar a un nuevo interés
        ];

        $response = $this->putJson('/api/info-usuarios/' . $infoUsuario->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'ciudad' => 'Ciudad Actualizada',
                     'edad' => 35,
                 ])
                 ->assertJsonFragment(['tipo' => $tipo2->tipo]);

        $this->assertDatabaseHas('info_usuarios', ['id' => $infoUsuario->id, 'ciudad' => 'Ciudad Actualizada', 'edad' => 35]);
        $this->assertDatabaseHas('usuario_intereses', ['info_usuario_id' => $infoUsuario->id, 'tipo_id' => $tipo2->id]);
        $this->assertDatabaseMissing('usuario_intereses', ['info_usuario_id' => $infoUsuario->id, 'tipo_id' => $tipo1->id]);
    }

    /**
     * Test that creating an info_usuario via /api/info-usuarios returns 400.
     */
    public function test_cannot_create_info_usuario_via_endpoint(): void
    {
        $infoUsuarioData = [
            'ciudad' => 'Ciudad Nueva',
            'edad' => 25,
            'user_id' => 1,
        ];

        $response = $this->postJson('/api/info-usuarios', $infoUsuarioData);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'InfoUsuario is created automatically on register']);
    }

    /**
     * Test that deleting an info_usuario via /api/info-usuarios returns 400.
     */
    public function test_cannot_delete_info_usuario_via_endpoint(): void
    {
        $user = User::factory()->create();
        $infoUsuario = InfoUsuario::where('user_id', $user->id)->first();

        $response = $this->deleteJson('/api/info-usuarios/' . $infoUsuario->id);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'InfoUsuario is deleted automatically with user']);
    }

    /**
     * Test that validation works for updating an info_usuario.
     */
    public function test_update_info_usuario_validation_fails_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $infoUsuario = InfoUsuario::where('user_id', $user->id)->first();

        $response = $this->putJson('/api/info-usuarios/' . $infoUsuario->id, ['edad' => 'not-a-number']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['edad']);
    }

    /**
     * Test that validation fails if interests are invalid.
     */
    public function test_update_info_usuario_validation_fails_with_invalid_interests(): void
    {
        $user = User::factory()->create();
        $infoUsuario = InfoUsuario::where('user_id', $user->id)->first();

        $response = $this->putJson('/api/info-usuarios/' . $infoUsuario->id, ['intereses' => [999]]); // ID de tipo que no existe

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['intereses.0']);
    }
}

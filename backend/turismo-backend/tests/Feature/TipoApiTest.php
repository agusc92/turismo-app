<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tipo;

class TipoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of tipos can be retrieved.
     */
    public function test_can_retrieve_list_of_tipos(): void
    {
        Tipo::factory()->count(3)->create();

        $response = $this->getJson('/api/tipos');

        $response->assertStatus(200)
                 ->assertJsonCount(3);

        $response->assertJson(Tipo::all()->pluck('tipo')->toArray());
    }

    /**
     * Test that a single tipo can be retrieved by ID.
     */
    public function test_can_retrieve_single_tipo(): void
    {
        $tipo = Tipo::factory()->create();

        $response = $this->getJson('/api/tipos/' . $tipo->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $tipo->id,
                     'tipo' => $tipo->tipo,
                 ]);
    }

    /**
     * Test that a tipo can be created.
     */
    public function test_can_create_tipo(): void
    {
        $tipoData = [
            'tipo' => 'Nuevo Tipo',
        ];

        $response = $this->postJson('/api/tipos', $tipoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'tipo' => 'Nuevo Tipo',
                 ]);

        $this->assertDatabaseHas('tipos', ['tipo' => 'Nuevo Tipo']);
    }

    /**
     * Test that a tipo can be updated.
     */
    public function test_can_update_tipo(): void
    {
        $tipo = Tipo::factory()->create();

        $updatedData = [
            'tipo' => 'Tipo Actualizado',
        ];

        $response = $this->putJson('/api/tipos/' . $tipo->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'tipo' => 'Tipo Actualizado',
                 ]);

        $this->assertDatabaseHas('tipos', ['id' => $tipo->id, 'tipo' => 'Tipo Actualizado']);
    }

    /**
     * Test that a tipo can be deleted.
     */
    public function test_can_delete_tipo(): void
    {
        $tipo = Tipo::factory()->create();

        $response = $this->deleteJson('/api/tipos/' . $tipo->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Tipo eliminado correctamente']);

        $this->assertDatabaseMissing('tipos', ['id' => $tipo->id]);
    }

    /**
     * Test that validation works for creating a tipo.
     */
    public function test_create_tipo_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/tipos', ['invalid_field' => 'value']); // Falta 'tipo'

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }

    /**
     * Test that validation works for updating a tipo.
     */
    public function test_update_tipo_validation_fails_with_invalid_data(): void
    {
        $tipo = Tipo::factory()->create();

        $response = $this->putJson('/api/tipos/' . $tipo->id, ['tipo' => '']); // Tipo vacío

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }
}

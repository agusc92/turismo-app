<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TipoGastronomico;

class TipoGastronomicoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a single tipo_gastronomico can be retrieved by ID.
     */
    public function test_can_retrieve_single_tipo_gastronomico(): void
    {
        $tipoGastronomico = TipoGastronomico::factory()->create();

        $response = $this->getJson('/api/tipo-gastronomicos/' . $tipoGastronomico->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $tipoGastronomico->id,
                     'tipo' => $tipoGastronomico->tipo,
                 ]);
    }

    /**
     * Test that a tipo_gastronomico can be created.
     */
    public function test_can_create_tipo_gastronomico(): void
    {
        $tipoGastronomicoData = [
            'tipo' => 'Nuevo Tipo Gastronomico',
        ];

        $response = $this->postJson('/api/tipo-gastronomicos', $tipoGastronomicoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'tipo' => 'Nuevo Tipo Gastronomico',
                 ]);

        $this->assertDatabaseHas('tipo_gastronomicos', ['tipo' => 'Nuevo Tipo Gastronomico']);
    }

    /**
     * Test that a tipo_gastronomico can be updated.
     */
    public function test_can_update_tipo_gastronomico(): void
    {
        $tipoGastronomico = TipoGastronomico::factory()->create();

        $updatedData = [
            'tipo' => 'Tipo Gastronomico Actualizado',
        ];

        $response = $this->putJson('/api/tipo-gastronomicos/' . $tipoGastronomico->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'tipo' => 'Tipo Gastronomico Actualizado',
                 ]);

        $this->assertDatabaseHas('tipo_gastronomicos', ['id' => $tipoGastronomico->id, 'tipo' => 'Tipo Gastronomico Actualizado']);
    }

    /**
     * Test that a tipo_gastronomico can be deleted.
     */
    public function test_can_delete_tipo_gastronomico(): void
    {
        $tipoGastronomico = TipoGastronomico::factory()->create();

        $response = $this->deleteJson('/api/tipo-gastronomicos/' . $tipoGastronomico->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Tipo gastronomico eliminado correctamente']);

        $this->assertDatabaseMissing('tipo_gastronomicos', ['id' => $tipoGastronomico->id]);
    }

    /**
     * Test that validation works for creating a tipo_gastronomico.
     */
    public function test_create_tipo_gastronomico_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/tipo-gastronomicos', ['invalid_field' => 'value']); // Falta 'tipo'

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }
}

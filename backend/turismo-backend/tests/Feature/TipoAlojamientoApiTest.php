<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TipoAlojamiento;

class TipoAlojamientoApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Forzar la limpieza de la caché de rutas y de la aplicación
        $this->artisan('route:clear');
        $this->artisan('cache:clear');
    }

    /**
     * Test that a list of tipo_alojamientos can be retrieved.
     */
    public function test_can_retrieve_list_of_tipo_alojamientos(): void
    {
        TipoAlojamiento::factory()->count(3)->create();

        $response = $this->getJson('/api/tipos-alojamientos');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'tipo', 'created_at', 'updated_at']
                 ]);
    }

    /**
     * Test that a single tipo_alojamiento can be retrieved by ID.
     */
    public function test_can_retrieve_single_tipo_alojamiento(): void
    {
        $tipoAlojamiento = TipoAlojamiento::factory()->create();

        $response = $this->getJson('/api/tipos-alojamientos/' . $tipoAlojamiento->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $tipoAlojamiento->id,
                     'tipo' => $tipoAlojamiento->tipo,
                 ]);
    }

    /**
     * Test that a tipo_alojamiento can be created.
     */
    public function test_can_create_tipo_alojamiento(): void
    {
        $tipoAlojamientoData = [
            'tipo' => 'Nuevo Tipo Alojamiento',
        ];

        $response = $this->postJson('/api/tipos-alojamientos', $tipoAlojamientoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'tipo' => 'Nuevo Tipo Alojamiento',
                 ]);

        $this->assertDatabaseHas('tipo_alojamientos', ['tipo' => 'Nuevo Tipo Alojamiento']);
    }

    /**
     * Test that a tipo_alojamiento can be updated.
     */
    public function test_can_update_tipo_alojamiento(): void
    {
        $tipoAlojamiento = TipoAlojamiento::factory()->create();

        $updatedData = [
            'tipo' => 'Tipo Alojamiento Actualizado',
        ];

        $response = $this->putJson('/api/tipos-alojamientos/' . $tipoAlojamiento->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'tipo' => 'Tipo Alojamiento Actualizado',
                 ]);

        $this->assertDatabaseHas('tipo_alojamientos', ['id' => $tipoAlojamiento->id, 'tipo' => 'Tipo Alojamiento Actualizado']);
    }

    /**
     * Test that a tipo_alojamiento can be deleted.
     */
    public function test_can_delete_tipo_alojamiento(): void
    {
        $tipoAlojamiento = TipoAlojamiento::factory()->create();

        $response = $this->deleteJson('/api/tipos-alojamientos/' . $tipoAlojamiento->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Tipo de alojamiento eliminado correctamente']);

        $this->assertDatabaseMissing('tipo_alojamientos', ['id' => $tipoAlojamiento->id]);
    }

    /**
     * Test that validation works for creating a tipo_alojamiento.
     */
    public function test_create_tipo_alojamiento_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/tipos-alojamientos', ['invalid_field' => 'value']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }

    /**
     * Test that validation works for updating a tipo_alojamiento.
     */
    public function test_update_tipo_alojamiento_validation_fails_with_invalid_data(): void
    {
        $tipoAlojamiento = TipoAlojamiento::factory()->create();

        $response = $this->putJson('/api/tipos-alojamientos/' . $tipoAlojamiento->id, ['tipo' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }

    /**
     * Test that 'tipo' field must be unique when creating.
     */
    public function test_create_tipo_alojamiento_validation_fails_if_tipo_not_unique(): void
    {
        $existingTipo = TipoAlojamiento::factory()->create(['tipo' => 'Hotel']);

        $tipoAlojamientoData = [
            'tipo' => 'Hotel',
        ];

        $response = $this->postJson('/api/tipos-alojamientos', $tipoAlojamientoData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }

    /**
     * Test that 'tipo' field must be unique when updating, ignoring itself.
     */
    public function test_update_tipo_alojamiento_validation_fails_if_tipo_not_unique_for_other_record(): void
    {
        $tipoAlojamiento1 = TipoAlojamiento::factory()->create(['tipo' => 'Hotel']);
        $tipoAlojamiento2 = TipoAlojamiento::factory()->create(['tipo' => 'Cabaña']);

        $updatedData = [
            'tipo' => 'Hotel', // Intentar cambiar el tipo de tipoAlojamiento2 a uno existente
        ];

        $response = $this->putJson('/api/tipos-alojamientos/' . $tipoAlojamiento2->id, $updatedData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }

    /**
     * Test that 'tipo' field can be the same when updating the same record.
     */
    public function test_update_tipo_alojamiento_validation_succeeds_if_tipo_is_same_for_same_record(): void
    {
        $tipoAlojamiento = TipoAlojamiento::factory()->create(['tipo' => 'Hotel']);

        $updatedData = [
            'tipo' => 'Hotel', // Mismo tipo para el mismo registro
        ];

        $response = $this->putJson('/api/tipos-alojamientos/' . $tipoAlojamiento->id, $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tipo_alojamientos', ['id' => $tipoAlojamiento->id, 'tipo' => 'Hotel']);
    }
}

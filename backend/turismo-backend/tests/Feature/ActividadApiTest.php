<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Actividad;
use App\Models\Tipo;
class ActividadApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of actividades can be retrieved.
     */
    public function test_can_retrieve_list_of_actividades(): void
    {
        Tipo::factory()->count(2)->create();
        Actividad::factory()->count(3)->create();

        $response = $this->getJson('/api/actividades');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'descripcion', 'redes_sociales', 'web', 'mail', 'telefono', 'imagen', 'tipo_id', 'dias_y_horarios', 'created_at', 'updated_at', 'tipo']
                 ]);
    }

    /**
     * Test that a single actividad can be retrieved by ID.
     */
    public function test_can_retrieve_single_actividad(): void
    {
        $tipo = Tipo::factory()->create();
        $actividad = Actividad::factory()->create(['tipo_id' => $tipo->id]);

        $response = $this->getJson('/api/actividades/' . $actividad->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $actividad->id,
                     'nombre' => $actividad->nombre,
                     'tipo' => [
                         'id' => $tipo->id,
                         'tipo' => $tipo->tipo,
                     ]
                 ]);
    }

    /**
     * Test that an actividad can be created.
     */
    public function test_can_create_actividad(): void
    {
        $tipo = Tipo::factory()->create();

        $actividadData = [
            'nombre' => 'Nueva Actividad',
            'direccion' => 'Nueva Direccion Actividad',
            'descripcion' => 'Descripcion de la nueva actividad',
            'redes_sociales' => 'http://nuevo.com/redes',
            'web' => 'http://nuevo.com/web',
            'mail' => 'nuevo@actividad.com',
            'telefono' => '111222333',
            'imagen' => 'http://nuevo.com/imagen.jpg',
            'tipo_id' => $tipo->id,
            'dias_y_horarios' => 'L-V 09-18',
        ];

        $response = $this->postJson('/api/actividades', $actividadData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nueva Actividad',
                 ]);

        $this->assertDatabaseHas('actividades', ['nombre' => 'Nueva Actividad']);
    }

    /**
     * Test that an actividad can be updated.
     */
    public function test_can_update_actividad(): void
    {
        $tipo = Tipo::factory()->create();
        $actividad = Actividad::factory()->create(['tipo_id' => $tipo->id]);
        $nuevoTipo = Tipo::factory()->create();

        $updatedData = [
            'nombre' => 'Actividad Actualizada',
            'direccion' => 'Direccion Actualizada Actividad',
            'tipo_id' => $nuevoTipo->id,
        ];

        $response = $this->putJson('/api/actividades/' . $actividad->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Actividad Actualizada',
                 ]);

        $this->assertDatabaseHas('actividades', ['id' => $actividad->id, 'nombre' => 'Actividad Actualizada', 'tipo_id' => $nuevoTipo->id]);
    }

    /**
     * Test that an actividad can be deleted.
     */
    public function test_can_delete_actividad(): void
    {
        $tipo = Tipo::factory()->create();
        $actividad = Actividad::factory()->create(['tipo_id' => $tipo->id]);

        $response = $this->deleteJson('/api/actividades/' . $actividad->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Actividad eliminada correctamente']);

        $this->assertDatabaseMissing('actividades', ['id' => $actividad->id]);
    }

    /**
     * Test that validation works for creating an actividad.
     */
    public function test_create_actividad_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/actividades', ['descripcion' => 'Solo descripcion']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre', 'direccion', 'tipo_id']);
    }

    /**
     * Test that validation works for updating an actividad.
     */
    public function test_update_actividad_validation_fails_with_invalid_mail(): void
    {
        $tipo = Tipo::factory()->create();
        $actividad = Actividad::factory()->create(['tipo_id' => $tipo->id]);

        $response = $this->putJson('/api/actividades/' . $actividad->id, ['mail' => 'invalid-mail']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['mail']);
    }

    /**
     * Test that validation fails if tipo_id does not exist.
     */
    public function test_create_actividad_validation_fails_if_tipo_id_does_not_exist(): void
    {
        $actividadData = [
            'nombre' => 'Nueva Actividad',
            'direccion' => 'Nueva Direccion Actividad',
            'tipo_id' => 999, // ID que no existe
        ];

        $response = $this->postJson('/api/actividades', $actividadData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo_id']);
    }
}

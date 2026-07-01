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
        Tipo::factory()->count(2)->create(); // Asegurar que hay tipos para las actividades
        Actividad::factory()->count(2)->create(['habilitado' => true]);
        Actividad::factory()->create(['habilitado' => false]); // Una actividad deshabilitada

        $response = $this->getJson('/api/actividades');

        $response->assertStatus(200)
                 ->assertJsonCount(2) // Solo 2 habilitadas
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'descripcion', 'redes_sociales', 'web', 'mail', 'telefono', 'imagen', 'latitud', 'longitud', 'tipo_id', 'dias_y_horarios', 'habilitado', 'created_at', 'updated_at', 'tipo']
                 ]);
        $response->assertJsonMissing(['habilitado' => false]); // Asegura que no hay deshabilitadas
    }

    /**
     * Test that a single actividad can be retrieved by ID.
     */
    public function test_can_retrieve_single_actividad(): void
    {
        $tipo = Tipo::factory()->create();
        $actividad = Actividad::factory()->create(['tipo_id' => $tipo->id, 'habilitado' => true]);

        $response = $this->getJson('/api/actividades/' . $actividad->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $actividad->id,
                     'nombre' => $actividad->nombre,
                     'imagen' => $actividad->imagen,
                     'latitud' => $actividad->latitud,
                     'longitud' => $actividad->longitud,
                     'habilitado' => true,
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
            'latitud' => -38.555,
            'longitud' => -58.777,
            'tipo_id' => $tipo->id,
            'dias_y_horarios' => 'L-V 09-18',
            'habilitado' => true,
        ];

        $response = $this->postJson('/api/actividades', $actividadData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nueva Actividad',
                     'imagen' => 'http://nuevo.com/imagen.jpg',
                     'latitud' => -38.555,
                     'longitud' => -58.777,
                     'habilitado' => true,
                 ]);

        $this->assertDatabaseHas('actividades', [
            'nombre' => 'Nueva Actividad',
            'imagen' => 'http://nuevo.com/imagen.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ]);
    }

    /**
     * Test that an actividad can be updated.
     */
    public function test_can_update_actividad(): void
    {
        $tipo = Tipo::factory()->create();
        $actividad = Actividad::factory()->create(['tipo_id' => $tipo->id, 'habilitado' => true]);
        $nuevoTipo = Tipo::factory()->create();

        $updatedData = [
            'nombre' => 'Actividad Actualizada',
            'direccion' => 'Direccion Actualizada Actividad',
            'imagen' => 'http://actualizada.com/imagen.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'tipo_id' => $nuevoTipo->id,
            'habilitado' => false,
        ];

        $response = $this->putJson('/api/actividades/' . $actividad->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Actividad Actualizada',
                     'imagen' => 'http://actualizada.com/imagen.jpg',
                     'latitud' => -38.666,
                     'longitud' => -58.888,
                     'habilitado' => false,
                 ]);

        $this->assertDatabaseHas('actividades', [
            'id' => $actividad->id,
            'nombre' => 'Actividad Actualizada',
            'imagen' => 'http://actualizada.com/imagen.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'tipo_id' => $nuevoTipo->id,
            'habilitado' => false,
        ]);
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
                 ->assertJson(['message' => 'Actividad eliminado correctamente']);

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

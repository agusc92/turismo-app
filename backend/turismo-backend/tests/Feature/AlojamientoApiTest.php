<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Alojamiento;
use App\Models\TipoAlojamiento;

class AlojamientoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of alojamientos can be retrieved.
     */
    public function test_can_retrieve_list_of_alojamientos(): void
    {
        // Crear algunos tipos de alojamiento para asociar
        $tipoAlojamiento1 = TipoAlojamiento::factory()->create(['tipo' => 'Hotel']);
        $tipoAlojamiento2 = TipoAlojamiento::factory()->create(['tipo' => 'Cabaña']);

        // Crear alojamientos y asociar tipos
        $alojamiento1 = Alojamiento::factory()->create(['habilitado' => true]);
        $alojamiento1->tiposAlojamiento()->attach($tipoAlojamiento1->id);

        $alojamiento2 = Alojamiento::factory()->create(['habilitado' => true]);
        $alojamiento2->tiposAlojamiento()->attach([$tipoAlojamiento1->id, $tipoAlojamiento2->id]);

        Alojamiento::factory()->create(['habilitado' => false]); // Un alojamiento deshabilitado

        $response = $this->getJson('/api/alojamientos');

        $response->assertStatus(200)
                 ->assertJsonCount(2) // Solo 2 habilitados
                 ->assertJsonStructure([
                     '*' => [
                         'id', 'nombre', 'direccion', 'telefono', 'redesSociales', 'paginaWeb', 'mail', 'mascotas', 'periodoApertura', 'imagen', 'latitud', 'longitud', 'habilitado', 'created_at', 'updated_at',
                         'tipos_alojamiento' => [
                             '*' => ['id', 'tipo', 'created_at', 'updated_at', 'pivot']
                         ]
                     ]
                 ]);
        $response->assertJsonMissing(['habilitado' => false]); // Asegura que no hay deshabilitados
        $response->assertJsonFragment(['tipo' => $tipoAlojamiento1->tipo]);
    }

    /**
     * Test that a single alojamiento can be retrieved by ID.
     */
    public function test_can_retrieve_single_alojamiento(): void
    {
        $tipoAlojamiento = TipoAlojamiento::factory()->create(['tipo' => 'Hotel']);
        $alojamiento = Alojamiento::factory()->create(['habilitado' => true]);
        $alojamiento->tiposAlojamiento()->attach($tipoAlojamiento->id);

        $response = $this->getJson('/api/alojamientos/' . $alojamiento->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $alojamiento->id,
                     'nombre' => $alojamiento->nombre,
                     'imagen' => $alojamiento->imagen,
                     'latitud' => $alojamiento->latitud,
                     'longitud' => $alojamiento->longitud,
                     'habilitado' => true,
                     'tipos_alojamiento' => [
                         ['id' => $tipoAlojamiento->id, 'tipo' => $tipoAlojamiento->tipo]
                     ]
                 ]);
    }

    /**
     * Test that an alojamiento can be created.
     */
    public function test_can_create_alojamiento(): void
    {
        $tipoAlojamiento1 = TipoAlojamiento::factory()->create(['tipo' => 'Hotel']);
        $tipoAlojamiento2 = TipoAlojamiento::factory()->create(['tipo' => 'Cabaña']);

        $alojamientoData = [
            'nombre' => 'Nuevo Alojamiento',
            'direccion' => 'Nueva Direccion Alojamiento',
            'telefono' => '111222333',
            'redesSociales' => 'http://nuevo.com/redes',
            'paginaWeb' => 'http://nuevo.com/web',
            'mail' => 'nuevo@alojamiento.com',
            'mascotas' => true,
            'periodoApertura' => 'Todo el año',
            'tipos_alojamiento_ids' => [$tipoAlojamiento1->id, $tipoAlojamiento2->id],
            'imagen' => 'http://nuevo.com/imagen.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ];

        $response = $this->postJson('/api/alojamientos', $alojamientoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nuevo Alojamiento',
                     'imagen' => 'http://nuevo.com/imagen.jpg',
                     'latitud' => -38.555,
                     'longitud' => -58.777,
                     'habilitado' => true,
                 ])
                 ->assertJsonFragment(['tipo' => $tipoAlojamiento1->tipo])
                 ->assertJsonFragment(['tipo' => $tipoAlojamiento2->tipo]);

        $this->assertDatabaseHas('alojamientos', [
            'nombre' => 'Nuevo Alojamiento',
            'imagen' => 'http://nuevo.com/imagen.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ]);

        $alojamiento = Alojamiento::where('nombre', 'Nuevo Alojamiento')->first();
        $this->assertCount(2, $alojamiento->tiposAlojamiento);
        $this->assertTrue($alojamiento->tiposAlojamiento->contains($tipoAlojamiento1));
        $this->assertTrue($alojamiento->tiposAlojamiento->contains($tipoAlojamiento2));
    }

    /**
     * Test that an alojamiento can be updated.
     */
    public function test_can_update_alojamiento(): void
    {
        $tipoAlojamientoOriginal = TipoAlojamiento::factory()->create(['tipo' => 'Hotel']);
        $alojamiento = Alojamiento::factory()->create(['habilitado' => true]);
        $alojamiento->tiposAlojamiento()->attach($tipoAlojamientoOriginal->id);

        $tipoAlojamientoNuevo = TipoAlojamiento::factory()->create(['tipo' => 'Apart Hotel']);

        $updatedData = [
            'nombre' => 'Alojamiento Actualizado',
            'direccion' => 'Direccion Actualizada Alojamiento',
            'mascotas' => false,
            'tipos_alojamiento_ids' => [$tipoAlojamientoNuevo->id],
            'imagen' => 'http://actualizada.com/imagen.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'habilitado' => false,
        ];

        $response = $this->putJson('/api/alojamientos/' . $alojamiento->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Alojamiento Actualizado',
                     'imagen' => 'http://actualizada.com/imagen.jpg',
                     'latitud' => -38.666,
                     'longitud' => -58.888,
                     'habilitado' => false,
                 ])
                 ->assertJsonFragment(['tipo' => $tipoAlojamientoNuevo->tipo])
                 ->assertJsonMissing(['tipo' => $tipoAlojamientoOriginal->tipo]);

        $this->assertDatabaseHas('alojamientos', [
            'id' => $alojamiento->id,
            'nombre' => 'Alojamiento Actualizado',
            'imagen' => 'http://actualizada.com/imagen.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'habilitado' => false,
        ]);

        $alojamiento->refresh();
        $this->assertCount(1, $alojamiento->tiposAlojamiento);
        $this->assertTrue($alojamiento->tiposAlojamiento->contains($tipoAlojamientoNuevo));
        $this->assertFalse($alojamiento->tiposAlojamiento->contains($tipoAlojamientoOriginal));
    }

    /**
     * Test that an alojamiento can be deleted.
     */
    public function test_can_delete_alojamiento(): void
    {
        $alojamiento = Alojamiento::factory()->create();
        $tipoAlojamiento = TipoAlojamiento::factory()->create();
        $alojamiento->tiposAlojamiento()->attach($tipoAlojamiento->id);

        $response = $this->deleteJson('/api/alojamientos/' . $alojamiento->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Alojamiento eliminado correctamente']);

        $this->assertDatabaseMissing('alojamientos', ['id' => $alojamiento->id]);
        $this->assertDatabaseMissing('alojamiento_tipo_alojamiento', ['alojamiento_id' => $alojamiento->id]);
    }

    /**
     * Test that validation works for creating an alojamiento.
     */
    public function test_create_alojamiento_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/alojamientos', ['direccion' => 'Solo direccion']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre', 'tipos_alojamiento_ids']);
    }

    /**
     * Test that validation works for updating an alojamiento.
     */
    public function test_update_alojamiento_validation_fails_with_invalid_mail(): void
    {
        $alojamiento = Alojamiento::factory()->create();

        $response = $this->putJson('/api/alojamientos/' . $alojamiento->id, ['mail' => 'invalid-mail']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['mail']);
    }

    /**
     * Test that validation fails if tipos_alojamiento_ids contains non-existent types.
     */
    public function test_create_alojamiento_validation_fails_with_non_existent_tipos_alojamiento_ids(): void
    {
        $alojamientoData = [
            'nombre' => 'Nuevo Alojamiento',
            'direccion' => 'Nueva Direccion Alojamiento',
            'mascotas' => true,
            'tipos_alojamiento_ids' => [99999], // ID de tipo que no existe
        ];

        $response = $this->postJson('/api/alojamientos', $alojamientoData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipos_alojamiento_ids.0']);
    }

    /**
     * Test that validation fails if tipos_alojamiento_ids is not an array.
     */
    public function test_create_alojamiento_validation_fails_if_tipos_alojamiento_ids_is_not_array(): void
    {
        $alojamientoData = [
            'nombre' => 'Nuevo Alojamiento',
            'direccion' => 'Nueva Direccion Alojamiento',
            'mascotas' => true,
            'tipos_alojamiento_ids' => 'not_an_array',
        ];

        $response = $this->postJson('/api/alojamientos', $alojamientoData);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipos_alojamiento_ids']);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Alojamiento;

class AlojamientoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of alojamientos can be retrieved.
     */
    public function test_can_retrieve_list_of_alojamientos(): void
    {
        Alojamiento::factory()->count(3)->create();

        $response = $this->getJson('/api/alojamientos');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'telefono', 'redesSociales', 'paginaWeb', 'mail', 'mascotas', 'periodoApertura', 'tipo', 'imagen', 'created_at', 'updated_at']
                 ]);
    }

    /**
     * Test that a single alojamiento can be retrieved by ID.
     */
    public function test_can_retrieve_single_alojamiento(): void
    {
        $alojamiento = Alojamiento::factory()->create();

        $response = $this->getJson('/api/alojamientos/' . $alojamiento->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $alojamiento->id,
                     'nombre' => $alojamiento->nombre,
                     'imagen' => $alojamiento->imagen,
                 ]);
    }

    /**
     * Test that an alojamiento can be created.
     */
    public function test_can_create_alojamiento(): void
    {
        $alojamientoData = [
            'nombre' => 'Nuevo Alojamiento',
            'direccion' => 'Nueva Direccion Alojamiento',
            'telefono' => '111222333',
            'redesSociales' => 'http://nuevo.com/redes',
            'paginaWeb' => 'http://nuevo.com/web',
            'mail' => 'nuevo@alojamiento.com',
            'mascotas' => true,
            'periodoApertura' => 'Todo el año',
            'tipo' => 'Hotel',
            'imagen' => 'http://nuevo.com/imagen.jpg',
        ];

        $response = $this->postJson('/api/alojamientos', $alojamientoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nuevo Alojamiento',
                     'imagen' => 'http://nuevo.com/imagen.jpg',
                 ]);

        $this->assertDatabaseHas('alojamientos', ['nombre' => 'Nuevo Alojamiento', 'imagen' => 'http://nuevo.com/imagen.jpg']);
    }

    /**
     * Test that an alojamiento can be updated.
     */
    public function test_can_update_alojamiento(): void
    {
        $alojamiento = Alojamiento::factory()->create();

        $updatedData = [
            'nombre' => 'Alojamiento Actualizado',
            'direccion' => 'Direccion Actualizada Alojamiento',
            'mascotas' => false,
            'imagen' => 'http://actualizada.com/imagen.jpg',
        ];

        $response = $this->putJson('/api/alojamientos/' . $alojamiento->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Alojamiento Actualizado',
                     'imagen' => 'http://actualizada.com/imagen.jpg',
                 ]);

        $this->assertDatabaseHas('alojamientos', ['id' => $alojamiento->id, 'nombre' => 'Alojamiento Actualizado', 'imagen' => 'http://actualizada.com/imagen.jpg']);
    }

    /**
     * Test that an alojamiento can be deleted.
     */
    public function test_can_delete_alojamiento(): void
    {
        $alojamiento = Alojamiento::factory()->create();

        $response = $this->deleteJson('/api/alojamientos/' . $alojamiento->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Alojamiento eliminado correctamente']);

        $this->assertDatabaseMissing('alojamientos', ['id' => $alojamiento->id]);
    }

    /**
     * Test that validation works for creating an alojamiento.
     */
    public function test_create_alojamiento_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/alojamientos', ['direccion' => 'Solo direccion']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre', 'tipo']);
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
}

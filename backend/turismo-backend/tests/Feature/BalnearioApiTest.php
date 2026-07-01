<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Balneario;

class BalnearioApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of balnearios can be retrieved.
     */
    public function test_can_retrieve_list_of_balnearios(): void
    {
        Balneario::factory()->count(2)->create(['habilitado' => true]);
        Balneario::factory()->create(['habilitado' => false]); // Un balneario deshabilitado

        $response = $this->getJson('/api/balnearios');

        $response->assertStatus(200)
                 ->assertJsonCount(2) // Solo 2 habilitados
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'telefono', 'redesSociales', 'servicios', 'mail', 'accesibilidad', 'fecha_desde_hasta', 'imagen', 'latitud', 'longitud', 'habilitado', 'created_at', 'updated_at']
                 ]);
        $response->assertJsonMissing(['habilitado' => false]); // Asegura que no hay deshabilitados
    }

    /**
     * Test that a single balneario can be retrieved by ID.
     */
    public function test_can_retrieve_single_balneario(): void
    {
        $balneario = Balneario::factory()->create(['habilitado' => true]);

        $response = $this->getJson('/api/balnearios/' . $balneario->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $balneario->id,
                     'nombre' => $balneario->nombre,
                     'imagen' => $balneario->imagen,
                     'latitud' => $balneario->latitud,
                     'longitud' => $balneario->longitud,
                     'habilitado' => true,
                 ]);
    }

    /**
     * Test that a balneario can be created.
     */
    public function test_can_create_balneario(): void
    {
        $balnearioData = [
            'nombre' => 'Nuevo Balneario',
            'direccion' => 'Nueva Direccion Balneario',
            'telefono' => '111222333',
            'redesSociales' => 'http://nuevo.com/redes',
            'servicios' => 'Servicios de prueba',
            'mail' => 'nuevo@balneario.com',
            'accesibilidad' => 'Accesibilidad de prueba',
            'fecha_desde_hasta' => 'Enero a Febrero',
            'imagen' => 'http://nuevo.com/imagen.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ];

        $response = $this->postJson('/api/balnearios', $balnearioData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nuevo Balneario',
                     'imagen' => 'http://nuevo.com/imagen.jpg',
                     'latitud' => -38.555,
                     'longitud' => -58.777,
                     'habilitado' => true,
                 ]);

        $this->assertDatabaseHas('balnearios', [
            'nombre' => 'Nuevo Balneario',
            'imagen' => 'http://nuevo.com/imagen.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ]);
    }

    /**
     * Test that a balneario can be updated.
     */
    public function test_can_update_balneario(): void
    {
        $balneario = Balneario::factory()->create(['habilitado' => true]);

        $updatedData = [
            'nombre' => 'Balneario Actualizado',
            'direccion' => 'Direccion Actualizada Balneario',
            'imagen' => 'http://actualizada.com/imagen.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'habilitado' => false,
        ];

        $response = $this->putJson('/api/balnearios/' . $balneario->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Balneario Actualizado',
                     'imagen' => 'http://actualizada.com/imagen.jpg',
                     'latitud' => -38.666,
                     'longitud' => -58.888,
                     'habilitado' => false,
                 ]);

        $this->assertDatabaseHas('balnearios', [
            'id' => $balneario->id,
            'nombre' => 'Balneario Actualizado',
            'imagen' => 'http://actualizada.com/imagen.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'habilitado' => false,
        ]);
    }

    /**
     * Test that a balneario can be deleted.
     */
    public function test_can_delete_balneario(): void
    {
        $balneario = Balneario::factory()->create();

        $response = $this->deleteJson('/api/balnearios/' . $balneario->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Balneario eliminado correctamente']);

        $this->assertDatabaseMissing('balnearios', ['id' => $balneario->id]);
    }

    /**
     * Test that validation works for creating a balneario.
     */
    public function test_create_balneario_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/balnearios', ['direccion' => 'Solo direccion']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre']);
    }

    /**
     * Test that validation works for updating a balneario.
     */
    public function test_update_balneario_validation_fails_with_invalid_mail(): void
    {
        $balneario = Balneario::factory()->create();

        $response = $this->putJson('/api/balnearios/' . $balneario->id, ['mail' => 'invalid-mail']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['mail']);
    }
}

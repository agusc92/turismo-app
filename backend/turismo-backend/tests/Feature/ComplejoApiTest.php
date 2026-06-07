<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Complejo;

class ComplejoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of complejos can be retrieved.
     */
    public function test_can_retrieve_list_of_complejos(): void
    {
        Complejo::factory()->count(3)->create();

        $response = $this->getJson('/api/complejos');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'mail', 'redesSociales', 'telefono', 'servicio', 'adicional', 'imagen', 'latitud', 'longitud', 'created_at', 'updated_at']
                 ]);
    }

    /**
     * Test that a single complejo can be retrieved by ID.
     */
    public function test_can_retrieve_single_complejo(): void
    {
        $complejo = Complejo::factory()->create();

        $response = $this->getJson('/api/complejos/' . $complejo->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $complejo->id,
                     'nombre' => $complejo->nombre,
                     'imagen' => $complejo->imagen,
                     'latitud' => $complejo->latitud,
                     'longitud' => $complejo->longitud,
                 ]);
    }

    /**
     * Test that a complejo can be created.
     */
    public function test_can_create_complejo(): void
    {
        $complejoData = [
            'nombre' => 'Nuevo Complejo',
            'direccion' => 'Nueva Direccion 123',
            'mail' => 'nuevo@complejo.com',
            'redesSociales' => 'http://nuevo.com/redes',
            'telefono' => '111222333',
            'servicio' => 'Piscina, Gimnasio',
            'adicional' => 'Estacionamiento',
            'imagen' => 'http://imagen.com/nuevo_complejo.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
        ];

        $response = $this->postJson('/api/complejos', $complejoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nuevo Complejo',
                     'imagen' => 'http://imagen.com/nuevo_complejo.jpg',
                     'latitud' => -38.555,
                     'longitud' => -58.777,
                 ]);

        $this->assertDatabaseHas('complejos', [
            'nombre' => 'Nuevo Complejo',
            'imagen' => 'http://imagen.com/nuevo_complejo.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
        ]);
    }

    /**
     * Test that a complejo can be updated.
     */
    public function test_can_update_complejo(): void
    {
        $complejo = Complejo::factory()->create();

        $updatedData = [
            'nombre' => 'Complejo Actualizado',
            'direccion' => 'Direccion Actualizada 456',
            'imagen' => 'http://imagen.com/complejo_actualizado.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
        ];

        $response = $this->putJson('/api/complejos/' . $complejo->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Complejo Actualizado',
                     'imagen' => 'http://imagen.com/complejo_actualizado.jpg',
                     'latitud' => -38.666,
                     'longitud' => -58.888,
                 ]);

        $this->assertDatabaseHas('complejos', [
            'id' => $complejo->id,
            'nombre' => 'Complejo Actualizado',
            'imagen' => 'http://imagen.com/complejo_actualizado.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
        ]);
    }

    /**
     * Test that a complejo can be deleted.
     */
    public function test_can_delete_complejo(): void
    {
        $complejo = Complejo::factory()->create();

        $response = $this->deleteJson('/api/complejos/' . $complejo->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Complejo eliminado correctamente']);

        $this->assertDatabaseMissing('complejos', ['id' => $complejo->id]);
    }

    /**
     * Test that validation works for creating a complejo.
     */
    public function test_create_complejo_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/complejos', ['direccion' => 'Solo direccion']); // Falta nombre

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre']);
    }

    /**
     * Test that validation works for updating a complejo.
     */
    public function test_update_complejo_validation_fails_with_invalid_mail(): void
    {
        $complejo = Complejo::factory()->create();

        $response = $this->putJson('/api/complejos/' . $complejo->id, ['mail' => 'invalid-mail']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['mail']);
    }
}

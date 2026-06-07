<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Evento;

class EventoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of eventos can be retrieved.
     */
    public function test_can_retrieve_list_of_eventos(): void
    {
        Evento::factory()->count(3)->create();

        $response = $this->getJson('/api/eventos');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'descripcion', 'fecha', 'lugar', 'imagen', 'destacado', 'latitud', 'longitud', 'created_at', 'updated_at']
                 ]);
    }

    /**
     * Test that a single evento can be retrieved by ID.
     */
    public function test_can_retrieve_single_evento(): void
    {
        $evento = Evento::factory()->create();

        $response = $this->getJson('/api/eventos/' . $evento->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $evento->id,
                     'nombre' => $evento->nombre,
                     'latitud' => $evento->latitud,
                     'longitud' => $evento->longitud,
                 ]);
    }

    /**
     * Test that a evento can be created.
     */
    public function test_can_create_evento(): void
    {
        $eventoData = [
            'nombre' => 'Nuevo Evento',
            'direccion' => 'Nueva Direccion Evento',
            'descripcion' => 'Descripcion del nuevo evento',
            'fecha' => '2025-01-01 10:00:00',
            'lugar' => 'Lugar del Evento',
            'imagen' => 'http://imagen.com/nuevo_evento.jpg',
            'destacado' => true,
            'latitud' => -38.555,
            'longitud' => -58.777,
        ];

        $response = $this->postJson('/api/eventos', $eventoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nuevo Evento',
                     'latitud' => -38.555,
                     'longitud' => -58.777,
                 ]);

        $this->assertDatabaseHas('eventos', [
            'nombre' => 'Nuevo Evento',
            'latitud' => -38.555,
            'longitud' => -58.777,
        ]);
    }

    /**
     * Test that a evento can be updated.
     */
    public function test_can_update_evento(): void
    {
        $evento = Evento::factory()->create();

        $updatedData = [
            'nombre' => 'Evento Actualizado',
            'direccion' => 'Direccion Actualizada Evento',
            'destacado' => false,
            'latitud' => -38.666,
            'longitud' => -58.888,
        ];

        $response = $this->putJson('/api/eventos/' . $evento->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Evento Actualizado',
                     'latitud' => -38.666,
                     'longitud' => -58.888,
                 ]);

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'nombre' => 'Evento Actualizado',
            'latitud' => -38.666,
            'longitud' => -58.888,
        ]);
    }

    /**
     * Test that a evento can be deleted.
     */
    public function test_can_delete_evento(): void
    {
        $evento = Evento::factory()->create();

        $response = $this->deleteJson('/api/eventos/' . $evento->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Evento eliminado correctamente']);

        $this->assertDatabaseMissing('eventos', ['id' => $evento->id]);
    }

    /**
     * Test that validation works for creating an evento.
     */
    public function test_create_evento_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/eventos', ['direccion' => 'Solo direccion']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre', 'fecha', 'lugar']);
    }

    /**
     * Test that destacados eventos can be retrieved.
     */
    public function test_can_retrieve_destacados_eventos(): void
    {
        Evento::factory()->create(['destacado' => true]);
        Evento::factory()->create(['destacado' => true]);
        Evento::factory()->create(['destacado' => false]);

        $response = $this->getJson('/api/eventos/destacados');

        $response->assertStatus(200)
                 ->assertJsonCount(2)
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'destacado']
                 ]);
        $this->assertTrue($response->json()[0]['destacado']);
        $this->assertTrue($response->json()[1]['destacado']);
    }
}

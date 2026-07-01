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
        Evento::factory()->count(2)->create(['habilitado' => true]);
        Evento::factory()->create(['habilitado' => false]); // Un evento deshabilitado

        $response = $this->getJson('/api/eventos');

        $response->assertStatus(200)
                 ->assertJsonCount(2) // Solo 2 habilitados
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'descripcion', 'fecha', 'lugar', 'imagen', 'destacado', 'latitud', 'longitud', 'habilitado', 'created_at', 'updated_at']
                 ]);
        $response->assertJsonMissing(['habilitado' => false]); // Asegura que no hay deshabilitados
    }

    /**
     * Test that a single evento can be retrieved by ID.
     */
    public function test_can_retrieve_single_evento(): void
    {
        $evento = Evento::factory()->create(['habilitado' => true]);

        $response = $this->getJson('/api/eventos/' . $evento->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $evento->id,
                     'nombre' => $evento->nombre,
                     'latitud' => $evento->latitud,
                     'longitud' => $evento->longitud,
                     'habilitado' => true,
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
            'habilitado' => true,
        ];

        $response = $this->postJson('/api/eventos', $eventoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nuevo Evento',
                     'latitud' => -38.555,
                     'longitud' => -58.777,
                     'habilitado' => true,
                 ]);

        $this->assertDatabaseHas('eventos', [
            'nombre' => 'Nuevo Evento',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ]);
    }

    /**
     * Test that a evento can be updated.
     */
    public function test_can_update_evento(): void
    {
        $evento = Evento::factory()->create(['habilitado' => true]);

        $updatedData = [
            'nombre' => 'Evento Actualizado',
            'direccion' => 'Direccion Actualizada Evento',
            'destacado' => false,
            'latitud' => -38.666,
            'longitud' => -58.888,
            'habilitado' => false,
        ];

        $response = $this->putJson('/api/eventos/' . $evento->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Evento Actualizado',
                     'latitud' => -38.666,
                     'longitud' => -58.888,
                     'habilitado' => false,
                 ]);

        $this->assertDatabaseHas('eventos', [
            'id' => $evento->id,
            'nombre' => 'Evento Actualizado',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'habilitado' => false,
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
        Evento::factory()->create(['destacado' => true, 'habilitado' => true]);
        Evento::factory()->create(['destacado' => true, 'habilitado' => false]); // Destacado pero deshabilitado
        Evento::factory()->create(['destacado' => false, 'habilitado' => true]);

        $response = $this->getJson('/api/eventos/destacados');

        $response->assertStatus(200)
                 ->assertJsonCount(1) // Solo 1 destacado y habilitado
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'destacado', 'habilitado']
                 ]);
        $this->assertTrue($response->json()[0]['destacado']);
        $this->assertTrue($response->json()[0]['habilitado']);
    }
}

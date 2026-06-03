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
        Balneario::factory()->count(3)->create();

        $response = $this->getJson('/api/balnearios');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'telefono', 'redesSociales', 'servicios', 'mail', 'accesibilidad', 'fecha_desde_hasta', 'imagen', 'created_at', 'updated_at']
                 ]);
    }

    /**
     * Test that a single balneario can be retrieved by ID.
     */
    public function test_can_retrieve_single_balneario(): void
    {
        $balneario = Balneario::factory()->create();

        $response = $this->getJson('/api/balnearios/' . $balneario->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $balneario->id,
                     'nombre' => $balneario->nombre,
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
        ];

        $response = $this->postJson('/api/balnearios', $balnearioData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nuevo Balneario',
                 ]);

        $this->assertDatabaseHas('balnearios', ['nombre' => 'Nuevo Balneario']);
    }

    /**
     * Test that a balneario can be updated.
     */
    public function test_can_update_balneario(): void
    {
        $balneario = Balneario::factory()->create();

        $updatedData = [
            'nombre' => 'Balneario Actualizado',
            'direccion' => 'Direccion Actualizada Balneario',
        ];

        $response = $this->putJson('/api/balnearios/' . $balneario->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Balneario Actualizado',
                 ]);

        $this->assertDatabaseHas('balnearios', ['id' => $balneario->id, 'nombre' => 'Balneario Actualizado']);
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

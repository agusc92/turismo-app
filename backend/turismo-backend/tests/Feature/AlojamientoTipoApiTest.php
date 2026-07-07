<?php

namespace Tests\Feature;

use App\Models\Alojamiento;
use App\Models\TipoAlojamiento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AlojamientoTipoApiTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Crear un usuario administrador para las pruebas, usando la columna 'rol'
        $this->adminUser = User::factory()->create(['rol' => 'admin']);
    }

    #[Test]
    public function it_can_list_associated_tipos_de_alojamiento_for_an_alojamiento()
    {
        $alojamiento = Alojamiento::factory()->create();
        $tipo1 = TipoAlojamiento::factory()->create();
        $tipo2 = TipoAlojamiento::factory()->create();

        $alojamiento->tiposAlojamiento()->attach([$tipo1->id, $tipo2->id]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
                         ->getJson("/api/alojamientos/{$alojamiento->id}/tipos");

        $response->assertOk()
                 ->assertJsonCount(2)
                 ->assertJsonFragment(['id' => $tipo1->id, 'tipo' => $tipo1->tipo])
                 ->assertJsonFragment(['id' => $tipo2->id, 'tipo' => $tipo2->tipo]);
    }

    #[Test]
    public function it_returns_404_if_alojamiento_not_found_when_listing_tipos()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
                         ->getJson("/api/alojamientos/9999/tipos"); // ID que no existe

        $response->assertNotFound()
                 ->assertJson(['message' => 'Alojamiento no encontrado'], 404);
    }

    #[Test]
    public function it_can_attach_a_tipo_de_alojamiento_to_an_alojamiento()
    {
        $alojamiento = Alojamiento::factory()->create();
        $tipo = TipoAlojamiento::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                         ->postJson("/api/alojamientos/{$alojamiento->id}/tipos", [
                             'tipo_alojamiento_id' => $tipo->id,
                         ]);

        $response->assertCreated() // 201 Created
                 ->assertJsonFragment(['id' => $tipo->id, 'tipo' => $tipo->tipo]);

        $this->assertDatabaseHas('alojamiento_tipo_alojamiento', [
            'alojamiento_id' => $alojamiento->id,
            'tipo_alojamiento_id' => $tipo->id,
        ]);
    }

    #[Test]
    public function it_returns_404_if_alojamiento_not_found_when_attaching_tipo()
    {
        $tipo = TipoAlojamiento::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                         ->postJson("/api/alojamientos/9999/tipos", [ // ID que no existe
                             'tipo_alojamiento_id' => $tipo->id,
                         ]);

        $response->assertNotFound()
                 ->assertJson(['message' => 'Alojamiento no encontrado'], 404);
    }

    #[Test]
    public function it_returns_422_if_invalid_tipo_id_when_attaching_tipo()
    {
        $alojamiento = Alojamiento::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                         ->postJson("/api/alojamientos/{$alojamiento->id}/tipos", [
                             'tipo_alojamiento_id' => 9999, // ID de tipo que no existe
                         ]);

        $response->assertStatus(422) // Unprocessable Entity
                 ->assertJsonValidationErrors(['tipo_alojamiento_id']);
    }

    #[Test]
    public function it_can_detach_a_tipo_de_alojamiento_from_an_alojamiento()
    {
        $alojamiento = Alojamiento::factory()->create();
        $tipo = TipoAlojamiento::factory()->create();

        $alojamiento->tiposAlojamiento()->attach($tipo->id);

        $this->assertDatabaseHas('alojamiento_tipo_alojamiento', [
            'alojamiento_id' => $alojamiento->id,
            'tipo_alojamiento_id' => $tipo->id,
        ]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
                         ->deleteJson("/api/alojamientos/{$alojamiento->id}/tipos/{$tipo->id}");

        $response->assertOk()
                 ->assertJson(['message' => 'Tipo de alojamiento desasociado correctamente']);

        $this->assertDatabaseMissing('alojamiento_tipo_alojamiento', [
            'alojamiento_id' => $alojamiento->id,
            'tipo_alojamiento_id' => $tipo->id,
        ]);
    }

    #[Test]
    public function it_returns_404_if_alojamiento_not_found_when_detaching_tipo()
    {
        $tipo = TipoAlojamiento::factory()->create();

        $response = $this->actingAs($this->adminUser, 'sanctum')
                         ->deleteJson("/api/alojamientos/9999/tipos/{$tipo->id}"); // ID de alojamiento que no existe

        $response->assertNotFound()
                 ->assertJson(['message' => 'Alojamiento no encontrado'], 404);
    }

    #[Test]
    public function it_handles_detaching_non_existent_tipo_gracefully()
    {
        $alojamiento = Alojamiento::factory()->create();
        $tipo = TipoAlojamiento::factory()->create(); // Este tipo no está asociado

        $response = $this->actingAs($this->adminUser, 'sanctum')
                         ->deleteJson("/api/alojamientos/{$alojamiento->id}/tipos/{$tipo->id}");

        $response->assertOk() // Aunque no estuviera asociado, la operación de detach no falla
                 ->assertJson(['message' => 'Tipo de alojamiento desasociado correctamente']);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Gastronomico;
use App\Models\TipoGastronomico;
use App\Models\Menu;

class GastronomicoApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of gastronomicos can be retrieved.
     */
    public function test_can_retrieve_list_of_gastronomicos(): void
    {
        Gastronomico::factory()->count(2)->create(['habilitado' => true]);
        Gastronomico::factory()->create(['habilitado' => false]); // Un gastronómico deshabilitado

        $response = $this->getJson('/api/gastronomicos');

        $response->assertStatus(200)
                 ->assertJsonCount(2) // Solo 2 habilitados
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'direccion', 'telefono', 'redesSociales', 'tiendaOnline', 'extras', 'horario', 'imagen', 'latitud', 'longitud', 'habilitado', 'created_at', 'updated_at', 'tipo', 'menu', 'menus', 'tipos']
                 ]);
        $response->assertJsonMissing(['habilitado' => false]); // Asegura que no hay deshabilitados
    }

    /**
     * Test that a single gastronomico can be retrieved by ID.
     */
    public function test_can_retrieve_single_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create(['habilitado' => true]);
        $tipo = TipoGastronomico::factory()->create(['tipo' => 'Restaurante']);
        $menu = Menu::factory()->create(['tipo' => 'Vegano']);
        $gastronomico->tipos()->attach($tipo->id);
        $gastronomico->menus()->attach($menu->id);

        $response = $this->getJson('/api/gastronomicos/' . $gastronomico->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $gastronomico->id,
                     'nombre' => $gastronomico->nombre,
                     'imagen' => $gastronomico->imagen,
                     'latitud' => $gastronomico->latitud,
                     'longitud' => $gastronomico->longitud,
                     'habilitado' => true,
                 ])
                 ->assertJsonFragment(['tipo' => 'Restaurante'])
                 ->assertJsonFragment(['tipo' => 'Vegano']);
    }

    /**
     * Test that a gastronomico can be created.
     */
    public function test_can_create_gastronomico(): void
    {
        $tipo = TipoGastronomico::factory()->create();
        $menu = Menu::factory()->create();

        $gastronomicoData = [
            'nombre' => 'Nuevo Gastronomico',
            'direccion' => 'Nueva Direccion 123',
            'telefono' => '111222333',
            'redesSociales' => 'http://nuevo.com/redes',
            'tiendaOnline' => 'http://nuevo.com/tienda',
            'extras' => 'WiFi',
            'horario' => 'L-V 09-18',
            'imagen' => 'http://nuevo.com/imagen.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
            'tipo_ids' => [$tipo->id],
            'menu_ids' => [$menu->id],
        ];

        $response = $this->postJson('/api/gastronomicos', $gastronomicoData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'nombre' => 'Nuevo Gastronomico',
                     'imagen' => 'http://nuevo.com/imagen.jpg',
                     'latitud' => -38.555,
                     'longitud' => -58.777,
                     'habilitado' => true,
                 ]);

        $this->assertDatabaseHas('gastronomicos', [
            'nombre' => 'Nuevo Gastronomico',
            'imagen' => 'http://nuevo.com/imagen.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ]);
        $this->assertDatabaseHas('gastronomico_tipo_gastronomico', ['gastronomico_id' => $response->json('id'), 'tipo_gastronomico_id' => $tipo->id]);
        $this->assertDatabaseHas('gastronomico_menus', ['gastronomico_id' => $response->json('id'), 'menu_id' => $menu->id]);
    }

    /**
     * Test that a gastronomico can be updated.
     */
    public function test_can_update_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create(['habilitado' => true]);
        $tipo1 = TipoGastronomico::factory()->create();
        $tipo2 = TipoGastronomico::factory()->create();
        $menu1 = Menu::factory()->create();
        $gastronomico->tipos()->attach($tipo1->id);
        $gastronomico->menus()->attach($menu1->id);

        $updatedData = [
            'nombre' => 'Gastronomico Actualizado',
            'direccion' => 'Direccion Actualizada 456',
            'imagen' => 'http://actualizada.com/imagen.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'habilitado' => false,
            'tipo_ids' => [$tipo2->id], // Sincronizar a un nuevo tipo
            'menu_ids' => [], // Eliminar menús
        ];

        $response = $this->putJson('/api/gastronomicos/' . $gastronomico->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'nombre' => 'Gastronomico Actualizado',
                     'imagen' => 'http://actualizada.com/imagen.jpg',
                     'latitud' => -38.666,
                     'longitud' => -58.888,
                     'habilitado' => false,
                 ]);

        $this->assertDatabaseHas('gastronomicos', [
            'id' => $gastronomico->id,
            'nombre' => 'Gastronomico Actualizado',
            'imagen' => 'http://actualizada.com/imagen.jpg',
            'latitud' => -38.666,
            'longitud' => -58.888,
            'habilitado' => false,
        ]);
        $this->assertDatabaseHas('gastronomico_tipo_gastronomico', ['gastronomico_id' => $gastronomico->id, 'tipo_gastronomico_id' => $tipo2->id]);
        $this->assertDatabaseMissing('gastronomico_tipo_gastronomico', ['gastronomico_id' => $gastronomico->id, 'tipo_gastronomico_id' => $tipo1->id]);
        $this->assertDatabaseMissing('gastronomico_menus', ['gastronomico_id' => $gastronomico->id, 'menu_id' => $menu1->id]);
    }

    /**
     * Test that a gastronomico can be deleted.
     */
    public function test_can_delete_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $tipo = TipoGastronomico::factory()->create();
        $menu = Menu::factory()->create();
        $gastronomico->tipos()->attach($tipo->id);
        $gastronomico->menus()->attach($menu->id);

        $response = $this->deleteJson('/api/gastronomicos/' . $gastronomico->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Gastronomico eliminado correctamente']);

        $this->assertDatabaseMissing('gastronomicos', ['id' => $gastronomico->id]);
        $this->assertDatabaseMissing('gastronomico_tipo_gastronomico', ['gastronomico_id' => $gastronomico->id]);
        $this->assertDatabaseMissing('gastronomico_menus', ['gastronomico_id' => $gastronomico->id]);
    }

    /**
     * Test that validation works for creating a gastronomico.
     */
    public function test_create_gastronomico_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/gastronomicos', ['direccion' => 'Solo direccion']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['nombre']);
    }

    /**
     * Test that validation works for updating a gastronomico.
     */
    public function test_update_gastronomico_validation_fails_with_invalid_mail(): void
    {
        $gastronomico = Gastronomico::factory()->create();

        $response = $this->putJson('/api/gastronomicos/' . $gastronomico->id, ['tipo_ids' => ['invalid-id']]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo_ids.0']);
    }

    /**
     * Test that tipos can be added to a gastronomico.
     */
    public function test_can_add_tipo_to_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $tipo = TipoGastronomico::factory()->create();

        $response = $this->postJson('/api/gastronomicos/' . $gastronomico->id . '/tipos', ['tipo_gastronomico_id' => $tipo->id]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['id' => $gastronomico->id])
                 ->assertJsonFragment(['tipo' => $tipo->tipo]);

        $this->assertDatabaseHas('gastronomico_tipo_gastronomico', [
            'gastronomico_id' => $gastronomico->id,
            'tipo_gastronomico_id' => $tipo->id,
        ]);
    }

    /**
     * Test that tipos can be removed from a gastronomico.
     */
    public function test_can_remove_tipo_from_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $tipo = TipoGastronomico::factory()->create();
        $gastronomico->tipos()->attach($tipo->id);

        $response = $this->deleteJson('/api/gastronomicos/' . $gastronomico->id . '/tipos/' . $tipo->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Tipo eliminado del gastronomico']);

        $this->assertDatabaseMissing('gastronomico_tipo_gastronomico', [
            'gastronomico_id' => $gastronomico->id,
            'tipo_gastronomico_id' => $tipo->id,
        ]);
    }

    /**
     * Test that menus can be added to a gastronomico.
     */
    public function test_can_add_menu_to_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $menu = Menu::factory()->create();

        $response = $this->postJson('/api/gastronomicos/' . $gastronomico->id . '/menus', ['menu_id' => $menu->id]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['id' => $gastronomico->id])
                 ->assertJsonFragment(['tipo' => $menu->tipo]);

        $this->assertDatabaseHas('gastronomico_menus', [
            'gastronomico_id' => $gastronomico->id,
            'menu_id' => $menu->id,
        ]);
    }

    /**
     * Test that menus can be removed from a gastronomico.
     */
    public function test_can_remove_menu_from_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $menu = Menu::factory()->create();
        $gastronomico->menus()->attach($menu->id);

        $response = $this->deleteJson('/api/gastronomicos/' . $gastronomico->id . '/menus/' . $menu->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Menu eliminado del gastronomico']);

        $this->assertDatabaseMissing('gastronomico_menus', [
            'gastronomico_id' => $gastronomico->id,
            'menu_id' => $menu->id,
        ]);
    }
}

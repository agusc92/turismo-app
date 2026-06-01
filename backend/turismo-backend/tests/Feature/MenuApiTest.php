<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Menu;

class MenuApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of menus can be retrieved.
     */
    public function test_can_retrieve_list_of_menus(): void
    {
        Menu::factory()->count(3)->create();

        $response = $this->getJson('/api/menus');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'tipo', 'created_at', 'updated_at']
                 ]);
    }

    /**
     * Test that a single menu can be retrieved by ID.
     */
    public function test_can_retrieve_single_menu(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->getJson('/api/menus/' . $menu->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $menu->id,
                     'tipo' => $menu->tipo,
                 ]);
    }

    /**
     * Test that a menu can be created.
     */
    public function test_can_create_menu(): void
    {
        $menuData = [
            'tipo' => 'Nuevo Tipo de Menu',
        ];

        $response = $this->postJson('/api/menus', $menuData);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'tipo' => 'Nuevo Tipo de Menu',
                 ]);

        $this->assertDatabaseHas('menus', ['tipo' => 'Nuevo Tipo de Menu']);
    }

    /**
     * Test that a menu can be updated.
     */
    public function test_can_update_menu(): void
    {
        $menu = Menu::factory()->create();

        $updatedData = [
            'tipo' => 'Tipo de Menu Actualizado',
        ];

        $response = $this->putJson('/api/menus/' . $menu->id, $updatedData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'tipo' => 'Tipo de Menu Actualizado',
                 ]);

        $this->assertDatabaseHas('menus', ['id' => $menu->id, 'tipo' => 'Tipo de Menu Actualizado']);
    }

    /**
     * Test that a menu can be deleted.
     */
    public function test_can_delete_menu(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->deleteJson('/api/menus/' . $menu->id);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Menu eliminado correctamente']);

        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    /**
     * Test that validation works for creating a menu.
     */
    public function test_create_menu_validation_fails_without_required_fields(): void
    {
        $response = $this->postJson('/api/menus', ['invalid_field' => 'value']); // Falta 'tipo'

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }

    /**
     * Test that validation works for updating a menu.
     */
    public function test_update_menu_validation_fails_with_invalid_data(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->putJson('/api/menus/' . $menu->id, ['tipo' => '']); // Tipo vacío

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tipo']);
    }
}

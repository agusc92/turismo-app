<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Gastronomico;
use App\Models\Menu;

class GastronomicoMenuApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a list of menus for a specific gastronomico can be retrieved.
     */
    public function test_can_retrieve_menus_for_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $menus = Menu::factory()->count(3)->create();
        $gastronomico->menus()->attach($menus->pluck('id'));

        $response = $this->getJson('/api/gastronomicos/' . $gastronomico->id . '/menus');

        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'tipo', 'created_at', 'updated_at', 'pivot']
                 ]);
    }

    /**
     * Test that a menu can be attached to a gastronomico.
     */
    public function test_can_attach_menu_to_gastronomico(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $menu = Menu::factory()->create();

        $response = $this->postJson('/api/gastronomicos/' . $gastronomico->id . '/menus', ['menu_id' => $menu->id]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['id' => $gastronomico->id]); // Devuelve el gastronómico actualizado

        $this->assertDatabaseHas('gastronomico_menus', [
            'gastronomico_id' => $gastronomico->id,
            'menu_id' => $menu->id,
        ]);
    }

    /**
     * Test that a menu can be detached from a gastronomico.
     */
    public function test_can_detach_menu_from_gastronomico(): void
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

    /**
     * Test that attaching a non-existent menu fails validation.
     */
    public function test_attach_menu_validation_fails_with_non_existent_menu(): void
    {
        $gastronomico = Gastronomico::factory()->create();

        $response = $this->postJson('/api/gastronomicos/' . $gastronomico->id . '/menus', ['menu_id' => 9999]); // ID de menú que no existe

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['menu_id']);
    }

    /**
     * Test that detaching a non-existent menu from a gastronomico returns 404.
     */
    public function test_detach_menu_non_existent_gastronomico_returns_404(): void
    {
        $menu = Menu::factory()->create();

        $response = $this->deleteJson('/api/gastronomicos/9999/menus/' . $menu->id); // ID de gastronómico que no existe

        $response->assertStatus(404);
    }

    /**
     * Test that detaching a menu from a non-existent gastronomico returns 404.
     */
    public function test_detach_menu_non_existent_menu_returns_404(): void
    {
        $gastronomico = Gastronomico::factory()->create();

        $response = $this->deleteJson('/api/gastronomicos/' . $gastronomico->id . '/menus/9999'); // ID de menú que no existe

        $response->assertStatus(404); // O 200 si la operación es idempotente y no hay error
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Menu;
use App\Models\Gastronomico;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a Menu instance can be created and has correct attributes.
     */
    public function test_menu_can_be_created_with_attributes(): void
    {
        $data = [
            'tipo' => 'Vegano',
        ];

        $menu = new Menu();
        $menu->fill($data);

        $this->assertEquals($data['tipo'], $menu->tipo);
        $this->assertNull($menu->id);
    }

    /**
     * Test that a Menu instance can be instantiated.
     */
    public function test_menu_can_be_instantiated(): void
    {
        $menu = new Menu();
        $this->assertInstanceOf(Menu::class, $menu);
    }

    /**
     * Test that the 'gastronomicos' relationship works correctly.
     */
    public function test_gastronomicos_relationship_works(): void
    {
        $menu = Menu::factory()->create();
        $gastronomico1 = Gastronomico::factory()->create();
        $gastronomico2 = Gastronomico::factory()->create();
        $menu->gastronomicos()->attach([$gastronomico1->id, $gastronomico2->id]);

        $menu->load('gastronomicos');

        $this->assertCount(2, $menu->gastronomicos);
        $this->assertTrue($menu->gastronomicos->contains($gastronomico1));
        $this->assertTrue($menu->gastronomicos->contains($gastronomico2));
        $this->assertInstanceOf(Gastronomico::class, $menu->gastronomicos->first());
    }
}

<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Menu;

class MenuTest extends TestCase
{
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
}

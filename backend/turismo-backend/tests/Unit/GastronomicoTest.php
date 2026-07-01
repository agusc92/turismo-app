<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Gastronomico;
use App\Models\TipoGastronomico;
use App\Models\Menu;

class GastronomicoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a Gastronomico instance can be created and has correct attributes.
     */
    public function test_gastronomico_can_be_created_with_attributes(): void
    {
        $data = [
            'nombre' => 'Restaurante de Prueba',
            'direccion' => 'Av. Principal 123',
            'telefono' => '1122334455',
            'redesSociales' => 'http://instagram.com/restaurante',
            'tiendaOnline' => 'http://tienda.com/restaurante',
            'extras' => 'Wifi, estacionamiento',
            'horario' => 'L-D 09:00-23:00',
            'imagen' => 'http://imagen.com/restaurante.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ];

        $gastronomico = new Gastronomico();
        $gastronomico->fill($data);

        $this->assertEquals($data['nombre'], $gastronomico->nombre);
        $this->assertEquals($data['direccion'], $gastronomico->direccion);
        $this->assertEquals($data['telefono'], $gastronomico->telefono);
        $this->assertEquals($data['redesSociales'], $gastronomico->redesSociales);
        $this->assertEquals($data['tiendaOnline'], $gastronomico->tiendaOnline);
        $this->assertEquals($data['extras'], $gastronomico->extras);
        $this->assertEquals($data['horario'], $gastronomico->horario);
        $this->assertEquals($data['imagen'], $gastronomico->imagen);
        $this->assertEquals($data['latitud'], $gastronomico->latitud);
        $this->assertEquals($data['longitud'], $gastronomico->longitud);
        $this->assertEquals($data['habilitado'], $gastronomico->habilitado);
        $this->assertNull($gastronomico->id);
    }

    /**
     * Test that a Gastronomico instance can be instantiated.
     */
    public function test_gastronomico_can_be_instantiated(): void
    {
        $gastronomico = new Gastronomico();
        $this->assertInstanceOf(Gastronomico::class, $gastronomico);
    }

    /**
     * Test that 'latitud' attribute is correctly cast to float.
     */
    public function test_latitud_attribute_is_float(): void
    {
        // Crear el modelo a través del factory para asegurar que los casts se apliquen correctamente
        $gastronomico = Gastronomico::factory()->make();

        $gastronomico->latitud = "-38.555";
        $this->assertIsFloat($gastronomico->latitud);
        $this->assertEquals(-38.555, $gastronomico->latitud);

        $gastronomico->latitud = -38.12345678; // Más decimales de los que soporta el DB
        $this->assertIsFloat($gastronomico->latitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-38.1234568, $gastronomico->latitud, 0.0000001);
    }

    /**
     * Test that 'longitud' attribute is correctly cast to float.
     */
    public function test_longitud_attribute_is_float(): void
    {
        // Crear el modelo a través del factory para asegurar que los casts se apliquen correctamente
        $gastronomico = Gastronomico::factory()->make();

        $gastronomico->longitud = "-58.777";
        $this->assertIsFloat($gastronomico->longitud);
        $this->assertEquals(-58.777, $gastronomico->longitud);

        $gastronomico->longitud = -58.98765432; // Más decimales de los que soporta el DB
        $this->assertIsFloat($gastronomico->longitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-58.9876543, $gastronomico->longitud, 0.0000001);
    }

    /**
     * Test that getTipoAttribute returns an array of associated types.
     */
    public function test_get_tipo_attribute_returns_array_of_types(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $tipo1 = TipoGastronomico::factory()->create(['tipo' => 'Restaurante']);
        $tipo2 = TipoGastronomico::factory()->create(['tipo' => 'Bar']);
        $gastronomico->tipos()->attach([$tipo1->id, $tipo2->id]);

        $gastronomico->load('tipos');

        $expectedTypes = ['Restaurante', 'Bar'];
        $this->assertEquals($expectedTypes, $gastronomico->tipo);
    }

    /**
     * Test that getMenuAttribute returns an array of associated menus.
     */
    public function test_get_menu_attribute_returns_array_of_menus(): void
    {
        $gastronomico = Gastronomico::factory()->create();
        $menu1 = Menu::factory()->create(['tipo' => 'Vegano']);
        $menu2 = Menu::factory()->create(['tipo' => 'Sin TACC']);
        $gastronomico->menus()->attach([$menu1->id, $menu2->id]);

        $gastronomico->load('menus');

        $expectedMenus = ['Vegano', 'Sin TACC'];
        $this->assertEquals($expectedMenus, $gastronomico->menu);
    }

    /**
     * Test that 'habilitado' attribute is correctly cast to boolean.
     */
    public function test_habilitado_attribute_is_boolean(): void
    {
        // Crear el modelo y luego refrescarlo para asegurar que los casts se apliquen correctamente
        $gastronomico = Gastronomico::factory()->create(['habilitado' => true])->refresh();

        // Test true values
        $gastronomico->habilitado = '1';
        $this->assertTrue($gastronomico->habilitado);
        $gastronomico->habilitado = 'true';
        $this->assertTrue($gastronomico->habilitado);
        $gastronomico->habilitado = 1;
        $this->assertTrue($gastronomico->habilitado);

        // Test false values
        $gastronomico->habilitado = '0';
        $this->assertFalse($gastronomico->habilitado);
        $gastronomico->habilitado = 'false';
        $this->assertFalse($gastronomico->habilitado);
        $gastronomico->habilitado = 0;
        $this->assertFalse($gastronomico->habilitado);
        $gastronomico->habilitado = null;
        $this->assertFalse($gastronomico->habilitado); // Laravel casts null to false for boolean
        $gastronomico->habilitado = '';
        $this->assertFalse($gastronomico->habilitado);
    }
}

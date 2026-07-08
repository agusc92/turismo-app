<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Balneario;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BalnearioTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a Balneario instance can be created and has correct attributes.
     */
    public function test_balneario_can_be_created_with_attributes(): void
    {
        $data = [
            'nombre' => 'Balneario de Prueba',
            'direccion' => 'Av. Costanera 100',
            'telefono' => '123456789',
            'redesSociales' => 'http://facebook.com/balneario',
            'servicios' => 'Sombrillas, duchas, bar',
            'mail' => 'info@balneario.com',
            'accesibilidad' => 'Rampas de acceso',
            'fecha_desde_hasta' => 'Diciembre a Marzo',
            'imagen' => 'http://imagen.com/balneario.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ];

        $balneario = new Balneario();
        $balneario->fill($data);

        $this->assertEquals($data['nombre'], $balneario->nombre);
        $this->assertEquals($data['direccion'], $balneario->direccion);
        $this->assertEquals($data['telefono'], $balneario->telefono);
        $this->assertEquals($data['redesSociales'], $balneario->redesSociales);
        $this->assertEquals($data['servicios'], $balneario->servicios);
        $this->assertEquals($data['mail'], $balneario->mail);
        $this->assertEquals($data['accesibilidad'], $balneario->accesibilidad);
        $this->assertEquals($data['fecha_desde_hasta'], $balneario->fecha_desde_hasta);
        $this->assertEquals($data['imagen'], $balneario->imagen);
        $this->assertEquals($data['latitud'], $balneario->latitud);
        $this->assertEquals($data['longitud'], $balneario->longitud);
        $this->assertEquals($data['habilitado'], $balneario->habilitado);
        $this->assertNull($balneario->id);
    }

    /**
     * Test that a Balneario instance can be instantiated.
     */
    public function test_balneario_can_be_instantiated(): void
    {
        $balneario = new Balneario();
        $this->assertInstanceOf(Balneario::class, $balneario);
    }

    /**
     * Test that 'latitud' attribute is correctly cast to float.
     */
    public function test_latitud_attribute_is_float(): void
    {
        // Crear el modelo a través del factory para asegurar que los casts se apliquen correctamente
        $balneario = Balneario::factory()->make();

        $balneario->latitud = "-38.555";
        $this->assertIsFloat($balneario->latitud);
        $this->assertEquals(-38.555, $balneario->latitud);

        $balneario->latitud = -38.12345678; // Más decimales de los que soporta el DB
        $this->assertIsFloat($balneario->latitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-38.1234568, $balneario->latitud, 0.0000001);
    }

    /**
     * Test that 'longitud' attribute is correctly cast to float.
     */
    public function test_longitud_attribute_is_float(): void
    {
        // Crear el modelo a través del factory para asegurar que los casts se apliquen correctamente
        $balneario = Balneario::factory()->make();

        $balneario->longitud = "-58.777";
        $this->assertIsFloat($balneario->longitud);
        $this->assertEquals(-58.777, $balneario->longitud);

        $balneario->longitud = -58.98765432; // Más decimales de los que soporta el DB
        $this->assertIsFloat($balneario->longitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-58.9876543, $balneario->longitud, 0.0000001);
    }

    /**
     * Test that 'habilitado' attribute is correctly cast to boolean.
     */
    public function test_habilitado_attribute_is_boolean(): void
    {
        // Crear el modelo y luego refrescarlo para asegurar que los casts se apliquen correctamente
        $balneario = Balneario::factory()->create(['habilitado' => true])->refresh();

        // Test true values
        $balneario->habilitado = '1';
        $this->assertTrue($balneario->habilitado);
        $balneario->habilitado = 'true';
        $this->assertTrue($balneario->habilitado);
        $balneario->habilitado = 1;
        $this->assertTrue($balneario->habilitado);

        // Test false values
        $balneario->habilitado = '0';
        $this->assertFalse($balneario->habilitado);
        $balneario->habilitado = 'false';
        $this->assertFalse($balneario->habilitado);
        $balneario->habilitado = 0;
        $this->assertFalse($balneario->habilitado);
        $balneario->habilitado = null;
        $this->assertFalse($balneario->habilitado); // Laravel casts null to false for boolean
        $balneario->habilitado = '';
        $this->assertFalse($balneario->habilitado);
    }
}

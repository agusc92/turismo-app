<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Complejo;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComplejoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a Complejo instance can be created and has correct attributes.
     */
    public function test_complejo_can_be_created_with_attributes(): void
    {
        $data = [
            'nombre' => 'Complejo de Prueba',
            'direccion' => 'Calle Falsa 123',
            'mail' => 'test@example.com',
            'redesSociales' => 'http://redes.com/test',
            'telefono' => '123456789',
            'servicio' => 'Piscina, Gimnasio',
            'adicional' => 'Wifi gratis',
            'imagen' => 'http://imagen.com/complejo.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ];

        $complejo = new Complejo();
        $complejo->fill($data);

        $this->assertEquals($data['nombre'], $complejo->nombre);
        $this->assertEquals($data['direccion'], $complejo->direccion);
        $this->assertEquals($data['mail'], $complejo->mail);
        $this->assertEquals($data['redesSociales'], $complejo->redesSociales);
        $this->assertEquals($data['telefono'], $complejo->telefono);
        $this->assertEquals($data['servicio'], $complejo->servicio);
        $this->assertEquals($data['adicional'], $complejo->adicional);
        $this->assertEquals($data['imagen'], $complejo->imagen);
        $this->assertEquals($data['latitud'], $complejo->latitud);
        $this->assertEquals($data['longitud'], $complejo->longitud);
        $this->assertEquals($data['habilitado'], $complejo->habilitado);
        $this->assertNull($complejo->id);
    }

    /**
     * Test that a Complejo instance can be instantiated.
     */
    public function test_complejo_can_be_instantiated(): void
    {
        $complejo = new Complejo();
        $this->assertInstanceOf(Complejo::class, $complejo);
    }

    /**
     * Test that 'latitud' attribute is correctly cast to float.
     */
    public function test_latitud_attribute_is_float(): void
    {
        // Crear el modelo a través del factory para asegurar que los casts se apliquen correctamente
        $complejo = Complejo::factory()->make();

        $complejo->latitud = "-38.555";
        $this->assertIsFloat($complejo->latitud);
        $this->assertEquals(-38.555, $complejo->latitud);

        $complejo->latitud = -38.12345678; // Más decimales de los que soporta el DB
        $this->assertIsFloat($complejo->latitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-38.1234568, $complejo->latitud, 0.0000001);
    }

    /**
     * Test that 'longitud' attribute is correctly cast to float.
     */
    public function test_longitud_attribute_is_float(): void
    {
        // Crear el modelo a través del factory para asegurar que los casts se apliquen correctamente
        $complejo = Complejo::factory()->make();

        $complejo->longitud = "-58.777";
        $this->assertIsFloat($complejo->longitud);
        $this->assertEquals(-58.777, $complejo->longitud);

        $complejo->longitud = -58.98765432; // Más decimales de los que soporta el DB
        $this->assertIsFloat($complejo->longitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-58.9876543, $complejo->longitud, 0.0000001);
    }

    /**
     * Test that 'habilitado' attribute is correctly cast to boolean.
     */
    public function test_habilitado_attribute_is_boolean(): void
    {
        // Crear el modelo y luego refrescarlo para asegurar que los casts se apliquen correctamente
        $complejo = Complejo::factory()->create(['habilitado' => true])->refresh();

        // Test true values
        $complejo->habilitado = '1';
        $this->assertTrue($complejo->habilitado);
        $complejo->habilitado = 'true';
        $this->assertTrue($complejo->habilitado);
        $complejo->habilitado = 1;
        $this->assertTrue($complejo->habilitado);

        // Test false values
        $complejo->habilitado = '0';
        $this->assertFalse($complejo->habilitado);
        $complejo->habilitado = 'false';
        $this->assertFalse($complejo->habilitado);
        $complejo->habilitado = 0;
        $this->assertFalse($complejo->habilitado);
        $complejo->habilitado = null;
        $this->assertFalse($complejo->habilitado); // Laravel casts null to false for boolean
        $complejo->habilitado = '';
        $this->assertFalse($complejo->habilitado);
    }
}

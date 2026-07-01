<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Evento;
use Carbon\Carbon;

class EventoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an Evento instance can be created and has correct attributes.
     */
    public function test_evento_can_be_created_with_attributes(): void
    {
        $data = [
            'nombre' => 'Concierto de Prueba',
            'direccion' => 'Plaza Central',
            'descripcion' => 'Un evento musical para toda la familia.',
            'fecha' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'), // Fecha futura
            'lugar' => 'Anfiteatro Municipal',
            'imagen' => 'http://imagen.com/concierto.jpg',
            'destacado' => true,
            'latitud' => -38.555,
            'longitud' => -58.777,
            'habilitado' => true,
        ];

        $evento = new Evento();
        $evento->fill($data);

        $this->assertEquals($data['nombre'], $evento->nombre);
        $this->assertEquals($data['direccion'], $evento->direccion);
        $this->assertEquals($data['descripcion'], $evento->descripcion);
        $this->assertEquals(Carbon::parse($data['fecha'])->format('Y-m-d H:i:s'), $evento->fecha->format('Y-m-d H:i:s'));
        $this->assertEquals($data['lugar'], $evento->lugar);
        $this->assertEquals($data['imagen'], $evento->imagen);
        $this->assertEquals($data['destacado'], $evento->destacado);
        $this->assertEquals($data['latitud'], $evento->latitud);
        $this->assertEquals($data['longitud'], $evento->longitud);
        $this->assertEquals($data['habilitado'], $evento->habilitado);
        $this->assertNull($evento->id);
    }

    /**
     * Test that an Evento instance can be instantiated.
     */
    public function test_evento_can_be_instantiated(): void
    {
        $evento = new Evento();
        $this->assertInstanceOf(Evento::class, $evento);
    }

    /**
     * Test that 'destacado' attribute is correctly cast to boolean.
     */
    public function test_destacado_attribute_is_boolean(): void
    {
        // Crear el modelo y luego refrescarlo para asegurar que los casts se apliquen correctamente
        $evento = Evento::factory()->create(['destacado' => true])->refresh();

        // Test true values
        $evento->destacado = '1';
        $this->assertTrue($evento->destacado);
        $evento->destacado = 'true';
        $this->assertTrue($evento->destacado);
        $evento->destacado = 1;
        $this->assertTrue($evento->destacado);

        // Test false values
        $evento->destacado = '0';
        $this->assertFalse($evento->destacado);
        $evento->destacado = 'false';
        $this->assertFalse($evento->destacado);
        $evento->destacado = 0;
        $this->assertFalse($evento->destacado);
        $evento->destacado = null;
        $this->assertFalse($evento->destacado); // Laravel casts null to false for boolean
        $evento->destacado = '';
        $this->assertFalse($evento->destacado);
    }

    /**
     * Test that 'fecha' attribute is correctly cast to Carbon instance.
     */
    public function test_fecha_attribute_is_carbon_instance(): void
    {
        $evento = new Evento();
        $dateString = '2025-01-01 10:00:00';
        $evento->fecha = $dateString;

        $this->assertInstanceOf(Carbon::class, $evento->fecha);
        $this->assertEquals(Carbon::parse($dateString), $evento->fecha);
    }

    /**
     * Test that 'latitud' attribute is correctly cast to float.
     */
    public function test_latitud_attribute_is_float(): void
    {
        // Crear el modelo a través del factory para asegurar que los casts se apliquen correctamente
        $evento = Evento::factory()->make();

        $evento->latitud = "-38.555";
        $this->assertIsFloat($evento->latitud);
        $this->assertEquals(-38.555, $evento->latitud);

        $evento->latitud = -38.12345678; // Más decimales de los que soporta el DB
        $this->assertIsFloat($evento->latitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-38.1234568, $evento->latitud, 0.0000001);
    }

    /**
     * Test that 'longitud' attribute is correctly cast to float.
     */
    public function test_longitud_attribute_is_float(): void
    {
        // Crear el modelo a través del factory para asegurar que los casts se apliquen correctamente
        $evento = Evento::factory()->make();

        $evento->longitud = "-58.777";
        $this->assertIsFloat($evento->longitud);
        $this->assertEquals(-58.777, $evento->longitud);

        $evento->longitud = -58.98765432; // Más decimales de los que soporta el DB
        $this->assertIsFloat($evento->longitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-58.9876543, $evento->longitud, 0.0000001);
    }

    /**
     * Test that 'habilitado' attribute is correctly cast to boolean.
     */
    public function test_habilitado_attribute_is_boolean(): void
    {
        // Crear el modelo y luego refrescarlo para asegurar que los casts se apliquen correctamente
        $evento = Evento::factory()->create(['habilitado' => true])->refresh();

        // Test true values
        $evento->habilitado = '1';
        $this->assertTrue($evento->habilitado);
        $evento->habilitado = 'true';
        $this->assertTrue($evento->habilitado);
        $evento->habilitado = 1;
        $this->assertTrue($evento->habilitado);

        // Test false values
        $evento->habilitado = '0';
        $this->assertFalse($evento->habilitado);
        $evento->habilitado = 'false';
        $this->assertFalse($evento->habilitado);
        $evento->habilitado = 0;
        $this->assertFalse($evento->habilitado);
        $evento->habilitado = null;
        $this->assertFalse($evento->habilitado); // Laravel casts null to false for boolean
        $evento->habilitado = '';
        $this->assertFalse($evento->habilitado);
    }
}

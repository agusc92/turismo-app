<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Alojamiento;

class AlojamientoTest extends TestCase
{
    /**
     * Test that an Alojamiento instance can be created and has correct attributes.
     */
    public function test_alojamiento_can_be_created_with_attributes(): void
    {
        $data = [
            'nombre' => 'Hotel de Prueba',
            'direccion' => 'Calle Falsa 456',
            'telefono' => '987654321',
            'redesSociales' => 'http://instagram.com/hotel',
            'paginaWeb' => 'http://hotelprueba.com',
            'mail' => 'reservas@hotelprueba.com',
            'mascotas' => true,
            'periodoApertura' => 'Todo el año',
            'tipo' => 'Hotel',
            'imagen' => 'http://imagen.com/hotel.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
        ];

        $alojamiento = new Alojamiento();
        $alojamiento->fill($data);

        $this->assertEquals($data['nombre'], $alojamiento->nombre);
        $this->assertEquals($data['direccion'], $alojamiento->direccion);
        $this->assertEquals($data['telefono'], $alojamiento->telefono);
        $this->assertEquals($data['redesSociales'], $alojamiento->redesSociales);
        $this->assertEquals($data['paginaWeb'], $alojamiento->paginaWeb);
        $this->assertEquals($data['mail'], $alojamiento->mail);
        $this->assertEquals($data['mascotas'], $alojamiento->mascotas);
        $this->assertEquals($data['periodoApertura'], $alojamiento->periodoApertura);
        $this->assertEquals($data['tipo'], $alojamiento->tipo);
        $this->assertEquals($data['imagen'], $alojamiento->imagen);
        $this->assertEquals($data['latitud'], $alojamiento->latitud);
        $this->assertEquals($data['longitud'], $alojamiento->longitud);
        $this->assertNull($alojamiento->id);
    }

    /**
     * Test that an Alojamiento instance can be instantiated.
     */
    public function test_alojamiento_can_be_instantiated(): void
    {
        $alojamiento = new Alojamiento();

        $this->assertInstanceOf(Alojamiento::class, $alojamiento);
    }

    /**
     * Test that 'mascotas' attribute is correctly cast to boolean.
     */
    public function test_mascotas_attribute_is_boolean(): void
    {
        $alojamiento = new Alojamiento();

        // Test true values
        $alojamiento->mascotas = '1';
        $this->assertTrue($alojamiento->mascotas);

        $alojamiento->mascotas = 'true';
        $this->assertTrue($alojamiento->mascotas);

        $alojamiento->mascotas = 1;
        $this->assertTrue($alojamiento->mascotas);

        // Test false values
        $alojamiento->mascotas = '0';
        $this->assertFalse($alojamiento->mascotas);

        $alojamiento->mascotas = 0;
        $this->assertFalse($alojamiento->mascotas);

        $alojamiento->mascotas = null;
        $this->assertNull($alojamiento->mascotas);

        $alojamiento->mascotas = '';
        $this->assertFalse($alojamiento->mascotas);
    }

    /**
     * Test that 'latitud' attribute is correctly cast to float.
     */
    public function test_latitud_attribute_is_float(): void
    {
        $alojamiento = new Alojamiento();

        $alojamiento->latitud = "-38.555";
        $this->assertIsFloat($alojamiento->latitud);
        $this->assertEquals(-38.555, $alojamiento->latitud);

        $alojamiento->latitud = -38.12345678;
        $this->assertIsFloat($alojamiento->latitud);

        $this->assertEqualsWithDelta(-38.1234568, $alojamiento->latitud, 0.0000001);
    }

    /**
     * Test that 'longitud' attribute is correctly cast to float.
     */
    public function test_longitud_attribute_is_float(): void
    {
        $alojamiento = new Alojamiento();

        $alojamiento->longitud = "-58.777";
        $this->assertIsFloat($alojamiento->longitud);
        $this->assertEquals(-58.777, $alojamiento->longitud);

        $alojamiento->longitud = -58.98765432;
        $this->assertIsFloat($alojamiento->longitud);

        $this->assertEqualsWithDelta(-58.9876543, $alojamiento->longitud, 0.0000001);
    }
}

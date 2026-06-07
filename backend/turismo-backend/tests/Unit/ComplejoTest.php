<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Complejo;

class ComplejoTest extends TestCase
{
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
        $complejo = new Complejo();

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
        $complejo = new Complejo();

        $complejo->longitud = "-58.777";
        $this->assertIsFloat($complejo->longitud);
        $this->assertEquals(-58.777, $complejo->longitud);

        $complejo->longitud = -58.98765432; // Más decimales de los que soporta el DB
        $this->assertIsFloat($complejo->longitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-58.9876543, $complejo->longitud, 0.0000001);
    }
}

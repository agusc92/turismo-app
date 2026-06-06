<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
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
}

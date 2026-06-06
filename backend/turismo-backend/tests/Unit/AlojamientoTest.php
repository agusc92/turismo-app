<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
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
}

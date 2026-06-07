<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Gastronomico;

class GastronomicoTest extends TestCase
{
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
}

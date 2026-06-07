<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Evento;
use Carbon\Carbon;

class EventoTest extends TestCase
{

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
}

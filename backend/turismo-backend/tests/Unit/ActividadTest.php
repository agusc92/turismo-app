<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Actividad;
use App\Models\Tipo;

class ActividadTest extends TestCase
{
    /**
     * Test that an Actividad instance can be created and has correct attributes.
     */
    public function test_actividad_can_be_created_with_attributes(): void
    {
        $data = [
            'nombre' => 'Clase de Surf',
            'direccion' => 'Playa Grande',
            'descripcion' => 'Clases para todos los niveles de surf.',
            'redes_sociales' => 'http://instagram.com/surfescuela',
            'web' => 'http://surfescuela.com',
            'mail' => 'info@surfescuela.com',
            'telefono' => '1122334455',
            'imagen' => 'http://imagen.com/surf.jpg',
            'tipo_id' => 1,
            'dias_y_horarios' => 'Lunes a Viernes de 10:00 a 18:00',
        ];

        $actividad = new Actividad();
        $actividad->fill($data);

        $this->assertEquals($data['nombre'], $actividad->nombre);
        $this->assertEquals($data['direccion'], $actividad->direccion);
        $this->assertEquals($data['descripcion'], $actividad->descripcion);
        $this->assertEquals($data['redes_sociales'], $actividad->redes_sociales);
        $this->assertEquals($data['web'], $actividad->web);
        $this->assertEquals($data['mail'], $actividad->mail);
        $this->assertEquals($data['telefono'], $actividad->telefono);
        $this->assertEquals($data['imagen'], $actividad->imagen);
        $this->assertEquals($data['tipo_id'], $actividad->tipo_id);
        $this->assertEquals($data['dias_y_horarios'], $actividad->dias_y_horarios);
        $this->assertNull($actividad->id);
    }

    /**
     * Test that an Actividad instance can be instantiated.
     */
    public function test_actividad_can_be_instantiated(): void
    {
        $actividad = new Actividad();
        $this->assertInstanceOf(Actividad::class, $actividad);
    }
}

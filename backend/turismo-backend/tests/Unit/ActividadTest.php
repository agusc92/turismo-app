<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Actividad;
use App\Models\Tipo;

class ActividadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an Actividad instance can be created and has correct attributes.
     */
    public function test_actividad_can_be_created_with_attributes(): void
    {
        // Crear un tipo para asociar
        $tipo = Tipo::factory()->create();

        $data = [
            'nombre' => 'Clase de Surf',
            'direccion' => 'Playa Grande',
            'descripcion' => 'Clases para todos los niveles de surf.',
            'redes_sociales' => 'http://instagram.com/surfescuela',
            'web' => 'http://surfescuela.com',
            'mail' => 'info@surfescuela.com',
            'telefono' => '1122334455',
            'imagen' => 'http://imagen.com/surf.jpg',
            'latitud' => -38.555,
            'longitud' => -58.777,
            'tipo_id' => $tipo->id, // Usar el ID del tipo creado
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
        $this->assertEquals($data['latitud'], $actividad->latitud);
        $this->assertEquals($data['longitud'], $actividad->longitud);
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

    /**
     * Test that 'latitud' attribute is correctly cast to float.
     */
    public function test_latitud_attribute_is_float(): void
    {
        $actividad = new Actividad();

        $actividad->latitud = "-38.555";
        $this->assertIsFloat($actividad->latitud);
        $this->assertEquals(-38.555, $actividad->latitud);

        $actividad->latitud = -38.12345678; // Más decimales de los que soporta el DB
        $this->assertIsFloat($actividad->latitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-38.1234568, $actividad->latitud, 0.0000001);
    }

    /**
     * Test that 'longitud' attribute is correctly cast to float.
     */
    public function test_longitud_attribute_is_float(): void
    {
        $actividad = new Actividad();

        $actividad->longitud = "-58.777";
        $this->assertIsFloat($actividad->longitud);
        $this->assertEquals(-58.777, $actividad->longitud);

        $actividad->longitud = -58.98765432; // Más decimales de los que soporta el DB
        $this->assertIsFloat($actividad->longitud);
        // Laravel y la DB pueden redondear, así que comparamos con un delta
        $this->assertEqualsWithDelta(-58.9876543, $actividad->longitud, 0.0000001);
    }

    /**
     * Test that the 'tipo' relationship works correctly.
     */
    public function test_tipo_relationship_works(): void
    {
        $tipo = Tipo::factory()->create(['tipo' => 'Deporte']);
        $actividad = Actividad::factory()->create(['tipo_id' => $tipo->id]);

        $actividad->load('tipo');

        $this->assertInstanceOf(Tipo::class, $actividad->tipo);
        $this->assertEquals($tipo->id, $actividad->tipo->id);
        $this->assertEquals('Deporte', $actividad->tipo->tipo);
    }
}

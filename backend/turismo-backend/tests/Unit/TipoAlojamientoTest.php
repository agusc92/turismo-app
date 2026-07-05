<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TipoAlojamiento;
use App\Models\Alojamiento;

class TipoAlojamientoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a TipoAlojamiento instance can be created and has correct attributes.
     */
    public function test_tipo_alojamiento_can_be_created_with_attributes(): void
    {
        $data = [
            'tipo' => 'Hotel',
        ];

        $tipoAlojamiento = new TipoAlojamiento();
        $tipoAlojamiento->fill($data);

        $this->assertEquals($data['tipo'], $tipoAlojamiento->tipo);
        $this->assertNull($tipoAlojamiento->id);
    }

    /**
     * Test that a TipoAlojamiento instance can be instantiated.
     */
    public function test_tipo_alojamiento_can_be_instantiated(): void
    {
        $tipoAlojamiento = new TipoAlojamiento();
        $this->assertInstanceOf(TipoAlojamiento::class, $tipoAlojamiento);
    }

    /**
     * Test that the 'alojamientos' relationship works correctly.
     */
    public function test_alojamientos_relationship_works(): void
    {
        $tipoAlojamiento = TipoAlojamiento::factory()->create();
        $alojamiento1 = Alojamiento::factory()->withoutTiposAlojamiento()->create();
        $alojamiento2 = Alojamiento::factory()->withoutTiposAlojamiento()->create();

        $tipoAlojamiento->alojamientos()->attach([$alojamiento1->id, $alojamiento2->id]);

        $tipoAlojamiento->load('alojamientos');

        $this->assertCount(2, $tipoAlojamiento->alojamientos);
        $this->assertTrue($tipoAlojamiento->alojamientos->contains($alojamiento1));
        $this->assertTrue($tipoAlojamiento->alojamientos->contains($alojamiento2));
        $this->assertInstanceOf(Alojamiento::class, $tipoAlojamiento->alojamientos->first());
    }
}

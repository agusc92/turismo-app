<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\TipoGastronomico;
use App\Models\Gastronomico;

class TipoGastronomicoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a TipoGastronomico instance can be created and has correct attributes.
     */
    public function test_tipo_gastronomico_can_be_created_with_attributes(): void
    {
        $data = [
            'tipo' => 'Restaurante',
        ];

        $tipoGastronomico = new TipoGastronomico();
        $tipoGastronomico->fill($data);

        $this->assertEquals($data['tipo'], $tipoGastronomico->tipo);
        $this->assertNull($tipoGastronomico->id);
    }

    /**
     * Test that a TipoGastronomico instance can be instantiated.
     */
    public function test_tipo_gastronomico_can_be_instantiated(): void
    {
        $tipoGastronomico = new TipoGastronomico();
        $this->assertInstanceOf(TipoGastronomico::class, $tipoGastronomico);
    }

    /**
     * Test that the 'gastronomicos' relationship works correctly.
     */
    public function test_gastronomicos_relationship_works(): void
    {
        $tipoGastronomico = TipoGastronomico::factory()->create();
        $gastronomico1 = Gastronomico::factory()->create();
        $gastronomico2 = Gastronomico::factory()->create();
        $tipoGastronomico->gastronomicos()->attach([$gastronomico1->id, $gastronomico2->id]);

        $tipoGastronomico->load('gastronomicos');

        $this->assertCount(2, $tipoGastronomico->gastronomicos);
        $this->assertTrue($tipoGastronomico->gastronomicos->contains($gastronomico1));
        $this->assertTrue($tipoGastronomico->gastronomicos->contains($gastronomico2));
        $this->assertInstanceOf(Gastronomico::class, $tipoGastronomico->gastronomicos->first());
    }
}

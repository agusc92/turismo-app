<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\TipoGastronomico;

class TipoGastronomicoTest extends TestCase
{
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
}

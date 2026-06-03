<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Tipo;

class TipoTest extends TestCase
{
    /**
     * Test that a Tipo instance can be created and has correct attributes.
     */
    public function test_tipo_can_be_created_with_attributes(): void
    {
        $data = [
            'tipo' => 'Aventura',
        ];

        $tipo = new Tipo();
        $tipo->fill($data);

        $this->assertEquals($data['tipo'], $tipo->tipo);
        $this->assertNull($tipo->id);
    }

    /**
     * Test that a Tipo instance can be instantiated.
     */
    public function test_tipo_can_be_instantiated(): void
    {
        $tipo = new Tipo();
        $this->assertInstanceOf(Tipo::class, $tipo);
    }
}

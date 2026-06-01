<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\InfoUsuario;

class InfoUsuarioTest extends TestCase
{
    /**
     * Test that an InfoUsuario instance can be created and has correct attributes.
     */
    public function test_info_usuario_can_be_created_with_attributes(): void
    {
        $data = [
            'ciudad' => 'Necochea',
            'edad' => 30,
            'estadia' => '1 semana',
            'integrantes' => 2,
            'user_id' => 1,
        ];

        $infoUsuario = new InfoUsuario();
        $infoUsuario->fill($data);

        $this->assertEquals($data['ciudad'], $infoUsuario->ciudad);
        $this->assertEquals($data['edad'], $infoUsuario->edad);
        $this->assertEquals($data['estadia'], $infoUsuario->estadia);
        $this->assertEquals($data['integrantes'], $infoUsuario->integrantes);
        $this->assertEquals($data['user_id'], $infoUsuario->user_id);
        $this->assertNull($infoUsuario->id);
    }

    /**
     * Test that an InfoUsuario instance can be instantiated.
     */
    public function test_info_usuario_can_be_instantiated(): void
    {
        $infoUsuario = new InfoUsuario();
        $this->assertInstanceOf(InfoUsuario::class, $infoUsuario);
    }
}

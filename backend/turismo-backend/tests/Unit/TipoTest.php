<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tipo;
use App\Models\Actividad;
use App\Models\InfoUsuario;
use App\Models\User;

class TipoTest extends TestCase
{
    use RefreshDatabase;

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

    /**
     * Test that the 'actividades' relationship works correctly.
     */
    public function test_actividades_relationship_works(): void
    {
        $tipo = Tipo::factory()->create();
        $actividad1 = Actividad::factory()->create(['tipo_id' => $tipo->id]);
        $actividad2 = Actividad::factory()->create(['tipo_id' => $tipo->id]);

        $tipo->load('actividades');

        $this->assertCount(2, $tipo->actividades);
        $this->assertTrue($tipo->actividades->contains($actividad1));
        $this->assertTrue($tipo->actividades->contains($actividad2));
        $this->assertInstanceOf(Actividad::class, $tipo->actividades->first());
    }

    /**
     * Test that the 'usuarios' relationship works correctly.
     */
    public function test_usuarios_relationship_works(): void
    {
        $tipo = Tipo::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $infoUsuario1 = InfoUsuario::factory()->create(['user_id' => $user1->id]);
        $infoUsuario2 = InfoUsuario::factory()->create(['user_id' => $user2->id]);

        $tipo->usuarios()->attach([$infoUsuario1->id, $infoUsuario2->id]);

        $tipo->load('usuarios');

        $this->assertCount(2, $tipo->usuarios);
        $this->assertTrue($tipo->usuarios->contains($infoUsuario1));
        $this->assertTrue($tipo->usuarios->contains($infoUsuario2));
        $this->assertInstanceOf(InfoUsuario::class, $tipo->usuarios->first());
    }
}

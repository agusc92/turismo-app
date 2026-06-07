<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\InfoUsuario;
use App\Models\User;
use App\Models\Tipo;

class InfoUsuarioTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an InfoUsuario instance can be created and has correct attributes.
     */
    public function test_info_usuario_can_be_created_with_attributes(): void
    {
        // Crear un usuario para asociar
        $user = User::factory()->create();

        $data = [
            'ciudad' => 'Necochea',
            'edad' => 30,
            'estadia' => '1 semana',
            'integrantes' => 2,
            'user_id' => $user->id, // Usar el ID del usuario creado
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

    /**
     * Test that the 'user' relationship works correctly.
     */
    public function test_user_relationship_works(): void
    {
        $user = User::factory()->create();
        $infoUsuario = InfoUsuario::factory()->create(['user_id' => $user->id]);

        $infoUsuario->load('user');

        $this->assertInstanceOf(User::class, $infoUsuario->user);
        $this->assertEquals($user->id, $infoUsuario->user->id);
        $this->assertEquals($user->name, $infoUsuario->user->name);
    }

    /**
     * Test that the 'intereses' relationship works correctly.
     */
    public function test_intereses_relationship_works(): void
    {
        $user = User::factory()->create();
        $infoUsuario = InfoUsuario::factory()->create(['user_id' => $user->id]); // Asegurar user_id

        $tipo1 = Tipo::factory()->create(['tipo' => 'Aventura']);
        $tipo2 = Tipo::factory()->create(['tipo' => 'Relax']);
        $infoUsuario->intereses()->attach([$tipo1->id, $tipo2->id]);

        $infoUsuario->load('intereses');

        $this->assertCount(2, $infoUsuario->intereses);
        $this->assertTrue($infoUsuario->intereses->contains($tipo1));
        $this->assertTrue($infoUsuario->intereses->contains($tipo2));
        $this->assertInstanceOf(Tipo::class, $infoUsuario->intereses->first());
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\InfoUsuario;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a User instance can be created and has correct attributes.
     */
    public function test_user_can_be_created_with_attributes(): void
    {
        $plainPassword = 'password123';
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => $plainPassword,
            'rol' => 'turista',
        ];

        $user = new User();
        $user->fill($data);

        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['rol'], $user->rol);

        // Verificar que la contraseña se ha hasheado correctamente
        $this->assertTrue(Hash::check($plainPassword, $user->password));

        $this->assertNull($user->id);
    }

    /**
     * Test that a User instance can be instantiated.
     */
    public function test_user_can_be_instantiated(): void
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Test that 'email_verified_at' attribute is correctly cast to Carbon instance.
     */
    public function test_email_verified_at_attribute_is_carbon_instance(): void
    {
        $user = new User();
        $dateTimeString = Carbon::now()->format('Y-m-d H:i:s');
        $user->email_verified_at = $dateTimeString;

        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);
        $this->assertEquals(Carbon::parse($dateTimeString), $user->email_verified_at);
    }

    /**
     * Test that 'password' attribute is hashed when set.
     */
    public function test_password_attribute_is_hashed(): void
    {
        $user = new User();
        $plainPassword = 'secretpassword';
        $user->password = $plainPassword;

        // Al asignar, el cast 'hashed' debería actuar
        $this->assertNotEquals($plainPassword, $user->password);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    /**
     * Test that the 'infoUsuario' relationship works correctly.
     */
    public function test_info_usuario_relationship_works(): void
    {
        $user = User::factory()->create();
        $infoUsuario = InfoUsuario::factory()->create(['user_id' => $user->id]);

        $user->load('infoUsuario');

        $this->assertInstanceOf(InfoUsuario::class, $user->infoUsuario);
        $this->assertEquals($infoUsuario->id, $user->infoUsuario->id);
        $this->assertEquals($user->id, $user->infoUsuario->user_id);
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
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
}

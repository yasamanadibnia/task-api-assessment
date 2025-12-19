<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_user_can_register()
    {
        $password = 'secret123';
        $data = [
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->post(route('auth.register'), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);
    }

    public function test_user_can_login()
    {
        $password = 'secret123';

        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertSuccessful();
    }
}

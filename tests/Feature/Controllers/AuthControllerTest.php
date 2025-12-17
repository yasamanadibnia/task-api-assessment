<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthControllerTest extends TestCase
{

    #[Test]
    public function can_register_user()
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

    #[Test]
    public function can_login_user()
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

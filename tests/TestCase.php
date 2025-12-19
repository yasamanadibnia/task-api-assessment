<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh'); // re-create tables in your sqlite testing db
    }

    protected function loginUser()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        return $user;
    }
}

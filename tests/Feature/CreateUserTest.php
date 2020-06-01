<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User auth test
     *
     * @return void
     */
    public function testCreateUserTest()
    {
        $this->artisan('user:create', ['email' => 'admin@example.com', '--admin' => true])
            ->expectsQuestion('Enter name for new user', 'Admin User')
            ->expectsQuestion('Enter password for new user', 'password123')
            ->expectsQuestion('Confirm password for new user', 'password123');
    }
}

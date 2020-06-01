<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use SampleAdminSeeder;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User auth test
     *
     * @return void
     */
    public function testUserAuthTest()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('GET', '/api/user');

        $response->assertStatus(401);
    }

    /**
     * login test
     *
     * @return void
     */
    public function testInvalidLoginTest()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/auth/login', [
            'email' => 'nonuser@example.com',
            'password' => 'hello_admin'
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'error' => "invalid_credentials"
        ]);
    }

    /**
     * login test
     *
     * @return void
     */
    public function testValidLoginTest()
    {
        $this->seed(SampleAdminSeeder::class);
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/auth/login', [
            'email' => 'sample.admin@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token', 'token_type', 'expires_in'
        ]);
    }
}

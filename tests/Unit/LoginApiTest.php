<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginApiTest extends TestCase
{
    public function test_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'access_token',
                'token_type',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ],
            'message',
        ]);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'These credentials do not match our records.',
        ]);
    }

    public function test_login_with_incomplete_data()
    {
        $response = $this->postJson('/api/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }
}

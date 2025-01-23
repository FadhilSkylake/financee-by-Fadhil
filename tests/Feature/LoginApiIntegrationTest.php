<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginApiIntegrationTest extends TestCase
{
    public function test_successful_login()
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
                'user',
            ],
            'message',
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'User logged in successfully',
        ]);

        // Pastikan token dikembalikan
        $this->assertNotNull($response->json('data.access_token'));
    }

    /**
     * Test login gagal dengan kredensial yang salah.
     */
    public function test_failed_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Periksa respons gagal
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'These credentials do not match our records.',
        ]);
    }
}

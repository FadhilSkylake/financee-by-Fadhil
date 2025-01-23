<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTransactionIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_transaction_successful()
    {
        $user = User::factory()->create();

        $data = [
            'amount' => 1000,
            'category' => 'Food',
            'type' => 'expense',
            'date' => '2025-01-23',
            'description' => 'Lunch payment',
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/transactions', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'user_id',
                'amount',
                'category',
                'type',
                'transaction_date',
                'description',
                'created_at',
                'updated_at',
            ],
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'category' => $data['category'],
        ]);
    }

    public function test_store_transaction_unauthenticated()
    {
        $data = [
            'amount' => 1000,
            'category' => 'Food',
            'type' => 'expense',
            'date' => '2025-01-23',
            'description' => 'Lunch payment',
        ];

        $response = $this->postJson('/api/transactions', $data);

        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Anda Belum Login',
            'code' => 'token_not_found',
        ]);
    }

    public function test_store_transaction_validation_error()
    {
        $user = User::factory()->create();

        $data = [
            'amount' => -500,
            'category' => '',
            'type' => 'invalid_type',
            'date' => 'invalid_date',
            'description' => str_repeat('A', 501),
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/transactions', $data);

        $response->assertStatus(400);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Input tidak lengkap atau tidak valid',
        ]);

        $this->assertDatabaseCount('transactions', 0);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MonthlyTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthorized access to monthly transactions API.
     */
    public function test_unauthorized_access()
    {
        $response = $this->postJson('/api/transactions/monthly', [
            'month' => 1,
            'year' => 2025,
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Test validation errors for missing or invalid input.
     */
    public function test_validation_errors()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/transactions/monthly', [
                'month' => 'invalid', // Invalid month
                'year' => 1999, // Below the minimum year
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'Input tidak lengkap atau tidak valid',
                'errors' => [
                    'month' => ['The month must be an integer.'],
                    'year' => ['The year must be at least 2000.'],
                ],
            ]);
    }

    /**
     * Test successful retrieval of monthly transactions.
     */
    public function test_successful_monthly_transactions()
    {
        $user = User::factory()->create();

        // Create transactions
        Transaction::factory()->create([
            'user_id' => $user->id,
            'amount' => 1000,
            'type' => 'income',
            'category' => 'Salary',
            'transaction_date' => now()->startOfMonth(),
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'amount' => 500,
            'type' => 'expense',
            'category' => 'Food',
            'transaction_date' => now()->startOfMonth(),
        ]);

        Transaction::factory()->create([
            'user_id' => $user->id,
            'amount' => 300,
            'type' => 'expense',
            'category' => 'Transport',
            'transaction_date' => now()->startOfMonth(),
        ]);

        // Call the API
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/transactions/monthly', [
                'month' => now()->month,
                'year' => now()->year,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'month' => now()->format('F Y'),
                    'total_income' => 1000,
                    'total_expense' => 800,
                    'balance' => 200,
                    'categories' => [
                        [
                            'category' => 'Food',
                            'amount' => 500,
                            'percentage' => 62.5,
                        ],
                        [
                            'category' => 'Transport',
                            'amount' => 300,
                            'percentage' => 37.5,
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Test when there are no transactions for the specified month/year.
     */
    public function test_no_transactions_for_month()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/transactions/monthly', [
                'month' => now()->addMonth()->month, // Future month
                'year' => now()->year,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'month' => now()->addMonth()->format('F Y'),
                    'total_income' => 0,
                    'total_expense' => 0,
                    'balance' => 0,
                    'categories' => [],
                ],
            ]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Budget;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BudgetUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthorized access to update budget.
     */
    public function test_unauthorized_access()
    {
        $response = $this->postJson('/api/budget/update', [
            'category' => 'Food',
            'budget' => 5000,
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ]);
    }

    /**
     * Test validation errors for update budget.
     */
    public function test_validation_errors()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/budget/update', [
                'category' => '', // Empty category
                'budget' => -100, // Invalid budget
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => 'Input tidak lengkap atau tidak valid',
                'errors' => [
                    'category' => ['The category field is required.'],
                    'budget' => ['The budget must be at least 0.'],
                ],
            ]);
    }

    /**
     * Test successful creation of a new budget.
     */
    public function test_successful_budget_creation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/budget/update', [
                'category' => 'Food',
                'budget' => 5000,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Anggaran berhasil diperbarui',
            ]);

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'category' => 'Food',
            'budget' => 5000,
        ]);
    }

    /**
     * Test successful update of an existing budget.
     */
    public function test_successful_budget_update()
    {
        $user = User::factory()->create();

        // Create initial budget
        $budget = Budget::factory()->create([
            'user_id' => $user->id,
            'category' => 'Food',
            'budget' => 3000,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/budget/update', [
                'category' => 'Food',
                'budget' => 6000,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Anggaran berhasil diperbarui',
            ]);

        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'category' => 'Food',
            'budget' => 6000, // Budget updated
        ]);
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;

class StoreTransactionTest extends TestCase
{
    public function test_store_transaction_valid_data()
    {
        $user = User::factory()->make();
        $data = [
            'amount' => 1000,
            'category' => 'Food',
            'type' => 'expense',
            'date' => '2025-01-23',
            'description' => 'Lunch payment',
        ];

        $validator = Validator::make($data, [
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $this->assertFalse($validator->fails());

        $transaction = new Transaction(array_merge($data, ['user_id' => $user->id]));
        $this->assertEquals($data['amount'], $transaction->amount);
        $this->assertEquals($data['category'], $transaction->category);
        $this->assertEquals($data['type'], $transaction->type);
    }

    public function test_store_transaction_invalid_data()
    {
        $data = [
            'amount' => -500,
            'category' => '',
            'type' => 'invalid_type',
            'date' => 'invalid_date',
            'description' => str_repeat('A', 501),
        ];

        $validator = Validator::make($data, [
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('amount', $validator->errors()->toArray());
        $this->assertArrayHasKey('category', $validator->errors()->toArray());
        $this->assertArrayHasKey('type', $validator->errors()->toArray());
        $this->assertArrayHasKey('date', $validator->errors()->toArray());
    }
}

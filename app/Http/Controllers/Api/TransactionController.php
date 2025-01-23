<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda Belum Login',
                'code' => 'token_not_found'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input tidak lengkap atau tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'amount' => $request->amount,
            'category' => $request->category,
            'type' => $request->type,
            'transaction_date' => $request->date,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil dicatat',
            'data' => $transaction
        ], 201);
    }
}

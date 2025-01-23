<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BudgetController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:100',
            'budget' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input tidak lengkap atau tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        $budget = Budget::create([
            'user_id' => $request->user()->id,
            'category' => $request->category,
            'budget' => $request->budget
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Anggaran berhasil dibuat',
            'data' => $budget
        ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:100',
            'budget' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input tidak lengkap atau tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        Budget::updateOrCreate([
            'user_id' => $request->user()->id,
            'category' => $request->category,
            'budget' => $request->budget
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Anggaran berhasil diperbarui'
        ], 200);
    }
}

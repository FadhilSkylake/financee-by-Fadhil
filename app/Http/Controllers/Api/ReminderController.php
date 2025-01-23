<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReminderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input tidak lengkap atau tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        $reminder = Reminder::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'is_completed' => false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengingat berhasil ditambahkan',
            'data' => $reminder
        ], 201);
    }
}

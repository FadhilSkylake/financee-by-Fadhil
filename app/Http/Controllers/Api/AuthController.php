<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || ! Hash::check($request->password, $user->password)) {

            return response([
                'success' => false,
                'data' => null,
                'message' => 'These credentials do not match our records.',
            ], 422);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ],
            'message' => 'User logged in successfully',
        ]);
    }

    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        try {
            // Create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // Create token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses mendaftarkan pengguna',
                'data' => [
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 201);
        } catch (\Exception $e) {
            // Check if the error is due to duplicate email
            if ($e->getCode() == 23000) { // SQL duplicate entry error code
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email sudah digunakan'
                ], 400);
            }

            // Handle other errors
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server'
            ], 500);
        }
    }
}

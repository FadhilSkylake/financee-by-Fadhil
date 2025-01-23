<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    return response()->json(['message' => 'Logged out successfully']);
})->middleware('auth:sanctum')->name('logout');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/profile', function () {
    return view('profile');
})->name('profile');
Route::get('/', function () {
    return view('welcome');
});

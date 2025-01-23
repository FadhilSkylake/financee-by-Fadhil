<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/login', function () {
    return view('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/register', function () {
    return view('register');
});

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    return response()->json(['message' => 'Logged out successfully']);
})->name('logout');



Route::get('/profile', function () {
    return view('profile');
})->name('profile');
Route::get('/', function () {
    return view('welcome');
});

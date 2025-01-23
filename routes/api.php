<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\ReminderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TransactionController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/users/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(TransactionController::class)->group(function () {
        Route::post('/transactions', 'store')->name('transactions-store');
    });

    Route::controller(ReportController::class)->group(function () {
        Route::get('/reports/monthly', 'monthly')->name('reports-monthly');
    });

    Route::controller(BudgetController::class)->group(function () {
        Route::put('/budgets', 'update')->name('budgets-update');
        Route::post('/budgets/store', 'store')->name('budgets-store');
    });

    Route::controller(ReminderController::class)->group(function () {
        Route::post('/reminders', 'store')->name('reminders-store');
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

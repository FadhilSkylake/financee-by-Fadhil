<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Input tidak lengkap atau tidak valid',
                'errors' => $validator->errors()
            ], 400);
        }

        // Mengambil transaksi sesuai bulan dan tahun
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->whereYear('transaction_date', $request->year)
            ->whereMonth('transaction_date', $request->month)
            ->get();

        // Menghitung total pemasukan dan pengeluaran
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        // Menghitung persentase per kategori untuk pengeluaran
        $expenseTransactions = $transactions->where('type', 'expense');
        $categories = [];

        foreach ($expenseTransactions->groupBy('category') as $category => $items) {
            $categoryAmount = $items->sum('amount');
            $percentage = $totalExpense > 0 ? ($categoryAmount / $totalExpense * 100) : 0;

            $categories[] = [
                'category' => $category,
                'amount' => $categoryAmount,
                'percentage' => round($percentage, 2)
            ];
        }

        // Format bulan dan tahun
        $monthYear = Carbon::createFromDate($request->year, $request->month, 1)
            ->format('F Y');

        return response()->json([
            'status' => 'success',
            'data' => [
                'month' => $monthYear,
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'balance' => $totalIncome - $totalExpense,
                'categories' => $categories
            ]
        ], 200);
    }
}

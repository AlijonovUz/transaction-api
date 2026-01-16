<?php

namespace App\Http\Controllers;

use App\Filters\TransactionFilter;
use App\Models\Transaction;
use App\Support\Response;

class StatsController extends Controller
{
    public function summary(TransactionFilter $filter)
    {
        $user_id = auth()->id();

        if (auth()->user()->isAdmin() && request()->filled('user_id')) {
            $user_id = request()->user_id;
        }

        $query = Transaction::where('user_id', $user_id);
        $query = $filter->apply($query);

        $income = (clone $query)->where('type', 'income')->sum('amount');
        $expense = (clone $query)->where('type', 'expense')->sum('amount');

        return Response::success([
            'total_income' => $income,
            'total_expense' => $expense,
            'balance' => $income - $expense
        ]);
    }
}

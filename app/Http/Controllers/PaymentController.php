<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $transactions = $user->transactions()->latest()->get();
        $wallet = $user->wallet;

        return view('dashboard', compact('transactions', 'wallet'));
    }

    public function showDepositForm()
    {
        return view('deposit');
    }

    public function deposit(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|string|unique:transactions,order_id',
                'amount' => 'required|numeric|min:0.01',
            ]);

            $user = auth()->user();

            $transaction = $user->transactions()->create([
                'order_id' => $request->order_id,
                'amount' => $request->amount,
                'type' => 'deposit',
                'status' => 1,
            ]);

            $user->wallet->increment('balance', $request->amount);

            return response()->json([
                'status' => 1,
                'message' => 'Deposit successful',
                'transaction' => $transaction,
                'wallet_balance' => $user->wallet->balance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 2,
                'message' => 'Error during deposit: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showWithdrawalForm()
    {
        return view('withdrawal');
    }

    public function withdraw(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
            ]);

            $user = auth()->user();
            $wallet = $user->wallet;

            if ($wallet->balance < $request->amount) {
                return response()->json([
                    'status' => 2,
                    'message' => 'Insufficient balance',
                ], 400);
            }

            $transaction = $user->transactions()->create([
                'order_id' => 'ORD' . now()->timestamp,
                'amount' => $request->amount,
                'type' => 'withdrawal',
                'status' => 1,
            ]);

            $wallet->decrement('balance', $request->amount);

            return response()->json([
                'status' => 1,
                'message' => 'Withdrawal successful',
                'transaction' => $transaction,
                'wallet_balance' => $wallet->balance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 2,
                'message' => 'Error during withdrawal: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function transactionsPage()
    {
        $user = auth()->user();
        $transactions = $user->transactions()->latest()->get();
        $wallet = $user->wallet;
        return view('transactions', compact('transactions', 'wallet'));
    }
}

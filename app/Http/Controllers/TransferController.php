<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessTransfer;
use App\Models\AdminFee;
use App\Models\Transaction;
use Pusher\Pusher;

class TransferController extends Controller
{
    public function showTransferForm()
    {
        return view('transactions');
    }

    public function transfer(Request $request)
    {
        try {
            $request->validate([
                'receiver_email' => 'required|email',
                'amount' => 'required|numeric|min:0.01',
            ]);

            $sender = auth()->user();

            $receiver = User::where('email', $request->receiver_email)->first();

            if (!$receiver) {
                return response()->json(['status' => 2, 'message' => 'Receiver not found'], 404);
            }

            if ($sender->email == $receiver->email) {
                return response()->json([
                    'status' => 2,
                    'message' => 'Cannot send to the recipients account'
                ], 404);
            }

            $admin_fee = AdminFee::first();
            $admin_fee_amount = $admin_fee ? $admin_fee->fee : 0;

            $total_amount = $request->amount + $admin_fee_amount;
            if ($sender->wallet->balance < $total_amount) {
                return response()->json(['status' => 2, 'message' => 'Insufficient balance'], 400);
            }

            $transfer = Transfer::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $request->amount,
                'admin_fee' => $admin_fee_amount,
                'status' => 'pending',
            ]);

            $senderTransaction = Transaction::create([
                'user_id' => $sender->id,
                'order_id' => 'ORD' . now()->timestamp . '-W',
                'amount' => $total_amount,
                'type' => 'transfer',
                'status' => 1,
            ]);

            $receiverTransaction = Transaction::create([
                'user_id' => $receiver->id,
                'order_id' => 'ORD' . now()->timestamp . '-D',
                'amount' => $request->amount,
                'type' => 'deposit',
                'status' => 1,
            ]);

            ProcessTransfer::dispatch($transfer);

            return response()->json([
                'status' => 1,
                'message' => 'Transfer request received',
                'transaction' => $senderTransaction,
                'receiver_transaction' => $receiverTransaction,
                'wallet_balance' => $sender->wallet->balance - $total_amount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 2,
                'message' => 'Error during transfer: ' . $e->getMessage(),
            ], 500);
        }
    }
}

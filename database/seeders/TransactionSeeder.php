<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        foreach (User::all() as $user) {
            for ($i = 1; $i <= 5; $i++) {
                Transaction::create([
                    'user_id' => $user->id,
                    'order_id' => 'ORD' . now()->timestamp . $i,
                    'amount' => rand(100, 1000),
                    'type' => 'deposit',
                    'status' => 1,
                ]);
            }
        }
    }
}

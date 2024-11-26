<?php

namespace Database\Seeders;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run()
    {
        foreach (User::all() as $user) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => rand(10000, 50000),
            ]);
        }
    }
}

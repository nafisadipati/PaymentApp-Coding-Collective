<?php

namespace App\Jobs;

use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class ProcessTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transfer;

    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }

    public function handle()
    {

        $senderWallet = $this->transfer->sender->wallet;
        $receiverWallet = $this->transfer->receiver->wallet;

        $senderWallet->decrement('balance', $this->transfer->amount + $this->transfer->admin_fee);
        $receiverWallet->increment('balance', $this->transfer->amount);

        $this->transfer->update(['status' => 'completed']);
    }
}

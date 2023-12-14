<?php

namespace App\Jobs;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MatureJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        Wallet::mtdr()->whereDoesntHave('matures', function($query) {
            $query->upcoming();
        })
        ->get()
        ->each(function($wallet){
            $wallet->matures()->create([
                'mature_date' => generate_wallet_upcoming_mature_date($wallet),
                'expected_amount' => $wallet->AproxRoiAmountPerMature(),
                'account_id' => $wallet->account_id,
                'user_id' => $wallet->holder_id,
            ]);
        });
    }
}

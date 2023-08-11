<?php

namespace Database\Seeders;

use App\Enums\DebtTypeEnum;
use App\Models\Account;
use App\Models\Debt;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DebtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Debt::create([
            'type' => DebtTypeEnum::PAYABLE->value,
            'name' => fake()->name,
            'description' => 'Borrowed money from John Doe for buying a new car',
            'amount' => 800000,
            'start_at' => now()->addYears(2),
            'account_id' => Account::first()->id,
            'wallet_id' => Wallet::first()->id,
            'color' => '#22b3e0',
        ]);

        Debt::create([
            'type' => DebtTypeEnum::RECEIVABLE->value,
            'name' => fake()->name,
            'description' => 'Received money from John Doe for buying a new phone',
            'amount' => 100000,
            'start_at' => now()->addYears(2),
            'account_id' => Account::first()->id,
            'wallet_id' => Wallet::first()->id,
            'color' => '#22b3e0',
        ]);

    }
}

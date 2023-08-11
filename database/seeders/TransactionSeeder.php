<?php

namespace Database\Seeders;

use App\Enums\SpendTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Enums\WalletTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = Account::all();

//        Transaction::create([
//            'wallet_id' => 1,
//            'payable_type' => User::class,
//            'payable_id' => 1,
//            'account_id'  => '01H7BGYYATNB2QY0KBCY60T4VH',
//            'amount'      => rand(500, 10000),
//            'type'        => TransactionTypeEnum::DEPOSIT->value,
//            'category_id' => 1,
//            'confirmed' => true,
//            'uuid' => fake()->uuid,
//            'happened_at' => now()->subDays(1),
//        ]);

        foreach ($accounts as $account) {
            $wallets = $account->wallets;
            $categories = $account->categories;

            foreach ($wallets->where('type', WalletTypeEnum::GENERAL->value) as $wallet) {
                $incomeCategories = $categories->where('type',
                    SpendTypeEnum::INCOME->value);
                $expenseCategories = $categories->where('type',
                    SpendTypeEnum::EXPENSE->value);

                foreach (range(0, 20) as $i) {
                    Transaction::insert([
                        [
                            'wallet_id' => $wallet->id,
                            'payable_type' => User::class,
                            'payable_id' => 1,
                            'account_id'  => $account->id,
                            'amount'      => rand(500, 10000),
                            'type'        => TransactionTypeEnum::DEPOSIT->value,
                            'category_id' => $incomeCategories->random()->id,
                            'confirmed' => true,
                            'uuid' => fake()->uuid,
                            'happened_at' => now()->subDays($i),
                        ],
                        [
                            'wallet_id' => $wallet->id,
                            'payable_type' => User::class,
                            'payable_id' => 1,
                            'account_id'  => $account->id,
                            'amount'      => rand(500, 10000),
                            'type'        => TransactionTypeEnum::DEPOSIT->value,
                            'category_id' => $incomeCategories->random()->id,
                            'confirmed' => true,
                            'uuid' => fake()->uuid,
                            'happened_at' => now()->subDays($i),
                        ],
                        [
                            'wallet_id' => $wallet->id,
                            'payable_type' => User::class,
                            'payable_id' => 1,
                            'account_id'  => $account->id,
                            'amount'      => -1 * rand(500, 1000),
                            'type'        => TransactionTypeEnum::WITHDRAW->value,
                            'category_id' => $expenseCategories->random()->id,
                            'confirmed' => true,
                            'uuid' => fake()->uuid,
                            'happened_at' => now()->subDays($i),
                        ],
                        [
                            'wallet_id' => $wallet->id,
                            'payable_type' => User::class,
                            'payable_id' => 1,
                            'account_id'  => $account->id,
                            'amount'      => -1 * rand(500, 1000),
                            'type'        => TransactionTypeEnum::WITHDRAW->value,
                            'category_id' => $expenseCategories->random()->id,
                            'confirmed' => true,
                            'uuid' => fake()->uuid,
                            'happened_at' => now()->subDays($i),
                        ],
                        [
                            'wallet_id' => $wallet->id,
                            'payable_type' => User::class,
                            'payable_id' => 1,
                            'account_id'  => $account->id,
                            'amount'      => -1 * rand(500, 1000),
                            'type'        => TransactionTypeEnum::WITHDRAW->value,
                            'category_id' => $expenseCategories->random()->id,
                            'confirmed' => true,
                            'uuid' => fake()->uuid,
                            'happened_at' => now()->subDays($i),
                        ],
                    ]);
                }
                $wallet->refreshBalance();
            }
        }
    }
}

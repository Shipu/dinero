<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AccountSeeder::class,
            WalletSeeder::class,
            CategorySeeder::class,
            TransactionSeeder::class,
            GoalSeeder::class,
            DebtSeeder::class,
        ]);
    }
}

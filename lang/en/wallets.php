<?php

use App\Enums\WalletTypeEnum;

return [
    'title' => 'Wallets',
    'title_singular' => 'Wallet',
    'fields' => [
        'name' => 'Name',
        'type' => 'Type',
        'balance' => 'Balance',
        'initial_balance' => 'Initial Balance',
        'credit_limit' => 'Credit Limit',
        'total_due' => 'Currently Total Due',
        'currency_code' => 'Currency',
        'description' => 'Description',
        'statement_day_of_month' => 'Statement Day of Month',
        'payment_due_day_of_month' => 'Payment Due Day of Month',
        'icon' => 'Icon',
        'color' => 'Color',
        'exclude' => [
            'title' => 'Exclude',
            'help_text' => 'Ignore this balance of this wallet on the total balance',
        ]
    ],
    'types' => [
        WalletTypeEnum::GENERAL->value => 'General',
        WalletTypeEnum::CREDIT_CARD->value => 'Credit Card',
    ]
];

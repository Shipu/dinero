<?php

use App\Enums\WalletTypeEnum;

return [
    'title' => 'Wallets',
    'title_singular' => 'Wallet',
    'actions' => [
        'refresh_balance' => 'Refresh Balance',
    ],
    'notifications' => [
        'balance_refreshed' => 'Balance Refreshed',
    ],
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
        'wallet_document' => 'Wallet Document',
        'start_date' => 'Start Date',
        'return_period_of_month' => 'Return Period of Month',
        'exclude' => [
            'title' => 'Exclude',
            'help_text' => 'Ignore this balance of this wallet on the total balance',
        ]
    ],
    'types' => [
        WalletTypeEnum::GENERAL->value => 'General',
        WalletTypeEnum::CREDIT_CARD->value => 'Credit Card',
        WalletTypeEnum::BANK_ACCOUNT->value => 'Bank Account',
        WalletTypeEnum::MOBILE_BANKING_ACCOUNT->value => 'Mobile Banking Account',
        WalletTypeEnum::DIGITAL_WALLET->value => 'Digital Wallet',
        WalletTypeEnum::FREELANCER_ACCOUNT->value => 'Freelancer Account',
        WalletTypeEnum::SAVING_ACCOUNT->value => 'Saving Account',
        WalletTypeEnum::MUDARABA_SCHEME_ACCOUNT->value => 'Mudaraba Scheme Account',
        WalletTypeEnum::CASH->value => 'Cash',
        WalletTypeEnum::INVESTMENT->value => 'Investment',
        WalletTypeEnum::LOAN->value => 'Loan',
        WalletTypeEnum::OTHER->value => 'Other',
        
    ]
];

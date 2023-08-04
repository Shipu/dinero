<?php

use App\Enums\DebtTypeEnum;

return [
    'title' => 'Debts',
    'title_singular' => 'Debt',
    'actions' => [
        'debt_collection' => 'Debt Collection',
    ],
    'fields' => [
        'name' => 'Name',
        'type' => 'Type',
        'amount' => 'Amount',
        'description' => 'Description',
        'start_at' => 'Start',
        'color' => 'Color',
        'wallet' => 'Wallet',
        'debt' => 'Debt',
        'from_wallet' => 'From Wallet',
    ],
    'types' => [
        DebtTypeEnum::PAYABLE->value => 'Payable',
        DebtTypeEnum::RECEIVABLE->value => 'Receivable',
    ]
];

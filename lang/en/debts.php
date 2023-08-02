<?php

use App\Enums\DebtTypeEnum;

return [
    'title' => 'Debts',
    'title_singular' => 'Debt',
    'fields' => [
        'name' => 'Name',
        'type' => 'Type',
        'amount' => 'Amount',
        'description' => 'Description',
        'start_at' => 'Start',
        'color' => 'Color',
        'wallet' => 'Wallet',
    ],
    'types' => [
        DebtTypeEnum::PAYABLE->value => 'Payable',
        DebtTypeEnum::RECEIVABLE->value => 'Receivable',
    ]
];

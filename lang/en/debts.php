<?php

use App\Enums\DebtActionTypeEnum;
use App\Enums\DebtTypeEnum;

return [
    'title' => 'Debts',
    'title_singular' => 'Debt',
    'actions' => [
        'debt_transaction' => 'Debt Transaction',
    ],
    'fields' => [
        'name' => 'Name',
        'type' => 'Type',
        'amount' => 'Amount',
        'description' => 'Description',
        'start_at' => 'Start',
        'color' => 'Color',
        'wallet' => 'Wallet',
        'initial_wallet' => 'Initial Wallet',
        'happened_at' => 'Happened',
        'debt' => 'Debt',
        'action_type' => 'Action Type',
        'from_wallet' => 'From Wallet',
    ],
    'types' => [
        DebtTypeEnum::PAYABLE->value => 'Payable',
        DebtTypeEnum::RECEIVABLE->value => 'Receivable',
    ],
    'action_types' => [
        DebtTypeEnum::RECEIVABLE->value => [
            DebtActionTypeEnum::DEBT_COLLECTION->value => 'Debt Collection',
            DebtActionTypeEnum::LOAN_INCREASE->value   => 'Loan Increase',
            DebtActionTypeEnum::LOAN_INTEREST->value   => 'Interest',
        ],
        DebtTypeEnum::PAYABLE->value => [
            DebtActionTypeEnum::REPAYMENT->value     => 'Repayment',
            DebtActionTypeEnum::DEBT_INCREASE->value => 'Debt Increase',
            DebtActionTypeEnum::DEBT_INTEREST->value => 'Interest',
        ],
    ]
];

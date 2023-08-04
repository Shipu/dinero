<?php

use App\Enums\SpendTypeEnum;
use App\Enums\VisibilityStatusEnum;

return [
    'title' => 'Goals',
    'title_singular' => 'Goal',
    'actions' => [
        'deposit' => 'Deposit',
        'withdraw' => 'Withdraw',
    ],
    'fields' => [
        'name' => 'Name',
        'amount' => 'Amount',
        'target_date' => 'Target Date',
        'currency_code' => 'Currency Code',
        'color' => 'Color',
        'wallet' => 'Wallet',
        'from_wallet' => 'From Wallet',
        'to_wallet' => 'To Wallet',
        'goal' => 'Goal',
        'target_amount' => 'Target Amount',
        'balance' => 'Balance',
        'created_from' => 'Created From',
        'created_until' => 'Created Until',
    ],
];

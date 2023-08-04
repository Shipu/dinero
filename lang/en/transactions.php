<?php

use App\Enums\SpendTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Enums\VisibilityStatusEnum;

return [
    'title' => 'Transactions',
    'title_singular' => 'Transaction',
    'fields' => [
        'amount' => 'Amount',
        'confirmed' => 'Confirmed',
        'category' => 'Category',
        'account' => 'Account',
        'happened_at' => 'Happened At',
        'description' => 'Description',
        'type' => 'Type',
        'wallet' => 'Wallet',
        'from_wallet' => 'From Wallet',
        'to_wallet' => 'To Wallet',
        'note' => 'Note',
        'attachment' => 'Attachment',
    ],
    'types' => [
        TransactionTypeEnum::DEPOSIT->value   => [
            'id' => TransactionTypeEnum::DEPOSIT->value,
            'label' => 'Deposit',
            'description' => 'Deposit to your wallet',
        ],
        TransactionTypeEnum::WITHDRAW->value  => [
            'id' => TransactionTypeEnum::WITHDRAW->value,
            'label' => 'Withdraw',
            'description' => 'Withdraw from your wallet',
        ],
        TransactionTypeEnum::TRANSFER->value  => [
            'id' => TransactionTypeEnum::TRANSFER->value,
            'label' => 'Transfer',
            'description' => 'Transfer between your wallets',
        ],
        TransactionTypeEnum::PAYMENT->value  => [
            'id' => TransactionTypeEnum::PAYMENT->value,
            'label' => 'Payment',
            'description' => 'Payment to one wallet to another wallet',
        ],
    ]
];

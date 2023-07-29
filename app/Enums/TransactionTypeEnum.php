<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case TRANSFER = 'transfer';

    public static function toArray(): array
    {
        return array_map(function ($value) {
            return $value->value;
        }, self::cases());
    }
}
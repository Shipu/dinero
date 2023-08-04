<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case TRANSFER = 'transfer';
    case PAYMENT = 'payment';

    public static function toArray(): array
    {
        return array_map(function ($value) {
            return $value->value;
        }, self::cases());
    }

    public static function toArrayExcept($except): array
    {
        return array_filter(array_map(function ($value) use ($except) {
            if (in_array($value->value, $except)) {
                return null;
            }
            return $value->value;
        }, self::cases()));
    }
}
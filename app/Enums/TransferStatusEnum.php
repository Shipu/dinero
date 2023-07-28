<?php

namespace App\Enums;

enum TransferStatusEnum: string
{
    case EXCHANGE = 'exchange';
    case TRANSFER = 'transfer';
    case REFUND = 'refund';
    case GIFT = 'gift';
    case PAID = 'paid';

    public static function toArray(): array
    {
        return array_map(function ($value) {
            return $value->value;
        }, self::cases());
    }
}
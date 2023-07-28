<?php

namespace App\Enums;

enum SpendTypeEnum: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public static function toArray(): array
    {
        // enum self::cases() to array
        return array_map(function ($value) {
            return $value->value;
        }, self::cases());
    }
}
<?php

namespace App\Enums;

enum DebtActionTypeEnum: string
{
    case REPAYMENT = 'repayment'; // withdraw
    case DEBT_INCREASE = 'debt_increase'; // deposit
    case DEBT_INTEREST = 'debt_interest'; // deposit without wallet bind
    case LOAN_INCREASE = 'loan_increase'; // withdraw
    case DEBT_COLLECTION = 'debt_collection'; // deposit
    case LOAN_INTEREST = 'loan_interest'; // deposit without wallet bind

    public static function toArray(): array
    {
        return array_map(function ($value) {
            return $value->value;
        }, self::cases());
    }

    public static function toArrayExcept(array $except): array
    {
        return array_filter(array_map(function ($value) use ($except) {
            if (in_array($value->value, $except)) {
                return null;
            }
            return $value->value;
        }, self::cases()));
    }
}
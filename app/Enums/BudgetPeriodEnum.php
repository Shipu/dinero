<?php

namespace App\Enums;

enum BudgetPeriodEnum: string
{
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case YEARLY = 'yearly';

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
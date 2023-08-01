<?php

namespace App\Enums;

enum QuarterEnum: string
{
    case FIRST_MONTH = 'first_month';
    case SECOND_MONTH = 'second_month';
    case THIRD_MONTH = 'third_month';


    public static function toArray(): array
    {
        return array_map(function ($value) {
            return $value->value;
        }, self::cases());
    }

}
<?php

use App\Enums\MonthEnum;
use App\Enums\QuarterEnum;
use App\Enums\VisibilityStatusEnum;
use App\Enums\WeekdayEnum;

return [
    'visibility_statuses' => [
        VisibilityStatusEnum::ACTIVE->value   => 'Active',
        VisibilityStatusEnum::INACTIVE->value => 'Inactive',
    ],
    'weekdays' => [
        WeekdayEnum::SUNDAY->value    => 'Sunday',
        WeekdayEnum::MONDAY->value    => 'Monday',
        WeekdayEnum::TUESDAY->value   => 'Tuesday',
        WeekdayEnum::WEDNESDAY->value => 'Wednesday',
        WeekdayEnum::THURSDAY->value  => 'Thursday',
        WeekdayEnum::FRIDAY->value    => 'Friday',
        WeekdayEnum::SATURDAY->value  => 'Saturday',
    ],
    'months' => [
        MonthEnum::JANUARY->value   => 'January',
        MonthEnum::FEBRUARY->value  => 'February',
        MonthEnum::MARCH->value     => 'March',
        MonthEnum::APRIL->value     => 'April',
        MonthEnum::MAY->value       => 'May',
        MonthEnum::JUNE->value      => 'June',
        MonthEnum::JULY->value      => 'July',
        MonthEnum::AUGUST->value    => 'August',
        MonthEnum::SEPTEMBER->value => 'September',
        MonthEnum::OCTOBER->value   => 'October',
        MonthEnum::NOVEMBER->value  => 'November',
        MonthEnum::DECEMBER->value  => 'December',
    ],
    'quarter_months' => [
        QuarterEnum::FIRST_MONTH->value  => 'First Month',
        QuarterEnum::SECOND_MONTH->value => 'Second Month',
        QuarterEnum::THIRD_MONTH->value  => 'Third Month',
    ]
];
<?php

use App\Enums\BudgetPeriodEnum;
use App\Enums\SpendTypeEnum;
use App\Enums\VisibilityStatusEnum;

return [
    'title' => 'Budgets',
    'title_singular' => 'Budget',
    'fields' => [
        'name' => 'Name',
        'amount' => 'Amount',
        'actual_amount' => 'Actual Amount',
        'spend_amount' => 'Spend Amount',
        'period' => 'Period',
        'day_of_month' => 'Day of Month',
        'day_of_week' => 'Day of Week',
        'month_of_year' => 'Month of Year',
        'month_of_quarter' => 'Month of Quarter',
        'status' => 'Status',
        'color' => 'Color',
        'categories' => 'Categories',
        'recurrence' => 'Recurrence',
        'enabled' => 'Enabled ?',
        'enabled_help_text' => 'Show this budget on the dashboard or report',
    ],
    'periods' => [
        BudgetPeriodEnum::WEEKLY->value    => 'Weekly',
        BudgetPeriodEnum::MONTHLY->value   => 'Monthly',
        BudgetPeriodEnum::QUARTERLY->value => 'Quarterly',
        BudgetPeriodEnum::YEARLY->value    => 'Yearly',
    ],
];

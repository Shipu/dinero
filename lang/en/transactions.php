<?php

use App\Enums\SpendTypeEnum;
use App\Enums\VisibilityStatusEnum;

return [
    'title' => 'Transactions',
    'title_singular' => 'Transaction',
    'fields' => [
        'name' => 'Name',
        'type' => 'Type',
        'icon' => 'Icon',
        'color' => 'Color',
        'is_visible' => 'Is Visible?',
        'is_visible_help_text' => 'Ignore this category on the total balance and not showing on the transaction list',
    ],
    'types' => [
        SpendTypeEnum::INCOME->value   => [
            'id' => SpendTypeEnum::INCOME->value,
            'label' => 'Income',
            'description' => 'your income category',
        ],
        SpendTypeEnum::EXPENSE->value   => [
            'id' => SpendTypeEnum::EXPENSE->value,
            'label' => 'Expense',
            'description' => 'your expense category',
        ],
    ],
    'visibility_statuses' => [
        VisibilityStatusEnum::ACTIVE->value   => 'Active',
        VisibilityStatusEnum::INACTIVE->value => 'Inactive',
    ],
];

<?php

use App\Enums\SpendTypeEnum;

return [
    'title' => 'Categories',
    'title_singular' => 'Category',
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
    ]
];

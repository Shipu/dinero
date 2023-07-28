<?php

return [
    'currencies' => collect(countries())->mapWithKeys(function ($country) {
        try {
            $currency = currency($country['currency']);
            return [
                $country['currency'] => sprintf('%s - %s - %s (%s)',$country['name'], $currency->getCurrency(), $currency->getName(), $currency->getSymbol())
            ];
        } catch (\Exception $e) {
            return [null => null];
        }

    })->filter(),
    'month_ordinal_numbers' => collect(range(1, 31))->map(fn ($day) => sprintf('%s%s', $day, match ($day) {
        1 => 'st',
        2 => 'nd',
        3 => 'rd',
        default => 'th'
    })),
];
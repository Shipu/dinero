<?php

use Carbon\Carbon;
use App\Models\Wallet;
use Illuminate\Support\Collection;

function country_with_currency_and_symbol($state = null): Collection|string {
    $countries = collect(countries())->mapWithKeys(function ($country) {
        try {
            $currency = currency($country['currency']);
            return [
                $country['currency'] => sprintf('%s - %s - %s (%s)',$country['name'], $currency->getCurrency(), $currency->getName(), $currency->getSymbol())
            ];
        } catch (\Exception $e) {
            return [null => null];
        }
    })->filter();

    if($state) {
        return $countries->get($state);
    }

    return $countries;
}

function month_ordinal_numbers(): Collection
{
    return collect(range(1, 31))->map(fn ($day) => sprintf('%s%s', $day, match ($day) {
        1 => 'st',
        2 => 'nd',
        3 => 'rd',
        default => 'th'
    }));
}


function curency_money_format(float $amount, string $currency): string
{
    
    return $currency. ' ' .number_format($amount, 2);
}

function wallet_latest_mature_date(Wallet $wallet): Carbon{
    return Carbon::parse($wallet->latestMature()?->mature_date ? $wallet->latestMature()?->mature_date : $wallet->start_date);
}

function generate_wallet_upcoming_mature_date(Wallet $wallet){
    return wallet_latest_mature_date($wallet)->addMonths($wallet->return_period_of_month ?? 1);
}

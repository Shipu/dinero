<?php

namespace App\Filament\Widgets;

use App\Models\Mature;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MatureStats extends BaseWidget
{
    protected int | string | array $columnSpan = 4;

    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        for($i = -3; $i < 6; $i++){
            $month = Carbon::now()->addMonths($i)->startOfDay();
            $monthlyExpectedAmount = Mature::whereMonth('mature_date', $month->month)
                                                ->whereYear('mature_date', $month->year)
                                                ->sum('expected_amount');
            $monthlyActualAmount = Mature::whereMonth('mature_date', $month->month)
                                                ->whereYear('mature_date', $month->year)
                                                ->sum('actual_amount');

            $previousUnpaidAmount = Mature::whereDate('mature_date', '<=', $month->startOfMonth())
                                            ->where('is_paid', false)
                                            ->sum('expected_amount');

            $stats[] = Stat::make($month->format('M Y'), plain_money_format($monthlyExpectedAmount))
                            ->chart([7, 2, 10, 3, 15, 4, 17])
                            ->description('Paid: ' . plain_money_format($monthlyActualAmount). ' | Total Unpaid: ' . plain_money_format($previousUnpaidAmount))
                            ->color(
                                Carbon::now()->startOfDay()->greaterThan($month) ? 'info' :
                                    (
                                        Carbon::now()->startOfDay()->equalTo($month) ? 'success' : 'gray'
                                    )
                            );
        }
        return $stats;
    }
}

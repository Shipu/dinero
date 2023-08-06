<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Transaction;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class CategoryChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'categoryChart';

    protected static ?int $contentHeight = 300;

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Category Transactions';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $transactions = Transaction::with('category')->whereNotNull('category_id')->whereBetween('happened_at', [now()->subDays(10)->startOfDay(), now()->endOfDay()])
            ->get()
            ->groupBy(function ($item) {
                return $item->category_id;
            })
            ->map(function ($item) {
                return $item->sum('amount');
            });
        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],
            'series' => $transactions->values()->toArray(),
            'labels' => Category::whereIn('id', $transactions->keys())->pluck('name')->toArray(),
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TransactionChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'transactionChart';

    protected static ?int $contentHeight = 300;

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Transactions';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $transactions = Transaction::whereBetween('happened_at', [now()->subDays(10)->startOfDay(), now()->endOfDay()])
            ->get()
            ->groupBy([
                function ($item) {
                    return $item->happened_at->format('Y-m-d');
                },
                function ($item) {
                    return $item->type;
                }
            ])
            ->map(function ($item) {
                return $item->map(function ($item) {
                    return $item->sum('amount');
                });
            });
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 260,
                'parentHeightOffset' => 2,
                'stacked' => true,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Deposit',
                    'data' => $transactions->pluck('deposit')->toArray()
                ],
                [
                    'name' => 'Withdrawal',
                    'data' => $transactions->pluck('withdraw')->toArray()
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => false,
                    'columnWidth' => '50%',
                ],
            ],
            'dataLabels' => [
                'enabled' => false
            ],
            'legend' => [
                'show' => true,
                'horizontalAlign' => 'right',
                'position' => 'top',
                'fontFamily' => 'inherit',
                'markers' => [
                    'height' => 12,
                    'width' => 12,
                    'radius' => 12,
                    'offsetX' => -3,
                    'offsetY' => 2
                ],
                'itemMargin' => [
                    'horizontal' => 5
                ]
            ],
            'grid' => [
                'show' => false,

            ],
            'xaxis' => [
                'categories' => $transactions->keys()->map(function ($item) {
                    return Carbon::parse($item)->format('M d');
                })->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit'
                    ]
                ],
                'axisTicks' => [
                    'show' => false
                ],
                'axisBorder' => [
                    'show' => false
                ]
            ],
            'yaxis' => [
                'offsetX' => -16,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit'
                    ]
                ],
                'min' => -200,
                'max' => 300,
                'tickAmount' => 5,
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'dark',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.5,
                    'gradientToColors' => ['#d97706', '#c2410c'],
                    'opacityFrom' => 1,
                    'opacityTo' => 1,
                    'stops' => [0, 100],
                ],
            ],
            'stroke' => [
                'curve' => 'smooth',
                'width' => 1,
                'lineCap' => 'round'
            ],
            'colors' => ['#f59e0b', '#ea580c'],
        ];
    }
}

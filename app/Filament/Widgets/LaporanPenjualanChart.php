<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class LaporanPenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan 2024',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                    'borderColor' => 'rgb(244, 63, 94)',
                    'backgroundColor' => 'rgb(244, 63, 94)',
                    'hoverBackgroundColor' => 'rgb(244, 63, 94)',
                    'pointBackgroundColor' => 'rgb(244, 63, 94)',
                    'pointBorderColor' => 'rgb(244, 63, 94)',
                    'pointHoverBackgroundColor' => 'rgb(244, 63, 94)',
                ],
                [
                    'label' => 'Pendapatan 2023',
                    'data' => [3, 50, 3, 2, 41, 38, 95, 24, 25, 95, 70, 69],
                    'borderColor' => 'rgb(103, 35, 46)',
                    'backgroundColor' => 'rgb(103, 35, 46)',
                    'hoverBackgroundColor' => 'rgb(103, 35, 46)',
                    'pointBackgroundColor' => 'rgb(103, 35, 46)',
                    'pointBorderColor' => 'rgb(103, 35, 46)',
                    'pointHoverBackgroundColor' => 'rgb(103, 35, 46)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ] ;
    }

    protected function getType(): string
    {
        return 'line';
    }
}

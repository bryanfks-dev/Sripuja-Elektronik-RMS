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
                    'borderColor' => 'rgb(11, 119, 156)',
                    'backgroundColor' => 'rgb(11, 119, 156)',
                    'hoverBackgroundColor' => 'rgb(11, 119, 156)',
                    'pointBackgroundColor' => 'rgb(11, 119, 156)',
                    'pointBorderColor' => 'rgb(11, 119, 156)',
                    'pointHoverBackgroundColor' => 'rgb(2, 29, 156)',
                ],
                [
                    'label' => 'Pendapatan 2023',
                    'data' => [3, 50, 3, 2, 41, 38, 95, 24, 25, 95, 70, 69],
                    'borderColor' => 'rgb(1, 14, 200)',
                    'backgroundColor' => 'rgb(1, 14, 200)',
                    'hoverBackgroundColor' => 'rgb(1, 14, 200)',
                    'pointBackgroundColor' => 'rgb(1, 14, 200)',
                    'pointBorderColor' => 'rgb(1, 14, 200)',
                    'pointHoverBackgroundColor' => 'rgb(1, 14, 200)',
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

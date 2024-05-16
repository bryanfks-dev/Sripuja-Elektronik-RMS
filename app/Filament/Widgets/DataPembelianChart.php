<?php

namespace App\Filament\Widgets;

use App\Models\Pembelian;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DataPembelianChart extends ChartWidget
{
    protected static bool $isLazy = false;

    protected static ?string $heading = 'Grafik Laporan Pembelian';

    public ?string $filter = 'year';

    // change font size
    protected static ?int $fontSize = 10;

    protected int|string|array $columnSpan = 'full';

    protected string $activeFilter;

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $date = now();

        $data1 = collect();
        $data2 = collect();

        $query = Trend::query(Pembelian::join(
            'detail_pembelians',
            'detail_pembelians.pembelian_id',
            '=',
            'pembelians.id'
        )
            ->groupBy('created_at'));

        if ($activeFilter == 'year') {
            $data1 =
                $query->between(
                    start: Carbon::now()->startOfYear(),
                    end: Carbon::now()->endOfYear(),
                )
                    ->perMonth()
                    ->sum('sub_total');

            $data2 =
                $query->between(
                    start: Carbon::now()->subYear()->startOfYear(),
                    end: Carbon::now()->subYear()->endOfYear(),
                )
                    ->perMonth()
                    ->sum('sub_total');

            $label1 = Carbon::now()->year;
            $label2 = Carbon::now()->subYear()->year;
        } else if ($activeFilter == 'month') {
            $data1 =
                $query->between(
                    start: Carbon::now()->startOfMonth(),
                    end: Carbon::now()->endOfMonth(),
                )
                    ->perDay()
                    ->sum('sub_total');

            $data2 =
                $query->between(
                    start: Carbon::now()->subMonth()->startOfMonth(),
                    end: Carbon::now()->subMonth()->endOfMonth(),
                )
                    ->perDay()
                    ->sum('sub_total');

            $label1 = $date->format('F');
            $label2 = $date->subMonth()->format('F');
        } else if ($activeFilter == 'week') {
            $data1 =
                $query->between(
                    start: Carbon::now()->startOfWeek(),
                    end: Carbon::now()->endOfWeek(),
                )
                    ->perDay()
                    ->sum('sub_total');
            $data2 =
                $query->between(
                    start: Carbon::now()->subWeek()->startOfWeek(),
                    end: Carbon::now()->subWeek()->endOfWeek(),
                )
                    ->perDay()
                    ->sum('sub_total');

            $label1 = "Minggu Ini";
            $label2 = "Minggu Lalu";
        } else if ($activeFilter == 'today') {
            $data1 =
                $query->between(
                    start: Carbon::now()->startOfDay(),
                    end: Carbon::now()->endOfDay(),
                )
                    ->perHour()
                    ->sum('sub_total');
            $data2 =
                $query->between(
                    start: Carbon::now()->subDay()->startOfDay(),
                    end: Carbon::now()->subDay()->endOfDay(),
                )
                    ->perHour()
                    ->sum('sub_total');

            $label1 = "Hari Ini";
            $label2 = "Kemarin";

        } else if ($activeFilter == 'last_year') {
            $data1 =
                $query->between(
                    start: Carbon::now()->subYear()->startOfYear(),
                    end: Carbon::now()->subYear()->endOfYear(),
                )
                    ->perMonth()
                    ->sum('sub_total');

            $data2 =
                $query->between(
                    start: Carbon::now()->subYears(2)->startOfYear(),
                    end: Carbon::now()->subYears(2)->endOfYear(),
                )
                    ->perMonth()
                    ->sum('sub_total');

            $label1 = Carbon::now()->subYear()->year;
            $label2 = Carbon::now()->subYears(2)->year;
        }

        $total1 = $data1->sum(fn(TrendValue $value) => $value->aggregate);
        $total1Formatted = number_format($total1, 0, '.', '.');

        $total2 = $data2->sum(fn(TrendValue $value) => $value->aggregate);
        $total2Formatted = number_format($total2, 0, '.', '.');

        return [
            'datasets' => [
                [
                    'label' => $label1 . ' ( Rp ' . $total1Formatted . ' )',
                    'data' => $data1->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(244, 63, 94)',
                    'backgroundColor' => 'rgb(244, 63, 94)',
                    'hoverBackgroundColor' => 'rgb(244, 63, 94)',
                    'pointBackgroundColor' => 'rgb(244, 63, 94)',
                    'pointBorderColor' => 'rgb(244, 63, 94)',
                    'pointHoverBackgroundColor' => 'rgb(244, 63, 94)',
                    'borderWidth' => 4,
                ],
                [
                    'label' => $label2 . ' ( Rp ' . $total2Formatted . ' )',
                    'data' => $data2->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(64, 32, 65)',
                    'backgroundColor' => 'rgb(64, 32, 65)',
                    'hoverBackgroundColor' => 'rgb(64, 32, 65)',
                    'pointBackgroundColor' => 'rgb(64, 32, 65)',
                    'pointBorderColor' => 'rgb(64, 32, 65)',
                    'pointHoverBackgroundColor' => 'rgb(64, 32, 65)',
                    'borderWidth' => 4,
                ],
            ],
            'labels' => $data1->map(function (TrendValue $value) use ($activeFilter) {
                // Adjust date format based on the expected format from Trend
                if ($activeFilter == 'year' || $activeFilter == 'last_year') {
                    $date = Carbon::createFromFormat('Y-m', $value->date);

                    return $date->format('M');
                } else if ($activeFilter == 'month' || $activeFilter == 'week') {
                    $date = Carbon::createFromFormat('Y-m-d', $value->date);

                    return $activeFilter == 'month' ? $date->format('d') : $date->format('D');
                } else if ($activeFilter == 'today') {
                    $date = Carbon::createFromFormat('Y-m-d H:i', $value->date);

                    return $date->format('H:i');
                }
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari',
            'week' => 'Minggu',
            'month' => 'Bulan',
            'year' => 'Tahun',
            'last_year' => 'Tahun Lalu',
        ];
    }
}

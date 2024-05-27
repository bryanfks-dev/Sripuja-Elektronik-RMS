<?php

namespace App\Filament\Widgets;

use App\Models\Pembelian;
use App\Models\Penjualan;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class LabaChart extends ChartWidget
{
    protected static bool $isLazy = false;

    protected static ?string $heading = 'Grafik Laba';

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }

    public ?string $filter = 'year';

    // change font size
    protected static ?int $fontSize = 10;

    protected int|string|array $columnSpan = 'full';

    protected string $activeFilter;

    protected string $label;

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $date = now();

        $jual1 = collect();
        $jual2 = collect();

        $beli1 = collect();
        $beli2 = collect();

        $laba1 = collect();
        $laba2 = collect();

        $queryPenjualan = Trend::query(Penjualan::join(
            'detail_penjualans',
            'detail_penjualans.penjualan_id',
            '=',
            'penjualans.id'
        )
            ->groupBy('created_at'));

        $queryPembelian = Trend::query(Pembelian::join(
            'detail_pembelians',
            'detail_pembelians.pembelian_id',
            '=',
            'pembelians.id'
        )
            ->groupBy('created_at'));

        if ($activeFilter == 'year') {
            $dateStart1 = Carbon::now()->startOfYear();
            $dateEnd1 = Carbon::now()->endOfYear();

            $dateStart2 = Carbon::now()->subYear()->startOfYear();
            $dateEnd2 = Carbon::now()->subYear()->endOfYear();

            $jual1 =
                $queryPenjualan->between(
                    start: $dateStart1,
                    end: $dateEnd1,
                )
                    ->perMonth()->sum('sub_total');

            $beli1 =
                $queryPembelian->between(
                    start: $dateStart1,
                    end: $dateEnd1,
                )
                    ->perMonth()
                    ->sum('sub_total');

            $jual2 =
                $queryPenjualan->between(
                    start: $dateStart2,
                    end: $dateEnd2,
                )
                    ->perMonth()->sum('sub_total');

            $beli2 =
                $queryPembelian->between(
                    start: $dateStart2,
                    end: $dateEnd2,
                )
                    ->perMonth()
                    ->sum('sub_total');

            $label1 = Carbon::now()->year;
            $label2 = Carbon::now()->subYear()->year;
        } else if ($activeFilter == 'month') {
            $dateStart1 = Carbon::now()->startOfMonth();
            $dateEnd1 = Carbon::now()->endOfMonth();

            $dateStart2 = Carbon::now()->subMonth()->startOfMonth();
            $dateEnd2 = Carbon::now()->subMonth()->endOfMonth();

            $jual1 =
                $queryPenjualan->between(
                    start: $dateStart1,
                    end: $dateEnd1,
                )
                    ->perDay()
                    ->sum('sub_total');

            $beli1 =
                $queryPenjualan->between(
                    start: $dateStart1,
                    end: $dateEnd1,
                )
                    ->perDay()
                    ->sum('sub_total');

            $jual2 =
                $queryPenjualan->between(
                    start: $dateStart2,
                    end: $dateEnd2,
                )
                    ->perDay()
                    ->sum('sub_total');

            $beli2 =
                $queryPembelian->between(
                    start: $dateStart2,
                    end: $dateEnd2,
                )
                    ->perDay()
                    ->sum('sub_total');

            $label1 = $date->format('F');
            $label2 = $date->subMonth()->format('F');
        } else if ($activeFilter == 'week') {
            $dateStart1 = Carbon::now()->startOfWeek();
            $dateEnd1 = Carbon::now()->endOfWeek();

            $dateStart2 = Carbon::now()->subWeek()->startOfWeek();
            $dateEnd2 = Carbon::now()->subWeek()->endOfWeek();

            $jual1 =
                $queryPenjualan->between(
                    start: $dateStart1,
                    end: $dateEnd1,
                )
                    ->perDay()
                    ->sum('sub_total');

            $beli1 =
                $queryPembelian->between(
                    start: $dateStart1,
                    end: $dateEnd1,
                )
                    ->perDay()
                    ->sum('sub_total');

            $jual2 =
                $queryPenjualan->between(
                    start: $dateStart2,
                    end: $dateEnd2,
                )
                    ->perDay()
                    ->sum('sub_total');

            $beli2 =
                $queryPembelian->between(
                    start: $dateStart2,
                    end: $dateEnd2,
                )
                    ->perDay()
                    ->sum('sub_total');

            $label1 = "Minggu Ini";
            $label2 = "Minggu Lalu";
        } else if ($activeFilter == 'today') {
            $dateStart1 = Carbon::now()->startOfDay();
            $dateEnd1 = Carbon::now()->endOfDay();

            $dateStart2 = Carbon::now()->subDay()->startOfDay();
            $dateEnd2 = Carbon::now()->subDay()->endOfDay();

            $jual1 =
                $queryPenjualan->between(
                    start: $dateStart1,
                    end: $dateEnd1,
                )
                    ->perHour()
                    ->sum('sub_total');

            $beli1 =
                $queryPembelian->between(
                    start: $dateStart1,
                    end: $dateEnd1,
                )
                    ->perHour()
                    ->sum('sub_total');

            $jual2 =
                $queryPenjualan->between(
                    start: $dateStart2,
                    end: $dateEnd2,
                )
                    ->perHour()
                    ->sum('sub_total');

            $beli2 =
                $queryPembelian->between(
                    start: $dateStart2,
                    end: $dateEnd2,
                )
                ->perHour()
                ->sum('sub_total');

            $label1 = "Hari Ini";
            $label2 = "Kemarin";
        }

        $laba1 = $jual1->zip($beli1)->map(fn($values) => $values[0]->aggregate - $values[1]->aggregate);
        $laba2 = $jual2->zip($beli2)->map(fn($values) => $values[0]->aggregate - $values[1]->aggregate);

        $total1 = $laba1->sum(fn($value) => $value);
        $total1Formatted = number_format($total1, 0, '.', '.');

        $total2 = $laba2->sum(fn($value) => $value);
        $total2Formatted = number_format($total2, 0, '.', '.');

        return [
            'datasets' => [
                [
                    'label' => $label1 . ' ( Rp ' . $total1Formatted . ' )',
                    'data' => $laba1,
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
                    'data' => $laba2,
                    'borderColor' => 'rgb(64, 32, 65)',
                    'backgroundColor' => 'rgb(64, 32, 65)',
                    'hoverBackgroundColor' => 'rgb(64, 32, 65)',
                    'pointBackgroundColor' => 'rgb(64, 32, 65)',
                    'pointBorderColor' => 'rgb(64, 32, 65)',
                    'pointHoverBackgroundColor' => 'rgb(64, 32, 65)',
                    'borderWidth' => 4,
                ],
            ],
            'labels' => $jual1->map(function (TrendValue $value) use ($activeFilter) {
                // Adjust date format based on the expected format from Trend
                if ($activeFilter == 'year') {
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
        ];
    }
}

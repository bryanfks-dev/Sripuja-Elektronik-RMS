<?php

namespace App\Filament\Widgets;

use App\Models\Pembelian;
use App\Models\Penjualan;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class LaporanLabaChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Laba';

    public ?string $filter = 'year';

    // change font size
    protected static ?int $fontSize = 10;

    protected int | string | array $columnSpan = 'full';

    protected string $activeFilter;
    protected string $label;

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $date = Carbon::createFromFormat('Y-m-d', Carbon::now()->format('Y-m-d'));

        $jual1 = collect();
        $jual2 = collect();
        $beli1 = collect();
        $beli2 = collect();
        $laba1 = collect();
        $laba2 = collect();

        if ($activeFilter == 'year') {
            $jual1 = Trend::query(Penjualan::join('detail_penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id'))
                ->between(
                    start: $startOfYear,
                    end: $endOfYear,
                )
                ->perMonth()
                ->sum('sub_total');



            $jual2 = Trend::query(Penjualan::join('detail_penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id'))
                ->between(
                    start: Carbon::now()->subYear()->startOfYear(),
                    end: Carbon::now()->subYear()->endOfYear(),
                )
                ->perMonth()
                ->sum('sub_total');

                $beli1 = Trend::query(Pembelian::join('detail_pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id'))
                ->dateColumn('pembelians.tanggal_waktu')
                ->between(
                        start: $startOfYear,
                        end: $endOfYear,
                    )
                    ->perMonth()
                    ->sum('sub_total');



                $beli2 = Trend::query(Pembelian::join('detail_pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id'))
                    ->dateColumn('pembelians.tanggal_waktu')
                    ->between(
                        start: Carbon::now()->subYear()->startOfYear(),
                        end: Carbon::now()->subYear()->endOfYear(),
                    )
                    ->perMonth()
                    ->sum('sub_total');

            $laba1 = $jual1->zip($beli1)->map(fn ($values) => $values[0]->aggregate - $values[1]->aggregate);
            $laba2 = $jual2->zip($beli2)->map(fn ($values) => $values[0]->aggregate - $values[1]->aggregate);

            $total1 = $laba1->sum(fn ($value) => $value);
            $total1Formatted = number_format($total1, 0, '.', '.');
            $total2 = $laba2->sum(fn ($value) => $value);
            $total2Formatted = number_format($total2, 0, '.', '.');
            $label1 = Carbon::now()->year;
            $label2 = Carbon::now()->subYear()->year;
        } elseif ($activeFilter == 'month') {
            $jual1 = Trend::query(Penjualan::join('detail_penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id'))
            ->between(
                start: Carbon::now()->startOfMonth(),
                end: Carbon::now()->endOfMonth(),
            )
            ->perDay()
            ->sum('sub_total');
            $jual2 = Trend::query(Penjualan::join('detail_penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id'))
                ->between(
                    start: Carbon::now()->subMonth()->startOfMonth(),
                    end: Carbon::now()->subMonth()->endOfMonth(),
                )
                ->perDay()
                ->sum('sub_total');

                $beli1 = Trend::query(Pembelian::join('detail_pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id'))
                ->dateColumn('pembelians.tanggal_waktu')
                ->between(
                    start: Carbon::now()->startOfMonth(),
                    end: Carbon::now()->endOfMonth(),
                )
                ->perDay()
                ->sum('sub_total');
            $beli2 = Trend::query(Pembelian::join('detail_pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id'))
                ->dateColumn('pembelians.tanggal_waktu')
                ->between(
                    start: Carbon::now()->subMonth()->startOfMonth(),
                    end: Carbon::now()->subMonth()->endOfMonth(),
                )
                ->perDay()
                ->sum('sub_total');

            $laba1 = $jual1->zip($beli1)->map(fn ($values) => $values[0]->aggregate - $values[1]->aggregate);
            $laba2 = $jual2->zip($beli2)->map(fn ($values) => $values[0]->aggregate - $values[1]->aggregate);

            $total1 = $laba1->sum(fn ($value) => $value);
            $total1Formatted = number_format($total1, 0, '.', '.');
            $total2 = $laba2->sum(fn ($value) => $value);
            $total2Formatted = number_format($total2, 0, '.', '.');
            $label1 = $date->format('F');
            $label2 = $date->subMonth()->format('F');
        } elseif ($activeFilter == 'week') {

            $jual1 = Trend::query(Penjualan::join('detail_penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id'))
                ->between(
                    start: Carbon::now()->startOfWeek(),
                    end: Carbon::now()->endOfWeek(),
                )
                ->perDay()
                ->sum('sub_total');
            $jual2 = Trend::query(Penjualan::join('detail_penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id'))
                ->between(
                    start: Carbon::now()->subWeek()->startOfWeek(),
                    end: Carbon::now()->subWeek()->endOfWeek(),
                )
                ->perDay()
                ->sum('sub_total');

                $beli1 = Trend::query(Pembelian::join('detail_pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id'))
                ->dateColumn('pembelians.tanggal_waktu')
                    ->between(
                        start: Carbon::now()->startOfWeek(),
                        end: Carbon::now()->endOfWeek(),
                    )
                    ->perDay()
                    ->sum('sub_total');
                $beli2 = Trend::query(Pembelian::join('detail_pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id'))
                ->dateColumn('pembelians.tanggal_waktu')
                    ->between(
                        start: Carbon::now()->subWeek()->startOfWeek(),
                        end: Carbon::now()->subWeek()->endOfWeek(),
                    )
                    ->perDay()
                    ->sum('sub_total');

            $laba1 = $jual1->zip($beli1)->map(fn ($values) => $values[0]->aggregate - $values[1]->aggregate);
            $laba2 = $jual2->zip($beli2)->map(fn ($values) => $values[0]->aggregate - $values[1]->aggregate);

            $total1 = $laba1->sum(fn ($value) => $value);
            $total1Formatted = number_format($total1, 0, '.', '.');
            $total2 = $laba2->sum(fn ($value) => $value);
            $total2Formatted = number_format($total2, 0, '.', '.');
            $label1 = "Minggu Ini";
            $label2 = "Minggu Lalu";
        } elseif ($activeFilter == 'today') {
            $jual1 = Trend::query(Penjualan::join('detail_penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id'))
                ->between(
                    start: Carbon::now()->startOfDay(),
                    end: Carbon::now()->endOfDay(),
                )
                ->perHour()
                ->sum('sub_total');
            $jual2 = Trend::query(Penjualan::join('detail_penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id'))
                ->between(
                    start: Carbon::now()->subDay()->startOfDay(),
                    end: Carbon::now()->subDay()->endOfDay(),
                )
                ->perHour()
                ->sum('sub_total');

                $beli1 = Trend::query(Pembelian::join('detail_pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id'))
                ->dateColumn('pembelians.tanggal_waktu')
                    ->between(
                        start: Carbon::now()->startOfDay(),
                        end: Carbon::now()->endOfDay(),
                    )
                    ->perHour()
                    ->sum('sub_total');
                $beli2 = Trend::query(Pembelian::join('detail_pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id'))
                ->dateColumn('pembelians.tanggal_waktu')
                    ->between(
                        start: Carbon::now()->subDay()->startOfDay(),
                        end: Carbon::now()->subDay()->endOfDay(),
                    )
                    ->perHour()
                    ->sum('sub_total');

            $total1 = $laba1->sum(fn ($value) => $value);
            $total1Formatted = number_format($total1, 0, '.', '.');
            $total2 = $laba2->sum(fn ($value) => $value);
            $total2Formatted = number_format($total2, 0, '.', '.');
            $laba1 = $jual1->zip($beli1)->map(fn ($values) => $values[0]->aggregate - $values[1]->aggregate);
            $laba2 = $jual2->zip($beli2)->map(fn ($values) => $values[0]->aggregate - $values[1]->aggregate);

            $label1 = "Hari Ini";
            $label2 = "Hari Lalu";
        }

        return [
            'datasets' => [
                [
                    'label' => $label1 . ' ( Rp. ' . $total1Formatted . ' )',
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
                    'label' => $label2 . ' ( Rp. ' . $total2Formatted . ' )',
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
                } elseif ($activeFilter == 'month' || $activeFilter == 'week') {
                    $date = Carbon::createFromFormat('Y-m-d', $value->date);
                    return $activeFilter == 'month' ? $date->format('d') : $date->format('D');
                } elseif ($activeFilter == 'today') {
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

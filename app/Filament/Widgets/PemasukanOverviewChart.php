<?php

namespace App\Filament\Widgets;

use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PemasukanOverviewChart extends BaseWidget
{
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        $total = DetailPenjualan::sum('sub_total');
        $laba = DetailPembelian::sum('sub_total');
        $totalFormatted = number_format($total, 0, '.', '.');
        $labaFormatted = number_format($total - $laba, 0, '.', '.');
        return [
            //


            Stat::make('Total Penjualan', 'Rp. ' . $totalFormatted)
                ->description('Hasil Penjualan Keseluruhan')
                ->descriptionIcon('heroicon-c-arrow-trending-up', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('warning'),
            Stat::make('Total Laba', 'Rp. ' . $labaFormatted)
                ->description('Hasil Pendapatan Bersih')
                ->descriptionIcon('heroicon-s-currency-dollar', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('success'),
            Stat::make('Jumlah Karyawan', User::where('email', '!=', 'NULL')->count())
                ->description('Sedang Aktif Bekerja')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('primary'),
        ];
    }
}

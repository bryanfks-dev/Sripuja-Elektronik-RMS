<?php

namespace App\Filament\Widgets;

use App\Models\Karyawan;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AdminDashboardHeaderStats extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalPenjualan = DetailPenjualan::sum('sub_total');
        $totalPembelian = DetailPembelian::sum('sub_total');
        $laba = $totalPenjualan - $totalPembelian;

        // Format currency
        $totalPenjualanFormatted = number_format($totalPenjualan,
            0, '.', '.');
        $labaFormatted = number_format($laba,
            0, '.', '.');

        return [
            Stat::make('Total Penjualan', 'Rp ' . $totalPenjualanFormatted)
                ->description('Hasil Penjualan Keseluruhan')
                ->descriptionIcon('heroicon-c-arrow-trending-up', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('warning'),
            Stat::make('Total Laba', 'Rp ' . $labaFormatted)
                ->description('Hasil Pendapatan Bersih')
                ->descriptionIcon('heroicon-s-currency-dollar', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color(($laba > 0) ? 'success' : 'danger'),
            Stat::make('Jumlah Karyawan', Karyawan::count())
                ->description('Sedang Aktif Bekerja')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color(Color::Blue),
        ];
    }
}

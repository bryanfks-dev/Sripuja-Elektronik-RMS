<?php

namespace App\Filament\Widgets;

use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use App\Models\Karyawan;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminDashboardHeaderStats extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $totalPenjualan = DetailPenjualan::sum('sub_total');
        $totalPembelian = DetailPembelian::sum('sub_total');

        // Format currency
        $totalPenjualanFormatted = number_format($totalPenjualan,
            0, '.', '.');
        $labaFormatted = number_format($totalPenjualan - $totalPembelian,
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
                ->color('success'),
            Stat::make('Jumlah Karyawan', Karyawan::count())
                ->description('Sedang Aktif Bekerja')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('primary'),
        ];
    }
}

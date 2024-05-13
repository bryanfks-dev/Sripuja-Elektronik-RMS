<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PemasukanOverviewChart extends BaseWidget
{
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Penjualan', 'Rp. 21.000.000')
                ->description('Hasil Penjualan Keseluruhan')
                ->descriptionIcon('heroicon-c-arrow-trending-up', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('warning'),
            Stat::make('Total Laba', 'Rp. 12.000.000')
                ->description('Hasil Pendapatan Bersih')
                ->descriptionIcon('heroicon-s-currency-dollar', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('success'),
            Stat::make('Jumlah Karyawan', User::count())
                ->description('Sedang Aktif Bekerja')
                ->descriptionIcon('heroicon-m-user-group', IconPosition::Before)
                ->chart([1, 3, 5, 10, 20, 40])
                ->color('primary'),
        ];
    }
}

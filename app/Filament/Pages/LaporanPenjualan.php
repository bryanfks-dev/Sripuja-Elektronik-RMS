<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LaporanLabaChart;
use App\Filament\Widgets\LaporanPembelianChart;
use Filament\Pages\Page;
use App\Filament\Widgets\LaporanPenjualanChart;

class LaporanPenjualan extends Page
{

    protected static string $view = 'filament.pages.laporan-penjualan';

    protected static ?string $navigationGroup = 'Laporan Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-line';

    protected static ?string $navigationLabel = 'Laporan Penjualan';

    protected function getHeaderWidgets(): array
    {
        return [
            LaporanPenjualanChart::class,
            LaporanPembelianChart::class,
            LaporanLabaChart::class,
        ];
    }
}

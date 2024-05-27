<?php

namespace App\Filament\Clusters\LaporanTransaksi\Pages;

use Filament\Pages\Page;
use App\Filament\Clusters\LaporanTransaksi;
use App\Filament\Widgets\DataPenjualanChart;
use Filament\Pages\SubNavigationPosition;

class LaporanPenjualan extends Page
{
    protected static string $view = 'filament.clusters.laporan-transaksi.pages.laporan-penjualan';

    protected static ?string $cluster = LaporanTransaksi::class;

    protected static ?int $navigationSort = 1;

    protected ?string $heading = 'Laporan Penjualan';

    protected static ?string $navigationLabel = 'Penjualan';

    protected static ?string $slug = 'penjualan';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DataPenjualanChart::class,
        ];
    }
}

<?php

namespace App\Filament\Clusters\LaporanTransaksi\Pages;

use App\Filament\Widgets\DataPembelianChart;
use Filament\Pages\Page;
use App\Filament\Clusters\LaporanTransaksi;
use Filament\Pages\SubNavigationPosition;

class LaporanPembelian extends Page
{
    protected static string $view = 'filament.clusters.laporan-transaksi.pages.laporan-pembelian';

    protected static ?string $cluster = LaporanTransaksi::class;

    protected static ?int $navigationSort = 2;

    protected ?string $heading = 'Laporan Pembelian';

    protected static ?string $navigationLabel = 'Pembelian';

    protected static ?string $slug = 'pembelian';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DataPembelianChart::class,
        ];
    }
}

<?php

namespace App\Filament\Clusters\GrafikTransaksi\Pages;

use App\Filament\Clusters\GrafikTransaksi;
use Filament\Pages\Page;
use App\Filament\Widgets\DataPenjualanChart;
use Filament\Pages\SubNavigationPosition;

class GrafikPenjualan extends Page
{
    protected static string $view = 'filament.clusters.grafik-transaksi.pages.grafik-penjualan';

    protected static ?string $cluster = GrafikTransaksi::class;

    protected static ?int $navigationSort = 1;

    protected ?string $heading = 'Grafik Penjualan';

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

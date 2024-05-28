<?php

namespace App\Filament\Clusters\GrafikTransaksi\Pages;

use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use App\Filament\Clusters\GrafikTransaksi;
use App\Filament\Widgets\DataPembelianChart;

class GrafikPembelian extends Page
{
    protected static string $view = 'filament.clusters.grafik-transaksi.pages.grafik-pembelian';

    protected static ?string $cluster = GrafikTransaksi::class;

    protected static ?int $navigationSort = 2;

    protected ?string $heading = 'Grafik Pembelian';

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

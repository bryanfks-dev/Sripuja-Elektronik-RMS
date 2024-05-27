<?php

namespace App\Filament\Clusters\LaporanTransaksi\Pages;

use App\Filament\Widgets\LabaChart;
use Filament\Pages\Page;
use App\Filament\Clusters\LaporanTransaksi;
use Filament\Pages\SubNavigationPosition;

class LaporanLaba extends Page
{
    protected static string $view = 'filament.clusters.laporan-transaksi.pages.laporan-laba';

    protected static ?string $cluster = LaporanTransaksi::class;

    protected static ?int $navigationSort = 3;

    protected ?string $heading = 'Laporan Laba';

    protected static ?string $navigationLabel = 'Laba';

    protected static ?string $slug = 'laba';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LabaChart::class,
        ];
    }
}

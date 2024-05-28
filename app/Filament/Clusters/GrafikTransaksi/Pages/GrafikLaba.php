<?php

namespace App\Filament\Clusters\GrafikTransaksi\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\LabaChart;
use Filament\Pages\SubNavigationPosition;
use App\Filament\Clusters\GrafikTransaksi;

class GrafikLaba extends Page
{
    protected static string $view = 'filament.clusters.grafik-transaksi.pages.grafik-laba';

    protected static ?string $cluster = GrafikTransaksi::class;

    protected static ?int $navigationSort = 3;

    protected ?string $heading = 'Grafik Laba';

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

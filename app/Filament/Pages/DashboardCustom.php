<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\LaporanPenjualanChart;
use App\Filament\Widgets\PemasukanOverviewChart;

class DashboardCustom extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard-custom';

    protected function getHeaderWidgets(): array
    {
        return [
            PemasukanOverviewChart::class,
            LaporanPenjualanChart::class,
        ];
    }
}

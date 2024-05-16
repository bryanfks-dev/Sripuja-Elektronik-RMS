<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminDashboardHeaderStats;
use App\Filament\Widgets\DataPenjualanChart;
use Filament\Pages\Page;

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-m-squares-2x2';

    protected static string $view = 'filament.pages.custom-dashboard';

    protected ?string $heading = 'Dashboard';

    protected static ?string $slug = 'dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            AdminDashboardHeaderStats::class,
            DataPenjualanChart::class,
        ];
    }
}

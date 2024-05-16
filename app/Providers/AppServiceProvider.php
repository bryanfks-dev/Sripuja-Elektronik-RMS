<?php

namespace App\Providers;

use App\Filament\Widgets\DataPembelianChart;
use App\Filament\Widgets\DataPenjualanChart;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Filament\Widgets\LabaChart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('filament.widgets.penjualan-chart', DataPenjualanChart::class);
        Livewire::component('filament.widgets.pembelian-chart', DataPembelianChart::class);
        Livewire::component('filament.widgets.laba-chart', LabaChart::class);
    }
}

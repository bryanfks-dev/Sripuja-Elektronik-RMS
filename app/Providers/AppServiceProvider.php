<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Filament\Widgets\BeliChart;
use App\Filament\Widgets\LabaChart;
use App\Filament\Widgets\LaporanLabaChart;
use App\Filament\Widgets\LaporanPenjualanChart;
use App\Filament\Widgets\LaporanPembelianChart;

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
        //
        Livewire::component('filament.widgets.test-chart', LaporanPenjualanChart::class);
        Livewire::component('filament.widgets.beli-chart', LaporanPembelianChart::class);
        Livewire::component('filament.widgets.laba-chart', LaporanLabaChart::class);
    }
}

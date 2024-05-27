<?php

namespace App\Providers;

use Carbon\Carbon;
use Livewire\Livewire;
use App\Filament\Widgets\LabaChart;
use Illuminate\Support\ServiceProvider;
use App\Filament\Widgets\DataPembelianChart;
use App\Filament\Widgets\DataPenjualanChart;

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
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        Livewire::component('filament.widgets.penjualan-chart', DataPenjualanChart::class);
        Livewire::component('filament.widgets.pembelian-chart', DataPembelianChart::class);
        Livewire::component('filament.widgets.laba-chart', LabaChart::class);
    }
}

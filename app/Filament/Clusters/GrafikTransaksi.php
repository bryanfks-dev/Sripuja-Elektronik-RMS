<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class GrafikTransaksi extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-line';

    protected static ?string $slug = 'grafik';

    protected static ?string $navigationGroup = 'Laporan Transaksi';

    protected static ?int $navigationSort = 1;
}

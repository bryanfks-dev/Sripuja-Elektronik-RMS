<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class LaporanTransaksi extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-line';

    protected static ?string $slug = 'laporan';

    protected static ?string $navigationGroup = 'Laporan Transaksi';

    protected static ?int $navigationSort = 1;
}

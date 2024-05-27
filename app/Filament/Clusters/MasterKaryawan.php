<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class MasterKaryawan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-user';

    protected static ?string $navigationGroup = 'Relasi';

    protected static ?string $navigationLabel = 'Karyawan';

    protected static ?string $slug = 'relasi/karyawan';

    protected static ?int $navigationSort = 1;

    public static function canAccessClusteredComponents(): bool {
        return auth()->user()->isAdmin();
    }
}

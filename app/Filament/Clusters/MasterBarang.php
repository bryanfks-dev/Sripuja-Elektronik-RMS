<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class MasterBarang extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-tag';

    protected static ?string $slug = 'master';

    public static function canAccessClusteredComponents(): bool {
        return auth()->user()->isAdmin();
    }
}

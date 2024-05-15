<?php

namespace App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

use App\Filament\Clusters\MasterBarang\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangs extends ListRecords
{
    protected static string $resource = BarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')
                ->icon('heroicon-m-plus')->label('Tambah Barang')
        ];
    }
}

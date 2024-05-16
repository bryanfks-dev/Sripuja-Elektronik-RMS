<?php

namespace App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource\Pages;

use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMerekBarangs extends ListRecords
{
    protected static string $resource = MerekBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')
                ->icon('heroicon-m-plus')->label('Tambah Merek Barang')
        ];
    }
}

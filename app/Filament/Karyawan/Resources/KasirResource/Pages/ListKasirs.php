<?php

namespace App\Filament\Karyawan\Resources\KasirResource\Pages;

use App\Filament\Karyawan\Resources\KasirResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKasirs extends ListRecords
{
    protected static string $resource = KasirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CetakLaporanResource\Pages;

use App\Filament\Resources\CetakLaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCetakLaporans extends ListRecords
{
    protected static string $resource = CetakLaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\CetakLaporanResource\Pages;

use App\Filament\Admin\Resources\CetakLaporanResource;
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

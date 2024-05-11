<?php

namespace App\Filament\Admin\Resources\KaryawanResource\Pages;

use App\Filament\Admin\Resources\KaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKaryawans extends ListRecords
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

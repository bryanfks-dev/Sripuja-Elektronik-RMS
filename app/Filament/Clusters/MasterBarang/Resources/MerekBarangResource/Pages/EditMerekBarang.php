<?php

namespace App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource;

class EditMerekBarang extends EditRecord
{
    protected static string $resource = MerekBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

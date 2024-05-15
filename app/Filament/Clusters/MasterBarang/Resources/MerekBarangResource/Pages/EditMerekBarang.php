<?php

namespace App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource\Pages;

use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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

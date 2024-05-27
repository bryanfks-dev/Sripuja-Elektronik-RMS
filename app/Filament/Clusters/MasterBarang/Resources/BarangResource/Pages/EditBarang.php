<?php

namespace App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Clusters\MasterBarang\Resources\BarangResource;

class EditBarang extends EditRecord
{
    protected static string $resource = BarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

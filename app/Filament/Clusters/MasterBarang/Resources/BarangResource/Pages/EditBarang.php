<?php

namespace App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

use App\Filament\Clusters\MasterBarang\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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

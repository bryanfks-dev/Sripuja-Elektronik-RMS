<?php

namespace App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource;

class EditJenisBarang extends EditRecord
{
    protected static string $resource = JenisBarangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

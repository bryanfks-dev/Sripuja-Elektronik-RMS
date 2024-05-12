<?php

namespace App\Filament\Admin\Resources\CetakLaporanResource\Pages;

use App\Filament\Admin\Resources\CetakLaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCetakLaporan extends EditRecord
{
    protected static string $resource = CetakLaporanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

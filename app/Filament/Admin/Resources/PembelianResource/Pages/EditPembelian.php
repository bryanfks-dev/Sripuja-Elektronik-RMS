<?php

namespace App\Filament\Admin\Resources\PembelianResource\Pages;

use App\Filament\Admin\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelian extends EditRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

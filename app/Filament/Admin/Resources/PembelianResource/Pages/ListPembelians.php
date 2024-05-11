<?php

namespace App\Filament\Admin\Resources\PembelianResource\Pages;

use App\Filament\Admin\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembelians extends ListRecords
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

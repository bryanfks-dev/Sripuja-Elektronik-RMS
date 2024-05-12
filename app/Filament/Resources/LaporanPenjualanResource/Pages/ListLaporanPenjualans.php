<?php

namespace App\Filament\Resources\LaporanPenjualanResource\Pages;

use App\Filament\Resources\LaporanPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPenjualans extends ListRecords
{
    protected static string $resource = LaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

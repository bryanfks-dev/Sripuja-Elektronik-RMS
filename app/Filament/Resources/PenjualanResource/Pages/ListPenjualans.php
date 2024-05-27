<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PenjualanResource;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')
                ->icon('heroicon-m-plus')->label('Buat Penjualan')
        ];
    }
}

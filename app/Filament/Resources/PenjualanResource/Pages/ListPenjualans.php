<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use Filament\Actions;
use App\Models\Barang;
use App\Models\Penjualan;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PenjualanResource;

class ListPenjualans extends ListRecords
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')
                ->icon('heroicon-m-plus')->label('Tambah Penjualan')
                ->before(function(Penjualan $record):void {
                    dd(Barang::count());
                    if (Barang::count() > 0) {
                        $record->create();
                    }

                    Notification::make()
                        ->title('Tidak ada barang yang tersedia untuk dijual')
                        ->danger()
                        ->send();
                })
        ];
    }
}

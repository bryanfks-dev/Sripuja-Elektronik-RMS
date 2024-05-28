<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Models\Penjualan;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PenjualanResource;

class EditPenjualan extends EditRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(fn(Penjualan $record) =>
                    PenjualanResource::deletePenjualan($record)),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Penjualan
    {
        // Remove no_invoice from data
        unset($data['no_invoice']);

        // Create penjualan
        $record->update($data);

        return $record;
    }
}

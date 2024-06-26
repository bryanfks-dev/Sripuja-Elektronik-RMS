<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Models\Invoice;
use App\Models\Penjualan;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PenjualanResource;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected ?string $heading = 'Buat Penjualan';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function handleRecordCreation(array $data): Penjualan
    {
        // Save no_invoice
        $noInvoice = $data['no_invoice'];

        // Remove no_invoice from data
        unset($data['no_invoice']);

        // Create penjualan
        $penjualan = static::getModel()::create($data);

        // Create invoice
        Invoice::create([
            'no_invoice' => $noInvoice,
            'penjualan_id' => $penjualan->id,
        ]);

        return $penjualan;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

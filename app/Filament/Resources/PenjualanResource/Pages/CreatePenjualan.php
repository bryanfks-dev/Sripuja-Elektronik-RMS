<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Models\Invoice;
use App\Models\Penjualan;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PenjualanResource;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected ?string $heading = 'Tambah Penjualan';

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

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Tambah')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label('Tambah Lagi')
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('gray');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batal')
            ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getResource()::getUrl()) . ')')
            ->color('gray');
    }
}

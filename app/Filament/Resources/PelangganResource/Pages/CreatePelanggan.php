<?php

namespace App\Filament\Resources\PelangganResource\Pages;

use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PelangganResource;

class CreatePelanggan extends CreateRecord
{
    protected static string $resource = PelangganResource::class;

    protected ?string $heading = 'Tambah Pelanggan';

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

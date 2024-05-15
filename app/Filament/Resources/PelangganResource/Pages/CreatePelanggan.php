<?php

namespace App\Filament\Resources\PelangganResource\Pages;

use App\Filament\Resources\PelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePelanggan extends CreateRecord
{
    protected static string $resource = PelangganResource::class;

    protected ?string $heading = 'Tambah Pelanggan';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

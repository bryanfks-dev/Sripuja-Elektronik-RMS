<?php

namespace App\Filament\Resources\PelangganResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PelangganResource;

class CreatePelanggan extends CreateRecord
{
    protected static string $resource = PelangganResource::class;

    protected ?string $heading = 'Buat Pelanggan';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

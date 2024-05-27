<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PembelianResource;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected ?string $heading = 'Buat Pembelian';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

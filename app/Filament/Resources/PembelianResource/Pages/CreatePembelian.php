<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Filament\Resources\PembelianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelian extends CreateRecord
{
    protected static string $resource = PembelianResource::class;

    protected ?string $heading = 'Tambah Pembelian';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

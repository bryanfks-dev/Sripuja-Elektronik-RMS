<?php

namespace App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\MasterBarang\Resources\BarangResource;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected ?string $heading = 'Buat Barang';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

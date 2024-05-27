<?php

namespace App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource;

class CreateMerekBarang extends CreateRecord
{
    protected static string $resource = MerekBarangResource::class;

    protected ?string $heading = 'Buat Merek Barang';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

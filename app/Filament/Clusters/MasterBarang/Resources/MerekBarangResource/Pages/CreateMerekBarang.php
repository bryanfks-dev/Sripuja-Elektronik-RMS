<?php

namespace App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource\Pages;

use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMerekBarang extends CreateRecord
{
    protected static string $resource = MerekBarangResource::class;

    protected ?string $heading = 'Tambah Merek Barang';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

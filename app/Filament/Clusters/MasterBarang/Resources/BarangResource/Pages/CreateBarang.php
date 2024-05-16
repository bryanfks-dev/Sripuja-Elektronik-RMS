<?php

namespace App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

use App\Filament\Clusters\MasterBarang\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected ?string $heading = 'Tambah Data Barang';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

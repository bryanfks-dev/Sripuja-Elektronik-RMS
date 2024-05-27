<?php

namespace App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource;

class CreateJenisBarang extends CreateRecord
{
    protected static string $resource = JenisBarangResource::class;

    protected ?string $heading = 'Buat Jenis Barang';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

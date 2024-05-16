<?php

namespace App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource\Pages;

use App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisBarang extends CreateRecord
{
    protected static string $resource = JenisBarangResource::class;

    protected ?string $heading = 'Tambah Jenis Barang';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;

    protected ?string $heading = 'Tambah Supplier';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

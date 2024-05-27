<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SupplierResource;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;

    protected ?string $heading = 'Buat Supplier';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

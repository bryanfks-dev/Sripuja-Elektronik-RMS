<?php

namespace App\Filament\Admin\Resources\LaporanPenjualanResource\Pages;

use App\Filament\Admin\Resources\LaporanPenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanPenjualan extends EditRecord
{
    protected static string $resource = LaporanPenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

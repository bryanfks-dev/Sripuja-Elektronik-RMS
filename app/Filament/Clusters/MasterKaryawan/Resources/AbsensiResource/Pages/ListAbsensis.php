<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources\AbsensiResource\Pages;

use App\Filament\Clusters\MasterKaryawan\Resources\AbsensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbsensis extends ListRecords
{
    protected static string $resource = AbsensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('go')
                ->icon('heroicon-m-cog-6-tooth')->label('Pengaturan')
                ->url('/relasi/karyawan/absensi/config')
        ];
    }
}

<?php

namespace App\Filament\Resources\KaryawanResource\Pages;

use App\Filament\Resources\KaryawanResource;
use App\Models\Karyawan;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKaryawans extends ListRecords
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make('create')
                ->icon('heroicon-m-plus')->label('Tambah Karyawan')
        ];

        // Add delete button if karyawan data exists
        if (Karyawan::exists()) {
            $delete = Actions\CreateAction::make('delete')
                ->icon('heroicon-m-trash')->label('Hapus');

            array_unshift($actions, $delete);
        }

        return $actions;
    }
}

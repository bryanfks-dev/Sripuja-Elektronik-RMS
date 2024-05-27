<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources\KaryawanResource\Pages;

use App\Models\User;
use App\Models\Karyawan;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\MasterKaryawan\Resources\KaryawanResource;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        $user = User::create([
            'username' => Karyawan::createUsername($data['nama_lengkap']),
            'password' => Karyawan::createPassword($data['no_hp']),
        ]);

        $data['user_id'] = $user->id;

        $data['gaji_bln_ini'] = $data['gaji'];

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

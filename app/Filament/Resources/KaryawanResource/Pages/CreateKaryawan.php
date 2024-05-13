<?php

namespace App\Filament\Resources\KaryawanResource\Pages;

use App\Models\User;
use App\Models\Karyawan;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\KaryawanResource;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        $data['no_hp'] = '0' . $data['no_hp'];

        $user = [
            'username' => Karyawan::createUsername($data['nama_lengkap']),
            'password' => Karyawan::createPassword($data['no_hp']),
        ];

        $user = User::create($user);

        $data['id_user'] = $user->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

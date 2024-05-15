<?php

namespace App\Filament\Resources\KaryawanResource\Pages;

use App\Models\User;
use App\Models\Karyawan;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\KaryawanResource;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;

    protected ?string $heading = 'Tambah Karyawan';

    protected function mutateFormDataBeforeCreate(array $data):array
    {
        $user = [
            'username' => Karyawan::createUsername($data['nama_lengkap']),
            'password' => Karyawan::createPassword($data['no_hp']),
        ];

        $user = User::create($user);

        $data['user_id'] = $user->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

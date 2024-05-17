<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources\KaryawanResource\Pages;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Support\Js;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\MasterKaryawan\Resources\KaryawanResource;

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

        $data['gaji_bln_ini'] = $data['gaji'];

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Tambah')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label('Tambah Lagi')
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('gray');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batal')
            ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getResource()::getUrl()) . ')')
            ->color('gray');
    }
}

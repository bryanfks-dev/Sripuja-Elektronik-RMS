<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources\KaryawanResource\Pages;

use App\Models\User;
use App\Models\Karyawan;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_lengkap')->label('Nama Lengkap')
                    ->autocapitalize('words')->required(),
                TextInput::make('alamat')->autocapitalize('sentences')
                    ->required(),
                TextInput::make('telepon')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
                TextInput::make('no_hp')->label('Nomor Hp')->tel()
                    ->maxLength(13)->telRegex('/^08[1-9][0-9]{6,10}$/')
                    ->required(),
                Select::make('tipe')->label('Tipe Karyawan')
                    ->native(false)->options(KaryawanResource:: $karyawanTypes)
                    ->default('Non-Kasir')->required(),
                TextInput::make('gaji')->numeric()->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)'))->stripCharacters(',')
                    ->minValue(1)->required(),
            ]);
    }
}

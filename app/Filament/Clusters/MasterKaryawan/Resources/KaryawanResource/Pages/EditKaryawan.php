<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources\KaryawanResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Karyawan;
use Filament\Forms\Form;
use Illuminate\Support\Js;
use Filament\Support\RawJs;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Clusters\MasterKaryawan\Resources\KaryawanResource;

class EditKaryawan extends EditRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Karyawan')
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
                            ->native(false)->options(KaryawanResource::$karyawanTypes)
                            ->default('Non-Kasir')->required(),
                        TextInput::make('gaji')->numeric()->prefix('Rp.')
                            ->mask(RawJs::make('$money($input)'))->stripCharacters(',')
                            ->minValue(1)->required(),
                        TextInput::make('gaji_bln_ini')->label('Gaji Bulan Ini')->numeric()->prefix('Rp.')
                            ->mask(RawJs::make('$money($input)'))->stripCharacters(',')
                            ->minValue(1)->required(),
                    ])->columns(['md' => 2]),
                Section::make('Akun Karyawan')
                    ->collapsed()
                    ->relationship('user')
                    ->schema([
                        TextInput::make('username')->label('Username')
                            ->readOnly()->required(),
                        TextInput::make('password')->label('New Password')
                            ->password()->dehydrateStateUsing(
                                fn($state): string => ($state != null) ? Hash::make($state) : ''
                            )
                            ->revealable(),
                    ])->columns(['md' => 2])
                    ->saveRelationshipsUsing(static function ($component, Karyawan $record, $state) {
                        if ($state['password'] != null) {
                            User::find($state['id'])->update($state);
                        }
                    })
            ]);
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Simpan')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batalkan')
            ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getResource()::getUrl()) . ')')
            ->color('gray');
    }
}

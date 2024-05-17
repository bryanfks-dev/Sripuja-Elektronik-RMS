<?php

namespace App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

use Filament\Forms\Form;
use Illuminate\Support\Js;
use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\MasterBarang\Resources\BarangResource;
use App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource;
use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected ?string $heading = 'Tambah Data Barang';

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_barang')->label('Kode Barang')
                    ->unique()->autocapitalize('characters')->required(),
                TextInput::make('nama_barang')->label('Nama Barang')
                    ->autocapitalize('sentences')->required(),
                Select::make('merek_barang_id')->label('Merek Barang')
                    ->relationship('merekBarang', 'nama_merek')
                    ->searchable()->preload()->native(false)
                            ->createOptionForm(
                                fn(Form $form) => MerekBarangResource::form($form)
                                    ->columns(['md' => 2])
                            )
                    ->required(),
                Select::make('jenis_barang_id')->label('Jenis Barang')
                    ->relationship('jenisBarang', 'nama_jenis')
                    ->searchable()->preload()->native(false)
                            ->createOptionForm(
                                fn(Form $form) => JenisBarangResource::form($form)
                                    ->columns(['md' => 2])
                            )
                    ->required(),
                TextInput::make('jumlah_per_grosir')->label('Jumlah / Grosir')
                    ->numeric()->minValue(0)->default(0)->required(),
                TextInput::make('harga_jual')->label('Harga Jual')
                    ->numeric()->prefix('Rp')->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')->minValue(1)->required(),
                TextInput::make('harga_beli')->label('Harga Beli')
                    ->numeric()->prefix('Rp')->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')->minValue(1)->required(),
                TextInput::make('harga_grosir')->label('Harga Grosir')
                    ->numeric()->prefix('Rp')->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')->default(0)->minValue(0)
                    ->required(),
            ]);
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

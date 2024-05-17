<?php

namespace App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Illuminate\Support\Js;
use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Clusters\MasterBarang\Resources\BarangResource;
use App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource;
use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource;

class EditBarang extends EditRecord
{
    protected static string $resource = BarangResource::class;

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
                TextInput::make('stock')->label('Stock Barang')
                    ->numeric()->minValue(0)->required(),
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

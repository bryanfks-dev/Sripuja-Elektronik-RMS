<?php

namespace App\Filament\Clusters\MasterBarang\Resources\BarangResource\RelationManagers;

use App\Models\Barang;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\DetailBarang;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Collection;
use Filament\Resources\RelationManagers\RelationManager;

class DetailBarangsRelationManager extends RelationManager
{
    protected static string $model = DetailBarang::class;

    protected static string $relationship = 'detailBarangs';

    protected static ?string $title = 'Barang';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_barang')->label('Kode Barang')
                    ->formatStateUsing(fn() => $this->getOwnerRecord()->first()->kode_barang)
                    ->readOnly(),
                TextInput::make('nama_barang')->label('Nama Barang')
                    ->formatStateUsing(fn() => $this->getOwnerRecord()->first()->nama_barang)
                    ->readOnly(),
                TextInput::make('stock')->default(0)->minValue(0)
                    ->required(),
                TextInput::make('harga_beli')->label('Harga Beli')
                    ->numeric()->prefix('Rp.')->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')->required(),
                TextInput::make('harga_jual')->label('Harga Jual')
                    ->numeric()->prefix('Rp.')->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')->required(),
                TextInput::make('harga_grosir')->label('Harga Grosir')
                    ->numeric()->default(0)->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)'))->stripCharacters(',')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Tanggal Masuk')
                    ->date('d M Y'),
                TextColumn::make('stock'),
                TextColumn::make('harga_beli')->label('Harga Beli')
                    ->money('Rp '),
                TextColumn::make('harga_jual')->label('Harga Jual')
                    ->money('Rp '),
                TextColumn::make('harga_grosir')->label('Harga Grosir')
                    ->money('Rp '),
                TextColumn::make('updated_at')->label('Update Terakhir')
                    ->date('d M Y'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Barang')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batalkan'),
                Tables\Actions\Action::make('delete')->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Barang')
                    ->modalSubheading('Konfirmasi untuk menghapus data ini')
                    ->modalButton('Hapus')
                    ->modalCloseButton()
                    ->modalCancelActionLabel('Batalkan')
                    ->icon('heroicon-c-trash')->color('danger')
                    ->action(function (DetailBarang $record) {
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkAction::make('delete')->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Barang yang Terpilih')
                    ->modalSubheading('Konfirmasi untuk menghapus data-data yang terpilih')
                    ->modalButton('Hapus')
                    ->modalCloseButton()
                    ->modalCancelActionLabel('Batalkan')
                    ->icon('heroicon-c-trash')->color('danger')
                    ->action(fn(Collection $records) => $records->each->delete()),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Pelanggan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\PelangganResource\Pages;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $pluralModelLabel = 'Data Pelanggan';

    protected static ?string $slug = 'relasi/pelanggan';

    protected static ?string $navigationGroup = 'Relasi';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?string $navigationLabel = 'Pelanggan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_lengkap')->label('Nama Lengkap')
                    ->autocapitalize('words')->required(),
                TextInput::make('alamat')->autocapitalize('sentences')
                    ->autocapitalize(),
                TextInput::make('telepon')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
                TextInput::make('no_hp')->label('Nomor Hp')->tel()
                    ->maxLength(13)->telRegex('/^08[1-9][0-9]{6,10}$/'),
                TextInput::make('fax')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama')
                    ->searchable(),
                TextColumn::make('alamat')->placeholder('-'),
                TextColumn::make('telepon')->placeholder('-'),
                TextColumn::make('no_hp')->label('Nomor Hp')
                    ->placeholder('-'),
                TextColumn::make('fax')->placeholder('-'),
                TextColumn::make('total_pembelian')->label('Total Pembelian')
                    ->money('Rp ')->default(0)
                    ->getStateUsing(
                        fn(Pelanggan $model) =>
                        $model->detailPenjualans()->sum('sub_total')
                    )
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('white'),
                Tables\Actions\DeleteAction::make()->label('Hapus')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}

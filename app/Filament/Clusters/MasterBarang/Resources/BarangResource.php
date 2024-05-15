<?php

namespace App\Filament\Clusters\MasterBarang\Resources;

use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use App\Filament\Clusters\MasterBarang;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Pages\SubNavigationPosition;
use App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

class BarangResource extends Resource
{
    protected static ?string $cluster = MasterBarang::class;

    protected static ?string $model = Barang::class;

    protected static ?string $pluralModelLabel = 'Data Barang';

    /* protected static ?string $navigationIcon = 'heroicon-m-cube'; */

    protected static ?string $navigationLabel = 'Data Barang';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $slug = 'data-barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_barang')->label('Kode Barang')
                    ->unique()->autocapitalize('characters')->required(),
                TextInput::make('nama_barang')->label('Nama Barang')
                    ->autocapitalize('sentences')->required(),
                TextInput::make('merek')->autocapitalize('characters')
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')->label('Kode Barang')
                    ->searchable(),
                TextColumn::make('nama_barang')->label('Nama Barang')
                    ->searchable(),
                TextColumn::make('merek')->searchable(),
                TextColumn::make('stock')->numeric()->sortable(),
                TextColumn::make('harga_jual')->label('Harga Jual')
                    ->money('Rp ')->sortable(),
                TextColumn::make('harga_beli')->label('Harga Beli')
                    ->money('Rp ')->sortable(),
                TextColumn::make('jumlah_per_grosir')->label('Jumlah / Grosir')
                    ->numeric()->sortable(),
                TextColumn::make('harga_grosir')->label('Harga Grosir')
                    ->money('Rp ')->sortable(),
                TextColumn::make('updated_at')->label('Update Terkahir')
                    ->date('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()->color('white'),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}

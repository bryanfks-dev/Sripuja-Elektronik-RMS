<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PenjualanResource\Pages;
use App\Filament\Admin\Resources\PenjualanResource\RelationManagers;
use App\Models\Invoice;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationGroup = 'Data Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-s-truck';

    protected static ?string $navigationLabel = 'Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_pelanggan')->label('Nama Pelanggan')
                    ->options(Pelanggan::all()
                        ->pluck('Nama_Lengkap', 'Id_Pelanggan'))
                    ->searchable()->createOptionForm(
                        fn(Form $form) => PelangganResource::form($form)
                    )
                    ->required(),
                TextInput::make('no_nota')->label('Nomor Nota')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}

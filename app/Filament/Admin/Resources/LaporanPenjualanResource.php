<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LaporanPenjualanResource\Pages;
use App\Filament\Admin\Resources\LaporanPenjualanResource\RelationManagers;
use App\Models\LaporanPenjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanPenjualanResource extends Resource
{
    protected static ?string $model = LaporanPenjualan::class;

    protected static ?string $navigationGroup = 'Laporan Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-s-presentation-chart-line';

    protected static ?string $navigationLabel = 'Laporan Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListLaporanPenjualans::route('/'),
            'create' => Pages\CreateLaporanPenjualan::route('/create'),
            'edit' => Pages\EditLaporanPenjualan::route('/{record}/edit'),
        ];
    }
}

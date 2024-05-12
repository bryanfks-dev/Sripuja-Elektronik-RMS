<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CetakLaporanResource\Pages;
use App\Filament\Resources\CetakLaporanResource\RelationManagers;
use App\Models\CetakLaporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CetakLaporanResource extends Resource
{
    protected static ?string $model = CetakLaporan::class;

    protected static ?string $navigationGroup = 'Laporan Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-s-printer';

    protected static ?string $navigationLabel = 'Cetak Laporan';

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
            'index' => Pages\ListCetakLaporans::route('/'),
        ];
    }
}

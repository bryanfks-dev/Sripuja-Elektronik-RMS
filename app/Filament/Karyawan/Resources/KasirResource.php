<?php

namespace App\Filament\Karyawan\Resources;

use App\Filament\Karyawan\Resources\KasirResource\Pages;
use App\Filament\Karyawan\Resources\KasirResource\RelationManagers;
use App\Models\Kasir;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KasirResource extends Resource
{
    protected static ?string $model = Kasir::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListKasirs::route('/'),
            'create' => Pages\CreateKasir::route('/create'),
            'edit' => Pages\EditKasir::route('/{record}/edit'),
        ];
    }
}

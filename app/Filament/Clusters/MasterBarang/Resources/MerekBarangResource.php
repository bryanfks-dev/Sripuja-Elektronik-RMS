<?php

namespace App\Filament\Clusters\MasterBarang\Resources;

use App\Filament\Clusters\MasterBarang;
use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource\Pages;
use App\Models\MerekBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MerekBarangResource extends Resource
{
    protected static ?string $cluster = MasterBarang::class;
    
    protected static ?string $model = MerekBarang::class;

    protected static ?string $pluralModelLabel = 'Merek Barang';

    protected static ?string $navigationLabel = 'Merek Barang';

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
            'index' => Pages\ListMerekBarangs::route('/'),
            'create' => Pages\CreateMerekBarang::route('/create'),
            'edit' => Pages\EditMerekBarang::route('/{record}/edit'),
        ];
    }
}

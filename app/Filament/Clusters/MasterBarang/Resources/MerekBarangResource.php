<?php

namespace App\Filament\Clusters\MasterBarang\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MerekBarang;
use Filament\Resources\Resource;
use App\Filament\Clusters\MasterBarang;
use Filament\Pages\SubNavigationPosition;
use App\Filament\Clusters\MasterBarang\Resources\MerekBarangResource\Pages;

class MerekBarangResource extends Resource
{
    protected static ?string $cluster = MasterBarang::class;

    protected static ?string $model = MerekBarang::class;

    protected static ?string $pluralModelLabel = 'Merek Barang';

    protected static ?string $navigationLabel = 'Merek Barang';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $slug = 'merek-barang';

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

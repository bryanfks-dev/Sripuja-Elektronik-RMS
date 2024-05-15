<?php

namespace App\Filament\Clusters\MasterBarang\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\JenisBarang;
use Filament\Resources\Resource;
use App\Filament\Clusters\MasterBarang;
use Filament\Pages\SubNavigationPosition;
use App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource\Pages;

class JenisBarangResource extends Resource
{
    protected static ?string $cluster = MasterBarang::class;

    protected static ?string $model = JenisBarang::class;

    protected static ?string $pluralModelLabel = 'Jenis Barang';

    /* protected static ?string $navigationIcon = 'heroicon-c-square-3-stack-3d'; */

    protected static ?string $navigationLabel = 'Jenis Barang';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $slug = 'jenis-barang';

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
            'index' => Pages\ListJenisBarangs::route('/'),
            'create' => Pages\CreateJenisBarang::route('/create'),
            'edit' => Pages\EditJenisBarang::route('/{record}/edit'),
        ];
    }
}

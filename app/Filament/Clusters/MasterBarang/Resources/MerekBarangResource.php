<?php

namespace App\Filament\Clusters\MasterBarang\Resources;

use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\MerekBarang;
use Filament\Resources\Resource;
use App\Filament\Clusters\MasterBarang;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
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
                TextInput::make('nama_merek')->label('Merek Barang')
                    ->autocapitalize('characters')->unique()
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_merek')->label('Merek Barang')
                    ->searchable(),
                TextColumn::make('jumlah')->label('Jumlah Barang')
                    ->numeric()->default(0)
                    ->getStateUsing(
                        function(Model $model) {
                            $sameMerek = Barang::where('merek_barang_id', '=', $model->id)
                                ->count();

                            return $sameMerek;
                    })
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('white'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
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
            'index' => Pages\ListMerekBarangs::route('/'),
            'create' => Pages\CreateMerekBarang::route('/create'),
            'edit' => Pages\EditMerekBarang::route('/{record}/edit'),
        ];
    }
}

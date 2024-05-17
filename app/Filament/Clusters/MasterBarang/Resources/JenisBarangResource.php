<?php

namespace App\Filament\Clusters\MasterBarang\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\JenisBarang;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use App\Filament\Clusters\MasterBarang;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Clusters\MasterBarang\Resources\JenisBarangResource\Pages;

class JenisBarangResource extends Resource
{
    protected static ?string $cluster = MasterBarang::class;

    protected static ?string $model = JenisBarang::class;

    protected static ?string $pluralModelLabel = 'Jenis Barang';

    protected static ?string $navigationLabel = 'Jenis Barang';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $slug = 'jenis-barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_jenis')->label('Jenis Barang')
                    ->autocapitalize('characters')->unique()
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_jenis')->label('Jenis Barang')
                    ->searchable(),
                TextColumn::make('jumlah')->label('Jumlah Barang')
                    ->numeric()->default(0)
                    ->getStateUsing(
                        fn(JenisBarang $model) => $model->barangs()->count()
                    ),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('white'),
                Action::make('delete')->label('Hapus')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Jenis Barang')
                        ->modalSubheading('Konfirmasi untuk menghapus data ini')
                        ->modalButton('Hapus')
                        ->modalCloseButton()
                        ->modalCancelActionLabel('Batalkan')
                        ->icon('heroicon-c-trash')->color('danger')
                        ->action(function (JenisBarang $record) {
                            $record->delete();
                        }),
            ])
            ->bulkActions([
                BulkAction::make('delete')->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Jenis Barang yang Terpilih')
                    ->modalSubheading('Konfirmasi untuk menghapus data-data yang terpilih')
                    ->modalButton('Hapus')
                    ->modalCloseButton()
                    ->modalCancelActionLabel('Batalkan')
                    ->icon('heroicon-c-trash')->color('danger')
                    ->action(fn(Collection $records) => $records->each->delete()),
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

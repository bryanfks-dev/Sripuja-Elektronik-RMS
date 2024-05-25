<?php

namespace App\Filament\Clusters\MasterBarang\Resources;

use App\Filament\Clusters\MasterBarang\Resources\BarangResource\RelationManagers\DetailBarangsRelationManager;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use App\Filament\Clusters\MasterBarang;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Clusters\MasterBarang\Resources\BarangResource\Pages;

class BarangResource extends Resource
{
    protected static ?string $cluster = MasterBarang::class;

    protected static ?string $model = Barang::class;

    protected static ?string $pluralModelLabel = 'Data Barang';

    protected static ?string $navigationLabel = 'Data Barang';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $slug = 'data-barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_barang')->label('Kode Barang')
                    ->unique(ignoreRecord: true)
                    ->autocapitalize('characters')->required(),
                TextInput::make('nama_barang')->label('Nama Barang')
                    ->autocapitalize('sentences')->required(),
                Select::make('merek_barang_id')->label('Merek Barang')
                    ->relationship('merekBarang', 'nama_merek')
                    ->searchable()->preload()->native(false)
                    ->createOptionForm(
                        fn(Form $form) => MerekBarangResource::form($form)
                            ->columns(['md' => 2])
                    )
                    ->required(),
                Select::make('jenis_barang_id')->label('Jenis Barang')
                    ->relationship('jenisBarang', 'nama_jenis')
                    ->searchable()->preload()->native(false)
                    ->createOptionForm(
                        fn(Form $form) => JenisBarangResource::form($form)
                            ->columns(['md' => 2])
                    )
                    ->required(),
                TextInput::make('jumlah_per_grosir')->label('Jumlah / Grosir')
                    ->numeric()->minValue(0)->default(0)->required(),
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
                TextColumn::make('merekBarang.nama_merek')->label('Merek')
                    ->searchable(),
                TextColumn::make('jenisBarang.nama_jenis')->label('Jenis')
                    ->searchable(),
                TextColumn::make('total_stock')->label('Total Stock')->numeric()
                    ->default(0)
                    ->formatStateUsing(function(Barang $model) {
                        return $model->detailBarangs()->sum('stock');
                    })->sortable(),
            ])

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('white'),
                Tables\Actions\Action::make('delete')->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Barang')
                    ->modalSubheading('Konfirmasi untuk menghapus data ini')
                    ->modalButton('Hapus')
                    ->modalCloseButton()
                    ->modalCancelActionLabel('Batalkan')
                    ->icon('heroicon-c-trash')->color('danger')
                    ->action(function (Barang $record) {
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkAction::make('delete')->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Barang yang Terpilih')
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
            DetailBarangsRelationManager::class,
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

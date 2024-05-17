<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources;

use Filament\Tables;
use App\Models\Karyawan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Clusters\MasterKaryawan;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Clusters\MasterKaryawan\Resources\KaryawanResource\Pages;

class KaryawanResource extends Resource
{
    protected static ?string $cluster = MasterKaryawan::class;

    protected static ?string $model = Karyawan::class;

    protected static ?string $pluralModelLabel = 'Data Karyawan';

    protected static ?string $navigationLabel = 'Data Karyawan';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $slug = 'data';

    protected static ?int $navigationSort = 1;

    public static array $karyawanTypes = [
        'Non-Kasir' => 'Non-Kasir',
        'Kasir' => 'Kasir',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_lengkap')->label('Nama Lengkap')
                    ->autocapitalize('words')->required(),
                TextInput::make('alamat')->autocapitalize('sentences')
                    ->required(),
                TextInput::make('telepon')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
                TextInput::make('no_hp')->label('Nomor Hp')->tel()
                    ->maxLength(13)->telRegex('/^08[1-9][0-9]{6,10}$/')
                    ->required(),
                Select::make('tipe')->label('Tipe Karyawan')
                    ->native(false)->options(KaryawanResource:: $karyawanTypes)
                    ->default('Non-Kasir')->required(),
                TextInput::make('gaji')->numeric()->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)'))->stripCharacters(',')
                    ->minValue(1)->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama')->searchable(),
                TextColumn::make('alamat')->searchable(),
                TextColumn::make('telepon')->searchable()->placeholder('-'),
                TextColumn::make('no_hp')->label('Nomor Hp')->searchable(),
                TextColumn::make('tipe')->label('Pekerjaan')
                    ->badge()->searchable(),
                TextColumn::make('gaji_bln_ini')->label('Gaji Bulan Ini')->money('Rp ')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipe')->label('Pekerjaan')
                    ->options(self::$karyawanTypes)
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('white'),
                Tables\Actions\DeleteAction::make()->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
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
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}

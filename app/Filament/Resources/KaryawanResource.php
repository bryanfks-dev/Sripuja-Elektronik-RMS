<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Karyawan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\KaryawanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KaryawanResource\RelationManagers;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $modelLabel = 'Karyawan';

    protected static ?string $navigationGroup = 'Relasi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-s-user';

    protected static ?string $navigationLabel = 'Karyawan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_lengkap')->label('Nama Lengkap')
                    ->autocapitalize()
                    ->required(),
                TextInput::make('alamat')->autocapitalize()->required(),
                TextInput::make('telepon')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
                TextInput::make('no_hp')->label('Nomor Hp')->tel()
                    ->maxLength(12)->prefix('+62')
                    ->telRegex('/^8[1-9][0-9]{6,12}$/')->required(),
                TextInput::make('gaji')->numeric()->prefix('Rp.')
                    ->mask(RawJs::make('$money($input)'))->stripCharacters(',')
                    ->minValue(1)->required(),
                Select::make('tipe')->label('Tipe Karyawan')
                    ->options([
                        'Non-Kasir' => 'Non-Kasir',
                        'Kasir' => 'Kasir'
                    ])
                    ->default('Non-Kasir')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama Karyawan')->searchable(),
                TextColumn::make('alamat')->searchable(),
                TextColumn::make('no_hp')->label('Nomor Hp')->searchable(),
                TextColumn::make('gaji')->money('Rp ')->sortable(),
                TextColumn::make('tipe')->label('Tipe Karyawan')->searchable(),
            ])
            ->filters([
                SelectFilter::make('tipe')->label('Tipe Karyawan')
                ->options([
                    'Non-Kasir', 'Kasir'
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListKaryawans::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}

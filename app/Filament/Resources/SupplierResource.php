<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\SupplierResource\Pages;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $pluralModelLabel = 'Data Supplier';

    protected static ?string $slug = 'relasi/supplier';

    protected static ?string $navigationGroup = 'Relasi';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-s-building-office-2';

    protected static ?string $navigationLabel = 'Supplier';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->autocapitalize('characters')
                    ->required(),
                TextInput::make('alamat')->autocapitalize('sentences')
                    ->required(),
                TextInput::make('telepon')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
                TextInput::make('no_hp')->label('Nomor Hp')->tel()
                    ->maxLength(13)->telRegex('/^08[1-9][0-9]{6,10}$/')
                    ->required(),
                TextInput::make('fax')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
                TextInput::make('nama_sales')->label('Nama Sales')
                    ->autocapitalize('words')->required(),
                TextInput::make('no_hp_sales')->label('Nomor Hp Sales')
                    ->tel()->maxLength(13)->telRegex('/^08[1-9][0-9]{6,10}+$/')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama Supplier')
                    ->searchable(),
                TextColumn::make('alamat'),
                TextColumn::make('telepon')->placeholder('-'),
                TextColumn::make('no_hp')->label('Nomor Hp'),
                TextColumn::make('fax')->placeholder('-'),
                TextColumn::make('nama_sales')->label('Nama Sales')
                    ->searchable(),
                TextColumn::make('no_hp_sales')->label('Nomor Hp Sales'),
                TextColumn::make('pembelian_terakhir')->label('Pembelian Terakhir')
                    ->date('d M Y')->placeholder('-')
                    ->getStateUsing(function (Supplier $model) {
                        return $model->pembelians()->latest()->first('created_at')->created_at;
                    })
                    ->sortable(),
            ])
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}

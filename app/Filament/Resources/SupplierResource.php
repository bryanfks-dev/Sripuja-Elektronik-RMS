<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationGroup = 'Relasi';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-s-building-office-2';

    protected static ?string $navigationLabel = 'Supplier';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->autocapitalize()->required(),
                TextInput::make('alamat')->autocapitalize()->required(),
                TextInput::make('telepon')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
                TextInput::make('no_hp')->label('Nomor Hp')->tel()
                    ->prefix('+62')->maxLength(12)
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/')->required(),
                TextInput::make('fax')->tel()
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/'),
                TextInput::make('nama_sales')->label('Nama Sales')
                    ->autocapitalize()->required(),
                TextInput::make('no_hp_sales')->label('Nomor Hp Sales')
                    ->tel()->prefix('+62')->maxLength(12)
                    ->telRegex('/^[(]?[0-9]{1,4}[)]?[0-9]+$/')->required(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources\PenjualanResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailPenjualanRelationManager extends RelationManager
{
    protected static string $relationship = 'DetailPenjualan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_barang')->label('Nama Barang')
                    ->relationship('barang', 'nama_barang')
                    ->searchable()->preload()->required(),
                TextInput::make('jumlah')->numeric()->minValue(1)
                    ->required(),
                TextInput::make('harga_jual')->label('Harga Jual')
                    ->numeric()->prefix('Rp')->maxValue(1)
                    ->default(function ($get) {
                        return Barang::find($get('id_barang')->harga_jual);
                    })
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_barang')
            ->columns([
                TextColumn::make('nama_barang')->label('Nama Barang'),
                TextColumn::make('jumlah'),
                TextColumn::make('harga_jual')->label('Harga Jual'),
                TextColumn::make('sub_total')->label('Sub Total'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->color('white'),
                Tables\Actions\DeleteAction::make(),
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
}

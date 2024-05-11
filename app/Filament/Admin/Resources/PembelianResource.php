<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PembelianResource\Pages;
use App\Filament\Admin\Resources\PembelianResource\RelationManagers;
use App\Models\Pembelian;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $navigationGroup = 'Data Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-cart';

    protected static ?string $navigationLabel = 'Pembelian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('id_supplier')->label('Nama Supplier')
                    ->options(Supplier::all()
                        ->pluck('Nama', 'Id_Supplier'))->searchable()
                    ->createOptionForm(
                        fn(Form $form) => SupplierResource::form($form)
                    )
                    ->required(),
                TextInput::make('no_nota')->label('Nomor Nota')
                    ->required(),
                DateTimePicker::make('tanggal_waktu')->label('Tanggal & Waktu Pembelian')
                    ->seconds(false),
                DatePicker::make('tanggal_jatuh_tempo')->label('Jatuh Tempo Pembayaran')
                    ->required(),
                Select::make('status')->label('Status Pembayaran')
                    ->options([
                        'belum_lunas' => 'Belum Lunas',
                        'lunas' => 'Lunas'
                    ])->default('belum_lunas')->required()
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}

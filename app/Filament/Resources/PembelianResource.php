<?php

namespace App\Filament\Resources;

use App\Models\Nota;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\DetailPembelian;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\SupplierResource;
use App\Filament\Resources\PembelianResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;
use App\Filament\Clusters\MasterBarang\Resources\BarangResource;

class PembelianResource extends Resource
{
    protected static ?string $model = Pembelian::class;

    protected static ?string $pluralModelLabel = 'Data Pembelian';

    protected static ?string $slug = 'transaksi/pembelian';

    protected static ?string $navigationGroup = 'Data Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-s-shopping-cart';

    protected static ?string $navigationLabel = 'Pembelian';

    protected static array $statues = [
        'Belum Lunas' => 'Belum Lunas',
        'Lunas' => 'Lunas'
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pembelian')
                    ->schema([
                        TextInput::make('no_nota')->label('Nomor Nota')
                            ->autocapitalize('characters')
                            ->default(Nota::generateNoNotaPembelian())->required(),
                        TextInput::make('no_faktur')->label('Nomor Faktur')
                            ->autocapitalize('characters')->required(),
                        DatePicker::make('jatuh_tempo')->label('Jatuh Tempo')
                            ->required(),
                        DatePicker::make('created_at')->label('Tanggal')
                            ->default(now())->dehydrated(false)->readOnly(),
                        Select::make('supplier_id')->label('Nama Supplier')
                            ->relationship('supplier', 'nama')
                            ->searchable()->preload()->native(false)
                            ->createOptionForm(
                                fn(Form $form) => SupplierResource::form($form)
                                    ->columns(['md' => 2])
                            )
                            ->required(),
                        Select::make('status')->label('Status Pembayaran')
                            ->native(false)->options(self::$statues)
                            ->default('Belum Lunas')->required()
                    ])
                    ->columns(['md' => 2]),
                Section::make('Detail Pembelian')
                    ->schema([
                        TableRepeater::make('detail_pembelian')
                            ->hiddenLabel()
                            ->relationship('detailPembelians')
                            ->headers([
                                Header::make('barang_id')->label('Nama Barang')
                                    ->width('50%')->markAsRequired(),
                                Header::make('jumlah')->width('10%')
                                    ->markAsRequired(),
                                Header::make('sub_total')->label('Sub Total')
                                    ->markAsRequired(),
                            ])
                            ->schema([
                                Select::make('barang_id')->relationship('barang', 'nama_barang')
                                    ->createOptionForm(
                                        fn(Form $form) => BarangResource::form($form)
                                            ->columns(['md' => 2])
                                    )
                                    ->live()->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            self::updateDatas($get, $set);
                                        }
                                    )
                                    ->native(false)->required(),
                                TextInput::make('jumlah')->numeric()->default(1)
                                    ->minValue(1)->live()->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            self::updateDatas($get, $set);
                                        }
                                    )
                                    ->required(),
                                TextInput::make('sub_total')->prefix('Rp ')
                                    ->default(0)->numeric()->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')->readOnly(),
                            ])
                            ->columnSpan('full')->stackAt(MaxWidth::Medium)
                            ->createItemButtonLabel('Tambah Pembelian')
                            ->emptyLabel('Tidak ada detail pembelian')
                            // Mutate data before save in create mode
                            ->mutateRelationshipDataBeforeCreateUsing(
                                function (array $data) {
                                    Barang::modifyStock($data['barang_id'], $data['jumlah']);

                                    return $data;
                                }
                            )
                            // Mutate Data before save in editing mode
                            ->mutateRelationshipDataBeforeSaveUsing(
                                function (array $data, Model $model) {
                                    // User not changing the nama_barang
                                    if ($model->barang_id == $data['barang_id']) {
                                        // For updating stock value, we could asume for n is stock value
                                        // and new n = n + x, with x is a difference between old and new
                                        // value of jumlah in detail_pembelian.

                                        // Evaluate jumlah value
                                        $detailPembelianOldJumlah = $model->jumlah;

                                        // Calculate difference value between old and new value
                                        $diff = $data['jumlah'] - $detailPembelianOldJumlah;

                                        Barang::modifyStock($data['barang_id'], $diff);
                                    } else {
                                        // Normalize barang stock
                                        Barang::modifyStock($model->barang_id, -1 * $model->jumlah);

                                        // Add new jumlah to other barang stock
                                        Barang::modifyStock($data['barang_id'], $data['jumlah']);
                                    }

                                    return $data;
                                }
                            )
                    ])
            ]);
    }

    private static function updateDatas(Get $get, Set $set)
    {
        $barangId = $get('barang_id');

        if (!empty($barangId)) {
            $barang = Barang::find($barangId);

            // The math function can be describes as
            // sub_total = (jumlah_grosir * harga_grosir) + (remaining_jumlah * harga_beli),
            // where jumlah_grosir = jumlah / jumlah_per_grosir
            // remaining_jumlah = jumlah % jumlah_grosir

            $jumlah = intval($get('jumlah'));

            $jumlahGrosir = intdiv($jumlah, $barang->jumlah_per_grosir);
            $remainingJumlah = $jumlah % $barang->jumlah_per_grosir;

            $set('sub_total', ($jumlahGrosir * $barang->harga_grosir)
                + ($remainingJumlah * $barang->harga_beli));
        } else {
            $set('sub_total', 0);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_nota')->label('Nomor Nota')
                    ->searchable(),
                TextColumn::make('created_at')->label('Tanggal Pembelian')
                    ->date('d M Y')->sortable(),
                TextColumn::make('jatuh_tempo')->label('Jatuh Tempo')
                    ->date('d M Y')->sortable(),
                TextColumn::make('supplier.nama')->label('Nama Supplier')
                    ->searchable(),
                TextColumn::make('status')->label('Status Pembayaran')
                    ->badge(),
                TextColumn::make('total')->label('Total')->money('Rp ')
                    ->getStateUsing(function (Pembelian $model) {
                        $pembelianId = $model->id;

                        $subTotals = DetailPembelian::where(
                            'pembelian_id',
                            '=',
                            $pembelianId
                        )->sum('sub_total');

                        return $subTotals;
                    })
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Status Pembayaran')
                    ->options(self::$statues)
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
            'index' => Pages\ListPembelians::route('/'),
            'create' => Pages\CreatePembelian::route('/create'),
            'edit' => Pages\EditPembelian::route('/{record}/edit'),
        ];
    }
}

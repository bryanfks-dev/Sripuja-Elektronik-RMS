<?php

namespace App\Filament\Resources;

use App\Models\DetailBarang;
use App\Models\Nota;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\SupplierResource;
use Illuminate\Database\Eloquent\Collection;
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

    public static array $statues = [
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
                            ->unique(ignoreRecord: true)
                            ->autocapitalize('characters')
                            ->default(Nota::generateNoNotaPembelian())->required(),
                        TextInput::make('no_faktur')->label('Nomor Faktur')
                            ->unique(ignoreRecord: true)->autocapitalize('characters')
                            ->required(),
                        DatePicker::make('created_at')->label(label: 'Tanggal')
                            ->default(now())->dehydrated(false)->readOnly(),
                        DatePicker::make('jatuh_tempo')->label('Jatuh Tempo')
                            ->native(false)->displayFormat('m / d / Y')
                            ->placeholder('mm-dd-yy')->minDate(now())
                            ->closeOnDateSelection()->required(),
                        Select::make('supplier_id')->label('Nama Supplier')
                            ->relationship('supplier', 'nama_supplier')
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
                            ->minItems(1)
                            ->headers([
                                Header::make('barang_id')->label('Nama Barang')
                                    ->width('20%')->markAsRequired(),
                                Header::make('jumlah')->markAsRequired(),
                                Header::make('harga_beli')->label('Harga Beli')
                                    ->markAsRequired(),
                                Header::make('harga_jual')->label('Harga Jual')
                                    ->markAsRequired(),
                                Header::make('harga_grosir')->label('Harga Grosir')
                                    ->markAsRequired(),
                                Header::make('sub_total')->label('Sub Total')
                                    ->markAsRequired(),
                            ])
                            ->schema([
                                Select::make('barang_id')->options(
                                    Barang::all()->pluck('nama_barang', 'id')
                                )
                                    ->createOptionForm(
                                        fn(Form $form) => BarangResource::form($form)
                                            ->model(Barang::class)
                                            ->columns(['md' => 2])
                                    )
                                    ->createOptionUsing(function ($data) {
                                        $newBarang = Barang::create($data);

                                        return $newBarang->id;
                                    })
                                    ->searchable()->live(true)->native(false)
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateUpdated(
                                        fn(Get $get, Set $set) =>
                                        self::updateDatas($get, $set)
                                    ),
                                TextInput::make('jumlah')->numeric()->default(1)
                                    ->minValue(1)->live(true, 600)
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->afterStateUpdated(
                                        fn(Get $get, Set $set) => self::setSubTotal($get, $set)
                                    ),
                                TextInput::make('harga_beli')->numeric()->prefix('Rp ')
                                    ->default(0)->mask(RawJs::make('$money($input)'))
                                    ->minValue(1)->stripCharacters(',')
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->live(true)->afterStateUpdated(
                                        fn(Get $get, Set $set) => self::setSubTotal($get, $set)
                                    ),
                                TextInput::make('harga_jual')->prefix('Rp ')
                                    ->default(0)->numeric()->mask(RawJs::make('$money($input)'))
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->minValue(1)->stripCharacters(','),
                                TextInput::make('harga_grosir')->prefix('Rp ')
                                    ->default(0)->numeric()->mask(RawJs::make('$money($input)'))
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->minValue(1)->stripCharacters(','),
                                TextInput::make('sub_total')->prefix('Rp ')
                                    ->default(0)->numeric()->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')->readOnly(),
                            ])
                            ->columnSpan('full')->stackAt(MaxWidth::Medium)
                            ->createItemButtonLabel('Tambah Pembelian')
                            ->emptyLabel('Tidak ada detail pembelian')
                            // Mutate data before save in create mode
                            ->mutateRelationshipDataBeforeCreateUsing(
                                fn(array $data) => self::createRecord($data)
                            ),
                        Section::make()
                            ->extraAttributes(['class' => '!mt-6'])
                            ->schema([
                                Placeholder::make('total_pembelian')->label('Total Biaya Pembelian')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'text-right font-semibold'])
                                    ->content(function (Get $get) {
                                        $total =
                                            collect($get('detail_pembelian'))->pluck('sub_total')->map(fn(?string $subTotal) =>
                                                intval(str_replace(',', '', $subTotal)));
                                        $total = $total->sum();

                                        return 'Rp ' . number_format($total, 0, '.', '.');
                                    })
                            ]),
                    ])
            ]);
    }

    public static function updateDatas(Get $get, Set $set)
    {
        $barangId = $get('barang_id');

        if (!empty($barangId)) {
            // Get related barang in detail_barang
            $barang = Barang::find($barangId);
            $detailBarang = $barang->detailBarangs()->latest()->first();

            if (isset($detailBarang)) {
                $set('harga_beli', $detailBarang->harga_beli);
                $set('harga_jual', $detailBarang->harga_jual);
                $set('harga_grosir', $detailBarang->harga_grosir);
            } else {
                $set('harga_beli', 0);
                $set('harga_jual', 0);
                $set('harga_grosir', 0);
            }

            self::setSubTotal($get, $set);
        }
    }

    public static function createRecord(array $data)
    {
        // Add detail barang
        $detailBarangId =
        DetailBarang::create([
            'barang_id' => $data['barang_id'],
            'stock' => $data['jumlah'],
            'harga_beli' => $data['harga_beli'],
            'harga_jual' => $data['harga_jual'],
            'harga_grosir' => $data['harga_grosir'],
        ]);

        $data['detail_barang_id'] = $detailBarangId->id;

        unset (
            $data['barang_id'],
            $data['harga_beli'],
            $data['harga_jual'],
            $data['harga_grosir']
        );

        return $data;
    }

    public static function setSubTotal(Get $get, Set $set)
    {
        $barangId = $get('barang_id');
        $jumlah = intval($get('jumlah'));
        $hargaBeli = intval(str_replace(',', '', $get('harga_beli')));

        if (!empty($barangId) && $jumlah > 0 && $hargaBeli > 0) {
            $set('sub_total', $jumlah * $hargaBeli);
        } else {
            $set('sub_total', 0);
        }
    }

    public static function deletePembelian(Pembelian $record)
    {
        $detailPembelians = $record->detailPembelians()->get();

        // Delete detail barangs
        foreach ($detailPembelians as $detail) {
            DetailBarang::destroy($detail->id);
        }

        $record->delete();
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
                TextColumn::make('supplier.nama_supplier')->label('Nama Supplier')
                    ->searchable(),
                TextColumn::make('status')->label('Status Pembayaran')
                    ->badge(),
                TextColumn::make('total')->label('Total')->money('Rp ')
                    ->getStateUsing(
                        fn(Pembelian $model) =>
                        $model->detailPembelians()->sum('sub_total')
                    )
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->label('Status Pembayaran')
                    ->options(self::$statues),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Periode Awal')
                            ->placeholder('mm / dd / yy')->native(false),
                        DatePicker::make('created_until')->label('Periode Akhir')
                            ->placeholder('mm / dd / yy')->native(false)
                            ->minDate(fn(Get $get) => $get('created_from')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('white'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('delete')->label('Hapus')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Pembelian yang Terpilih')
                    ->modalSubheading('Konfirmasi untuk menghapus data-data yang terpilih')
                    ->modalButton('Hapus')
                    ->modalCloseButton()
                    ->modalCancelActionLabel('Batalkan')
                    ->icon('heroicon-c-trash')->color('danger')
                    ->action(function (Collection $records) {
                        $records->each(fn(Pembelian $record) =>
                            self::deletePembelian($record));
                    }),
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

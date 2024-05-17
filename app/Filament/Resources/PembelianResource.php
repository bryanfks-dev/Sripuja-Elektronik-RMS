<?php

namespace App\Filament\Resources;

use App\Models\DetailPembelian;
use App\Models\Nota;
use Filament\Forms\Components\Placeholder;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
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
                                    ->native(false)->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required(),
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
                                function (array $data, DetailPembelian $model) {
                                    // There 2 likely case when user updating things
                                    // First, whether user upadting the indetifier
                                    // Second, user not updating the indetifier

                                    // In this context, barang_id is the identifier of
                                    // the record

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
                            ),
                        Section::make()
                            ->extraAttributes(['class' => '!mt-6'])
                            ->schema([
                                Placeholder::make('total_pembelian')->label('Total Biaya Pembelian')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'text-right font-semibold'])
                                    ->content(function (Get $get) {
                                        $sum = 0;

                                        $pembelians = $get('detail_pembelian');

                                        foreach ($pembelians as $data) {
                                            $sum += $data['sub_total'];
                                        }

                                        return 'Rp ' . number_format($sum, 2);
                                    })
                            ]),
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
                        DatePicker::make('created_from')->label('Periode Awal'),
                        DatePicker::make('created_until')->label('Periode Akhir'),
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

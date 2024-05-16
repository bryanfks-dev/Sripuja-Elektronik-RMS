<?php

namespace App\Filament\Resources;

use App\Models\Nota;
use App\Models\User;
use Filament\Tables;
use App\Models\Barang;
use App\Models\Invoice;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Penjualan;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use App\Models\DetailPenjualan;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Support\Colors\Color;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PelangganResource;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\PenjualanResource\Pages;
use Awcodes\TableRepeater\Components\TableRepeater;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $pluralModelLabel = 'Data Penjualan';

    protected static ?string $slug = 'transaksi/penjualan';

    protected static ?string $navigationGroup = 'Data Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-s-truck';

    protected static ?string $navigationLabel = 'Penjualan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Penjualan')
                    ->schema([
                        TextInput::make('no_nota')->label('Nomor Nota')
                            ->autocapitalize('characters')
                            ->formatStateUsing(fn() => Nota::generateNoNotaPenjualan())
                            ->readOnly(),
                        TextInput::make('no_invoice')->label('Nomor Invoice')
                            ->autocapitalize('characters')
                            ->formatStateUsing(fn() => Invoice::generateNoInvoice())
                            ->readOnly(),
                        DatePicker::make('created_at')->label('Tanggal')
                            ->default(now())->dehydrated(false)->readOnly(),
                        Select::make('pelanggan_id')->label('Nama Pelanggan')
                            ->relationship('pelanggan', 'nama_lengkap')
                            ->createOptionForm(
                                fn(Form $form) => PelangganResource::form($form)
                            )
                            ->native(false)->preload()->searchable()
                            ->required(),
                    ])->columns(['md' => 2]),
                Section::make('Detail Penjualan')
                    ->schema([
                        TableRepeater::make('detail_penjualan')
                            ->hiddenLabel()
                            ->relationship('detailPenjualans')
                            ->headers([
                                Header::make('barang_id')->label('Nama Barang')
                                    ->width('50%')->markAsRequired(),
                                Header::make('jumlah')->width('10%')
                                    ->markAsRequired(),
                                Header::make('harga_jual')->label('Harga Jual')
                                    ->markAsRequired(),
                                Header::make('sub_total')->label('Sub Total')
                                    ->markAsRequired()
                            ])
                            ->schema([
                                Select::make('barang_id')->relationship('barang', 'nama_barang')
                                    ->native(false)->preload()->searchable()
                                    ->live()->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            self::updateDatasPrimary($get, $set);
                                        }
                                    )
                                    ->required(),
                                TextInput::make('jumlah')->numeric()->minValue(1)
                                    ->default(1)->maxValue(function (Get $get) {
                                        if (!empty ($barangId = $get('barang_id'))) {
                                            return Barang::find($barangId)->stock;
                                        }
                                    })->live()
                                    ->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            self::updateDatasSecondary($get, $set);
                                        }
                                    )
                                    ->required(),
                                TextInput::make('harga_jual')->prefix('Rp ')
                                    ->numeric()->default(0)->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->live()->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            self::updateDatasSecondary($get, $set);
                                        }
                                    )
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->required(),
                                TextInput::make('sub_total')->prefix('Rp ')
                                    ->numeric()->default(0)->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')->readOnly(),
                            ])
                    ])
            ]);
    }

    private static function getSubTotal($jumlah, $barang, $hargaJual = null)
    {
        if ($hargaJual == null) {
            $hargaJual = $barang->harga_jual;
        }

        $jumlahGrosir = intdiv($jumlah, $barang->jumlah_per_grosir);
        $remainingJumlah = $jumlah % $barang->jumlah_per_grosir;

        return ($jumlahGrosir * $barang->harga_grosir) + ($remainingJumlah * $hargaJual);
    }

    private static function updateDatasPrimary(Get $get, Set $set)
    {
        $barangId = $get('barang_id');

        if (!empty($barangId)) {
            $barang = Barang::find($barangId);

            // The math function can be describes as
            // sub_total = (jumlah_grosir * harga_grosir) + (remaining_jumlah * harga_beli),
            // where jumlah_grosir = jumlah / jumlah_per_grosir
            // remaining_jumlah = jumlah % jumlah_grosir

            $jumlah = intval($get('jumlah'));

            $set('harga_jual', $barang->harga_jual);
            $set('sub_total', self::getSubTotal($jumlah, $barang));
        } else {
            $set('harga_jual', 0);
            $set('sub_total', 0);
        }
    }

    private static function updateDatasSecondary(Get $get, Set $set)
    {
        $barangId = $get('barang_id');

        if (!empty($barangId)) {
            $barang = Barang::find($barangId);
            $jumlah = intval($get('jumlah'));

            $set(
                'sub_total',
                self::getSubTotal(
                    $jumlah,
                    $barang,
                    intval(str_replace(',', '', $get('harga_jual')))
                )
            );
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_nota')->label('Nomor Nota')
                    ->searchable(),
                TextColumn::make('invoice.no_invoice')->label('Nomor Invoice')
                    ->searchable(),
                TextColumn::make('created_at')->label('Tanggal Penjualan')
                    ->date('d M Y')->sortable(),
                TextColumn::make('pelanggan.nama_lengkap')->label('Nama Pelanggan')
                    ->searchable(),
                TextColumn::make('total_pembelian')->label('Total Pembelian')
                    ->money('Rp ')->default(0)->placeholder('-')
                    ->getStateUsing(function (Penjualan $model) {
                        $detailPenjualans =
                            DetailPenjualan::where('penjualan_id', '=', $model->id)
                                ->sum('sub_total');

                        return $detailPenjualans;
                    })->sortable(),
                TextColumn::make('user.username')->label('Kasir')
                    ->color(
                        function (Penjualan $model) {
                            return (User::find($model->user_id)->email != null) ?
                                Color::Green : Color::Amber;
                        }
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                MultiSelectFilter::make('user_id')->label('Username Kasir')
                    ->relationship(
                        'user',
                        'username',
                        fn(Builder $query) => $query
                            ->join('karyawans', 'users.id', '=', 'karyawans.user_id', 'left')
                            ->where('email', '<>', 'NULL')
                            ->orWhere('karyawans.tipe', '=', 'Kasir')
                    )
                    ->preload()->searchable(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Periode Awal'),
                        DatePicker::make('created_until')->label('Periode Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}

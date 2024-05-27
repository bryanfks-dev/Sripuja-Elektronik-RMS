<?php

namespace App\Filament\Resources;

use App\Models\DetailBarang;
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
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\PelangganResource;
use Filament\Forms\Components\Actions\Action;
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

    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isKaryawanKasir();
    }

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
                            ->minItems(1)
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
                                    ->live(true)->afterStateUpdated(
                                        function (Get $get, Set $set) {
                                            self::updateDatas($get, $set);
                                        }
                                    )
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()->required(),
                                TextInput::make('jumlah')->numeric()->minValue(1)
                                    ->default(1)->maxValue(function (Penjualan $data, Get $get) {
                                        if (!empty ($barangId = $get('barang_id'))) {
                                            $detailPenjualan = $data->detailPenjualans()
                                                ->where('barang_id', '=', $barangId)->first();

                                            if (isset($detailPenjualan)) {
                                                return Barang::find($barangId)->detailBarangs()->sum('stock')
                                                    + self::sumJumlah(json_decode($detailPenjualan->jumlah, true));
                                            }

                                            return Barang::find($barangId)->detailBarangs()->sum('stock');
                                        }
                                    })->live(true, 600)
                                    ->afterStateUpdated(
                                        fn(Get $get, Set $set) =>
                                            self::setSubTotal($get, $set)
                                    )
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->required(),
                                TextInput::make('harga_jual')->prefix('Rp ')
                                    ->numeric()->default(0)->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->live(true, 600)->afterStateUpdated(
                                        fn (Get $get, Set $set) =>
                                            self::setSubTotal($get, $set)
                                    )
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->required(),
                                TextInput::make('sub_total')->prefix('Rp ')
                                    ->numeric()->default(0)->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')->readOnly(),
                            ])
                            ->columnSpan('full')->stackAt(MaxWidth::Medium)
                            ->createItemButtonLabel('Tambah Penjualan')
                            ->emptyLabel('Tidak ada detail penjualan')
                            ->deleteAction(function (Action $action) {
                                $action->before(function ($state, array $arguments) {
                                    $record = $state[$arguments['item']];

                                    if (isset ($record['id']) && isset ($record['barang_id'])) {
                                        Barang::modifyStock($record['barang_id'], $record['jumlah']);
                                    }
                                });
                            })
                            ->mutateRelationshipDataBeforeFillUsing(
                                function(array $data) {
                                    return [
                                        'barang_id' => $data['barang_id'],
                                        'jumlah' => self::sumJumlah(
                                            json_decode($data['jumlah'], true)),
                                        'harga_jual' => $data['harga_jual'],
                                        'sub_total' => $data['sub_total'],
                                    ];
                                }
                            )
                            // Mutate data before save in create mode
                            ->mutateRelationshipDataBeforeCreateUsing(
                                function (array $data) {
                                    $detailBarangs =
                                        Barang::find($data['barang_id'])->detailBarangs()->get();

                                    $tempJumlah = intval($data['jumlah']);

                                    $jumlahJson = [];

                                    // Iterrate through all detail_barangs
                                    // Drop barang stocks
                                    foreach ($detailBarangs as $detail) {
                                        if ($tempJumlah == 0) {
                                            break;
                                        }

                                        if ($detail->stock > 0 && $tempJumlah > 0) {
                                            // Case when jumlah is bigger than current detail barang
                                            if ($tempJumlah >= $detail->stock) {
                                                $tempJumlah -= $detail->stock;
                                                $jumlahJson[$detail->id] = $detail->stock;

                                                DetailBarang::setStock($detail->id, 0);

                                                continue;
                                            }

                                            $jumlahJson[$detail->id] = $tempJumlah;

                                            // Case when jumlah is smaller than current detail barang
                                            DetailBarang::modifyStock($detail->id, -1 * $tempJumlah);

                                            $tempJumlah = 0;
                                        }
                                    }

                                    $data['jumlah'] = json_encode($jumlahJson);

                                    return $data;
                                }
                            )
                            // Mutate Data before save in editing mode
                            ->mutateRelationshipDataBeforeSaveUsing(
                                function (array $data, DetailPenjualan $model) {
                                    // There 2 likely case when user updating things
                                    // First, whether user upadting the indetifier
                                    // Second, user not updating the indetifier

                                    // In this context, barang_id is the identifier of
                                    // the record

                                    $model = $model->detailBarang()->first();
                                    $model->jumlah = json_decode($model->jumlah, true);

                                    // User not changing the nama_barang
                                    if ($model->barang_id == $data['barang_id']) {
                                        // For updating stock value, we could asume for n is stock value
                                        // and new n = n + x, with x is a difference between old and new
                                        // value of jumlah in detail_penjualan.

                                        // Calculate difference value between old and new value
                                        $diff = $data['jumlah'] - self::sumJumlah($model->jumlah);

                                        // User input newest jumlah less than old value
                                        if ($diff < 0) {
                                            for ($i = count($model->jumlah) - 1; $i >= 0; $i--) {
                                                if ($diff == 0) {
                                                    break;
                                                }

                                                $currJumlah = $model->jumlah[$i];
                                                $key = array_keys($model->jumlah)[$i];

                                                if (-1 * $diff > $currJumlah) {
                                                    $diff += $currJumlah;

                                                    DetailBarang::modifyStock(intval($key), $currJumlah);

                                                    unset ($currJumlah);

                                                    continue;
                                                }

                                                $currJumlah += $diff;

                                                DetailBarang::modifyStock(intval($key), $currJumlah);
                                            }
                                        } else {
                                            end(array: $model->jumlah);
                                            $key = key($model->jumlah);

                                            $model->jumlah[$key] += $diff;

                                            DetailBarang::modifyStock(intval($key), -1 * $diff);
                                        }
                                    } else {
                                        // Modify detail barang stocks
                                        foreach ($model->jumlah as $key => $value) {
                                            // Assume data json would be like this:
                                            // [
                                            //      "id": jumlah_val,
                                            //      .
                                            //      .
                                            // ]
                                            // So, id would be a string, while jumlah_val always an integer
                                            DetailBarang::modifyStock(intval($key), $value);
                                        }
                                    }

                                    $model->jumlah = json_encode($model->jumlah);

                                    return $data;
                                }
                            ),
                        Section::make()
                            ->extraAttributes(['class' => '!mt-6'])
                            ->schema([
                                Placeholder::make('total_penjualan')->label('Total Biaya Penjualan')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'text-right font-semibold'])
                                    ->content(function (Get $get) {
                                        $total =
                                            collect($get('detail_penjualan'))->pluck('sub_total')->map(fn(?string $subTotal) =>
                                                intval(str_replace(',', '', $subTotal)));
                                        $total = $total->sum();

                                        return 'Rp ' . number_format($total, 0, '.', '.');
                                    })
                            ]),
                    ])
            ]);
    }

    private static function sumJumlah(array $data): int
    {
        // Assume data json would be like this:
        // [
        //      "id": jumlah_val,
        //      .
        //      .
        // ]

        return array_sum(array_values($data));
    }

    private static function updateDatas(Get $get, Set $set)
    {
        $barangId = $get('barang_id');

        if (!empty($barangId)) {
            $barang = Barang::find($barangId);
            $detailBarangs = $barang->detailBarangs()->get();

            $jumlah = intval($get('jumlah'));

            // There are 2 types of barang, newest and oldest
            // So, each barang should and ONLY contains these 2 detail barangs

            // Set harga_jual to the newest detail_barang
            if ($jumlah > $detailBarangs->first()->stock) {
                $set('harga_jual', $detailBarangs->last()->harga_jual);
            } else {
                $set('harga_jual', $detailBarangs->first()->harga_jual);
            }
        } else {
            $set('harga_jual', 0);
        }

        self::setSubTotal($get, $set);
    }

    private static function setSubTotal(Get $get, Set $set)
    {
        $barangId = $get('barang_id');

        if (!empty($barangId)) {
            $barang = Barang::find($barangId);
            $detailBarangs = $barang->detailBarangs()->get();

            $jumlah = intval($get('jumlah'));
            $hargaJual = intval(str_replace(',', '', $get('harga_jual')));

            // The math function can be describes as
            // sub_total = (jumlah_grosir * harga_grosir) + (remaining_jumlah * harga_beli),
            // where jumlah_grosir = jumlah / jumlah_per_grosir
            // remaining_jumlah = jumlah % jumlah_grosir

            if ($barang->jumlah_per_grosir > 1) {
                $jumlahGrosir = intdiv($jumlah, $barang->jumlah_per_grosir);
                $remainingJumlah = $jumlah - ($jumlahGrosir * $barang->jumlah_per_grosir);

                if ($jumlah > $detailBarangs->first()->stock) {
                    $set('sub_total', ($jumlahGrosir * $detailBarangs->last()->harga_grosir)
                        + ($remainingJumlah * $hargaJual));

                    return;
                }

                $set('sub_total', ($jumlahGrosir * $detailBarangs->first()->harga_grosir)
                    + ($remainingJumlah * $hargaJual));

                return;
            }

            $set('sub_total', ($jumlah * $hargaJual));

            return;
        }

        $set('sub_total', 0);
    }

    public static function deletePenjualan(Penjualan $record)
    {
        $detailPenjualans = $record->detailPenjualans()->get();

        // Iterate through all detail penjualans
        foreach ($detailPenjualans as $detail) {
            // Iterate through detail barangs
            foreach ($detail->detail_barangs as $key => $value) {
                DetailBarang::modifyStock(intval($key), $value);
            }
        }

        $record->delete();
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
                        ->getStateUsing(
                            fn(Penjualan $model) => $model->detailPenjualans()->sum('sub_total')
                        )->sortable(),
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
                    Tables\Actions\Action::make('delete')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Data Penjualan')
                        ->modalSubheading('Konfirmasi untuk menghapus data ini')
                        ->modalButton('Hapus')
                        ->modalCloseButton()
                        ->modalCancelActionLabel('Batalkan')
                        ->icon('heroicon-c-trash')->color('danger')
                        ->action(fn(Penjualan $record) => self::deletePenjualan($record)),
                ])
            ->bulkActions([
                    BulkAction::make('delete')
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Data Penjualan yang Terpilih')
                        ->modalSubheading('Konfirmasi untuk menghapus data-data yang terpilih')
                        ->modalButton('Hapus')
                        ->modalCloseButton()
                        ->modalCancelActionLabel('Batalkan')
                        ->icon('heroicon-c-trash')->color('danger')
                        ->action(function (Collection $records) {
                            $records->each(fn(Penjualan $record) =>
                                self::deletePenjualan($record));
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}

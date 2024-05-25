<?php

namespace App\Filament\Resources\PembelianResource\Pages;

use App\Models\Pembelian;
use Throwable;
use App\Models\Nota;
use Filament\Actions;
use App\Models\Barang;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\Support\Js;
use Filament\Support\RawJs;
use App\Models\DetailBarang;
use Filament\Actions\Action;
use App\Models\DetailPembelian;
use Awcodes\TableRepeater\Header;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\SupplierResource;
use App\Filament\Resources\PembelianResource;
use Awcodes\TableRepeater\Components\TableRepeater;
use App\Filament\Clusters\MasterBarang\Resources\BarangResource;

class EditPembelian extends EditRecord
{
    protected static string $resource = PembelianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus')
                ->action(fn(Pembelian $record) =>
                    PembelianResource::deletePembelian($record)),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Simpan')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batalkan')
            ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getResource()::getUrl()) . ')')
            ->color('gray');
    }

    public function form(Form $form): Form
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
                            ->native(false)->options(PembelianResource::$statues)
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
                                    ->width('40%')->markAsRequired(),
                                Header::make('jumlah')->markAsRequired(),
                                Header::make('harga_beli')->label('Harga Beli')
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
                                    ->afterStateUpdated(
                                        fn(Get $get, Set $set) =>
                                        PembelianResource::updateDatas($get, $set)
                                    )
                                    ->searchable()->live(true)
                                    ->native(false)->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required(),
                                TextInput::make('jumlah')->numeric()->default(1)
                                    ->minValue(1)->live(true,600)
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->afterStateUpdated(
                                        fn(Get $get, Set $set) =>
                                        PembelianResource::setSubTotal($get, $set)
                                    )
                                    ->required(),
                                TextInput::make('harga_beli')->prefix('Rp ')
                                    ->default(0)->numeric()->mask(RawJs::make('$money($input)'))
                                    ->minValue(1)->stripCharacters(',')
                                    ->disabled(fn(Get $get) => ($get('barang_id') == null))
                                    ->live(true)->afterStateUpdated(
                                        fn(Get $get, Set $set) =>
                                        PembelianResource::setSubTotal($get, $set)
                                    ),
                                TextInput::make('sub_total')->prefix('Rp ')
                                    ->default(0)->numeric()->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')->readOnly(),
                            ])
                            ->columnSpan('full')->stackAt(MaxWidth::Medium)
                            ->createItemButtonLabel('Tambah Pembelian')
                            ->emptyLabel('Tidak ada detail pembelian')
                            ->deleteAction(function (\Filament\Forms\Components\Actions\Action $action) {
                                $action->before(function ($state, array $arguments) {
                                    $record = $state[$arguments['item']];
                                    $queryRes =
                                        DetailPembelian::join('detail_barangs', 'detail_pembelians.detail_barang_id', '=', 'detail_barangs.id')
                                            ->where('detail_barangs.barang_id', '=', $record['barang_id'])
                                            ->first(['detail_pembelians.id', 'detail_pembelians.detail_barang_id']);

                                    // Delete detail barang
                                    if (isset($queryRes)) {
                                        try {
                                            DB::beginTransaction();

                                            DetailBarang::destroy($queryRes->detail_barang_id);
                                            DetailPembelian::destroy($queryRes->id);

                                            DB::commit();
                                        } catch (Halt $exception) {
                                            $exception->shouldRollbackDatabaseTransaction() ?
                                                DB::rollBack() :
                                                DB::commit();

                                            return;
                                        } catch (Throwable $exception) {
                                            DB::rollBack();

                                            throw $exception;
                                        }
                                    }
                                });
                            })
                            // Load datas from other relational table
                            ->mutateRelationshipDataBeforeFillUsing(
                                function (Get $get, array $data) {
                                    $detailBarang =
                                        DetailBarang::find($data['detail_barang_id']);

                                    return [
                                        'barang_id' => $detailBarang->barang_id ?? '',
                                        'jumlah' => $data['jumlah'],
                                        'harga_beli' => $detailBarang->harga_beli ?? 0,
                                        'sub_total' => $data['sub_total']
                                    ];
                                }
                            )
                            // Mutate data before save in create mode
                            ->mutateRelationshipDataBeforeCreateUsing(
                                fn(array $data) => PembelianResource::createRecord($data)
                            )
                            // Mutate Data before save in editing mode
                            ->mutateRelationshipDataBeforeSaveUsing(
                                function (array $data, DetailPembelian $model) {
                                    // There 2 likely case when user updating things
                                    // First, whether user upadting the indetifier
                                    // Second, user not updating the indetifier

                                    // In this context, barang_id is the identifier of
                                    // the record

                                    $model = $model->detailBarang()->first();

                                    // User not changing the nama_barang
                                    if ($model->barang_id == $data['barang_id']) {
                                        DetailBarang::setStock($model->id, $data['jumlah']);
                                    } else {
                                        // Delete past model
                                        DetailBarang::destroy($model->id);

                                        // Create new data
                                        $newDetailBarang = DetailBarang::create([
                                            'barang_id' => $data['barang_id'],
                                            'stock' => $data['jumlah'],
                                            'harga_beli' => $data['harga_beli'],
                                            'harga_jual' => 0,
                                            'harga_grosir' => 0,
                                        ]);

                                        $data['detail_barang_id'] = $newDetailBarang->id;
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
}

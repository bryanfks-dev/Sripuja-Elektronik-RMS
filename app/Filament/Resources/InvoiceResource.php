<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Penjualan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InvoiceResource\Pages;

class InvoiceResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $pluralModelLabel = 'Cetak Invoice Penjualan';

    protected static ?string $navigationGroup = 'Laporan Transaksi';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Cetak Invoice';

    protected static ?string $navigationIcon = 'heroicon-s-printer';

    public static function canViewAny(): bool
    {
        return !auth()->user()->isKaryawanNonKasir();
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
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
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
                Tables\Actions\Action::make('Kirim WA')->label('Kirim WA')
                    ->icon('heroicon-s-cloud-arrow-up')->color('success')
                    ->url(fn (Penjualan $record) => route('invoices.pdf.send-wa', $record->id)),
                Tables\Actions\Action::make('Download PDF')->label('Download PDF')
                    ->icon('heroicon-s-document-arrow-down')->color('warning')
                    ->url(fn (Penjualan $record) => route('invoices.pdf.download', $record->id))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListInvoices::route('/')
        ];
    }
}

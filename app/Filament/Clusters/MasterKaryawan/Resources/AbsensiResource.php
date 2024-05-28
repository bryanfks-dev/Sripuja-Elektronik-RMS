<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources;

use App\Models\Absensi;
use App\Models\Karyawan;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Clusters\MasterKaryawan;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\MasterKaryawan\Resources\AbsensiResource\Pages;

class AbsensiResource extends Resource
{
    protected static ?string $cluster = MasterKaryawan::class;

    protected static ?string $model = Karyawan::class;

    protected static ?string $pluralModelLabel = 'Data Absensi';

    protected static ?string $navigationLabel = 'Data Absensi';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $slug = 'absensi';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin();
    }

    private static function ubahAbsensi(Karyawan $record)
    {
        $absensiRecord = $record->absensis()
            ->whereDate('tanggal_waktu', '=', date('Y-m-d'))
            ->first();

        if ($absensiRecord == null) {
            // Create record
            Absensi::create([
                'karyawan_id' => $record->id,
                'tanggal_waktu' => now()
            ]);
        } else {
            $absensiRecord->delete();
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama Karyawan')
                    ->searchable(),
                TextColumn::make('tanggal_waktu')->label('Waktu Absen')
                    ->placeholder('-')
                    ->date('H:i:s')
                    ->getStateUsing(function (Karyawan $model) {
                        $record = $model->absensis()
                            ->whereDate('tanggal_waktu', '=', date('Y-m-d'))
                            ->get();

                        if (!$record->isEmpty()) {
                            return $record[0]->tanggal_waktu;
                        }
                    }),
            ])
            ->filters([
                SelectFilter::make('kehadiran')->label('Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'tidak_hadir' => 'Tidak Hadir'
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                function (Builder $query, $value): Builder {
                                    $notPresents =
                                        DB::table('karyawans')
                                            ->leftJoin('absensis', function ($join) {
                                                $today = date('Y-m-d');

                                                $join->on('absensis.karyawan_id', '=', 'karyawans.id')
                                                    ->on(DB::raw("DATE(absensis.tanggal_waktu)"), '=', DB::raw("DATE('$today')"));
                                            })
                                            ->whereNull('absensis.tanggal_waktu')
                                            ->get('karyawans.id')
                                            ->map(fn($val) => $val->id)->toArray();

                                    if ($value == 'hadir') {
                                        return $query->whereNotIn('id', $notPresents);
                                    }

                                    return $query->whereIn('id', $notPresents);
                                },
                            );
                    }),
            ])
            ->actions([
                Action::make('ubah')->color('primary')
                    ->action(fn(Karyawan $record) => self::ubahAbsensi($record))
            ])
            ->bulkActions([
                BulkAction::make('ubah')->color('primary')
                    ->action(function (Collection $records) {
                        $records->each(fn(Karyawan $record) => self::ubahAbsensi($record));
                    })
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
            'index' => Pages\ListAbsensis::route('/'),
            'config' => Pages\Config::route('/config'),
        ];
    }
}

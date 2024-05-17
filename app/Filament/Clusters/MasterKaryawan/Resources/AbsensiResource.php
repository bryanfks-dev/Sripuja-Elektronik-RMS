<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources;

use App\Models\Karyawan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Clusters\MasterKaryawan;
use Filament\Pages\SubNavigationPosition;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama Karyawan'),
                TextColumn::make('waktu')->label('Waktu Absen')
                    ->placeholder('-')
                    ->date('H:i:s')
                    ->getStateUsing(function (Karyawan $model) {
                        $today = date('Y-m-d');

                        $record = $model->absensis()
                            ->whereDate('tanggal_waktu', '=', $today)->get();

                        if (!$record->isEmpty()) {
                            return $record[0]->tanggal_waktu;
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
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

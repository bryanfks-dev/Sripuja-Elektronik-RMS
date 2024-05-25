<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\ConfigJson;
use App\Models\User;
use Filament\Widgets\Widget;

class CheckInKaryawan extends Widget
{
    protected static bool $isLazy = false;

    protected static string $view = 'filament.widgets.check-in-karyawan';

    protected static ?string $model = Absensi::class;

    protected array|string|int $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'record' => User::find(auth()->id())->karyawan()->first()->absensis()
                    ->whereDate('tanggal_waktu', '=', date('Y-m-d'))->first(),
            'batas_waktu_masuk' => ConfigJson::loadJson()['waktu_masuk']
        ];
    }

    public function checkIn()
    {
        // Create absensi
        Absensi::create([
            'karyawan_id' => User::find(auth()->id())->karyawan()->first()->id,
            'tanggal_waktu' => now()
        ]);
    }
}

<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources\AbsensiResource\Pages;

use App\Models\ConfigJson;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Clusters\MasterKaryawan\Resources\AbsensiResource;

class Config extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = AbsensiResource::class;

    protected static string $view = 'filament.clusters.master-karyawan.resources.absensi-resource.pages.config';

    protected ?string $heading = 'Konfigurasi';

    public static array $json;
    public string $waktuMasuk;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getFormSchema(): array
    {
        return [
            TimePicker::make('waktuMasuk')->label('Maksimum Waktu Masuk')
                ->default(function() {
                        self::$json = ConfigJson::loadJson();

                        return self::$json['waktu_masuk'];
                })->native(false)->seconds(false)
                ->format('H:i')->displayFormat('H:i')->required(),
        ];
    }

    public function save()
    {
        $waktuMasuk = $this->waktuMasuk;

        self::$json['waktu_masuk'] = date('H:i',
            strtotime($waktuMasuk));

        // Save to json
        ConfigJson::modifyJson(self::$json);

        Notification::make()
            ->title('Success')
            ->success()
            ->icon('heroicon-c-check-circle')
            ->iconColor('white')
            ->duration(5000)
            ->send();
    }
}

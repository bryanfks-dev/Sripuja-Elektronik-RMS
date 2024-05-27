<?php

namespace App\Filament\Clusters\MasterKaryawan\Resources\AbsensiResource\Pages;

use Exception;
use Filament\Forms\Form;
use App\Models\ConfigJson;
use Illuminate\Support\Js;
use Filament\Support\RawJs;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
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

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TimePicker::make('waktu_masuk')->label('Maksimum Waktu Masuk')
                    ->default(function () {
                        self::$json = ConfigJson::loadJson();

                        return self::$json['waktu_masuk'];
                    })->native(false)->seconds(false)
                    ->format('H:i')->displayFormat('H:i')->required(),
                TextInput::make('jumlah_potongan')->label('Jumlah Potongan')->prefix('Rp ')
                    ->numeric()->mask(RawJs::make('$money($input)'))->stripCharacters(',')
                    ->minValue(1)->default(fn() => self::$json['jumlah_potongan'])
                    ->required(),
                Toggle::make('otomasi')->label('Otomasi Pengurangan Gaji Karyawan')
                    ->default(fn() => self::$json['otomasi'])->inline(false)
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->submit('save'),
            Action::make('cancel')
                ->label('Batal')
                ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getResource()::getUrl()) . ')')
                ->color('gray')
        ];
    }

    public function save()
    {
        try {
            $data = $this->form->getState();

            self::$json['waktu_masuk'] = date(
                'H:i',
                strtotime($data['waktu_masuk'])
            );
            self::$json['jumlah_potongan'] = intval($data['jumlah_potongan']);
            self::$json['otomasi'] = $data['otomasi'];

            // Save to json
            ConfigJson::modifyJson(self::$json);

            Notification::make()
                ->success()
                ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
                ->send();
        } catch (Exception $e) {
            throw $e;
        }
    }
}

<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PembayaranStatus: string implements HasLabel, HasColor, HasIcon
{
    case BELUM_LUNAS = 'Belum Lunas';
    case LUNAS = 'Lunas';

    public function getLabel(): ?string
    {
        return match($this) {
            self::BELUM_LUNAS => 'Belum Lunas',
            self::LUNAS => 'Lunas',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::BELUM_LUNAS => 'warning',
            self::LUNAS => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match($this) {
            self::BELUM_LUNAS => 'heroicon-c-x-circle',
            self::LUNAS => 'heroicon-c-check-circle',
        };
    }
}

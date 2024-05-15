<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum KaryawanType: string implements HasLabel, HasColor
{
    case NON_KASIR = 'Non-Kasir';
    case KASIR = 'Kasir';

    public function getLabel(): ?string
    {
        return match($this) {
            self::NON_KASIR => 'Non-Kasir',
            self::KASIR => 'Kasir',
        };
    }

    public function getColor(): string|array|null
    {
        return match($this) {
            self::NON_KASIR => Color::Sky,
            self::KASIR => Color::Amber,
        };
    }
}

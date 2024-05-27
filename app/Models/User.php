<?php

namespace App\Models;

use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements HasName
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getFilamentName(): string
    {
        return $this->getAttributeValue('username');
    }

    public function isAdmin():bool
    {
        return $this->email !== null &&
            $this->karyawan()->first() === null;
    }

    public function isKaryawanNonKasir():bool
    {
        return $this->email === null &&
            $this->karyawan()->first('tipe')->tipe->value === 'Non-Kasir';
    }

    public function isKaryawanKasir():bool
    {
        return $this->email === null &&
            $this->karyawan()->first('tipe')->tipe->value === 'Kasir';
    }

    public function karyawan(): HasOne
    {
        return $this->hasOne(Karyawan::class);
    }

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'alamat',
        'telepon',
        'no_hp',
        'fax'
    ];

    public function penjualans(): HasMany
    {
        return $this->hasMany(Penjualan::class);
    }

    public function detailPenjualans(): HasManyThrough
    {
        return $this->hasManyThrough(DetailPenjualan::class,
            Penjualan::class, 'id', 'penjualan_id');
    }
}

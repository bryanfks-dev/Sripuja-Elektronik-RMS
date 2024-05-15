<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function penjualan() : HasOne
    {
        return $this->hasOne(Penjualan::class);
    }
}

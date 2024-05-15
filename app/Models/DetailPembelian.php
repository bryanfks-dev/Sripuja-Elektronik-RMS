<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DetailPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'pembelian_id',
        'jumlah',
        'sub_total'
    ];

    public $timestamps = false;

    public function penjualans(): BelongsToMany {
        return $this->belongsToMany(Penjualan::class);
    }

    public function barang():HasOne  {
        return $this->hasOne(Barang::class);
    }
}

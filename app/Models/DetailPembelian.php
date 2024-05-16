<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DetailPembelian extends Model
{
    use HasFactory;

    // Testing DB (Deleteable)
    protected $fillable = [
        'pembelian_id',
        'barang_id',
        'jumlah',
        'sub_total',
    ];

    public $timestamps = false;

    public function penjualans(): BelongsToMany {
        return $this->belongsToMany(Penjualan::class);
    }
}

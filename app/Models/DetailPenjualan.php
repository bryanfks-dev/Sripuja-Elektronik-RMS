<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'penjualan_id',
        'jumlah',
        'harga_jual',
        'sub_total',
    ];

    public $timestamps = false;

    public function penjualans(): BelongsToMany
    {
        return $this->belongsToMany(Penjualan::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}

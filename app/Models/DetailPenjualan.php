<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Znck\Eloquent\Relations\BelongsToThrough;

class DetailPenjualan extends Model
{
    use HasFactory, \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'jumlah',
        'harga_jual',
        'sub_total',
    ];

    protected $casts = [
        'jumlah' => 'array',
    ];

    public $timestamps = false;

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function pelanggan(): BelongsToThrough
    {
        return $this->belongsToThrough(Pelanggan::class, Penjualan::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function detailBarang(): BelongsToThrough
    {
        return $this->belongsToThrough(DetailBarang::class, Barang::class);
    }
}

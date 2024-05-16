<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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



    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}

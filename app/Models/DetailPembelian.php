<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Znck\Eloquent\Relations\BelongsToThrough;

class DetailPembelian extends Model
{
    use HasFactory, \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'pembelian_id',
        'detail_barang_id',
        'jumlah',
        'sub_total',
    ];

    public $timestamps = false;

    public function detailBarang(): BelongsTo
    {
        return $this->belongsTo(DetailBarang::class);
    }

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }

    public function barang(): BelongsToThrough
    {
        return $this->belongsToThrough(Barang::class, DetailBarang::class);
    }
}

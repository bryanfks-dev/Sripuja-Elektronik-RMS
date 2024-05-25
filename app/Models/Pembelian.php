<?php

namespace App\Models;

use App\Enums\PembayaranStatus;
use App\Models\DetailPembelian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Pembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'no_nota',
        'no_faktur',
        'jatuh_tempo',
        'status'
    ];

    protected $casts = [
        'status' => PembayaranStatus::class
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detailPembelians(): HasMany
    {
        return $this->hasMany(DetailPembelian::class);
    }

    public function detailBarangs(): HasManyThrough
    {
        return $this->hasManyThrough(DetailBarang::class, DetailPembelian::class, 'detail_barang_id', 'id');
    }
}

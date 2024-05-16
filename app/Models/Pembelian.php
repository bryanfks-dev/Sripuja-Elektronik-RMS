<?php

namespace App\Models;

use App\Models\DetailPembelian;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    // Testing DB (Deleteable)
    protected $fillable = [
        'supplier_id',
        'no_nota',
        'tanggal_waktu',
        'status',
        'tanggal_jatuh_tempo',
    ];

    public $timestamps = false;

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}

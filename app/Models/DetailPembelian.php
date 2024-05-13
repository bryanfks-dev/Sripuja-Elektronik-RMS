<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DetailPembelian extends Model
{
    use HasFactory;

    public function penjualans(): BelongsToMany {
        return $this->belongsToMany(Penjualan::class);
    }
}

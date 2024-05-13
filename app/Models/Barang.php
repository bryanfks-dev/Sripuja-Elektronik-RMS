<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stock',
        'harga_jual',
        'harga_beli',
        'harga_grosir',
    ];

    public function detailPenjualans():BelongsToMany {
        return $this->belongsToMany(DetailPenjualan::class);
    }
}

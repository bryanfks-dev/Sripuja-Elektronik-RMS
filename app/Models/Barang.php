<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'jenis_barang_id',
        'merek_barang_id',
        'stock',
        'harga_jual',
        'harga_beli',
        'jumlah_per_grosir',
        'harga_grosir',
    ];

    public static function modifyStock($id, $val)
    {
        $barang = self::find($id);
        $barang->stock += $val;

        // Save barang new value
        $barang->save();
    }

    public function jenisBarang(): BelongsTo
    {
        return $this->belongsTo(JenisBarang::class);
    }

    public function merekBarang(): BelongsTo
    {
        return $this->belongsTo(MerekBarang::class);
    }

    public function detailPenjualans(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function detailPembelians(): HasMany
    {
        return $this->hasMany(DetailPembelian::class);
    }
}

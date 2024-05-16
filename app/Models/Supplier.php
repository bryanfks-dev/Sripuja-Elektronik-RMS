<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'no_hp',
        'fax',
        'nama_sales',
        'no_hp_sales',
    ];

    public function pembelians(): HasMany
    {
        return $this->hasMany(Pembelian::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Absensi extends Model
{
    use HasFactory;

    // Testing DB (Deleteable)
    protected $fillable = [
        'karyawan_id',
        'tanggal_waktu',
    ];

    public $timestamps = false;


    public function karyawan(): HasMany {
        return $this->hasMany(Karyawan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'tanggal_waktu',
    ];

    public $timestamps = false;


    public function karyawans(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }
}

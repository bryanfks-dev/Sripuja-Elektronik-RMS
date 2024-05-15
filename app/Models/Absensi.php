<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Absensi extends Model
{
    use HasFactory;

    public function karyawans(): BelongsToMany
    {
        return $this->belongsToMany(Karyawan::class);
    }
}

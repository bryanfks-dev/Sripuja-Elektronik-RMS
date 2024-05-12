<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'alamat',
        'no_hp',
        'gaji',
        'tipe',
    ];
}

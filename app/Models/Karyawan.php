<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Validator;

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

    public $timestamps = false;

    public static function createUsername($fullName)
    {
        $username = explode(' ', $fullName);
        $username = $username[0] . '.' . end($username);

        $users = User::where('username', $username)->get();

        // Check if username exists
        if (!$users->isEmpty()) {
            $username = $username . $users->count();
        }

        return $username;
    }

    public static function createPassword($phoneNum)
    {
        return Hash::make($phoneNum);
    }

    public function user(): HasOne {
        return $this->hasOne(User::class);
    }
}

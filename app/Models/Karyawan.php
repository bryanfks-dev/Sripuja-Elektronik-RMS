<?php

namespace App\Models;

use App\Enums\KaryawanType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'alamat',
        'telepon',
        'no_hp',
        'gaji',
        'gaji_bln_ini',
        'tipe',
    ];

    protected $casts = [
        'tipe' => KaryawanType::class,
    ];

    public $timestamps = false;

    public static function createUsername($fullName)
    {
        $username = explode(' ', $fullName);
        $lastEle = end($username);

        if ($username[0] != $lastEle) {
            $username = $username[0] . '.' . $lastEle;
        }
        else {
            $username = $username[0];
        }

        // Get exact username from user table
        $user = User::where
            ('username', 'REGEXP', "^$username\d*")->latest()
            ->get('username');

        // Check alike username found
        if (!$user->isEmpty()) {
            // Get highest number from username
            $found = preg_match('/\d/',
                $user[0], $num);

            if ($found) {
                // Append number to username
                $username = $username . ($num[0] + 1);
            }
            // Case like abc not abc1
            else {
                $username = $username . '1';
            }
        }

        return $username;
    }

    public static function createPassword($password)
    {
        return Hash::make($password);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}

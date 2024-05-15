<?php

namespace App\Models;

class Nota
{
    public static function generateNoNotaPembelian(): string
    {
        $base = 'SP/PB/' . date('d-m-Y') . '/';

        $lastNota = Pembelian::where('no_nota', 'REGEXP', "^$base\d+$")
            ->latest()->get('no_nota');

        // Haven't made nota today
        if ($lastNota->isEmpty()) {
            return $base . '001';
        }

        // Get last nota count
        $todayLastNotaNum = explode('/', $lastNota[0]->no_nota);
        $todayLastNotaNum = end($todayLastNotaNum);

        return $base . str_pad(intval($todayLastNotaNum), 3,
            '0', STR_PAD_LEFT);
    }

    public static function generateNoNotaPenjualan(): string
    {
        $base = 'SP/PJ/' . date('d-m-Y') . '/';

        $lastNota = Penjualan::where('no_nota', 'REGEXP', "^$base\d+$")
            ->latest()->get('no_nota');

        // Haven't made nota today
        if ($lastNota->isEmpty()) {
            return $base . '001';
        }

        // Get last nota count
        $todayLastNotaNum = explode('/', $lastNota[0]->no_nota);
        $todayLastNotaNum = end($todayLastNotaNum);

        return $base . str_pad(intval($todayLastNotaNum), 3,
            '0', STR_PAD_LEFT);
    }
}

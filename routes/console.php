<?php

use App\Models\ConfigJson;
use App\Models\Karyawan;

// Update karyawan gaji_bln_ini to base gaji, for every month
Schedule::call(function () {
    Karyawan::lockForUpdate();
    DB::table('karyawans')
        ->update(['gaji_bln_ini' => DB::raw('gaji')]);
})->monthly();

Schedule::call(function () {
    $today = date('Y-m-d');

    $notPresents =
        Karyawan::select('karyawans.id', 'absensis.tanggal_waktu')
            ->leftJoin('absensis', 'karyawans.id', '=','absensis.karyawan_id')

        ->get('karyawans.id');

    // Subtract Rp 50_000 from karyawans
    Karyawan::where('id', 'IN', $notPresents)->update([
        'gaji_bln_ini' => DB::raw('`gaji_bln_ini` - 50000'),
    ]);
})->dailyAt('23:30');


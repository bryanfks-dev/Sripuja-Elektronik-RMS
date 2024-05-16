<?php

use App\Models\ConfigJson;
use Filament\Support\Exceptions\Halt;

// Update karyawan gaji_bln_ini to base gaji, for every month
Schedule::call(function () {
    try {
        DB::beginTransaction();

        DB::table('karyawans')
        ->update(['gaji_bln_ini' => DB::raw('gaji')]);

        DB::commit();
    } catch (Halt $exception) {
        $exception->shouldRollbackDatabaseTransaction() ?
            DB::rollBack() :
            DB::commit();

        return;
    } catch (Throwable $exception) {
        DB::rollBack();

        throw $exception;
    }
})->monthly();

// Subtract karyawan gaji_bln_ini if karyawan not present today, every day
Schedule::call(function () {
    $waktuMasuk = ConfigJson::loadJson()['waktu_masuk'];

    $notPresents =
        DB::table('karyawans')
            ->leftJoin('absensis', function($join) {
                $today = date('Y-m-d');

                $join->on('absensis.karyawan_id', '=', 'karyawans.id')
                    ->on(DB::raw("DATE(absensis.tanggal_waktu)"), '=', DB::raw("DATE('$today')"));
            })
            ->whereNull('absensis.tanggal_waktu')
            ->orWhere(DB::raw("TIME(absensis.tanggal_waktu)"), '>', DB::raw("TIME('$waktuMasuk')"))
            ->get('karyawans.id')
            ->map(fn ($val) => $val->id)->toArray();

    try {
        DB::beginTransaction();

        // Subtract Rp 50_000 from karyawans
        DB::table('karyawans')->whereIn('id', $notPresents)
        ->update([
            'gaji_bln_ini' => DB::raw('CASE WHEN `gaji_bln_ini` > 50000 THEN
                (`gaji_bln_ini` - 50000) ELSE `gaji_bln_ini` END'),
        ]);

        DB::commit();
    } catch (Halt $exception) {
        $exception->shouldRollbackDatabaseTransaction() ?
            DB::rollBack() :
            DB::commit();

        return;
    } catch (Throwable $exception) {
        DB::rollBack();

        throw $exception;
    }
})->dailyAt('23:30')->when(
    fn () => ConfigJson::loadJson()['otomasi']
);

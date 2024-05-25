<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Throwable;
use Illuminate\Support\Facades\DB;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Znck\Eloquent\Relations\BelongsToThrough;

class DetailBarang extends Model
{
    use HasFactory, \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'barang_id',
        'stock',
        'harga_jual',
        'harga_beli',
        'harga_grosir',
    ];

    public static function setStock(int $id, int $val) {
        try {
            DB::beginTransaction();

            $detailBarang = self::find($id);

            $detailBarang->stock = $val;
            $detailBarang->save();

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
    }

    public static function modifyStock(int $id, int $val)
    {
        try {
            DB::beginTransaction();

            $detailBarang = self::find($id);

            $detailBarang->stock += $val;
            $detailBarang->save();

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
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function detailPenjualans(): HasManyThrough
    {
        return $this->hasManyThrough(DetailPenjualan::class, Barang::class, 'id', 'barang_id');
    }

    public function pembelian(): BelongsToThrough
    {
        return $this->belongsToThrough(Pembelian::class, DetailPembelian::class);
    }

    public function detailPembelian(): BelongsTo
    {
        return $this->belongsTo(DetailPembelian::class);
    }
}

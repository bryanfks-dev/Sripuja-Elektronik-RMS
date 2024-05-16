<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_invoice',
        'penjualan_id',
    ];

    public static function generateNoInvoice(): string
    {
        $base = 'PB' . date('dmY') . '-';

        $lastInvoice = Invoice::where('no_invoice', 'REGEXP', "^$base\d+$")
            ->latest()->get('no_invoice');

        // Haven't made invoice today
        if ($lastInvoice->isEmpty()) {
            return $base . '001';
        }

        // Get last nota count
        $todayLastInvoiceNum = explode('-', $lastInvoice[0]->no_invoice);
        $todayLastInvoiceNum = end($todayLastInvoiceNum);

        return $base . str_pad((intval($todayLastInvoiceNum) + 1), 3,
            '0', STR_PAD_LEFT);
    }

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class);
    }
}

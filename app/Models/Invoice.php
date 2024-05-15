<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

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

        return $base . str_pad(intval($todayLastInvoiceNum), 3,
            '0', STR_PAD_LEFT);
    }
}

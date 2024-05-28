<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get("/{record}/pdf/download", [InvoiceController::class, 'download'])
    ->name('invoices.pdf.download');

Route::get('/{record}/pdf/send-wa', [InvoiceController::class,'sendWA'])
    ->name('invoices.pdf.send-wa');

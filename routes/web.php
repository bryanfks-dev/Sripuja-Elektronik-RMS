<?php

use App\Http\Controllers\DownloadPDFController;
use Illuminate\Support\Facades\Route;

Route::get("/{record}/pdf/download", [DownloadPDFController::class, 'index'])
    ->name('invoices.pdf.download');

<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Routing\Controller;

class DownloadPDFController extends Controller
{
    public function index(Penjualan $record)
    {
        // Get the karyawan for the user
        $karyawan = $record->user->karyawan;

        // Get the detail penjualans for the penjualan
        $detailPenjualans = $record->detailPenjualans()->get();

        $data = [
            'title' => 'Invoice',
            'seller' => $karyawan->nama_lengkap ?? 'Admin',
            'seller_alamat' => 'Jl. Danau Buyan No.12, Loloan Bar., Kec. Negara, Kabupaten Jembrana, Bali 82214',
            'seller_telepon' => '(0365) 41713 / 0819-9991-9001',
            'buyer' => $record->pelanggan->nama_lengkap,
            'buyer_alamat' => $record->pelanggan->alamat ?? '-',
            'buyer_telepon' => $record->pelanggan->telepon ?? '-',
            'buyer_nohp' => $record->pelanggan->no_hp ?? '-',
            'buyer_fax' => $record->pelanggan->fax ?? '-',
            'date' => Carbon::parse($record->created_at)->translatedFormat('l, d M Y'),
            'no_invoice' => $record->invoice->no_invoice,
            'no_nota' => $record->no_nota,
            'detailPenjualans' => $detailPenjualans,
        ];

        try {
            $pdf = Pdf::loadView('invoices', $data);
            return $pdf->download('Invoice ' . $data['no_nota'] . '.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}

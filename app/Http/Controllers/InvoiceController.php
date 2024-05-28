<?php

namespace App\Http\Controllers;

use Config;
use CURLFile;
use Carbon\Carbon;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    private function renderBladeAsPdf(Penjualan $record)
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

        return Pdf::loadView('invoices', $data);
    }

    public function download(Penjualan $record)
    {
        // Prevent certain role access this controller method
        if (auth()->user()->isKaryawanNonKasir()) {
            abort(403);
            return;
        }

        try {
            $pdf = $this->renderBladeAsPdf($record);
            return $pdf->download($record->invoice->no_invoice . '.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    private function downloadToStorage(Penjualan $record)
    {
        $pdf = $this->renderBladeAsPdf($record);
        $content = $pdf->download()->getOriginalContent();

        $pdfPath = 'invoice/' . $record->invoice->no_invoice . '.pdf';

        Storage::put($pdfPath, $content);

        return $pdfPath;
    }

    private function getFileId(array $api, string $filePath)
    {
        $file = new CURLFile($filePath);

        $mime = mime_content_type($file->getFilename());

        $file->setMimeType($mime);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $api['base_endpoint'] . '/media',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => [
                'messaging_product' => 'whatsapp',
                'type' => $mime,
                'file' => $file
            ],
            CURLOPT_HTTPHEADER => ["Authorization: Bearer $api[token]"],
        ]);

        $result = json_decode(curl_exec($curl), true);

        return $result['id'];
    }

    public function sendWA(Penjualan $record)
    {
        // Prevent certain role access this controller method
        if (auth()->user()->isKaryawanNonKasir()) {
            abort(403);
            return;
        }

        $api = [
            'base_endpoint' => 'https://graph.facebook.com/v19.0/' . Config::get('whatsapp.phone_num_id'),
            'token' => Config::get('whatsapp.api_token'),
        ];

        $invoicePath = $this->downloadToStorage($record);
        $absInvoicePath = str_replace('/', '\\',
            storage_path('app/' . $invoicePath));

        $messageBody = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => '+62' . substr($record->pelanggan->no_hp, 1),
            'type' => 'document',
            'document' => [
                'id' => $this->getFileId($api, $absInvoicePath),
                'caption' => 'Halo ' . $record->pelanggan->nama_lengkap . ', terima kasih telah berbelanja di Sripuja Elektronik, ini invoice belanja kamu.',
                'filename' => $record->invoice->no_invoice . '.pdf'
            ]
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $api['base_endpoint'] . '/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($messageBody),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $api[token]",
                'Content-Type: application/json'
            ),
        ]);

        curl_exec($curl);
        curl_close($curl);

        Storage::delete($invoicePath);

        return redirect()->back();
    }
}

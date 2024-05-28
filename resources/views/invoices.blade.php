<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $title }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css" media="screen">
        @page {
            background-image: url('images/invoice_bg.png');
            background-image-resize: 6;
            background-image-opacity: 0.2;
        }

        html {
            font-family: sans-serif;
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            font-size: 10px;
            margin: 36pt;
            position: relative;
            /* Required for the background image to be positioned correctly */
        }

        .background-image {
            position: absolute;
            top: 50%;
            left: 50%;
            width: auto;
            /* Ensure the width is auto */
            height: auto;
            /* Ensure the height is auto */
            max-width: 100%;
            max-height: 100%;
            transform: translate(-50%, -50%);
            z-index: -1;
            opacity: 0.2;
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        strong {
            font-weight: bolder;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        table {
            border-collapse: collapse;
        }

        th {
            text-align: inherit;
        }

        h4,
        .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4,
        .h4 {
            font-size: 1.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
        }

        .table.table-items td {
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
            padding-left: 0 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        * {
            font-family: "DejaVu Sans";
        }

        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        table,
        th,
        tr,
        td,
        p,
        div {
            line-height: 1.1;
        }

        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }

        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }

        .border-0 {
            border: none !important;
        }

        .cool-gray {
            color: #6B7280;
        }
    </style>
</head>

<body>

    <img src="images/invoice_logo.png" alt="logo" height="100"></img>

    <header>
        <img src="images/invoice_bg.png" alt="Background Image" class="background-image">
    </header>
    <main>
        <table class="table mt-5">
            <tbody>
                <tr>
                    <td class="pl-0 border-0" width="70%">
                        <h4 class="text-uppercase">
                            <strong>{{ $no_invoice }}</strong>
                        </h4>
                    </td>
                    <td class="pl-0 border-0">
                        <p>No Nota: <strong>{{ $no_nota }}</strong></p>
                        <p>Hari/Tanggal: <strong>{{ $date }}</strong></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th class="pl-0 border-0 party-header" width="48.5%">
                        Kasir
                    </th>
                    <th class="border-0" width="3%"></th>
                    <th class="pl-0 border-0 party-header">
                        Pembeli
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
                        <p class="seller-name">
                            <strong>{{ $seller }}</strong>
                        </p>
                        <p class="seller-address">
                            Alamat: {{ $seller_alamat }}
                        </p>
                        <p class="seller-phone">
                            Telepon: {{ $seller_telepon }}
                        </p>
                    </td>
                    <td class="border-0"></td>
                    <td class="px-0">
                        <p class="buyer-name">
                            <strong>{{ $buyer }}</strong>
                        </p>
                        <p class="buyer-address">
                            Alamat: {{ $buyer_alamat }}
                        </p>
                        <p class="buyer-phone">
                            Telepon: {{ $buyer_telepon }}
                        </p>
                        <p class="buyer-custom-field">
                            No HP: {{ $buyer_nohp }}
                        </p>
                        <p class="buyer-custom-field">
                            Fax: {{ $buyer_fax }}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-items">
            <thead>
                <tr>
                    <th scope="col" class="pl-0 border-0">Barang</th>
                    <th scope="col" class="text-center border-0">Jumlah</th>
                    <th scope="col" class="text-right border-0">Harga</th>
                    <th scope="col" class="pr-0 text-right border-0">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detailPenjualans as $item)
                    <tr>
                        <td class="pl-0">
                            {{ $item->barang->nama_barang }}
                        </td>
                        <td class="text-center">{{ array_sum(array_values(json_decode($item->jumlah, true))) }}</td>
                        <td class="text-right">
                            {{ 'Rp ' . number_format($item->harga_jual, 0, '.', '.') }}
                        </td>
                        <td class="pr-0 text-right">
                            {{ 'Rp ' . number_format($item->sub_total, 0, '.', '.') }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="2" class="border-0"></td>
                    <td class="pl-0 text-right">Total Keseluruhan</td>
                    <td class="pr-0 text-right total-amount">
                        {{ 'Rp ' . number_format($detailPenjualans->sum('sub_total'), 0, '.', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
    <footer>
        <img src="images/invoice_bg.png" alt="Background Image" class="background-image">
    </footer>
</body>

</html>

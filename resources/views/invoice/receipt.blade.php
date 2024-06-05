<!-- <!DOCTYPE html>
<html>
<head>
    <title>Struk Pembelian Indomaret</title>
    <style>
        /* Gaya untuk struk pembelian */
        body {
            font-family: Arial, sans-serif;
        }
        .struk-container {
            width: 300px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
        .item {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="struk-container">
        <h1>Struk Pembelian</h1>
        <p>Toko: Indomaret</p>
        <p>Tanggal: 24 Juli 2023</p>
        <hr>
        <div class="item">
            <p>1 x Sabun Mandi</p>
            <p>Rp 10,000</p>
        </div>
        <div class="item">
            <p>2 x Mi Instan</p>
            <p>Rp 6,000</p>
        </div>
        <div class="item">
            <p>1 x Gula</p>
            <p>Rp 12,000</p>
        </div>
        <hr>
        <p>Total: Rp 28,000</p>
        <p>Bayar dengan: Tunai</p>
        <p>Terima kasih telah berbelanja di Indomaret!</p>
    </div>
</body>
</html> -->


<!DOCTYPE html>
<html>

<head>
    <title>receipt {{ $transaction->invoice_no }}</title>
    <style>
        /* Gaya untuk struk pembelian */

        @font-face {
            font-family: "source_sans_proregular";
            src: local("Source Sans Pro"), url("fonts/sourcesans/sourcesanspro-regular-webfont.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;

        }

        @media print {
            @page {
                size: 100%;
                width: 90mm;
                margin: 1rem;
                height: 100% !important;
            }

            html,
            body {
                font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
                height: 100% !important;
            }
        }

        body {
            /* font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; */
            font-size: 13.5pt;
            width: 90mm;
            /* Lebar kertas thermal */
            margin: 0;
            padding: 0;
            color: #717180;
            font-family: Arial, sans-serif;
        }

        .lucida {
            font-size: 12pt !important;
        }

        .lucida-content {
            font-size: 15pt !important;
        }

        .struk-container {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .struk-container table {
            width: 100%;
            font-size: 10px;
        }

        .struk-header,
        .struk-body,
        .struk-footer {
            margin: 10px 30px 0 30px;
        }

        .struk-header {
            font-weight: 600;
        }


        /* .item {
            display: flex;
            justify-content: space-between;
        } */
    </style>
</head>

<body>
    <div class="struk-container">
        <div class="struk-header">
            <table class="lucida">
                <tr>
                    <td>
                        <img src="{{ asset('img/logo.png') }}" alt="logo" width="140" height="50">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; font-size:12px">
                        Office 88 Kasablanka Tower A, 18<sup>th</sup>Floor<br>
                        Jl. Casablanca Raya Kav 88<br> Jakarta 12870,
                        Indonesia. <br> <span>+62 859-1065-30391, support@natha.co.id </span>
                    </td>
                </tr>
            </table>
            <hr>
        </div>

        <p style="margin: 0 30px;font-size:12px; font-weight:600">
            {{ date('d / m / y', strtotime($transaction->date)) . ' - ' . date('H:i', strtotime($transaction->created_at)) }}
            | {{ $transaction->created_by }} | {{ $transaction->invoice_no }}
        </p>

        <p style="margin: 0 30px;font-size:12px; font-weight:600">Customer :
            {{ substr($transaction->customer_name, 0, 20) }} |
            {{ $transaction->customer_phone }}
        </p>

        <div class="struk-body">
            <hr>
            <table class="lucida-content">
                @php
                    $total = 0;
                    $diskon = 0;
                @endphp
                @foreach ($transaction->details as $key => $item)
                    @php
                        $price = $item->price + ($item->price * 11) / 100;
                        $subtotal = $price * $item->qty;
                        $diskon += $item->discount;
                        $total = $total + $subtotal;
                    @endphp
                    <tr>
                        <td colspan="3" style="font-size:13px; font-weight:600">
                            {{ strtoupper($item->item_name) }}
                        </td>
                    </tr>
                    <tr style="font-size:12px; font-weight:500">
                        <td>Rp.
                            {{ number_format($item->price + $item->ppn / $item->qty, 0, ',', '.') }}</td>
                        <td>x {{ $item->qty }}</td>
                        <td style="text-align: right">Rp.
                            {{ number_format($item->total + $item->discount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if ($item->discount > 0.0)
                        <tr style="font-size:12px; font-weight:500">
                            <td colspan="2" style="text-align: right">Discount</td>
                            <td style="text-align: right">Rp. {{ format_rupiah($item->discount) }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                @if ($transaction->discount > 0)
                    <tr style="font-size:12px; font-weight:600">
                        <td style="text-align: right" colspan="2">DISCOUNT</td>
                        <td style="text-align: right">Rp. {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr style="font-size:12px; font-weight:600">
                    <td style="text-align: right" colspan="2">{{ $transaction->paymentType->name }}</td>
                    <td style="text-align: right">Rp. {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                </tr>

            </table>
            <hr>
        </div>
        <div class="struk-footer" style="font-size:12px; font-weight:500">
            <p>TERIMA KASIH</p>
        </div>
    </div>

    <script>
        // Fungsi untuk mencetak struk ke printer thermal

        window.print(); // Ini akan mencetak halaman saat ini ke printer thermal
    </script>
</body>

</html>

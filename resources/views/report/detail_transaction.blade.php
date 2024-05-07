<html>

<head>
    <style>
        /** Define the margins of your page **/
        /* @media print { */
        /* set margin top pada halaman kedua dan seterusnya */
        /* @page :first { */
        /* margin-top: 0; */
        /* } */
        /* } */


        /* @page :first {
                    margin-top: 30px;
                } */

        @page {
            margin-top: 120px;
            size: A4;
            /* Menggabungkan definisi orientasi dan ukuran kertas dalam satu baris */
        }


        /* @page :first {
                margin-top: 0;
            } */

        /* @page {
                margin-top: 120px;
            } */

        header {
            position: fixed;
            top: -110px;
            left: 0px;
            right: 0px;
            height: 150px;
            /* margin-top:30px; */
            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            /* color: white; */
            /* text-align: center; */
            /* line-height: 35px; */
        }

        header .logo {
            float: left;
        }

        header .logo img {
            left: 0px;
            /* width: 30%; */
            height: 50%;
            margin-left: -10px
        }

        header .office {
            float: right;
            /* width: 300px; */
            margin-left: 10px;
            /* position: relative; */
            /* margin-top: 10px; */
        }

        header .office table {
            font-size: 10px;
        }

        header .hr {
            background-color: #00AFA0;
            height: 5px;
            width: 704px;
            margin-top: 90px;
        }

        header .hr2 {
            background-color: #00AFA0;
            height: 2px;
            width: 704px;
            margin-top: 2px;
        }

        main {
            margin-top: 2rem;
            font-size: 10px;
        }

        main .content .title {
            font-size: 14px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            /* margin-top: 20px; */
        }

        main .content .identity {
            margin-top: 25px;
        }

        main .content .identity .cust {
            float: left;
            font-size: 10px;
        }

        main .content .identity .cust table th {
            text-align: left;
        }

        main .content .identity .detail-cust {
            float: right;
            font-size: 10px;
            margin-right: 20px;
        }

        main .content .identity .detail-cust th {
            text-align: left;
            font-size: 10px;
        }

        main .content .goods {
            text-align: center;
        }

        main .content .goods table {
            margin-top: 20px;
            /* width: 460px; */
            border-collapse: collapse;
        }

        main .content .goods table th,
        main .content .goods table td {
            border: 1px solid black;
        }

        main .content .goods table thead {
            position: sticky;
            /* height: 70px; */
            top: 90px;
            background-color: #f2f2f2;
            margin-top: 120px;
            /* display: table-header-group; */
        }



        main .content .signature {
            margin-top: 70px;
        }

        main .content .signature .cust-signature {
            float: left;
            font-size: 10px;
        }

        main .content .signature .cust-signature p {
            text-align: center;
        }

        main .content .signature .pharm-signature {
            float: right;
            font-size: 10px;
            margin-right: 20px;
        }

        main .content .signature .pharm-signature p {
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
        }

        td {
            padding: 2px;
        }
    </style>
</head>

<body>
    <header style="margin-top: 2rem;">
        <div class="logo">
            <img src="{{ public_path('img/logo.png') }}" alt="">
        </div>
        <div class="office">
            <table>
                <tr>
                    <th valign="top">
                        Apotek
                    </th>
                    <td valign="top">:</td>
                    <td width="170px" style="text-align:justify">
                        Jl. Pantai Indah Kapuk Ruko Emerald Park No. 8-9, RT.6/RW.2, Kamal Muara, Kec. Penjaringan, Kota
                        Jkt Utara, Jakarta 14470
                    </td>
                </tr>
                <tr>
                    <th valign="top">
                        Phone
                    </th>
                    <td>:</td>
                    <td class="justy">
                        021 29031691
                    </td>
                </tr>
            </table>
        </div>
        <div class="hr"></div>
        <div class="hr2"></div>
    </header>

    <main>
        <div class="content">
            <div class="title">Laporan Transaksi</div>
            <div class="goods">
                <table width="100%">
                    <thead>
                        <tr style="text-align:center;">
                            <th>No</th>
                            <th>No. Invoice</th>
                            <th>Nama Item</th>
                            <th>Kuantitas</th>
                            <th>Harga Jual</th>
                            <th>Subtotal</th>
                            <th>Pajak</th>
                            <th>Diskon</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($details as $key => $item)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $item['no_invoice'] }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td style="text-align:center">{{ $item['qty'] }}</td>
                                <td style="text-align:right; padding-right: 10px;">{{ format_rupiah($item['price']) }}
                                </td>
                                <td style="text-align:right; padding-right: 10px;">
                                    {{ format_rupiah($item['subtotal']) }}
                                </td>
                                <td style="text-align:right; padding-right: 10px;">{{ format_rupiah($item['pajak']) }}
                                </td>
                                <td style="text-align:right; padding-right: 10px;">
                                    {{ format_rupiah($item['discount']) }}
                                </td>
                                <td style="text-align:right; padding-right: 10px;">{{ format_rupiah($item['total']) }}
                                </td>
                            </tr>
                            {{ $no++ }}
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="signature">
                <div class="pharm-signature">
                    <p>Pharmacy</p><br><br><br>
                    <p>(...........................)</p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>

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
        }

        @page {
            size: A5;
        }

        @font-face {
            font-family: "source_sans_proregular";
            src: local("Source Sans Pro"), url("fonts/sourcesans/sourcesanspro-regular-webfont.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;

        }

        body {
            font-family: Arial, sans-serif;
        }

        .circle {
            z-index: 12;
            position: absolute;
            right: 180px;
            top: -135px;
            height: 20px;
            width: 115px;
            background-color: #aed18a;
            /* border-radius: 50%; */
            display: inline-block;
            opacity: 0.7;
        }

        /* footer color :   #7197cb */


        /* @page :first {
                margin-top: 0;
            } */

        /* @page {
                margin-top: 120px;
            } */

        header {
            position: fixed;
            top: -130px;
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
            height: 30%;
            margin-left: -10px
        }

        header .office {
            float: right;
            /* width: 300px; */
            margin-left: 10px;
            font-size: 34px;
            font-weight: bold;
            color: #707070;
            /* position: relative; */
            /* margin-top: 10px; */
        }

        header .office table {
            font-size: 10px;
        }

        header .hr {
            background-color: #7ca3d6;
            height: 5px;
            width: 470px;
            margin-top: 90px;
        }

        header .hr2 {
            background-color: #7ca3d6;
            height: 1px;
            width: 470px;
            margin-top: 50px;


        }

        main {
            margin-top: 10px;
            font-size: 10px;
        }

        /* main .content .title {
            font-size: 14px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        } */

        main .content .identity {
            margin-top: -50px;
            margin-left: -5px;
        }

        main .content .identity .company {
            float: left;
            font-size: 10px;
            color: #707070;
            line-height: 1.6;
            font-weight: 700;
        }

        main .content .identity .company table th {
            text-align: left;
        }

        main .content .identity .detail-inv {
            float: right;
            font-size: 10px;
            color: #707070;
            /* margin-right: 20px; */
        }

        main .content .identity .detail-inv td {
            text-align: left;
            font-size: 10px;
        }

        main. .content .cust {
            margin-top: 100px;
            line-height: 1.5;
            color: #707070;
            font-weight: 400;
        }

        main .content .goods table {
            margin-top: 40px;
            width: 470px;
            border-collapse: collapse;
        }

        main .content .goods table th,
        main .content .goods table td {
            /* border: 1px solid black; */
            height: 28px;
        }

        main .content .goods table th {
            /* border: 1px solid #707070; */
        }

        main .content .goods table thead {
            position: sticky;
            /* height: 70px; */
            top: 90px;
            /* background-color: #f2f2f2; */
            margin-top: 120px;
            /* display: table-header-group; */
            color: #707070;
            border: 1px solid #707070;
        }

        main .content .goods table tbody tr {
            border: 1px solid #d3d4d4;
            color: #707070;
            font-size: 6pt;
        }

        .subtotal {
            border-left: 1px solid #d3d4d4;
            border-bottom: 1px solid #d3d4d4;
            color: #707070;
        }

        .subtotal-value {
            border-bottom: 1px solid #d3d4d4;
            border-right: 1px solid #d3d4d4;
            color: #707070;
        }

        .bank-account {
            color: #707070;
            font-weight: 400;
        }

        main .content .signature {
            color: #707070;
            box-sizing: border-box;
        }

        .main .content .signature:after {
            content: "";
            display: table;
            clear: both;
            display: flex;
            justify-content: center;
        }

        .column {
            float: left;
            width: 33%;
            height: 100px;
        }

        .cust-signature {
            float: left;
            font-size: 10px;
        }

        main .content .signature .cust-signature p {
            text-align: left;
        }

        main .content .signature .who-is-known,
        main .content .signature .who-is-known p {
            text-align: center;
        }

        main .content .signature .employee-signature {
            float: right;
            font-size: 10px;
            /* margin-right: 20px; */
        }

        main .content .signature .employee-signature p {
            text-align: right;
        }

        /* footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

            background-color: #03a9f4;
            color: white;
        } */

        .footer {
            position: fixed;
            font-size: 12px;
            bottom: -70px;
            left: -50px;
            height: 50px;
            width: 800px;
            /** Extra personal styles **/
            background: #7299cd;
        }

        td {
            padding: 2px;
        }
    </style>
</head>

<body>
    <div class="circle"></div>
    <header style="margin-top: 2rem;">
        <div class="logo">
            <img src="{{ public_path('img/logo.png') }}" alt="">
        </div>
        <div class="office">
            Invoice
            {{-- <table>
                <tr>
                    <th valign="top">
                        Office:
                    </th>
                    <td valign="top">:</td>
                    <td width="170px" style="text-align:justify">
                        Office 88 Kasablanka Tower A, 18th floor Jl. Casablanca Raya Kav 88 Jakarta 12870,
                        Indonesia
                    </td>
                </tr>
                <tr>
                    <th valign="top">
                        Phone
                    </th>
                    <td>:</td>
                    <td class="justy">
                        +62 859-1065-30391
                    </td>
                </tr>
            </table> --}}
        </div>
        {{-- <div class="hr"></div> --}}
        <div class="hr2"></div>
    </header>

    <main>
        <div class="content">
            <div class="identity">
                <div class="company">
                    PT. Natha Jaya Makmur<br>
                    Casablanca Raya EffistSuite Office Lt.18<br>
                    Menteng Dalam, Tebet<br>
                    Kota Adm. Jakarta Selatan, DKI Jakarta<br>
                    12870. Indonesia
                    {{-- <table>
                        <tr>
                            <th>Name</th>
                            <th>:</th>
                            <th><i>{{ $transaction->customer_name }}</i></th>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <th>:</th>
                            <th><i>{{ $transaction->customer_phone }}</i></th>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <th>:</th>
                            <th><i>{{ $transaction->customer_address }}</i></th>
                        </tr>
                    </table> --}}
                </div>
                <div class="detail-inv">
                    <table>
                        <tr>
                            <td>No.</td>
                            <td>:</td>
                            <td><i>{{ $transaction->invoice_no }}</i></td>
                            {{-- <td>#INV/018/03/2024/NJM</td> --}}
                        </tr>
                        <tr>
                            <td>Tgl</td>
                            <td>:</td>
                            <td><i>{{ sqlindo_date($transaction->date) }}</i></td>
                        </tr>
                        <tr>
                            <td>No. SJ</td>
                            <td>:</td>
                            <td><i>{{ $transaction->paymentType->name }}</i></td>
                        </tr>
                        <tr>
                            <td>Tgl. SJ</td>
                            <td>:</td>
                            <td><i>{{ $transaction->paymentType->name }}</i></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="cust">
                Kepada, <br>
                Michelle Gonatha<br>
                Jakarta, Indonesia
            </div>

            <div class="goods">
                <table>
                    <thead>
                        <tr>
                            <th width="20px" align="center">No</th>
                            <th style="padding-left:15px" width="170px" align="left">Keterangan</th>
                            <th width="50px" align="center">Jumlah Barang</th>
                            <th width="100px">Harga</th>
                            {{-- <th>Di10count</th> --}}
                            <th width="100px">Jumlah Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->details as $key => $item)
                            <tr>
                                <td align="center">{{ $key + 1 }}</td>
                                <td style="padding-left:15px">
                                    @php
                                        $ids = json_decode($item->item_id);
                                        $qty = json_decode($item->qty_item);

                                    @endphp

                                    {{-- @if (count($ids) > 1)
                                        <span style="font-size: 10pt">{{ $item->item_name }}</span>
                                        <ul>
                                            @foreach ($ids as $id)
                                                @php
                                                    $dtl = App\Models\Master\Item::where('id', $id)->first();
                                                @endphp
                                                <li>{{ $dtl->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else --}}
                                    <span>{{ $item->item_name }}</span>
                                    {{-- @endif --}}


                                </td>
                                <td align="center">{{ $item->qty }}</td>
                                <td style="text-align:right;padding-right:30px">
                                    {{ 'Rp. ' . format_rupiah($item->price) }}</td>
                                {{-- <td>
                                    {{ format_rupiah(($item->price * $item->qty * $item->discount) / 100) }}
                                </td> --}}
                                <td style="text-align:right;padding-right:30px">
                                    {{ 'Rp. ' . format_rupiah($item->total) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr style="border:none">
                            <td colspan="3" style="border:0;"></td>
                            <td colspan="1" class="subtotal">
                                <strong>Subtotal</strong>
                            </td>
                            <td class="subtotal-value" style="text-align:right;padding-right:30px">
                                <strong>{{ 'Rp. ' . format_rupiah($transaction->sub_total) }}</strong>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td colspan="4" style="border:0;"></td>
                            <td colspan="1"><strong>Pajak</strong></td>
                            <td style="text-align:right"><strong>{{ format_rupiah($transaction->pajak) }}</strong></td>
                        </tr> --}}
                        <tr style="border:none">
                            <td colspan="3" style="border:0;"></td>
                            <td colspan="1" class="subtotal">
                                <strong>Discount</strong>
                            </td>
                            <td class="subtotal-value" style="text-align:right;padding-right:30px">
                                <strong>{{ 'Rp. ' . format_rupiah($transaction->discount) }}</strong>
                            </td>
                        </tr>
                        <tr style="border:none">
                            <td colspan="3" style="border:0;"></td>
                            <td colspan="1" class="subtotal">
                                <strong>Total</strong>
                            </td>
                            <td class="subtotal-value" style="text-align:right;padding-right:30px">
                                <strong>{{ format_rupiah($transaction->total) }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>



            <div class="bank-account">
                No. FP: -<br><br>
                No. Rek:<br>
                Bank Central Asia Tbk<br>
                A/C : 211-088-1188 (IDR)<br>
                A/N : PT. Natha Jaya Makmur<br><br>
                Tanggal Jatuh Tempo :<br>
                20/3/2024
            </div>

            <div class="signature">
                <div class="column cust-signature">
                    <p>Yang Menerima</p><br><br><br>
                    <p>(...........................)</p>
                </div>
                <div class="column who-is-known">
                    <p>Mengetahui</p><br><br><br>
                    <p>(...........................)</p>
                </div>
                <div class="column employee-signature">
                    <p>Hormat Kami</p><br><br><br>
                    <p>(...........................)</p>
                </div>
            </div>
        </div>
    </main>

    <div class="footer">
        <div style="padding-left:150px;padding-top:5px"></div>
    </div>
</body>

</html>

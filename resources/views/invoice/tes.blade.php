<html>
    <head>
        <style>
            /** Define the margins of your page **/
            @page { size: A5 }

            /* Margin top pada halaman kedua */
            @page :first {
                margin-top: 0;
            }
            @page {
                margin-top: 120px;
            }

            header {
                position: fixed;
                top: 10px;
                left: 0;
                right: 0;
                bottom: 0;
                height: 150px;
                /* background-color: #03a9f4; */

                /* line-height: 35px; */
            }

            header .logo {
                float:left;
            }
            header .logo img {
              left: 0px;
              width: 30%;
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
              width: 460px;
              margin-top: 90px;
            }

            header .hr2 {
              background-color: #00AFA0;
              height: 2px;
              width: 460px;
              margin-top: 2px;
            }

            main {
              /* margin-top: 80px; */
              font-size: 10px;
            }
            
            main .content {
                margin-top: 140px;
            }

            main .title {
              font-size: 14px;
              text-align: center;
              font-weight: bold;
              text-transform: uppercase;
              margin-top: -20px;
            }

            main .identity {
              margin-top: 25px;
            }

            main .identity .cust {
              float: left;
              font-size: 10px;
            }

            main .identity .cust table th{
              text-align: left;
            }

            main .identity .detail-cust {
              float: right;
              font-size: 10px;
              margin-right: 20px;
            }

            main .identity .detail-cust th {
              text-align: left;
              font-size: 10px;
            }

            main .content .p p {
                page-break-after: always;
            }
            main .content .goods table th, .content .goods table td {
              font-size: 10px;
              border: 1px solid black;
              /* border-collapse:collapse; */
            }

            main .content .goods {
                margin-top:80px;
            }

            main .content .goods .next{
                margin-top:120px;
            }

            /* main .content .goods table th {
                position: sticky;
                height: 30px;
            } */

            main .content .goods table {
              width: 460px;
              border-collapse:collapse;
            }

            main .content .goods table thead th {
                position: sticky;
                height: 30px;
                background-color: #f2f2f2;
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
                text-align: center;
                line-height: 35px;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
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
                Jl. Pantai Indah Kapuk Ruko Emerald Park No. 8-9, RT.6/RW.2, Kamal Muara, Kec. Penjaringan, Kota Jkt Utara, Jakarta 14470
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
        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
          <div class="content">
            <div class="title">Invoice</div>
            <div class="identity">
              <div class="cust">
                <table>
                  <tr>
                    <th>Name</th>
                    <th>:</th>
                    <th><i>Cust Name</i></th>
                  </tr>
                  <tr>
                    <th>Phone Number</th>
                    <th>:</th>
                    <th><i>Cust Phone Number</i></th>
                  </tr>
                  <tr>
                    <th>Address</th>
                    <th>:</th>
                    <th><i>Cust Address</i></th>
                  </tr>
                </table>
              </div>
              <div class="detail-cust">
                <table>
                  <tr>
                    <th width="100px">Invoice No.</th>
                    <th width="10px">:</th>
                    <th><i>Cust Invoice Number</i></th>
                  </tr>
                  <tr>
                    <th>Date</th>
                    <th>:</th>
                    <th><i>Invoice Date</i></th>
                  </tr>
                  <tr>
                    <th>Payment Type</th>
                    <th>:</th>
                    <th><i>Cust Payment Type</i></th>
                  </tr>
                </table>
              </div>
            </div>
            <div class="p">
                <p>testing content</p>
                <p>testing content</p>
                <p>testing content</p>
                <p>testing content</p>
                <p>testing content</p>
                <p>testing content</p>
            </div>
            
            <!--
            <div class="goods">
              <table>
                <thead>
                  <tr style="text-align:center;">
                    <th>No.</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th width="100px">Total</th>
                  </tr>
                </thead>
                {{ $no = 1 }}
                {{ $subtotal = 0; }}
                @foreach($transaction as $tr)
                <tr>
                  <td style="text-align:center">{{ $no; }}</td>
                  <td>{{ $tr['name'] }}</td>
                  <td style="text-align:center">{{ $tr['qty'] }}</td>
                  <td style="text-align:right">{{ $tr['unit_price'] }}</td>
                  <td style="text-align:right">{{ $tr['discount'] }}</td>
                  <td style="text-align:right">{{ $tr['total'] }}</td>
                </tr>
                {{ $subtotal = $subtotal + $tr['total'] }}
                @if($no === count($transaction))
                <tr>
                  <td colspan="3" style="border:0;"></td>
                  <td colspan="2">Sub Total</td>
                  <td style="text-align:right"></td>
                </tr>
                <tr>
                  <td style="border:0;"colspan="3"></td>
                  <td colspan="2">Pajak</td>
                  <td style="text-align:right"></td>
                </tr>
                <tr>
                  <td style="border:0;" colspan="3"></td>
                  <td colspan="2">Biaya Pengiriman</td>
                  <td style="text-align:right"></td>
                </tr>
                <tr>
                  <td style="border:0;" colspan="3"></td>
                  <td colspan="2">TOTAL</td>
                  <td style="text-align:right">{{ $subtotal }}</td>
                </tr>
                @endif
                {{ $no = $no + 1 }}
                @endforeach
              </table>
            </div> -->
            <div class="signature">
              <div class="cust-signature">
                <p>Customer</p><br><br><br>
                <p>(...........................)</p>
              </div>
              <div class="pharm-signature">
                <p>Pharmacy</p><br><br><br>
                <p>(...........................)</p>
              </div>
            </div>
          </div>
          <!-- <p style="margin-top: 120px; page-break-after:always">testing content</p> -->
           <!-- <div style="page-break-after:always"> -->
                <!-- </div> -->
        </main>
    </body>
</html>
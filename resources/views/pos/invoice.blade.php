@extends('pos.layout')
@push('css')
    <style>
        @media print {
            @page {
                size: A5;
            }

            div.divFooter {
                position: absolute;
                right: 25%;
                bottom: -20pc;
            }

            div.divFooterBeib {
                position: absolute;
                right: 35%;
                bottom: -23pc;
            }

            html,
            body {
                height: 100%
            }

            .hr1 {
                background-color: var(--primary);
                height: 5px;
                margin-top: 2rem;
            }

            .hr2 {
                background-color: var(--primary);
                height: 2px;
                margin-bottom: 2rem;
                margin-top: 2px;
            }
        }

        .divFooter {
            position: absolute;

            bottom: -25pc;
        }


        .table tr td {
            border-bottom: 1px solid #000;
            /* Change the color you want to set */
        }

        .table>thead>tr>th {
            border-bottom: 1px solid #000;
            /* Change the color you want to set */
        }

        .hr1 {
            background-color: var(--primary);
            height: 5px;
            margin-top: 2rem;
        }

        .hr2 {
            background-color: var(--primary);
            height: 2px;
            margin-bottom: 2rem;
            margin-top: 2px;
        }

        hr {
            border-top: 1px solid #000 !important;
        }

        @page {
            size: A5;
        }
    </style>
@endpush
@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="nav-icon fas fa-file"></i> Show Invoice

                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-boxes"></i> Invoice</li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-file"></i> Show invoice</li>

                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-body">

                    <div class="row col-12">
                        <div class="col-6">
                            <img src="{{ $transaction->product_id == 2 ? asset('img/logo-beib-1.svg') : asset('img/logo.png') }}"
                                width="250px" alt="">
                        </div>
                        <div class="col-6 ">
                            <table class="">
                                {{-- <tr>
                                    <th valign="top">
                                        Lokasi
                                    </th>
                                    <td valign="top">:</td>
                                    <td width="" style="text-align:justify">
                                        Office 88 Kasablanka Tower A, 18th floor Jl. Casablanca Raya Kav 88 Jakarta 12870,
                                        Indonesia
                                    </td>
                                </tr> --}}
                                {{-- <tr>
                                    <th valign="top">
                                        Phone
                                    </th>
                                    <td>:</td>
                                    <td class="justy">
                                        +62 859-1065-30391
                                    </td>
                                </tr> --}}
                            </table>
                        </div>
                    </div>
                    <div class="hr1"></div>
                    <div class="hr2"></div>
                    <div class="row">
                        <div class="col-6">
                            <dl class="row">
                                <dt class="col-4">Nama</dt>
                                <dd class="col-8">: {{ $transaction->customer_name }}</dd>
                                <dt class="col-4">No Telepon</dt>
                                <dd class="col-8">: {{ $transaction->customer_phone }}</dd>
                                <dt class="col-4">Alamat</dt>
                                <dd class="col-8">: {{ $transaction->customer_address }}</dd>
                            </dl>
                        </div>
                        <div class="col-6">
                            <dl class="row">
                                <dt class="col-4">No Invoice</dt>
                                <dd class="col-8">: {{ $transaction->invoice_no }}</dd>
                                <dt class="col-4">Tanggal Transaksi</dt>
                                <dd class="col-8">: {{ sqlindo_date($transaction->date) }}</dd>
                                <dt class="col-4">Tipe Pembayaran</dt>
                                <dd class="col-8">: {{ $transaction->paymentType->name }}</dd>
                            </dl>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Kuantitas</th>
                                <th style="text-align:center">Harga Satuan</th>
                                <th style="text-align:center">Diskon</th>
                                <th style="text-align:center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->details as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->item_name }}
                                    </td>
                                    <td style="">x{{ $item->qty }}</td>
                                    <td style="text-align:center">
                                        {{ number_format($item->price + $item->ppn / $item->qty, 0, ',', '.') }}
                                    <td style="text-align:center">
                                        {{ number_format($item->discount, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align:right">
                                        {{ number_format($item->total, 0, ',', '.') }}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="row col-12" style="padding-right: unset;">
                        <div class="col-6">
                            {{-- <div class="form-group" style="position: absolute; bottom: 0px;width:90%">
                                <label>Kode Diskon</label>
                                <input type="text" class="form-control" name="kode_diskon" id="kode_diskon"
                                    value="" {{ $model->status_id != 1 ? 'readonly' : '' }}>
                            </div> --}}
                        </div>
                        <div class="col-6 p-0" style="background: white;padding-inline:1rem;">
                            <dl class="row mb-0">
                                <dt class="col-8"><label class="">Subtotal</label></dt>
                                <dd class="col-4" style="text-align: right"><label class=""><strong>
                                            {{ number_format($transaction->total, 0, ',', '.') }}</strong></label></dd>
                            </dl>
                            <hr style="">
                            <dl class="row mb-0">
                                <dt class="col-8"><label class="">Diskon</label></dt>
                                <dd class="col-4" style="text-align: right">
                                    <label class=""><strong>
                                            {{ number_format($transaction->discount, 0, ',', '.') }}</strong></label>
                                </dd>
                            </dl>
                            <hr>
                            <dl class="row mb-0">
                                <dt class="col-8"><label class="">Total</label></dt>
                                <dd class="col-4" style="text-align: right">
                                    <label class="" id="grandTotal"><strong>
                                            {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></label>
                                </dd>
                            </dl>

                        </div>
                    </div>
                    <div>
                        No. Rek:<br>
                        BCA WISMA MILLENIA<br>
                        {{ $transaction->product_id == 2 ? 'A/C : 005.988.2025' : 'A/C : 005.088.1997' }} <br>
                        A/N : Michele Gonatha<br>
                        <span style="font-style: italic">Notes : Pengiriman dilakukan setelah pembayaran</span>
                    </div>

                    <div class="row" style="margin-top: 4rem">
                        <div class="col-sm-4">
                            <p style="margin-bottom: 10rem">{{ $transaction->product_id == 2 ? 'Beib' : 'Soraba' }}
                                Official</p>
                            <p>Michele Gonatha</p>
                        </div>
                    </div>
                    <div class="float-right no-print">
                        {{-- <a href="{{ route('invoice.download', ['id' => base64_encode($transaction->id)]) }}"
                            class="btn btn-success"><i class="fas fa-download"></i> Download</a> --}}
                        <button type="button" class="btn btn-info" onclick="window.print()"><i class="fas fa-receipt"></i>
                            Cetak
                            Invoice</a>
                    </div>

                    @if ($transaction->product_id == 2)
                        <div class="divFooterBeib">
                            <div class="text-center">
                                <h4 style="align-self: center;color:var(--primary);font-weight: 700">FIND YOUR BEAUTY IN
                                    BALANCE!</h4>
                            </div>
                        </div>
                    @else
                        <div class="divFooter">
                            <div class="text-center">
                                <img src="{{ asset('img/soraba-tag.png') }}" width="500px" alt="">
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>

    <!-- /.modal -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            window.print()
        })
    </script>
@endpush

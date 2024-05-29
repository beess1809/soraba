@extends('pesanan-online.layout')
@push('css')
    <style>
        @media print {
            @page {
                size: A5;
            }
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
                            <img src="{{ asset('img/logo.png') }}" width="250px" alt="">
                        </div>
                        <div class="col-6 ">
                            <table class="">
                                <tr>
                                    <th valign="top">
                                        Apotek
                                    </th>
                                    <td valign="top">:</td>
                                    <td width="" style="text-align:justify">
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
                    <div class="row col-12" style="padding-right: unset;">
                        <div class="col-6">
                            {{-- <div class="form-group" style="position: absolute; bottom: 0px;width:90%">
                                <label>Kode Diskon</label>
                                <input type="text" class="form-control" name="kode_diskon" id="kode_diskon"
                                    value="" {{ $model->status_id != 1 ? 'readonly' : '' }}>
                            </div> --}}
                        </div>
                        <div class="col-6" style="background: white;padding-inline:1rem;padding-top:1.5rem">
                            <dl class="row">
                                <dt class="col-8"><label class="">Subtotal</label></dt>
                                <dd class="col-4" style="text-align: right"><label class=""><strong>
                                            {{ number_format($transaction->total, 0, ',', '.') }}</strong></label></dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-8"><label class="">Diskon</label></dt>
                                <dd class="col-4" style="text-align: right">
                                    <label class=""><strong>
                                            {{ number_format($transaction->discount, 0, ',', '.') }}</strong></label>
                                </dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-8"><label class="">Total</label></dt>
                                <dd class="col-4" style="text-align: right">
                                    <label class="" id="grandTotal"><strong>
                                            {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></label>
                                </dd>
                            </dl>

                        </div>
                    </div>
                    <small>

                        <ul>
                            <li>Transaksi sudah termasuk pajak</li>
                            <li>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan</li>
                        </ul>

                    </small>
                    <div class="float-right no-print">
                        {{-- <a href="{{ route('invoice.download', ['id' => base64_encode($transaction->id)]) }}"
                            class="btn btn-success"><i class="fas fa-download"></i> Download</a> --}}
                        <button type="button" class="btn btn-info" onclick="window.print()"><i class="fas fa-receipt"></i>
                            Cetak
                            Invoice</a>
                    </div>
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

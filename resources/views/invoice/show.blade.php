@extends('layouts.app')

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
                        <div class="col-8">
                            <img src="{{ asset('img/logo.png') }}" width="250px" alt="">
                        </div>
                        <div class="col-4 ">
                            <table class="">
                                <tr>
                                    <th valign="top">
                                        Office
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
                                <dt class="col-4">Customer Name</dt>
                                <dd class="col-8">: {{ $transaction->customer_name }}</dd>
                                <dt class="col-4">Customer Phone</dt>
                                <dd class="col-8">: {{ $transaction->customer_phone }}</dd>
                                <dt class="col-4">Customer Address</dt>
                                <dd class="col-8">: {{ $transaction->customer_address }}</dd>
                            </dl>
                        </div>
                        <div class="col-6">
                            <dl class="row">
                                <dt class="col-4">Invoice No.</dt>
                                <dd class="col-8">: {{ $transaction->invoice_no }}</dd>
                                <dt class="col-4">Date</dt>
                                <dd class="col-8">: {{ sqlindo_date($transaction->date) }}</dd>
                                <dt class="col-4">Payment Type</dt>
                                <dd class="col-8">: {{ $transaction->paymentType->name }}</dd>
                            </dl>
                        </div>
                    </div>

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->details as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @php
                                            $ids = json_decode($item->item_id);
                                            $qty = json_decode($item->qty_item);

                                        @endphp

                                        {{-- @if (count($ids) > 1)
                                            <span style="font-size: 12pt">{{ $item->item_name }}</span>
                                            <ul>
                                                @foreach ($ids as $id)
                                                    @php
                                                        $dtl = App\Models\Master\Item::where('id', $id)->first();
                                                    @endphp
                                                    <li style="font-size: 10pt">{{ $dtl->name }}</li>
                                                @endforeach
                                            </ul>
                                        @else --}}
                                        <span>{{ $item->item_name }}</span>
                                        {{-- @endif --}}


                                    </td>
                                    <td style="">{{ $item->qty }}</td>
                                    <td style="text-align:right">{{ format_rupiah($item->price) }}</td>
                                    <td style="text-align:right">
                                        {{ format_rupiah(($item->price * $item->qty * $item->discount) / 100) }}
                                    </td>
                                    <td style="text-align:right">{{ format_rupiah($item->total) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" style="border:0;"></td>
                                <td colspan="1"><strong>Sub Total</strong></td>
                                <td style="text-align:right"><strong>{{ format_rupiah($transaction->sub_total) }}</strong>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td colspan="4" style="border:0;"></td>
                                <td colspan="1"><strong>Pajak</strong></td>
                                <td style="text-align:right"><strong>{{ format_rupiah($transaction->pajak) }}</strong></td>
                            </tr> --}}
                            <tr>
                                <td colspan="4" style="border:0;"></td>
                                <td colspan="1"><strong>Biaya Pengiriman</strong></td>
                                <td style="text-align:right">
                                    <strong>{{ format_rupiah($transaction->biaya_pengiriman) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="border:0;"></td>
                                <td colspan="1"><strong>Discount</strong></td>
                                <td style="text-align:right">
                                    <strong>{{ format_rupiah($transaction->discount) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="border:0;"></td>
                                <td colspan="1"><strong>TOTAL</strong></td>
                                <td style="text-align:right"><strong>{{ format_rupiah($transaction->sub_total) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="float-right">
                        <a href="{{ route('invoice.download', ['id' => base64_encode($transaction->id)]) }}"
                            class="btn btn-success"><i class="fas fa-download"></i> Download</a>
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
            selectItem()
        })
    </script>
@endpush

@extends('pos.layout')

@section('content')
    <img src="{{ asset('img/login-back.png') }}" alt="Logo" class="logo-detail-transaction">
    <div class="container-inventory">
        <!-- Content Header (Page header) -->
        <form action="{{ route('pos.update', ['id' => base64_encode($model->id)]) }}" method="patch" id="form-transaction">
            @csrf
            <div class="row">
                <div class="col-lg-3 customer" style="overflow-y: scroll;height: 86.8vh">
                    <div class="form-group">
                        <h5 style="color: #191b2580;margin-bottom:0px"><strong>No. Invoice</strong></h5>
                        <p>{{ $model->invoice_no }}</p>
                        <input type="hidden" class="form-control" name="no_invoice" id="no_invoice" value="">
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ $model->customer_name }}" {{ $model->status_id != 1 ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label>Usia</label>
                        <input type="text" class="form-control" name="usia" id="usia"
                            value="{{ $model->customer_age }}" {{ $model->status_id != 1 ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label>No. Telp</label>
                        <input type="text" class="form-control" name="no_telp" id="no_telp"
                            value="{{ $model->customer_phone }}" {{ $model->status_id != 1 ? 'readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <Textarea class="form-control" name="alamat" id="alamat" {{ $model->status_id != 1 ? 'readonly' : '' }}>{{ $model->customer_address }}</Textarea>
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" id="tanggal"
                            value="{{ $model->date }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tipe Pembayaran <span class="text-red">(* Wajib Diisi)</span></label>
                        <select name="tipe_pembayaran" id="tipe_pembayaran" class="form-control select2">
                            <option value="">Pilih Tipe Pembayaran</option>
                            @foreach (App\Models\Master\PaymentType::all() as $key => $item)
                                <option value="{{ $item->id }}"
                                    {{ $item->id == $model->payment_type_id ? 'selected' : '' }}>{{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipe_pembayaran')
                            <small class="text-red">Anda belum memilih Tipe Pembayaran</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>No. Kartu</label>
                        <input type="text" class="form-control" name="card_number" id="no_kartu"
                            value="{{ $model->card_number }}" {{ $model->status_id != 1 ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="col-lg-9 detail-transaction">
                    <div class="content-header">
                        <div class="row col-12">
                            <div class="col-md-6">
                                <h5 class="m-0" style="color: #191b2580"> <strong>Detail Transaction</strong></h5>
                            </div>
                            <div class="col-md-6">
                                @if ($model->status_id == 1)
                                    <button type="button" class="btn btn-outline-danger float-right btn-cancel"><i
                                            class="fa fa-trash"></i>
                                        &nbsp;Batalkan Transaksi</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="col-12">
                        <table class="table table-striped ">
                            <thead>
                                <tr style="text-align: center">
                                    <th style="width: 50px">No</th>
                                    <th>Nama Item</th>
                                    <th>Kuantitas</th>
                                    <th>Harga Satuan</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody style="background: white">
                                @php
                                    $qty = 0;
                                @endphp
                                @foreach ($model->details as $key => $item)
                                    <tr style="text-align: center">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->item_name }}</td>
                                        <td>x{{ $item->qty }}</td>
                                        <td style="text-align: center">
                                            {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td style="text-align: center">
                                            {{ number_format($item->discount, 0, ',', '.') }}
                                        </td>
                                        <td style="text-align: right">
                                            {{ number_format($item->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @php
                                        $qty += $item->qty;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row col-12" style="padding-right: unset;">
                        <div class="col-6">
                            {{-- <div class="form-group" style="position: absolute; bottom: 0px;width:90%">
                                <label>Kode Diskon</label>
                                <input type="text" class="form-control" name="kode_diskon" id="kode_diskon"
                                    value="" {{ $model->status_id != 1 ? 'readonly' : '' }}>
                            </div> --}}

                            <small>* Sub Total = Dpp {{ number_format($model->sub_total, 0, ',', '.') }} + Pajak
                                {{ number_format($model->pajak, 0, ',', '.') }}</small>
                        </div>
                        <div class="col-6" style="background: white;padding-inline:1rem;padding-top:1.5rem">
                            <dl class="row">
                                <dt class="col-8"><label class="label-bordered">Sub Total</label></dt>
                                <dd class="col-4" style="text-align: right"><label class="label-bordered"><strong>
                                            {{ number_format($model->total, 0, ',', '.') }}</strong></label></dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-8"><label class="label-bordered">Discount</label></dt>
                                <dd class="col-4" style="text-align: right">
                                    @if ($model->status_id != 1)
                                        <label class="label-bordered"><strong>
                                                {{ number_format($model->discount, 0, ',', '.') }}</strong></label>
                                    @else
                                        <input type="text" class="form-control number text-right" name="discount"
                                            id="discount" value="{{ $model->discount ? $model->discount : 0 }}"
                                            onkeyup="disc()">
                                    @endif

                                </dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-8"><label class="label-bordered">Grand Total</label></dt>
                                <dd class="col-4" style="text-align: right">
                                    <label class="label-bordered" id="grandTotal"><strong>
                                            {{ number_format($model->grand_total, 0, ',', '.') }}</strong></label>
                                </dd>
                            </dl>

                        </div>
                    </div>
                    <br>
                    <div>
                        @if ($model->status_id == 1)
                            <button type="button" class="btn btn-inventory float-right"
                                style="width: 360px;height:60px;border-radius: 1rem;" id="done">Selesaikan
                                Transaksi</button>
                        @elseif($model->status_id == 2)
                            <div class="float-right">
                                <a href="{{ route('pos.invoice', ['id' => base64_encode($model->id)]) }}" target="_blank"
                                    type="button" class="btn btn-info" title="Edit Item"><i class="fas fa-receipt"></i>
                                    Cetak Invoice</a>
                                <a href="{{ route('pos.struk', ['id' => base64_encode($model->id)]) }}" target="_blank"
                                    type="button" class="btn btn-secondary" title="Edit Item"><i
                                        class="fas fa-receipt"></i>
                                    Cetak Struk</a>
                                {{-- &nbsp;&nbsp;<a href="{{ route('invoice.download', ['id' => base64_encode($model->id)]) }}"
                                    type="button" class="btn btn-sm btn-success" title="Download"><i
                                        class="fa fa-download"></i></a> --}}
                            </div>
                        @endif
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
        </form>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@push('scripts')
    <script>
        $(".btn-cancel").click(function() {
            Swal.fire({
                title: " Batalkan Transaksi",
                text: "Apakah Anda yakin membatalkan Transaksi ini ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Iya, Batalkan!",
                cancelButtonText: "Tidak!",
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('pos.batal', ['id' => base64_encode($model->id)]) }}",
                        method: "put",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Success!",
                                    text: "Transaksi Dibatalkan!",
                                    timer: 2000,
                                    confirmButtonColor: "#3085d6",
                                });
                                window.location.replace(response.data.url);
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Terjadi Kesalahan!",
                                    confirmButtonColor: "#3085d6",
                                });
                            }
                        },
                        error: function(xhr) {
                            var res = xhr.responseJSON;
                            if (res.message != "") {}
                            console.log(res.errors);
                            if ($.isEmptyObject(res.errors) == false) {
                                $.each(res.errors, function(key, value) {
                                    $("#" + key)
                                        .closest(".form-control")
                                        .addClass("is-invalid");
                                    $(
                                        '<span class="invalid-feedback" role="alert"><strong>' +
                                        value +
                                        "</strong></span>"
                                    ).insertAfter($("#" + key));
                                });
                            }

                            Swal.fire({
                                icon: "error",
                                title: "Something went wrong!",
                                text: "Check your values",
                                confirmButtonColor: "#3085d6",
                            });
                        },
                    });
                }
            });
        });
        $("#done").click(function() {
            var form = $("#form-transaction")
            form.find(".invalid-feedback").remove();
            $.ajax({
                url: "{{ route('pos.update', ['id' => base64_encode($model->id)]) }}",
                method: "put",
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Selamat !",
                            text: "Transaksi Berhasil",
                            timer: 10000,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "Oke !",
                        }).then((result) => {
                            window.location.reload();

                        });
                    } else {
                        console.log(response.responseJSON)
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Terjadi Kesalahan!",
                            confirmButtonColor: "#3085d6",
                        });
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    console.log(xhr);
                    if ($.isEmptyObject(res.errors) == false) {
                        $.each(res.errors, function(key, value) {
                            $("#" + key)
                                .closest(".form-control")
                                .addClass("is-invalid");
                            $(
                                '<small class="invalid-feedback" role="alert"><strong>' +
                                value +
                                "</strong></small>"
                            ).insertAfter($("#" + key));
                        });
                    }

                    Swal.fire({
                        icon: "warning",
                        title: "Periksa Kembali Form Pembelian!",
                        confirmButtonColor: "#3085d6",
                    });
                },
            });
        });

        function disc() {
            let _discount = $("#discount").val().replace(/\./g, "")

            let total = parseFloat({{ $model->total }}) - parseFloat(_discount) //+ parseFloat(_ppn)
            $('#grandTotal').html(formatRupiah(total.toString()))
        }
    </script>
@endpush

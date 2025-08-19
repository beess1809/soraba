@extends('pos.layout')

@section('content')
    <div class="container-inventory">
        <!-- Content Header (Page header) -->
        <div class="row">
            <div class="col-lg-8">
                <div class="content-header">
                    <div class="">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0"> Buat Pesanan</small></h1>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <div class="row col-12">
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="umum-tab" data-toggle="pill" href="#umum" role="tab"
                                    aria-controls="umum" aria-selected="false"><i class="fas fa-notes-medical">
                                        &nbsp;</i>Reguler</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bundling-tab" data-toggle="pill" href="#bundling" role="tab"
                                    aria-controls="bundling" aria-selected="false"><i class="fas fa-pills">
                                        &nbsp;</i>Paket</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="flash-sale-umum-tab" data-toggle="pill" href="#flash-sale-umum"
                                    role="tab" aria-controls="flash-sale-umum" aria-selected="false"><i
                                        class="fas fa-stopwatch"></i>&nbsp;Flash
                                    Sale Reguler</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="flash-sale-bundling-tab" data-toggle="pill"
                                    href="#flash-sale-bundling" role="tab" aria-controls="flash-sale-bundling"
                                    aria-selected="false"><i class="fas fa-stopwatch-20"></i>
                                    &nbsp;Flash Sale Paket</a>
                            </li> --}}
                        </ul>
                    </div>

                </div>

                <div class="tab-content" id="tabContent" style="padding-top: 1rem;">
                    <div class="tab-pane fade show active" id="umum" role="tabpanel" aria-labelledby="umum">
                        @include('pos.tab.umum')
                    </div>
                    <div class="tab-pane fade show" id="bundling" role="tabpanel" aria-labelledby="bundling">
                        @include('pos.tab.bundling')
                    </div>
                    {{-- <div class="tab-pane fade show" id="flash-sale-umum" role="tabpanel" aria-labelledby="flash-sale-umum">
                        @include('pos.tab.flash-sale-umum')
                    </div>
                    <div class="tab-pane fade show" id="flash-sale-bundling" role="tabpanel"
                        aria-labelledby="flash-sale-bundling">
                        @include('pos.tab.flash-sale-bundling')
                    </div> --}}
                </div>
            </div>
            <!-- /.col-md-6 -->
            <div class="col-lg-4 order">
                <div class="p-3 order-title d-flex justify-content-between">
                    <div>
                        <span>{{ Auth::user()->roles[0]->name }}</span>
                        <br>
                        <strong>{{ Auth::user()->name }}</strong>
                    </div>
                    <div class="d-flex align-items-center" style="font-weight: bold">
                        <span id="free-gift"></span>
                        <input type="hidden" name="total_qty" id="total_qty" value="0">
                    </div>
                </div>
                <form action="{{ route('pos.store') }}" method="post" id="form-pesan">
                    @csrf
                    <div class="p-3">
                        <h4>Pesanan</h4>
                        <div id="detail" class="detail-pesanan" style="overflow-y: scroll;height: 100vh">
                            @if ($model->exists)
                                @foreach ($model->details as $key => $detail_transaksi)
                                    @php
                                        $item = null;

                                        $item_id = json_decode($detail_transaksi->item_id);
                                        $qty_item = json_decode($detail_transaksi->qty_item);
                                        $item_price = json_decode($detail_transaksi->item_price);
                                        $item_discount = json_decode($detail_transaksi->item_discount);

                                        if (count($item_id) <= 1) {
                                            $item = \App\Models\Master\Item::find($item_id[0]);
                                        } else {
                                            $item = \App\Models\Master\Item::find($item_id[0]);
                                        }

                                    @endphp
                                    <div class="row item-detail" id="detail-{{ $detail_transaksi->id }}">
                                        <div class="col-2">
                                            <img src="https://soraba.test/img/no-pict.png" width="100%" height="84px"
                                                class="rounded">
                                        </div>
                                        <div class="col-10">
                                            <dl>
                                                <dd style="margin-bottom: 0px">
                                                    {{ strtoupper($detail_transaksi->item_name) }}</dd>
                                                <input type="hidden" name="item_name[]"
                                                    value="{{ strtoupper($detail_transaksi->item_name) }}">
                                                <input type="hidden" name="index[]" value="{{ $detail_transaksi->id }}">
                                                <input type="hidden" name="item_id[{{ $detail_transaksi->id }}][0]"
                                                    value="{{ $item->id }}">
                                                <dd style="margin-bottom: 0px;color:#626E73"><strong>x1</strong></dd>
                                                <input type="hidden" name="item_qty[{{ $detail_transaksi->id }}][0]"
                                                    value="{{ $detail_transaksi->qty }}">
                                                <input type="hidden" name="item_price[{{ $detail_transaksi->id }}][0]"
                                                    value="{{ $detail_transaksi->price }}">
                                                <input type="hidden" name="item_discount[{{ $detail_transaksi->id }}][0]"
                                                    value="{{ $detail_transaksi->discount }}">
                                                <input type="hidden" name="discount[]"
                                                    value="{{ $detail_transaksi->discount }}">
                                                <input type="hidden" name="qty[]" id="{{ $detail_transaksi->id }}"
                                                    value="{{ $detail_transaksi->qty }}">
                                                <dd style="margin-bottom: 0px">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <textarea class="form-control" rows="1" name="notes[]" placeholder="Catatan" style="min-width: 100%"></textarea>

                                                        </div>
                                                        <div class="col-7">
                                                            <strong class="float-right" style="margin-right: 2rem">
                                                                Rp.
                                                                {{ number_format($detail_transaksi->total, '0', ',', '.') }}
                                                                <input type="hidden" name="price[]"
                                                                    id="price_{{ $detail_transaksi->id }}"
                                                                    value="{{ $detail_transaksi->total }}">
                                                                <input type="hidden" name="cost[]"
                                                                    id="cost_{{ $detail_transaksi->id }}"
                                                                    value="{{ $detail_transaksi->cost }}">
                                                                <input type="hidden" name="sub_price[]"
                                                                    id="sub_price_{{ $detail_transaksi->id }}"
                                                                    value="{{ $detail_transaksi->price }}">
                                                                <button type="button" class="btn btn-xs btn-danger"
                                                                    onclick="hapusOrder(this,{{ $detail_transaksi->id }})">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                            </strong>
                                                        </div>
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    @if ($model->exists)
                        <div class="p3 footer-pesanan">
                            <div class="row col-12">
                                <div class="col-6">
                                    <h5>Subtotal</h5>
                                </div>
                                <div class="col-6" style="text-align: right" id="subtotal">
                                    <h5> Rp. <span
                                            id="sub">{{ number_format($model->sub_total, '0', ',', '.') }}</span>
                                    </h5>
                                    <input type="hidden" name="_subtotal" id="_subtotal"
                                        value="{{ $model->sub_total }}">
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="col-6">
                                    <h5>Item</h5>
                                </div>
                                <div class="col-6" style="text-align: right" id="tot_qty">
                                    <h5><span id="pcs">{{ count($model->details) }}</span> Pcs</h5>
                                    <input type="hidden" name="_tot_qty" id="_tot_qty" value="0">
                                </div>
                            </div>
                            <hr>
                            <div class="row col-12" style="margin-bottom: 3rem">
                                <div class="col-6">
                                    <h5>Total</h5>
                                </div>
                                <div class="col-6" style="text-align: right" id="total">
                                    <h5>Rp. <span id="tot">{{ number_format($model->total, '0', ',', '.') }}</span>
                                    </h5>
                                    <input type="hidden" name="total" id="_total" value="{{ $model->total }}">
                                    <input type="hidden" name="total_cost" id="_cost" value="{{ $model->cost }}">
                                </div>
                            </div>
                            <button type="button" class="btn btn-inventory btn-block btn-lg" id="btn-lanjut">Lanjut
                                Pembayaran</button>
                        </div>
                    @else
                        <div class="p3 footer-pesanan">
                            <div class="row col-12">
                                <div class="col-6">
                                    <h5>Subtotal</h5>
                                </div>
                                <div class="col-6" style="text-align: right" id="subtotal">
                                    <h5> Rp. <span id="sub">0</span></h5>
                                    <input type="hidden" name="_subtotal" id="_subtotal" value="0">
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="col-6">
                                    <h5>Item</h5>
                                </div>
                                <div class="col-6" style="text-align: right" id="tot_qty">
                                    <h5><span id="pcs">0</span> Pcs</h5>
                                    <input type="hidden" name="_tot_qty" id="_tot_qty" value="0">
                                </div>
                            </div>
                            <hr>
                            <div class="row col-12" style="margin-bottom: 3rem">
                                <div class="col-6">
                                    <h5>Total</h5>
                                </div>
                                <div class="col-6" style="text-align: right" id="total">
                                    <h5>Rp. <span id="tot">0</span></h5>
                                    <input type="hidden" name="total" id="_total" value="0">
                                    <input type="hidden" name="total_cost" id="_cost" value="0">
                                </div>
                            </div>
                            <button type="button" class="btn btn-inventory btn-block btn-lg" id="btn-lanjut">Lanjut
                                Pembayaran</button>
                        </div>
                    @endif

                </form>

            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
@endsection
@push('scripts')
    <script>
        function pesan(id, type) {
            if (type == 1) {
                var qty = $(".input-number-" + id).val();
                var price = $(".input-price-" + id).val();
                var item_id = id
            } else if (type == 2) {
                var qty = $(".input-number-" + id).val();
                var price = $(".input-price-" + id).val();
                var item_id = id;
            } else if (type == 3) {
                var qty = $(".input-number-" + id).val();
                var price = $(".input-price-" + id).val();
                var item_id = id;
            } else if (type == 4) {
                var qty = $(".input-number-" + id).val();
                var price = $(".input-price-" + id).val();
                var item_id = id;
            }

            var _total_qty = $('#total_qty').val();
            var _total_free_gift = $('#free-gift').val();

            console.log('type: ' + type);
            console.log('item : ' + id);
            console.log('qty : ' + qty);

            $.ajax({
                url: "{{ route('pos.add-to-cart') }}",
                method: 'post',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'qty': qty,
                    'tipe': type,
                    'item_name': item_id,
                    'item_id': item_id,
                },
                success: function(response) {

                    if (response.success) {
                        $('#__item_name').val('')
                        $('#__qty_item_1').val('')
                        $("#__item_ids").val('').trigger('change');
                        $("#cost").val('').trigger('change');
                        $('#__qty').val('')
                        $('#item-detail').find('.with-item').remove();

                        $('#detail').append(response.data.html)

                        var _sub = $('#_subtotal').val();
                        var _cost = $('#_cost').val();

                        if (parseInt(price) > 0) {
                            var total_qty = parseInt(_total_qty) + parseInt(qty);
                            var free_gift = Math.floor(total_qty / 2);

                            $('#total_qty').val(total_qty.toString());
                            $('#free-gift').text(free_gift.toString() + ' Free Gift');
                        }

                        var sub = parseInt(_sub) + parseInt(response.data.item.harga);
                        var cost = parseInt(_cost) + parseInt((response.data.item.cost));

                        $('#_subtotal').val(sub.toString());
                        $('#sub').html(formatRupiah(sub.toString()));
                        $('#tot').html(formatRupiah(sub.toString()));
                        $('#_total').val(sub.toString());
                        $('#_cost').val(cost.toString());

                        var pcs = $('.item-detail').length
                        $('#pcs').html(pcs);

                        Swal.fire({
                            icon: 'success',
                            text: 'Item berhasil ditambahkan!',
                            timer: 2000,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: response.data.message,
                            confirmButtonColor: '#3085d6',
                        });
                    }

                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if (res.message != '') {

                    }
                    console.log(res.errors)
                    if ($.isEmptyObject(res.errors) == false) {
                        $.each(res.errors, function(key, value) {
                            $('#' + key)
                                .closest('.form-control')
                                .addClass('is-invalid')
                            $('<span class="invalid-feedback" role="alert"><strong>' + value +
                                '</strong></span>').insertAfter($('#' + key))
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong!',
                        text: 'Check your values',
                        confirmButtonColor: '#3085d6',
                    });

                }
            })
        }

        function hapusOrder(btn, id) {
            var _qty = $('#qty_' + id).val();

            var _sub = $('#_subtotal').val();
            var _price = $('#price_' + id).val();

            var _cost = $('#cost_' + id).val();
            var cost = $('#_cost').val();

            var _total_qty = $('#total_qty').val();
            var _total_free_gift = $('#free-gift').val();

            if (parseInt(_price) > 0) {
                var total_qty = parseInt(_total_qty) - parseInt(_qty);
                var free_gift = Math.floor(total_qty / 2);

                $('#total_qty').val(total_qty.toString());
                $('#free-gift').text(free_gift.toString() + ' Free Gift');
            }

            var sub = parseInt(_sub) - parseInt(_price);
            var embalase = parseInt(cost) - parseInt(_cost);

            $('#_subtotal').val(sub.toString());
            $('#sub').html(formatRupiah(sub.toString()));
            $('#tot').html(formatRupiah(sub.toString()));
            $('#_total').val(sub.toString());
            $('#_cost').val(embalase.toString());

            $('#detail-' + id).remove();
            var pcs = $('.item-detail').length
            $('#pcs').html(pcs);

            Swal.fire({
                icon: 'error',
                text: 'Item berhasil dihapus!',
                timer: 2000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
            });
        }

        $("#btn-lanjut").click(function(event) {
            event.preventDefault();

            var form = $("#form-pesan"),
                url = form.attr("action"),
                method = "POST";

            console.log(url)
            console.log(method)

            form.find(".help-block").remove();
            form.find(".form-group").removeClass("has-error");

            $.ajax({
                url: url,
                method: method,
                data: form.serialize(),
                success: function(response) {
                    form.trigger("reset");
                    console.log(response.success);
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Data has been saved!",
                            timer: 2000,
                            confirmButtonColor: "#3085d6",
                        });
                        window.location.replace(response.data.url);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: response.data.message,
                            confirmButtonColor: '#3085d6',
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
        });

        $("#btn-cari").click(function() {
            var cari = $('#cari').val();
            $.ajax({
                url: "{{ route('pos.cari') }}",
                method: 'post',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'cari': cari,
                },
                success: function(response) {

                    if (response.success) {
                        $('#card-item').html('')

                        for (let i = 0; i < response.data.length; i++) {
                            $('#card-item').append(response.data[i])
                        }

                        Swal.fire({
                            icon: 'success',
                            text: 'Item  ditemukan!',
                            timer: 2000,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: response.data.message,
                            confirmButtonColor: '#3085d6',
                        });
                    }

                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if (res.message != '') {

                    }
                    console.log(res.errors)
                    if ($.isEmptyObject(res.errors) == false) {
                        $.each(res.errors, function(key, value) {
                            $('#' + key)
                                .closest('.form-control')
                                .addClass('is-invalid')
                            $('<span class="invalid-feedback" role="alert"><strong>' +
                                value +
                                '</strong></span>').insertAfter($('#' + key))
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong!',
                        text: 'Check your values',
                        confirmButtonColor: '#3085d6',
                    });

                }
            })
        })

        $('#category').change(() => {
            // Swal.showLoading();
            var cat_id = $('#category').val();

            // if(cat_id) {

            $.ajax({
                url: '{{ route('items.getItemByCategory') }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    cat_id
                },
                success: (data) => {
                    // console.log(data);
                    $('#card-item').html('')

                    for (let i = 0; i < data.length; i++) {
                        $('#card-item').append(data[i])
                    }
                    // var str_data = '<option value="">Select City</option>';
                    // str_data += data.map((project) => {
                    //     return '<option value="' + project.city_id + '">' + project.city_name +
                    //         '</option>';
                    // });
                    // $('#city').html(str_data);
                    // Swal.close();
                },
                error: (xhr) => {
                    console.log(xhr);
                    // Swal.close();
                }
            });
            // }
        });
    </script>
@endpush

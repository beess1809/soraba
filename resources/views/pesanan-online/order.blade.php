@extends('pesanan-online.layout')

@section('content')
    <div class="container-inventory">
        <!-- Content Header (Page header) -->
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="content-header">
                    <div class="">
                        <div class="row mb-2">
                            {{-- <div class="col-sm-6">
                                <h1 class="m-0"> Buat Pesanan</small></h1>
                            </div> --}}
                        </div>
                    </div>
                </div>

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
                            <li class="nav-item">
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
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="tab-content" id="tabContent" style="padding-top: 1rem;">
                    <div class="tab-pane fade show active" id="umum" role="tabpanel" aria-labelledby="umum">
                        @include('pesanan-online.tab.umum')
                    </div>
                    <div class="tab-pane fade show" id="bundling" role="tabpanel" aria-labelledby="bundling">
                        @include('pesanan-online.tab.bundling')
                    </div>
                    <div class="tab-pane fade show" id="flash-sale-umum" role="tabpanel" aria-labelledby="flash-sale-umum">
                        @include('pesanan-online.tab.flash-sale-umum')
                    </div>
                    <div class="tab-pane fade show" id="flash-sale-bundling" role="tabpanel"
                        aria-labelledby="flash-sale-bundling">
                        @include('pesanan-online.tab.flash-sale-bundling')
                    </div>
                </div>
            </div>
            <!-- /.col-md-6 -->
            <div class="col-lg-4 col-12 order" id="pesanan">
                {{-- <div class="p-3 order-title"> --}}
                {{-- <span>tes</span> --}}
                {{-- <br> --}}
                {{-- <strong>tes</strong> --}}
                {{-- </div> --}}
                <form action="{{ route('pesanan-online.store') }}" method="post" id="form-pesan">
                    @csrf
                    <div class="p-3">
                        <h4>Pesanan</h4>
                        <div id="detail" class="detail-pesanan" style="overflow-y: scroll;height: 250px">

                        </div>
                    </div>

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
                </form>

            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pengumuman</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="closePopUp()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Sedang Ada Promo Flash Sale
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                        onclick="closePopUp()">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
@push('scripts')
    <script>
        if (document.cookie.indexOf("popupShown=true") == -1) {
            document.cookie = "popupShown=true; max-age=86400"; // 86400: seconds in a day
            $('#modal-default').modal('show');
        }

        function pesan(id, type) {
            if (type == 1) {
                var qty = $(".input-number-" + id).val()
                var item_id = id
            } else if (type == 2) {
                var qty = $(".input-number-" + id).val();
                var item_id = id
            } else if (type == 3) {
                var qty = $(".input-number-" + id).val();
                var item_id = id
            } else if (type == 4) {
                var qty = $(".input-number-" + id).val();
                var item_id = id
            }

            var cart_amount = $('.cart-amount').text();
            console.log(cart_amount)

            console.log('type: ' + type);
            console.log('item : ' + id);
            console.log('qty : ' + qty);
            // console.log('item id : ' + item_id);
            // console.log('qty item : ' + qty_item);

            $.ajax({
                url: "{{ route('pesanan-online.add-to-cart') }}",
                method: 'post',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'qty': qty,
                    'tipe': type,
                    'item_name': item_id,
                    'item_id': item_id,
                    // 'qty_item': qty_item,
                    // 'cost': cost,
                },
                success: function(response) {
                    if (response.success) {
                        cart_amount++;
                        $('.cart-amount').text(cart_amount);

                        $('#__item_name').val('')
                        $('#__qty_item_1').val('')
                        $("#__item_ids").val('').trigger('change');
                        $("#cost").val('').trigger('change');
                        $('#__qty').val('')
                        $('#item-detail').find('.with-item').remove();

                        $('#detail').append(response.data.html)

                        var _sub = $('#_subtotal').val();
                        var _cost = $('#_cost').val();

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
                        $(".input-number-" + id).val(0)
                    } else {
                        $(".input-number-" + id).val(0)
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
            var cart_amount = $('.cart-amount').text();
            var _sub = $('#_subtotal').val();
            var _price = $('#price_' + id).val();

            var _cost = $('#cost_' + id).val();
            var cost = $('#_cost').val();

            var sub = parseInt(_sub) - parseInt(_price);
            var embalase = parseInt(cost) - parseInt(_cost);

            $('#_subtotal').val(sub.toString());
            $('#sub').html(formatRupiah(sub.toString()));
            $('#tot').html(formatRupiah(sub.toString()));
            $('#_total').val(sub.toString());
            $('#_cost').val(embalase.toString());

            cart_amount = cart_amount - 1;
            $('.cart-amount').text(cart_amount);

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
                url: "{{ route('pesanan-online.cari') }}",
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
        })

        $('#category').change(() => {
            // Swal.showLoading();
            var cat_id = $('#category').val();

            // if(cat_id) {

            $.ajax({
                url: '{{ route('pesanan-online.getItemByCategory') }}',
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

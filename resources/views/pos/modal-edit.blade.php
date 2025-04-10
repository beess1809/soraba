<form class="form-horizontal" action="{{ route('items.updateuploadExcel') }}" method="POST">
    @if ($tipe == 1)
        @php
            $stock = $item->qty == 0 ? '<small class="text-red">Out of Stock !</small>' : '<small>In Stock</small>';
            $pajak = 0; //pajak($item->sale_price);
            $harga = $item->sale_price + $pajak;
            if (!is_null($item->discount)) {
                $total =
                    '<dd style="color:grey"><strong><s>Rp. ' .
                    number_format($harga, 0, ',', '.') .
                    '</s></strong></dd>';
                $harga = $harga - $item->discount;
                $discount = '<span><strong>Rp. ' . number_format($harga, 0, ',', '.') . '</strong></span>';
            } else {
                $total = '<dd style=""><strong>Rp. ' . number_format($harga, 0, ',', '.') . '</strong></dd>';
                $discount = '';
            }
        @endphp
        <div class="card">
            <div class="card-body" style="background-color: #F2F2F280">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ asset('img/no-pict.png') }}" alt="" width="100%" height="100px"
                            class="rounded">
                    </div>
                    <div class="col-md-8">
                        <dl>
                            <dd>{{ $item->name }} </dd>
                            <dd></dd>
                        </dl>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <dl>
                            {!! $total !!}
                            <input type="hidden" name="old_total_update"
                                id="old_total_update-{{ $item->id }}-{{ $model->id }}"
                                value="{{ $old_total }}">
                            <input type="hidden" name="input_price"
                                class="input-price-{{ $item->id }}-{{ $model->id }}"
                                value="{{ number_format($item->sale_price, '0', ',', '.') }}">
                            <dd style="margin-bottom: 0px"><span>{{ format_rupiah($item->qty) }}</span></dd>
                            <dd style="margin-bottom: 0px">{!! $stock !!}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        {{ $discount }}
                        <div class="input-group" style="margin-top: 0.5rem">
                            <span class="input-group-btn">
                                <button type="button"
                                    class="btn btn-xs btn-danger btn-number-{{ $item->id }}-{{ $model->id }}"
                                    onclick="minusEditItem({{ $item->id }}, {{ $model->id }})"
                                    data-type="minus" data-field="quant[2]">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </span>
                            <input type="text" name="quant[2]"
                                class="form-control input-number-{{ $item->id }}-{{ $model->id }}"
                                value="{{ $qty_item }}" min="0" max="{{ $item->qty }}"
                                style="background-color: #F2F2F280;border: 0px;text-align:center">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-xs btn-soraba btn-number-{{ $item->id }}"
                                    onclick="plusEditItem({{ $item->id }},{{ $model->id }})" data-type="plus"
                                    data-field="quant[2]">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-block btn-inventory"
                    onclick="pesanEdit('{{ $item->id }}',{{ $model->id }},1)">Ubah ke Pesanan</button>

            </div>
        </div>
    @elseif ($tipe == 2)
        @php
            $harga = $bundling->price;
            $total = '<dd><strong>Rp. ' . number_format($harga, 0, ',', '.') . '</strong></dd>';
            $items = json_decode($bundling->item_id);

            $li = '<ul style="margin-bottom: 0; padding-left: 10px">';
            foreach ($items as $key => $item) {
                $item_name = App\Models\Master\Item::find($item->item);
                $li .= '<li>';
                $li .=
                    '<input type="hidden" class="item-formula" value="' .
                    $item_name->id .
                    '">' .
                    $item_name->name .
                    ' ';
                $li .=
                    '<input type="hidden" class="form-control qty-formula" name="__qty_item" id="__qty_item' .
                    $bundling->id .
                    $item->item .
                    '" value="' .
                    $item->qty .
                    '" readonly> | ' .
                    $item->qty .
                    ' ' .
                    $item_name->uom->name;
                $li .= '</li>';
            }
            $li .= '</ul>';
        @endphp
        <div class="card">
            <div class="card-body item-body" style="background-color: #F2F2F280">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-3">
                        <img src="{{ asset('img/no-pict.png') }}" alt="" width="100%" height="100px"
                            class="rounded">
                    </div>
                    <div class="col-lg-8 col-md-8 col-6">
                        <dl>
                            <dd>{{ $bundling->name }} </dd>
                            {!! $total !!}
                            <input type="hidden" name="old_total_update" id="old_total_update-{{ $bundling->id }}-{{ $model->id }}" value="{{ $old_total }}">
                            <input type="hidden" name="input_price"
                                class="input-price-{{ $bundling->id }}-{{ $model->id }}"
                                value="{{ number_format($bundling->price, '0', ',', '.') }}">
                            <dd style="margin-bottom: 0px">
                                <span class="item_bundling">{!! $li !!}</span>
                            </dd>
                        </dl>
                    </div>
                    <div class="col-lg-6 col-md-6 col-3 px-0">
                        <div class="input-group d-flex align-items-center" style="margin-top: 0.5rem">
                            <span>
                                <button type="button" class="btn btn-xs btn-danger btn-number-{{ $bundling->id }}"
                                    onClick="minusEditBundling({{ $bundling->id }},{{ $model->id }})"
                                    data-type="minus" data-field="quant[2]">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </span>
                            <input type="text" name="quant[2]"
                                class="form-control input-number-{{ $bundling->id }}-{{ $model->id }}"
                                value="{{ $qty_item }}" min="0"
                                style="background-color: #F2F2F280;border: 0px;text-align:center">
                            <span>
                                <button type="button" class="btn btn-xs btn-soraba btn-number-{{ $bundling->id }}"
                                    onClick="plusEditBundling({{ $bundling->id }}, {{ $model->id }})"
                                    data-type="plus" data-field="quant[2]">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-block btn-inventory"
                    onclick="pesanEdit('{{ $bundling->id }}',{{ $model->id }},2)">Ubah ke Pesanan</button>
            </div>
        </div>
    @endif
</form>

<script>
    function plusEditItem(id, id_detail_transaksi) {
        var input = $(".input-number-" + id + "-" + id_detail_transaksi);
        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if (currentVal == input.attr('max')) {
                $(this).attr('disabled', true);
            }
        } else {
            input.val(0);
        }
    }

    function minusEditItem(id, id_detail_transaksi) {
        var input = $(".input-number-" + id + "-" + id_detail_transaksi);
        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if (parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }
        } else {
            input.val(0);
        }
    }

    function plusEditBundling(id, id_detail_transaksi) {
        var input = $(".input-number-" + id + "-" + id_detail_transaksi);
        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            input.val(currentVal + 1).change();
        } else {
            input.val(0);
        }
    }

    function minusEditBundling(id, id_detail_transaksi) {
        var input = $(".input-number-" + id + "-" + id_detail_transaksi);
        var currentVal = parseInt(input.val());

        if (!isNaN(currentVal)) {
            if (currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if (parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }
        } else {
            input.val(0);
        }
    }

    function pesanEdit(id, id_detail_transaksi, type) {
        if (type == 1) {
            var qty = $(".input-number-" + id + "-" + id_detail_transaksi).val();
            var price = $(".input-price-" + id + "-" + id_detail_transaksi).val();
            var item_id = id
        } else if (type == 2) {
            var qty = $(".input-number-" + id + "-" + id_detail_transaksi).val();
            var price = $(".input-price-" + id + "-" + id_detail_transaksi).val();
            var item_id = id;
        } else if (type == 3) {
            var qty = $(".input-number-" + id + "-" + id_detail_transaksi).val();
            var price = $(".input-price-" + id + "-" + id_detail_transaksi).val();
            var item_id = id;
        } else if (type == 4) {
            var qty = $(".input-number-" + id + "-" + id_detail_transaksi).val();
            var price = $(".input-price-" + id + "-" + id_detail_transaksi).val();
            var item_id = id;
        }

        var _old_total_update = $('#old_total_update-' + id + '-' + id_detail_transaksi).val();
        var _total_qty = $('#total_qty').val();
        var _total_free_gift = $('#free-gift').val();
        console.log('type: ' + type);
        console.log('item : ' + id);
        console.log('id_detail_transaksi : ' + id_detail_transaksi);
        console.log('qty : ' + qty);
        console.log('old total update : ' + _old_total_update);
        $.ajax({
            url: "{{ route('pos.update-cart') }}",
            method: 'post',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'qty': qty,
                'transaction_detail_id': id_detail_transaksi,
                'tipe': type,
                'item_name': item_id,
                'item_id': item_id,
                'old_total_update': _old_total_update
            },
            success: function(response) {
                if (response.success) {
                    $('#__item_name').val('')
                    $('#__qty_item_1').val('')
                    $("#__item_ids").val('').trigger('change');
                    $("#cost").val('').trigger('change');
                    $('#__qty').val('')
                    $('#item-detail').find('.with-item').remove();

                    // $('#detail').append(response.data.html)
                    $("#detail-" + id_detail_transaksi).html(response.data.html);

                    var _sub = $('#_subtotal').val();
                    var _cost = $('#_cost').val();
                    if (parseInt(price) > 0) {
                        var total_qty = parseInt(_total_qty) + parseInt(qty);
                        var free_gift = Math.floor(total_qty / 2);
                        $('#total_qty').val(total_qty.toString());
                        $('#free-gift').text(free_gift.toString() + ' Free Gift');
                    }
                    var sub = parseInt(_sub) - parseInt(_old_total_update) + parseInt(response.data.item
                        .harga);
                    var cost = parseInt(_cost) + parseInt((response.data.item.cost));
                    console.log(parseInt(_sub));
                    console.log(parseInt(_cost));
                    console.log(parseInt(response.data.item.cost));
                    console.log(parseInt(response.data.item.harga));
                    console.log('last total : ' + parseInt(response.data.item.last_total));
                    $('#_subtotal').val(sub.toString());
                    $('#sub').html(formatRupiah(sub.toString()));
                    $('#tot').html(formatRupiah(sub.toString()));
                    $('#_total').val(sub.toString());
                    $('#_cost').val(cost.toString());
                    var pcs = $('.item-detail').length
                    $('#pcs').html(pcs);
                    Swal.fire({
                        icon: 'success',
                        text: 'Item berhasil diubah!',
                        timer: 2000,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                    });
                    $("#modal").modal("hide");
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
                if (res.message != '') {}
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
</script>

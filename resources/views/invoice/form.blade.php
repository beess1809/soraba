@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        @if ($model->exists)
                            <i class="nav-icon fas fa-eidt"></i> Edit Invoice
                        @else
                            <i class="nav-icon fas fa-plus"></i> Create Invoice
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-boxes"></i> Invoice</li>
                        @if ($model->exists)
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-edit"></i> Edit invoice</li>
                        @else
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-plus"></i> Add invoice</li>
                        @endif
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
                    <form class="form-horizontal"
                        action="{{ $model->exists ? route('invoice.update', base64_encode($model->id)) : route('invoice.store') }}"
                        method="POST">
                        {{ csrf_field() }}
                        @if ($model->exists)
                            <input type="hidden" name="_method" value="PUT">
                        @endif
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="customer_name" class="col-sm-12 col-form-label">Customer Name <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="customer_name" id="customer_name" class="form-control"
                                        placeholder="Customer Name"
                                        value="{{ $model->exists ? $model->customer_name : old('customer_name') }}">
                                    @error('customer_name')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="no_invoice" class="col-sm-12 col-form-label">No Invoice <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control"
                                        placeholder="INV/0001/I/NATHA/HF-PIK/2023"
                                        value="{{ $model->exists ? $model->invoice_no : $invoice_no }}" readonly>
                                    @error('invoice_no')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="customer_phone" class="col-sm-12 col-form-label">Customer Phone <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="customer_phone" id="customer_phone" class="form-control"
                                        placeholder="Customer Phone"
                                        value="{{ $model->exists ? $model->customer_phone : old('customer_phone') }}">
                                    @error('customer_phone')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="no_invoice" class="col-sm-12 col-form-label">Date <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="date" name="date" id="date" class="form-control" placeholder=""
                                        value="{{ $model->exists ? $model->date : date('Y-m-d') }}">
                                    @error('date')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="customer_address" class="col-sm-12 col-form-label">Customer Address <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="customer_address" id="customer_address" class="form-control"
                                        placeholder="Customer Address"
                                        value="{{ $model->exists ? $model->customer_address : old('customer_address') }}">
                                    @error('customer_address')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="payment_type" class="col-sm-12 col-form-label">Payment Method <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="select2 form-control" name="payment_type" id="payment_type">
                                        @if ($model->exists)
                                            <option value="{{ $model->payment_type_id }}">{{ $model->paymentType->name }}
                                            </option>
                                        @else
                                            <option value="">Select Payment Method</option>
                                        @endif
                                        @foreach (App\Models\Master\PaymentType::all() as $paymentType)
                                            <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_type')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="container">
                            <div class="form-group">
                                <label for="item" class="col-sm-12 col-form-label">Type </label>
                                <div class="col-sm-12">
                                    <select class="select2 form-control" id="type-item" onchange="typeItem()">
                                        <option value="">Select Type Item</option>
                                        <option value="0">Reguler</option>
                                        <option value="1">Dispensing Obat</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div id="reguler" style="display: none;">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="item" class="col-sm-12 col-form-label">Item <span
                                                class="text-red">*</span></label>
                                        <div class="col-sm-12">
                                            <select class=" form-control select-item item-regular" name="__item_id[]"
                                                onchange="getItem()" id="__item_id">
                                                <option value="">Select Item</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="item" class="col-sm-12 col-form-label">Quantity <span
                                            class="text-red">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="number" name="__qty_item[]" id="__qty_item"
                                            class="form-control qty-regular" placeholder="">
                                        @error('qty')
                                            <small class="text-red">
                                                <strong>{{ $message }}</strong>
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="item" class="col-sm-12 col-form-label">Discount (%) <span
                                            class="text-red">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="number" name="__discount" id="__discount" class="form-control"
                                            placeholder="" value="0" readonly>
                                        @error('__discount')
                                            <small class="text-red">
                                                <strong>{{ $message }}</strong>
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary float-right add-item">Add Item</button>
                                </div>
                            </div>
                            <div id="formula" style="display: none;">
                                <div class="form-group">
                                    <label for="item" class="col-sm-12 col-form-label">Item Name <span
                                            class="text-red">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="__item_name" id="__item_name" class="form-control"
                                            placeholder="">
                                    </div>
                                </div>
                                <div class="row col-12">
                                    <div class="form-group col-6">
                                        <label for="item" class="col-sm-12 col-form-label">Item <span
                                                class="text-red">*</span></label>
                                        <div class="col-sm-12">
                                            <select class=" form-control select-item item-formula" name="__item_id[]"
                                                id="__item_ids">
                                                <option value="">Select Item To Blend</option>
                                            </select>
                                            @error('item')
                                                <small class="text-red">
                                                    <strong>{{ $message }}</strong>
                                                </small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group col-5">
                                        <label for="item" class="col-sm-12 col-form-label">Item Quantity <span
                                                class="text-red">*</span></label>
                                        <div class="col-sm-12">
                                            <input type="number" name="__qty_item[]" id="__qty_item_1"
                                                class="form-control qty-formula" placeholder="">
                                            @error('qty')
                                                <small class="text-red">
                                                    <strong>{{ $message }}</strong>
                                                </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group  col-1">
                                        <label for="item" class="col-sm-12 col-form-label"> <span
                                                class="text-white">*</span></label>
                                        <button type="button" class="btn btn-secondary" id="add-obat"><i
                                                class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div id="item-detail">

                                </div>
                                <div class="form-group col-12">
                                    <label for="item" class="col-sm-12 col-form-label">Quantity <span
                                            class="text-red">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="number" name="__qty" id="__qty" class="form-control"
                                            placeholder="">
                                        @error('qty')
                                            <small class="text-red">
                                                <strong>{{ $message }}</strong>
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="item" class="col-sm-12 col-form-label">Discount (%) <span
                                            class="text-red">*</span></label>
                                    <div class="col-sm-12">
                                        <input type="number" name="__discount_formula" id="__discount_formula"
                                            class="form-control" placeholder="" value="0">
                                        @error('customer_address')
                                            <small class="text-red">
                                                <strong>{{ $message }}</strong>
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary float-right add-item">Add Item</button>
                                </div>
                            </div>
                            <br>
                            <br>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_detail">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="border:0;"></td>
                                        <td>Sub Total</td>
                                        <td colspan="1"><input type="text" id="subTotal" name="subTotal"
                                                value="0" style="text-align:right;width:100%;" readonly></td>
                                    </tr>
                                    {{-- <tr>
                                        <td style="border:0;" colspan="3"></td>
                                        <td>Pajak</td>
                                        <td colspan="1"><input type="text" id="pajak" name="pajak"
                                                style="text-align:right;width:100%;" value="0"></td>
                                    </tr> --}}
                                    <tr>
                                        <td style="border:0;" colspan="3"></td>
                                        <td>Biaya Pengiriman</td>
                                        <td colspan="1"><input type="text" id="biayaPengiriman"
                                                name="biayaPengiriman" value="0" onkeyup="biaya()"
                                                style="text-align:right;width:100%;"></td>
                                    </tr>
                                    <tr>
                                        <td style="border:0;" colspan="3"></td>
                                        <td>Discount</td>
                                        <td colspan="1"><input type="text" id="discountTotal" name="discountTotal"
                                                value="0" onkeyup="discount()" style="text-align:right;width:100%;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border:0;" colspan="3"></td>
                                        <td>TOTAL</td>
                                        <td colspan="1"><input type="text" id="total" name="total"
                                                value="0" style="text-align:right;width:100%;" readonly></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="float-right">
                            <button type="submit" class="btn bg-inventory">Submit</button>
                        </div>
                    </form>
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

        function typeItem() {
            let value = $('#type-item').val()
            if (value == 0) {
                $('#reguler').show()
                $('#formula').hide()
            } else if (value == 1) {
                $('#reguler').hide()
                $('#formula').show()
            } else {
                $('#reguler').hide()
                $('#formula').hide()
            }
        }

        function getItem() {
            let itemId = $("#__item_id").val();
            $.get("{{ route('items.index') }}" + '/' + itemId, function(data) {
                $('#__discount').val(data.discount);
                // console.log(data);
            });
        }

        function selectItem(params) {
            $('.select-item').select2({
                ajax: {
                    url: '{{ route('items.data') }}',
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        }

        $(".add-item").click(function() {
            var qty = $('#__qty').val();
            var discount = $('#__discount').val();
            var discount_formula = $('#__discount_formula').val();
            var item_name = $('#__item_name').val();

            let type = $('#type-item').val()

            if (type == 0) {
                var item_id = $('.item-regular').map((_, i) => i.value).get()
                var qty_item = $('.qty-regular').map((_, q) => q.value).get()
                var unitprice = 0;
            } else if (type == 1) {
                var item_id = $('.item-formula').map((_, i) => i.value).get()
                var qty_item = $('.qty-formula').map((_, q) => q.value).get()
                var unitprice = $('#__price').val();
            }

            $('#__item_name').val('')
            $('#__qty').val('')
            $('.qty-regular').val('')
            $('#__discount_formula').val('0')
            $('#__discount').val('0')
            $('#__price').val('');
            $('#item-detail').find('.with-item').remove();

            $.ajax({
                url: "{{ route('invoice.addItem') }}",
                type: "POST",
                data: {
                    item_name: item_name,
                    qty: qty,
                    discount: discount,
                    discount_formula: discount_formula,
                    item_id: item_id,
                    qty_item: qty_item,
                    price: unitprice,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(data) {

                    if (data.success) {
                        $("#tbody_detail").append(data.row)

                        let _subTotal = $('#subTotal').val().replace(/\./g, "")
                        let subTotal = parseFloat(_subTotal) + data.subTotal;
                        $('#subTotal').val(formatRupiah(subTotal.toString()))
                        let _ppn = $('#pajak').val().replace(/\./g, "")
                        let pajak = subTotal * (11 / 100)
                        $('#pajak').val(formatRupiah(Math.round(pajak).toString()))

                        // let pajak = subTotal * (11 / 100)
                        // $('#pajak').val(formatRupiah(pajak.toString()))

                        let _total = $('#total').val().replace(/\./g, "")
                        let total = parseFloat(subTotal) + parseFloat(_total) //+ parseFloat(pajak)
                        $('#total').val(formatRupiah(total.toString()))
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops!!! Not Enough Stock',
                            text: data.message,
                            confirmButtonColor: '#3085d6',
                        });
                    }

                }
            });
        })
        $("#add-obat").click(function() {
            const d = new Date();
            let norow = d.getTime();
            var row = '<div id="' + norow + '" class="with-item">' +
                '<div class="row col-12">' +
                '<div class="form-group col-6">' +
                '<label for="item" class="col-sm-12 col-form-label">Item <span class="text-red">*</span></label>' +
                '<div class="col-sm-12">' +
                '<select class=" form-control select-item item-formula" name="__item_id[]" id="__item_ids_' +
                norow + '">' +
                '<option value="">Select Item To Blend</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<div class="form-group col-5">' +
                '<label for="item" class="col-sm-12 col-form-label">Item Quantity <span class="text-red">*</span></label>' +
                '<div class="col-sm-12">' +
                '<input type="number" name="__qty_item" class="form-control qty-formula" placeholder="">' +
                '</div>' +
                '</div>' +
                '<div class="form-group  col-1">' +
                '<label for="item" class="col-sm-12 col-form-label"> <span class="text-white">*</span></label>' +
                '<button type="button" class="btn btn-danger" id="delete-obat"  onclick="deleteObat(this, ' +
                norow + ')"><i class="fas fa-trash"></i></button>' +
                '</div>' +
                '</div>' +
                '</div>';
            $("#item-detail").append(row)
            selectItem()

        })

        function deleteRow(btn, norow, sub) {
            var row = btn.parentNode.parentNode;

            let _subTotal = $('#subTotal').val().replace(/\./g, "")
            let subTotal = parseFloat(_subTotal) - sub
            $('#subTotal').val(formatRupiah(subTotal.toString()))


            // let pajak = subTotal * (11 / 100)
            // $('#pajak').val(formatRupiah(pajak.toString()))

            let _biaya = $("#biayaPengiriman").val().replace(/\./g, "")
            let _discountTotal = $("#discountTotal").val().replace(/\./g, "")

            let total = parseFloat(subTotal) + parseFloat(_biaya) - parseFloat(_discountTotal) //+ parseFloat(pajak)
            $('#total').val(formatRupiah(total.toString()))

            row.parentNode.removeChild(row);
        }

        function deleteObat(btn, norow) {
            var row = btn.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        function biaya() {
            let _subTotal = $('#subTotal').val().replace(/\./g, "")
            // let _ppn = $('#pajak').val().replace(/\./g, "")
            let _total = $('#total').val().replace(/\./g, "")
            let _biaya = $("#biayaPengiriman").val().replace(/\./g, "")
            let _discountTotal = $("#discountTotal").val().replace(/\./g, "")

            let total = parseFloat(_subTotal) + parseFloat(_biaya) - parseFloat(_discountTotal) //+ parseFloat(_ppn)
            $('#total').val(formatRupiah(total.toString()))
            $("#biayaPengiriman").val(formatRupiah(_biaya.toString()))
        }

        function discount() {
            let _subTotal = $('#subTotal').val().replace(/\./g, "")
            // let _ppn = $('#pajak').val().replace(/\./g, "")
            let _total = $('#total').val().replace(/\./g, "")
            let _biaya = $("#biayaPengiriman").val().replace(/\./g, "")
            let _discountTotal = $("#discountTotal").val().replace(/\./g, "")

            let total = parseFloat(_subTotal) + parseFloat(_biaya) - parseFloat(_discountTotal) //+ parseFloat(_ppn)
            $('#total').val(formatRupiah(total.toString()))
            $("#discountTotal").val(formatRupiah(_discountTotal.toString()))
        }
    </script>
@endpush

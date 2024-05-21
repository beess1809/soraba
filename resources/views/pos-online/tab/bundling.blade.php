    <div class="form-group">
        <label for="bundling" class="col-sm-12 col-form-label">Nama Bundle <span class="text-red">*</span></label>
        <div class="col-sm-6">
            <select name="bundling_id" id="bundling_id" class="form-control form-control-sm select2">
                <option value="">Select All</option>
                @foreach (App\Models\Master\Bundling::all() as $bund)
                    <option value="{{ $bund->id }}">{{ $bund->name }}</option>
                @endforeach
            </select>
            @error('bundling_id')
                <small class="text-red">
                    <strong>{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div>

    <div class="row col-12">
        {{-- <div class="form-group col-6">
            <label for="item" class="col-sm-12 col-form-label">Pilih Item <span class="text-red">*</span></label>
            <div class="col-sm-12">
                <select class=" form-control select-item item-formula" name="__item_id[]" id="__item_ids">
                    <option value="">Pilih Item Untuk di bundle</option>
                </select>
                @error('item')
                    <small class="text-red">
                        <strong>{{ $message }}</strong>
                    </small>
                @enderror
            </div>
        </div> --}}

        {{-- <div class="form-group col-2">
            <label for="item" class="col-sm-12 col-form-label">Kuantitas <span class="text-red">*</span></label>
            <div class="col-sm-12">
                <input type="number" name="__qty_item[]" id="__qty_item_1" class="form-control qty-formula"
                    placeholder="">
                @error('qty')
                    <small class="text-red">
                        <strong>{{ $message }}</strong>
                    </small>
                @enderror
            </div>
        </div> --}}

    </div>
    <div id="item-detail">

    </div>

    <div class="form-group col-12">
        <button type="button" class="btn" id="add-obat" style="display: none">
            <i class="fas fa-plus" style="color: var(--primary)"></i>
            &nbsp;<span style="color: var(--primary)"> Tambah Item Bundling</span>
        </button>
    </div>
    <div class="form-group col-6">
        <label for="item" class="col-sm-12 col-form-label">Jumlah Paket <span class="text-red">*</span></label>
        <div class="col-sm-12">
            <input type="number" name="__qty" id="__qty" class="form-control" placeholder="">
            @error('qty')
                <small class="text-red">
                    <strong>{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div>

    <div class="form-group col-6">
        <label for="item" class="col-sm-12 col-form-label">Harga Bundle <span class="text-red">*</span></label>
        <div class="col-sm-12">
            <input type="text" class="form-control number" name="cost" id="cost" value="{{ old('cost') }}"
                readonly>
            {{-- <select name="cost" id="cost" class="form-control select2">
                <option value="">Pilih Harga</option>
                @foreach (App\Models\Master\Parameter::whereIn('code', ['em1', 'em2'])->get() as $item)
                    <option value="{{ $item->value }}">
                        {{ $item->name . '(Rp. ' . number_format($item->value, 0, ',', '.') . ' )' }}</option>
                @endforeach
            </select> --}}
        </div>
    </div>
    {{-- <div class="form-group">
        <label for="item" class="col-sm-12 col-form-label">Discount (%) <span class="text-red">*</span></label>
        <div class="col-sm-12">
            <input type="number" name="__discount_formula" id="__discount_formula" class="form-control" placeholder=""
                value="0">
            @error('customer_address')
                <small class="text-red">
                    <strong>{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div> --}}
    <div class="col-12">
        <button type="button" class="btn btn-inventory add-item" onclick="pesan(0,2)">Tambah Ke pesanan</button>
    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {
                selectItem();
                selectBundling();
            })

            function selectBundling(params) {
                $('.select-bundling').select2({
                    ajax: {
                        url: '{{ route('bundling.data') }}',
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

            function selectItem(params) {
                $('.select-item').select2({
                    ajax: {
                        url: '{{ route('pos-online.items.data') }}',
                        dataType: 'json',
                        data: function(params) {
                            return {
                                q: $.trim(params.term)
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text
                                    }
                                })
                            };
                        },
                        cache: true
                    },
                    // formatSelection: function(element) {
                    //     return tag.id + " " + tag.name + " : " + tag.type;
                    // }
                });
            }

            $('#bundling_id').change(() => {
                // Swal.showLoading();
                var bundling_id = $('#bundling_id').val();

                $.ajax({
                    url: '{{ route('pos-online.getItemByBundling') }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        bundling_id
                    },
                    success: (data) => {
                        console.log(data);
                        $('#item-detail').html('')

                        $('#item-detail').append(data.html);
                        if (data.html) {
                            $('#add-obat').css('display', 'block')
                            $('#cost').val(data.harga);
                        } else {
                            $('#add-obat').css('display', 'none')
                        }

                        // $.each(data.items, function(index, item) {
                        //     var itemSelect = $('<select>', {
                        //         class: 'form-control select-item item-formula',
                        //         'data-selected': item.selected_value
                        //     });

                        //     $.each(item.options, function(optionIndex, option) {
                        //         itemSelect.append($('<option>', {
                        //             value: option.id,
                        //             text: option.text
                        //         }));
                        //     });

                        //     // $('#item-detail').append(itemSelect);

                        //     var row =
                        //         '<div class="with-item">' +
                        //         '   <div class="row col-12">' +
                        //         '       <div class="form-group col-6">' +
                        //         '           <label for="item" class="col-sm-12 col-form-label">Item <span class="text-red">*</span></label>' +
                        //         '               <div classz="col-sm-12">';
                        //     row.append(itemSelect);
                        //     row +=
                        //         '               </div>' +
                        //         '       </div>' +
                        //         '    </div>' +
                        //         '</div>';

                        //     $("#item-detail").append(row);
                        $('.select-item').select2();

                        //     //set the selected value
                        // $('.select-item').val(item.selected_value).trigger('change');
                        // })





                        // for (let i = 0; i < data.length; i++) {
                        //     var row = '<div class="with-item">' +
                        //         '<div class="row col-12">' +
                        //         '<div class="form-group col-6">' +
                        //         '<label for="item" class="col-sm-12 col-form-label">Item <span class="text-red">*</span></label>' +
                        //         '<div class="col-sm-12">' +
                        //         '<select class=" form-control select-item item-formula" name="__item_id[]" id="__item_ids_' +
                        //         bundling_id + i + '">' +
                        //         '<option value="">Pilih Item Untuk di bundle</option>' +
                        //         '</select>' +
                        //         '</div>' +
                        //         '</div>' +
                        //         '<div class="form-group col-2">' +
                        //         '<label for="item" class="col-sm-12 col-form-label">Item Quantity <span class="text-red">*</span></label>' +
                        //         '<div class="col-sm-12">' +
                        //         '<input type="number" name="__qty_item" class="form-control qty-formula" placeholder="" value="' +
                        //         data[i].qty + '">' +
                        //         '</div>' +
                        //         '</div>' +
                        //         '</div>' +
                        //         '</div>';
                        //     $("#item-detail").append(row);
                        //     selectItem();
                        //     console.log(bundling_id + i)

                        //     console.log(data[i].item);

                        //     if ($('#__item_ids_' + bundling_id + i).find("option[value='" + data[i].item +
                        //             "']") == data[i].item) {
                        //         $('#__item_ids_' + bundling_id + i).val(data[i].item).trigger(
                        //             'change.select2');
                        //     }
                        // }

                    },
                    error: (xhr) => {
                        console.log(xhr);
                        // Swal.close();
                    }
                });
            });

            $("#add-obat").click(function() {
                const d = new Date();
                let norow = d.getTime();
                var row = '<div id="' + norow + '" class="with-item">' +
                    '<div class="row">' +
                    '<div class="form-group col-lg-6 col-md-5 col-8">' +
                    '<label for="item" class="col-sm-12 col-form-label">Item <span class="text-red">*</span></label>' +
                    '<div class="col-sm-12">' +
                    '<select class=" form-control select-item item-formula" name="__item_id[]" id="__item_ids_' +
                    norow + '">' +
                    '<option value="">Pilih Item Untuk di bundle</option>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group col-lg-3 col-md-3 col-4">' +
                    '<label for="item" class="col-sm-12 col-form-label">Item Quantity <span class="text-red">*</span></label>' +
                    '<div class="col-sm-12">' +
                    '<input type="number" name="__qty_item" class="form-control qty-formula" placeholder="">' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group col-lg-3 col-md-4 col-12"><label class="col-sm-12 col-form-label label-delete">&nbsp</label>' +

                    '<button type="button" class="btn " id="delete-obat"  onclick="deleteObat(this, ' +
                    norow + ')"><i class="fas fa-minus" style="color: red"></i> ' +
                    '&nbsp;<span style="color: red"> Hapus Item Racikan</span> </i></button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                $("#item-detail").append(row)
                selectItem()

            })

            function deleteObat(btn, norow) {
                var row = btn.parentNode.parentNode;
                row.parentNode.removeChild(row);
            }
        </script>
    @endpush

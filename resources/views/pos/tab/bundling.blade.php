<div class="row col-12" id="card-item2" style="overflow-y: scroll;height: 86.8vh">
    @foreach ($bundlings as $item)
        {!! $item !!}
    @endforeach
</div>

@push('scripts')
    <script>
        function plusBundling(id) {
            var input = $(".input-number-" + id);
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                input.val(currentVal + 1).change();
            } else {
                input.val(0);
            }
        }

        function minusBundling(id) {
            var input = $(".input-number-" + id);
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
                    url: '{{ route('items.data') }}',
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
                url: '{{ route('bundling.getItemByBundling') }}',
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
                '<div class="row col-12">' +
                '<div class="form-group col-6">' +
                '<label for="item" class="col-sm-12 col-form-label">Item <span class="text-red">*</span></label>' +
                '<div class="col-sm-12">' +
                '<select class=" form-control select-item item-formula" name="__item_id[]" id="__item_ids_' +
                norow + '">' +
                '<option value="">Pilih Item Untuk di bundle</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<div class="form-group col-2">' +
                '<label for="item" class="col-sm-12 col-form-label">Item Quantity <span class="text-red">*</span></label>' +
                '<div class="col-sm-12">' +
                '<input type="number" name="__qty_item" class="form-control qty-formula" placeholder="">' +
                '</div>' +
                '</div>' +
                '<div class="form-group  col-4">' +
                '<label for="item" class="col-sm-12 col-form-label"> <span class="text-white">*</span></label>' +
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

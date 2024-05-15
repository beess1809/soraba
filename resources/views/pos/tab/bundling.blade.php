    <div class="form-group">
        <label for="item" class="col-sm-12 col-form-label">Nama Bundle <span class="text-red">*</span></label>
        <div class="col-sm-6">
            <input type="text" name="__item_name" id="__item_name" class="form-control" placeholder="">
        </div>
    </div>
    <div class="row col-12">
        <div class="form-group col-6">
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
        </div>

        <div class="form-group col-2">
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
        </div>
        <div class="form-group  col-4">
            <label for="item" class="col-sm-12 col-form-label"> <span class="text-white">*</span></label>
            <button type="button" class="btn" id="add-obat">
                <i class="fas fa-plus" style="color: var(--primary)"></i>
                &nbsp;<span style="color: var(--primary)"> Tambah Item Bundling</span>
            </button>
        </div>
    </div>
    <div id="item-detail">

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
            <input type="text" class="form-control number" name="cost" id="cost" value="{{ old('cost') }}">
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
                selectItem()
            })

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

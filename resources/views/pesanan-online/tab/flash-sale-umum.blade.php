@foreach ($flash_sale as $fs)
    @if (date('H:i:s') > $fs->time_start && date('H:i:s') < $fs->time_end)
        <div class="row col-12">
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <select name="category" id="category" class="form-control form-control-sm select2">
                        <option value="">Select All</option>
                        @foreach (App\Models\Master\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="input-group input-group-sm">
                    <input type="text" id="cari" class="form-control">
                    <span class="input-group-append">
                        <button type="button" id="btn-cari" class="btn btn-inventory btn-flat"><i
                                class="fas fa-search"></i>Cari</button>
                    </span>
                </div>
            </div>
        </div>

        <div class="row col-12" id="card-item3" style="overflow-y: scroll;height: 86.8vh">
            @foreach ($flash_sale_items as $item)
                {!! $item !!}
            @endforeach
        </div>
    @else
        {{ 'tidak ada flash sale' }}
    @endif
@endforeach

@push('scripts')
    <script>
        function plusFlashSaleItem(id) {
            var input = $(".input-number-" + id);
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }
            } else {
                input.val(0);
            }
        }

        function minusFlashSaleItem(id) {

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
    </script>
@endpush

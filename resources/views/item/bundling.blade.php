    <div id="{{ $indexBundling }}">
        <div class="row">
            <div class="form-group col-md-4 my-md-1">
                <div class="col-sm-12">
                    <select class="form-control form-control-sm select2bs42" name="item[]" id="item{{ $indexBundling }}">
                        <option value="">-Pilih Item-</option>
                        @foreach ($item as $i)
                            <option value="{{ $i->id }}">{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group col-md-2 my-md-1">
                <div class="col-sm-12">
                    <input type="text" name="qty[]" id="qty{{ $indexBundling }}"
                        class="form-control form-control-sm number" placeholder="Quantity">
                </div>
            </div>

            <div class="form-group col-md-2 my-md-1">
                <span class="input-group-append">
                    <button type="button" id="removeBundling{{ $indexBundling }}"
                        class="btn btn-sm btn-danger btn-flat ml-2"> <i class="fa fa-trash"></i> Hapus</button>
                </span>
            </div>
        </div>

    </div>

    <script>
        $('#removeBundling{{ $indexBundling }}').click(() => {
            Swal.showLoading();
            $('#{{ $indexBundling }}').remove();
            Swal.close();
        });
    </script>

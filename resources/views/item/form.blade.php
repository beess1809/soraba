@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        @if ($model->exists)
                            <i class="nav-icon fas fa-eidt"></i> Edit Item
                        @else
                            <i class="nav-icon fas fa-plus"></i> Add Item
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-boxes"></i> Item</li>
                        @if ($model->exists)
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-edit"></i> Edit Item</li>
                        @else
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-plus"></i> Add Item</li>
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
                        action="{{ $model->exists ? route('items.update', base64_encode($model->id)) : route('items.store') }}"
                        method="POST">
                        {{ csrf_field() }}
                        @if ($model->exists)
                            <input type="hidden" name="_method" value="PUT">
                        @endif
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-sm-12 col-form-label">Name <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Nama" value="{{ $model->exists ? $model->name : old('name') }}">
                                    @error('name')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="code" class="col-sm-12 col-form-label">Composition</label>
                                <div class="col-sm-12">
                                    <Textarea name="composition" id="composition" class="form-control">{{ $model->exists ? $model->composition : old('composition') }}</Textarea>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="qty" class="col-sm-12 col-form-label">Quantity <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="qty" id="qty"
                                        class="form-control form-control-sm number" placeholder="Quantity"
                                        value="{{ $model->exists ? format_rupiah($model->qty) : old('qty') }}">
                                    @error('qty')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>



                            <div class="form-group col-md-6">
                                <label for="uom" class="col-sm-12 col-form-label">Uom <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-11">
                                    <div class="input-group">
                                        <select name="uom" id="uom" class="form-control">
                                            @if ($model->exists)
                                                <option value="{{ $model->uom_id }}">{{ $model->uom->name }}</option>
                                            @endif
                                        </select>
                                        <span class="input-group-append">
                                            <button type="button" class="btn  bg-inventory btn-sm btn-flat ml-1"
                                                data-toggle="modal" data-target="#modal-uom"><i class="fas fa-plus"></i> Add
                                                Uom</button>
                                        </span>
                                    </div>
                                    @error('uom')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="category_id" class="col-sm-12 col-form-label">Category <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="select2 form-control" name="category_id" id="category_id">
                                        @if ($model->exists)
                                            <option value="{{ $model->category_id }}">{{ $model->category->name }}
                                            </option>
                                        @else
                                            <option value="">Select Category</option>
                                        @endif
                                        @foreach (App\Models\Master\Category::all() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="warehouse_id" class="col-sm-12 col-form-label">Warehouse <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <select class="select2 form-control" name="warehouse_id" id="warehouse_id">
                                        @if ($model->exists)
                                            <option value="{{ $model->warehouse_id }}">{{ $model->warehouse->name }}
                                            </option>
                                        @else
                                            <option value="">Pilih Area</option>
                                        @endif
                                        @foreach (App\Models\Master\Warehouse::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="vendor_id" class="col-sm-12 col-form-label">Vendor</label>
                                <div class="col-sm-12">
                                    <select class="select2 form-control" name="vendor_id" id="vendor_id">
                                        @if ($model->exists)
                                            <option value="{{ $model->vendor_id }}">{{ $model->vendor->name }}</option>
                                        @else
                                            <option value="">Select Vendor</option>
                                        @endif
                                        @foreach (App\Models\Master\Vendor::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sale_price" class="col-sm-12 col-form-label">Sell Price</label>
                                <div class="col-sm-12">
                                    <input type="text" name="sale_price" id="sale_price"
                                        class="form-control form-control-sm number" placeholder="Sell Price"
                                        value="{{ $model->exists ? format_rupiah($model->sale_price) : old('sale_price') }}">
                                    @error('sale_price')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="discount" class="col-sm-12 col-form-label">Discount (Rp)</label>
                                <div class="col-sm-12">
                                    <input type="text" name="discount" id="discount"
                                        class="form-control form-control-sm number" placeholder="Discount"
                                        value="{{ $model->exists ? format_rupiah($model->discount) : old('discount') }}">
                                    @error('discount')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="expired_date" class="col-sm-12 col-form-label">Discount Expired Date</label>
                                <div class="col-sm-12">
                                    <input type="date" name="expired_date" id="expired_date"
                                        class="form-control form-control-sm" placeholder="Expired Date"
                                        value="{{ $model->exists ? $model->expired_discount : old('expired_date') }}">
                                    @error('expired_date')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            <button type="submit" id="save" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-uom">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Uom</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body-uom">
                    <form class="form-horizontal" action="{{ route('master.uom.store') }}" method="POST">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-sm-12 col-form-label">Name <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="name" id="nameUom" class="form-control"
                                        placeholder="Nama"
                                        @if ($model->exists) value="{{ $model->uom->name }}" @endif>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="code" class="col-sm-12 col-form-label">Code</label>
                                <div class="col-sm-12">
                                    <input type="text" name="code" id="codeUom" class="form-control"
                                        placeholder="pcs "
                                        @if ($model->exists) value="{{ $model->uom->code }}" @endif>
                                    <small>maximum 6 character</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="save-uom" class="btn btn-primary">Save</button>
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
        $('#save-uom').click(function(event) {
            event.preventDefault();

            var form = $('#modal-body-uom form'),
                url = form.attr('action')

            form.find('.help-block').remove();
            form.find('.form-group').removeClass('has-error');

            $.ajax({
                url: '{{ route('master.uom.store') }}',
                method: 'post',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'name': $('#nameUom').val(),
                    'code': $('#codeUom').val()
                },
                success: function(response) {
                    form.trigger('reset');
                    $('#modal-uom').modal('hide');
                    // $('#datatable').DataTable().ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data has been saved!',
                        timer: 2000,
                        confirmButtonColor: '#3085d6',
                    });
                    selectUom()

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
        });

        $(document).ready(function() {
            selectUom()
        })

        function selectUom(params) {
            $('#uom').select2({
                ajax: {
                    url: '{{ route('master.uom.data') }}',
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
    </script>
@endpush

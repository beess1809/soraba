@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        @if ($model->exists)
                            <i class="nav-icon fas fa-eidt"></i> Edit Bundling
                        @else
                            <i class="nav-icon fas fa-plus"></i> Add Bundling
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-boxes"></i> Bundling</li>
                        @if ($model->exists)
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-edit"></i> Edit Bundling</li>
                        @else
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-plus"></i> Add Bundling</li>
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
                        action="{{ $model->exists ? route('items.update', base64_encode($model->id)) : route('items.storeBundling') }}"
                        method="POST">
                        {{ csrf_field() }}
                        @if ($model->exists)
                            <input type="hidden" name="_method" value="PUT">
                        @endif
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-sm-12 col-form-label">Bundling Name <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="name" id="name" class="form-control form-control-sm"
                                        placeholder="Nama Bundling"
                                        value="{{ $model->exists ? $model->name : old('name') }}">
                                    @error('name')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="price" class="col-sm-12 col-form-label">Sell Price</label>
                                <div class="col-sm-12">
                                    <input type="text" name="price" id="price"
                                        class="form-control form-control-sm number" placeholder="Harga"
                                        value="{{ $model->exists ? format_rupiah($model->price) : old('price') }}">
                                    @error('price')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4 my-md-1">
                                <label for="item" class="col-sm-12 col-form-label">Item <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <select name="item[]" id="item" class="form-control select2">
                                        <option value="">-Pilih Item-</option>
                                        @foreach ($item as $i)
                                            <option value="{{ $i->id }}">{{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-2 my-md-1">
                                <label for="qty" class="col-sm-12 col-form-label">Quantity <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="qty[]" id="qty"
                                        class="form-control form-control-sm number" placeholder="Quantity" value="">
                                    @error('qty')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-md-2 my-md-1">
                                <label for="qty" class="col-sm-12 col-form-label">&nbsp;</label>
                                <button type="button" id="tambahItem" class="btn bg-inventory btn-sm btn-flat ml-2">
                                    <i class="fas fa-plus"></i> Add Item
                                </button>
                            </div>
                        </div>

                        <div id="list_item">

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
        $('#tambahItem').click(() => {
            Swal.showLoading();
            $.ajax({
                url: '{{ route('items.addBundling') }}',
                method: 'GET',
                dataType: 'html',
                success: (data) => {
                    $('#list_item').append(data);
                    Swal.close();
                },
                error: (xhr) => {
                    console.log(xhr);
                    Swal.close();
                }
            });
        });

        {{--
            $("#tambahItem").click(function(e) {
            e.preventDefault();
            const d = new Date();
            let norow = d.getTime();
            var list_info =
                '<div class="row mt-1" id="' + norow + '">' +
                '  <div class="col-sm-6">' +
                '    <div class="input-group">' +
                '      <select name="item[]" id="item"' + norow + ' class="form-control select2">' +
                '        <option value=""></option>' +
                @foreach ($item as $i)
                    '<option> {{ $i->name }}  </option>' +
                @endforeach
            '      </select>' +
            '    </div>' +
            '  </div>' +
            '</div>';


            '<input type="text" class="form-control col-sm-6" name="item[]" > ' +
            '               &nbsp;<button class="btn btn-xs btn-danger" onclick="deleteItem(this, ' +
            norow + ')">Hapus</button>' +
                '            </div>';
            $("#list_item").append(list_info);
        })
        --}}


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

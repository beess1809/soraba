@extends('layouts.app')

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    @if ($model->exists)
                    <i class="nav-icon fas fa-eidt"></i> Edit Category
                    @else
                    <i class="nav-icon fas fa-plus"></i> Add Category
                    @endif
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><i class="nav-icon fas fa-boxes"></i> Category</li>
                    @if ($model->exists)
                    <li class="breadcrumb-item active"><i class="nav-icon fas fa-edit"></i> Edit Category</li>
                    @else
                    <li class="breadcrumb-item active"><i class="nav-icon fas fa-plus"></i> Add Category</li>
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
                <form class="form-horizontal" action="{{ $model->exists ? route('master.category.update', base64_encode($model->id)) : route('master.category.store') }}" method="POST">
                    {{ csrf_field() }}
                    @if ($model->exists)
                    <input type="hidden" name="_method" value="PUT">
                    @endif
                    <!-- <div class="row">
                        <div class="form-group col-md-12">
                            <label for="code" class="col-sm-12 col-form-label">Code <span class="text-red">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" name="code" id="code" class="form-control" placeholder="Code" value="{{ $model->exists ? $model->code : old('code') }}">
                                @error('code')
                                <small class="text-red">
                                    <strong>{{ $message }}</strong>
                                </small>
                                @enderror
                            </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="code" class="col-sm-12 col-form-label">Name <span class="text-red">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $model->exists ? $model->name : old('name') }}">
                                @error('name')
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

<div class="modal fade" id="modal-category">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body-category">
                <form class="form-horizontal" action="{{ route('master.category.store') }}" method="POST">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="name" class="col-sm-12 col-form-label">Name <span class="text-red">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" name="name" id="nameCategory" class="form-control" placeholder="Nama" @if ($model->exists) value="{{ $model->name }}" @endif>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="code" class="col-sm-12 col-form-label">Code</label>
                            <div class="col-sm-12">
                                <input type="text" name="code" id="codeCategory" class="form-control" placeholder="pcs " @if ($model->exists) value="{{ $model->code }}" @endif>
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
    $('#save-category').click(function(event) {
        event.preventDefault();

        var form = $('#modal-body-category form'),
            url = form.attr('action')

        form.find('.help-block').remove();
        form.find('.form-group').removeClass('has-error');

        $.ajax({
            url: '{{ route("master.uom.store") }}',
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
                        $('<span class="invalid-feedback" role="alert"><strong>' + value + '</strong></span>').insertAfter($('#' + key))
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
                url: '{{ route("master.uom.data") }}',
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
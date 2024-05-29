@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        @if ($model->exists)
                            <i class="nav-icon fas fa-eidt"></i> Edit Flash Sale
                        @else
                            <i class="nav-icon fas fa-plus"></i> Add Flash Sale
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-boxes"></i> Flash Sale</li>
                        @if ($model->exists)
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-edit"></i> Edit Flash Sale</li>
                        @else
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-plus"></i> Add Flash Sale</li>
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
                        action="{{ $model->exists ? route('flash-sale.update', base64_encode($model->id)) : route('flash-sale.store') }}"
                        method="POST">
                        {{ csrf_field() }}
                        @if ($model->exists)
                            <input type="hidden" name="_method" value="PUT">
                        @endif
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="time_start" class="col-sm-12 col-form-label">Waktu Mulai</label>
                                <div class="col-sm-12">
                                    <input type="time" name="time_start" id="time_start"
                                        class="form-control form-control-sm number" placeholder="time_start"
                                        value="{{ $model->exists ? $model->time_start : old('time_start') }}">
                                    @error('time_start')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="time_end" class="col-sm-12 col-form-label">Waktu Selesai</label>
                                <div class="col-sm-12">
                                    <input type="time" name="time_end" id="time_end"
                                        class="form-control form-control-sm number" placeholder="time_end"
                                        value="{{ $model->exists ? $model->time_end : old('time_end') }}">
                                    @error('time_end')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="time_end" class="col-sm-12 col-form-label">Aktif</label>
                                <div class="col-sm-12">
                                    <select name="active" class="form-control select2" id="active">
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                    @error('time_end')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            <button type="submit" id="save" class="btn btn-primary">Simpan</button>
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

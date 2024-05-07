@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        @if ($model->exists)
                            <i class="nav-icon fas fa-eidt"></i> Edit Warehouse
                        @else
                            <i class="nav-icon fas fa-plus"></i> Add Warehouse
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-boxes"></i> Warehouse</li>
                        @if ($model->exists)
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-edit"></i> Edit Warehouse</li>
                        @else
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-plus"></i> Add Warehouse</li>
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
                        action="{{ $model->exists ? route('master.warehouse.update', base64_encode($model->id)) : route('master.warehouse.store') }}"
                        method="POST">
                        {{ csrf_field() }}
                        @if ($model->exists)
                            <input type="hidden" name="_method" value="PUT">
                        @endif
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="code" class="col-sm-12 col-form-label">Code <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="Code" value="{{ $model->exists ? $model->code : old('code') }}">
                                    @error('code')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name" class="col-sm-12 col-form-label">Nama <span
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
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="address" class="col-sm-12 col-form-label">Address <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <textarea name="address" id="address" class="form-control">{{ $model->exists ? $model->address : old('address') }}</textarea>
                                    @error('address')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name" class="col-sm-12 col-form-label">NPWP <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="npwp"
                                        value="{{ $model->exists ? $model->npwp : old('npwp') }}"
                                        data-inputmask="'mask': ['99.999.999.9-999.999']" data-mask>
                                    @error('npwp')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="no_telp" class="col-sm-12 col-form-label">No Telepon <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="no_telp" id="no_telp" class="form-control"
                                        placeholder="021xxxxxx"
                                        value="{{ $model->exists ? $model->no_telp : old('no_telp') }}">
                                    @error('no_telp')
                                        <small class="text-red">
                                            <strong>{{ $message }}</strong>
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email" class="col-sm-12 col-form-label">Email <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Email" value="{{ $model->exists ? $model->email : old('email') }}">
                                    @error('email')
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
@endsection

@push('scripts')
    <script></script>
@endpush

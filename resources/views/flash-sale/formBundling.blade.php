@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        @if ($model->exists)
                            <i class="nav-icon fas fa-eidt"></i> Edit Bundling Flash Sale
                        @else
                            <i class="nav-icon fas fa-plus"></i> Add Bundling Flash Sale
                        @endif
                    </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-boxes"></i> Bundling Flash Sale</li>
                        @if ($model->exists)
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-edit"></i> Edit Bundling Flash Sale
                            </li>
                        @else
                            <li class="breadcrumb-item active"><i class="nav-icon fas fa-plus"></i> Add Bundling Flash Sale
                            </li>
                        @endif
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    @php
        $items = json_decode($model->item_id);
    @endphp
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-body">
                    <form class="form-horizontal"
                        action="{{ $model->exists ? route('flash-sale.updateBundling', base64_encode($model->id)) : route('flash-sale.storeBundling') }}"
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
                                            <option value="{{ $i->id }}"
                                                {{ $model->exists && $items[0]->item == $i->id ? 'selected' : '' }}>
                                                {{ $i->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-md-2 my-md-1">
                                <label for="qty" class="col-sm-12 col-form-label">Quantity <span
                                        class="text-red">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="qty[]" id="qty"
                                        class="form-control form-control-sm number" placeholder="Quantity"
                                        value="{{ $model->exists ? $items[0]->qty : '' }}">
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
                            @if ($model->exists)
                                @for ($indexItem = 1; $indexItem < count($items); $indexItem++)
                                    <div id="{{ $indexItem }}">
                                        <div class="row">
                                            <div class="form-group col-md-4 my-md-1">
                                                <div class="col-sm-12">
                                                    <select name="item[]" id="item{{ $indexItem }}"
                                                        class="form-control selectbs42">
                                                        <option value="">-Pilih Item-</option>
                                                        @foreach ($item as $i)
                                                            <option value="{{ $i->id }}"
                                                                {{ $items[$indexItem]->item == $i->id ? 'selected' : '' }}>
                                                                {{ $i->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2 my-md-1">
                                                <div class="col-sm-12">
                                                    <input type="text" name="qty[]" id="qty{{ $indexItem }}"
                                                        class="form-control  number" placeholder="Quantity"
                                                        value="{{ $items[$indexItem]->qty }}">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2 my-md-1">
                                                <span class="input-group-append">
                                                    <button type="button" id="removeBundling{{ $indexItem }}"
                                                        class="btn btn-sm btn-danger btn-flat ml-2"> <i
                                                            class="fa fa-trash"></i> Hapus</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        // $(document).ready(function() {
                                        $('#removeBundling{{ $indexItem }}').click(() => {
                                            Swal.showLoading();
                                            $('#{{ $indexItem }}').remove();
                                            Swal.close();
                                        });

                                        // });
                                    </script>
                                @endfor
                            @endif

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
    <script>
        $('#tambahItem').click(() => {
            Swal.showLoading();
            $.ajax({
                url: '{{ route('bundling.addBundling') }}',
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

        $('.selectbs42').select2();
    </script>
@endpush

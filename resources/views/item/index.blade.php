@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="nav-icon fas fa-boxes"></i> Items</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-boxes"></i> Item</li>
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
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="item-tab" data-toggle="pill" href="#item" role="tab"
                                aria-controls="item" aria-selected="false"><i class="fas fa-notes-medical">
                                    &nbsp;</i>Item</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bundling-tab" data-toggle="pill" href="#bundling" role="tab"
                                aria-controls="bundling" aria-selected="false"><i class="fas fa-pills">
                                    &nbsp;</i>Bundling</a>
                        </li>
                    </ul>

                    <div class="dl-buttons btn-group flex-wrap float-right">
                        <a href="{{ route('items.create') }}" class="btn btn-sm bg-inventory" title="Add Item"><i
                                class="fas fa-plus"></i> Add Item</a>
                        &nbsp <a href="{{ route('items.formUpload') }}" class="btn btn-sm btn-success modal-create-upload"
                            title="Add Item"><i class="fas fa-file-excel"></i> Upload Items</a>
                        &nbsp <a href="{{ route('items.formUpdateUpload') }}"
                            class="btn btn-sm btn-primary modal-create-upload" title="Update Bulk Item"><i
                                class="fas fa-file-excel"></i> Update Bulk Items</a>
                        &nbsp <a href="{{ route('bundling.create') }}" class="btn btn-sm btn-secondary"
                            title="Add Bundling"><i class="fas fa-plus"></i> Add Bundling</a>
                    </div>

                    <div class="tab-content" id="tabContent" style="padding-top: 1rem;">
                        <div class="tab-pane fade show active" id="item" role="tabpanel" aria-labelledby="item">
                            @include('item.item_list')
                        </div>
                        <div class="tab-pane fade show" id="bundling" role="tabpanel" aria-labelledby="bundling">
                            @include('item.bundling_list')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script></script>
@endpush

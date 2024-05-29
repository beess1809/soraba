@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="nav-icon fas fa-boxes"></i> Flash Sale</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-boxes"></i> Flash Sale</li>
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
                    <table id="datatable" class="table table-sm  table-hover">
                        <thead>
                            <tr>
                                <th class="no-sort" style="width: 30px">No</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th class="no-sort" style="width: 100px"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>
                        Bundling Flash Sale
                    </h4>
                </div>
                <div class="card-body">

                    <table id="datatable2" class="table table-sm  table-hover">
                        <thead>
                            <tr>
                                <th class="no-sort" style="width: 30px">No</th>
                                <th>Nama Bundling</th>
                                <th>Harga</th>
                                <th>Data Item</th>
                                <th class="no-sort" style="width: 100px"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#datatable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                autoWidth: false,
                responsive: true,
                processing: true,
                serverSide: true,
                cache: false,
                ajax: {
                    url: "{{ route('flash-sale.datatable') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            // name: $("#_name").val(),
                            // email: $("#_email").val(),
                            // role_id: $("#_role_ids").val()
                        });
                    }
                },
                dom: '<"toolbar">lfrtip',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'time_start',
                        name: 'time_start'
                    },
                    {
                        data: 'time_end',
                        name: 'time_end'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                columnDefs: [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
                order: [
                    [1, "asc"]
                ]
            });

            const toolbar = '<div class="dt-buttons btn-group flex-wrap">' +
                '<a href="{{ route('flash-sale.create') }}" class="btn btn-sm bg-inventory" title="Buat Flash Sale"><i class="fas fa-plus"></i> Buat Flash Sale</a>' +
                '<a href="{{ route('flash-sale.createBundling') }}" class="btn btn-sm bg-secondary" title="Buat Bundling Flash Sale"><i class="fas fa-plus"></i> Buat Bundling Flash Sale</a>' +
                '</div>';
            $("div.toolbar").html(toolbar);
            $("#_search").click(() => {
                $('#datatable-all').DataTable().ajax.reload();
            });

            $('#datatable2').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                autoWidth: false,
                responsive: true,
                processing: true,
                serverSide: true,
                cache: false,
                ajax: {
                    url: "{{ route('flash-sale.datatableBundling') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            // name: $("#_name").val(),
                            // email: $("#_email").val(),
                            // role_id: $("#_role_ids").val()
                        });
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'items',
                        name: 'items'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                columnDefs: [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
                order: [
                    [1, "asc"]
                ]
            });
        });
    </script>
@endpush

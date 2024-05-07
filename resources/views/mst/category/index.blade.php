@extends('layouts.app')

@section('content-header')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="nav-icon fas fa-boxes"></i> Category</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><i class="nav-icon fas fa-boxes"></i> Category</li>
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
                            <!-- <th>Code</th> -->
                            <th>Name</th>
                            <th class="no-sort" style="width: 100px">Action</th>
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
            searching: false,
            ordering: true,
            autoWidth: false,
            responsive: true,
            processing: true,
            serverSide: true,
            cache: false,
            ajax: {
                url: "{{ route('master.category.datatable') }}",
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
                // {
                //     data: 'code',
                //     name: 'code'
                // },
                {
                    data: 'name',
                    name: 'name'
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
            '<a href="{{ route("master.category.create") }}" class="btn btn-sm bg-inventory" title="Add Category"><i class="fas fa-plus"></i> Add Category</a>' +
            '&nbsp <a href="{{ route("master.category.formUpload") }}" class="btn btn-sm btn-success modal-create-upload" title="Add Category"><i class="fas fa-file-excel"></i> Upload Category</a>' +
            '</div>';
        $("div.toolbar").html(toolbar);

        $("#_search").click(() => {
            $('#datatable-all').DataTable().ajax.reload();
        });
    });
</script>
@endpush
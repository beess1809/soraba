@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="nav-icon fas fa-receipt"></i> Laporan Detail Transaksi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-receipt"></i> Daftar Detail Transaksi
                        </li>
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
                    <div class="form-group col-md-6">
                        <label for="name" class="col-sm-12 col-form-label">Tanggal Pemesanan</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right daterange" id="tanggal">
                            </div>

                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="col-sm-6">
                            <button class="btn btn-inventory" id="_search"><i class="fas fa-search"></i> Cari
                                Transaksi</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-secondary" id="export"><i class="fas fa-file"></i> Download
                        Transaksi</button>
                    <button class="btn btn-info" id="print"><i class="fas fa-file"></i> Print
                        Transaksi</button>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No Invoice</th>
                                <th>Name</th>
                                <th>Kuantitas</th>
                                <th>Harga Satuan</th>
                                <th>Discount</th>
                                <th>Total</th>
                                {{--  <th>Subtotal</th> --}}
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
                autoWidth: true,
                ordering: false,
                sorting: false,
                responsive: true,
                processing: true,
                serverSide: true,
                cache: false,
                ajax: {
                    url: "{{ route('report.detailTable') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            tanggal: $("#tanggal").val(),
                            // email: $("#_email").val(),
                            // role_id: $("#_role_ids").val()
                        });
                    }
                },
                dom: '<"toolbar">lfrtip',
                columns: [{
                        data: 'no_invoice',
                        name: 'no_invoice'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    }
                    // {
                    //     data: 'subtotal',
                    //     name: 'subtotal'
                    // },
                    // {
                    //     data: 'pajak',
                    //     name: 'pajak'
                    // },

                ],


            });

            // const toolbar = '<div class="dt-buttons btn-group flex-wrap">' +
            //     '<a href="{{ route('invoice.create') }}" class="btn btn-sm bg-inventory" title="Create Invoice"><i class="fas fa-plus"></i> Create Invoice</a>' +
            //     '</div>';
            // $("div.toolbar").html(toolbar);

            $("#_search").click(() => {
                $('#datatable').DataTable().ajax.reload();
            });

            $('#export').click(function() {
                var date = $("#tanggal").val();
                window.location.href = "/report/detail/export/" + encodeURI(date);
            })

            $('#print').click(function(event) {
                var date = $("#tanggal").val();
                event.preventDefault();

                $.ajax({
                    url: '{{ route('report.printDetailTransaction') }}',
                    method: 'POST',
                    data: {
                        date: date,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function(response) {
                        // window.location.href = response.pdfPath;

                        // console.log(response.pdfPath)
                        var downloadLink = document.createElement("a");
                        downloadLink.href = response.pdfPath;
                        downloadLink.download = response.fileName;
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                });
            });
        });
    </script>
@endpush

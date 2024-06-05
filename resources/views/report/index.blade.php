@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="nav-icon fas fa-receipt"></i> Laporan Transaksi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-receipt"></i> Daftar Transaksi</li>
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
                    <button class="btn btn-secondary" id="export"><i class="fas fa-file"></i> Download Transaksi</button>
                    <button class="btn btn-info" id="print"><i class="fas fa-file"></i> Print Transaksi</button>
                    <div id="pdfContainer"></div>
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="no-sort" style="width: 30px">No</th>
                                <th>No Invoice</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Tipe Pembayaran</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Total</th>
                                <th>Diskon</th>
                                <th>Grand Total</th>
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
                    url: "{{ route('report.transaction') }}",
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
                        data: 'DT_RowIndex',
                        name: 'id'
                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'waktu_pemesanan',
                        name: 'created_at'
                    },
                    {
                        data: 'payment_type',
                        name: 'payment_type'
                    },
                    {
                        data: 'waktu_pembayaran',
                        name: 'updated_at'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'grand_total',
                        name: 'grand_total'
                    }
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
                window.location.href = "/report/transaction/export/" + encodeURI(date);
            })

            $('#print').click(function(event) {
                var date = $("#tanggal").val();
                event.preventDefault();

                $.ajax({
                    url: '{{ route('report.printTransaction') }}',
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

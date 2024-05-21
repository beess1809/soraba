@extends('pos-online.layout')

@section('content')
    <div class="container-inventory">
        <div class="justify-content-center">
            <div class="content-header">
                <div class="">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><strong>Daftar Pesanan</strong></h1>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <div class="col-md-12">
                <table id="datatable" class="table table-sm  table-hover">
                    <thead>
                        <tr>
                            <th style="width: 30px">No</th>
                            <th>Invoice No</th>
                            <th>Waktu Order</th>
                            <th>Nama Pelanggan</th>
                            <th>Tipe Pembayaran</th>
                            <th>Waktu Pembayaran</th>
                            <th>Jumlah Item</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="width: 130px"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
                ordering: false,
                autoWidth: false,
                responsive: true,
                processing: true,
                serverSide: true,
                cache: false,
                ajax: {
                    url: "{{ route('pos-online.datatable') }}",
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
                        name: 'id',

                    },
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'payment_type',
                        name: 'payment_type'
                    },
                    {
                        data: 'waktu_pembayaran',
                        name: 'waktu_pembayaran'
                    },
                    {
                        data: 'jumlah_item',
                        name: 'jumlah_item'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
            });

            // const toolbar = '<div class="dt-buttons btn-group flex-wrap">' +
            //     '<a href="{{ route('invoice.create') }}" class="btn btn-sm bg-inventory" title="Create Invoice"><i class="fas fa-plus"></i> Create Invoice</a>' +
            //     '</div>';
            $("div.toolbar").html(toolbar);

            $("#_search").click(() => {
                $('#datatable-all').DataTable().ajax.reload();
            });
        });
    </script>
@endpush

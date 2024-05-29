<table id="datatable2" class="table table-sm  table-hover">
    <thead>
        <tr>
            <th class="no-sort" style="width: 30px">No</th>
            <th>Nama Bundling</th>
            <th>Harga</th>
            <th>Harga Flash Sale</th>
            <th>Data Item</th>
            <th class="no-sort" style="width: 100px"></th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script>
        $(function() {
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
                    url: "{{ route('bundling.datatable') }}",
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'flash_sale_price',
                        name: 'flash_sale_price'
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

            $("#_search").click(() => {
                $('#datatable-all').DataTable().ajax.reload();
            });
        });
    </script>
@endpush

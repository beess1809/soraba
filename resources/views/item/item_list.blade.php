<table id="datatable" class="table table-sm  table-hover">
    <thead>
        <tr>
            <th class="no-sort" style="width: 30px">No</th>
            <th>Category</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Uom</th>
            <th>Sell Price</th>
            <th>Discount (%)</th>
            <th>Discount Expired Date</th>
            <th>Vendor</th>
            <th>Warehouse</th>
            <th class="no-sort" style="width: 100px"></th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

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
                    url: "{{ route('items.datatable') }}",
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
                        data: 'category',
                        name: 'category'
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
                        data: 'uom',
                        name: 'uom'
                    },

                    {
                        data: 'sale_price',
                        name: 'sale_price'
                    },
                    {
                        data: 'discount',
                        name: 'discount'
                    },
                    {
                        data: 'expired_date',
                        name: 'expired_date'
                    },
                    {
                        data: 'vendor',
                        name: 'vendor'
                    },
                    {
                        data: 'warehouse',
                        name: 'warehouse'
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

            // const toolbar = '<div class="dt-buttons btn-group flex-wrap">' +
            //     '<a href="{{ route('items.create') }}" class="btn btn-sm bg-inventory" title="Add Item"><i class="fas fa-plus"></i> Add Item</a>' +
            //     '&nbsp <a href="{{ route('items.formUpload') }}" class="btn btn-sm btn-success modal-create-upload" title="Add Item"><i class="fas fa-file-excel"></i> Upload Items</a>' +
            //     '&nbsp <a href="{{ route('items.formUpdateUpload') }}" class="btn btn-sm btn-primary modal-create-upload" title="Update Bulk Item"><i class="fas fa-file-excel"></i> Update Bulk Items</a>' +
            //     // '&nbsp <a href="{{ route('items.createBundling') }}" class="btn btn-sm btn-secondary" title="Add Bundling"><i class="fas fa-plus"></i> Add Bundling</a>' +
            //     '</div>';
            // $("div.toolbar").html(toolbar);

            $("#_search").click(() => {
                $('#datatable-all').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
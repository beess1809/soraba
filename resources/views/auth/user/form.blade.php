<form class="form-horizontal"
    action="{{ $model->exists ? route('auth.user.update', base64_encode($model->id)) : route('auth.user.store') }}"
    method="POST">
    {{ csrf_field() }}
    @if ($model->exists)
        <input type="hidden" name="_method" value="PUT">
    @endif

    <div class="form-group row">
        <label for="role_id" class="col-sm-2 col-form-label">Role</label>
        <div class="col-sm-10">
            <select name="role_id" id="role_id" class="select2 form-control" style="width: 100%;">
                <option value="">Pilih Role</option>
                @foreach (App\Models\Auth\Role::all() as $item)
                    <option {{ $model->hasRole($item->name) ? 'selected' : '' }} value="{{ $item->id }}">
                        {{ $item->display_name }}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Name</label>
        <div class="col-sm-10">
            <input type="text" name="name" id="name" class="form-control" placeholder="Name"
                @if ($model->exists) value="{{ $model->name }}" @endif>
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="email" name="email" id="email" class="form-control" placeholder="example@mail.com"
                @if ($model->exists) value="{{ $model->email }}" @endif>
        </div>
    </div>

    @if (!$model->exists)
        <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" name="password" id="password" placeholder="Password" class="form-control">
            </div>
        </div>
    @endif
</form>
<script>
    $(document).ready(function() {
        selectWilayah()
    })

    function selectWilayah(params) {
        $('#wilayah').select2({
            theme: 'bootstrap4',
            dropdownParent: $("#modal"),
            ajax: {
                url: '{{ route('auth.wilayah.data') }}',
                dataType: 'json',
                data: function(params) {
                    return {
                        wilayah: $.trim(params.term),
                        wilayah_id: $.trim(params.wilayah_id),
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    }

    $('.select2').select2()
</script>

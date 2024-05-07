<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-body">
                <form class="form-horizontal" action="{{ route('master.category.uploadExcel') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="code" class="col-sm-12 col-form-label">Upload Excel</label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" name="file_upload">
                                <a href="{{ route('master.category.formatExcel') }}">Format Upload</a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
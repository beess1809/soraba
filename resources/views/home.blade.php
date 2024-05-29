@extends('layouts.app')

@section('content-header')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="small-box bg-inventory">
                <div class="inner">
                    <h3>{{ $transaksi }}</h3>
                    <p>Today Transactions</p>
                </div>
                <div class="icon">
                    <i class="fa fa-chart-bar"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($pendapatan, '0', ',', '.') }}</h3>
                    <p>Today Income</p>
                </div>
                <div class="icon">
                    <i class="fa fa-chart-line"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h3 class="m-0"><strong>10 Stok Tersedikit</strong></h3>
            <table class="table table-striped ">
                <thead>
                    <tr style="text-align: center">
                        <th class="no-sort" style="width: 30px">No</th>
                        <th>Category</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Warehouse</th>
                    </tr>
                </thead>
                <tbody style="background: white">
                    @php
                        $key = 0;
                    @endphp
                    @foreach ($items->get() as $item)
                        <tr style="text-align: center">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->warehouse->name }}</td>
                        </tr>
                        @php
                            $key++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

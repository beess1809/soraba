<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS | Healthy Fit</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bs-stepper/css/bs-stepper.min.css') }}">

    @stack('css')

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->

        <nav class="main-header navbar navbar-expand-md bg-inventory" style="font-size: 14pt">

            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                @php
                    $segments = Request::segments();
                    $url = '/' . implode('/', $segments);
                @endphp
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item @if ($url == '/pos') active-link @endif">
                        <a href="{{ route('pos.index') }}" class="nav-link link-pos"><i class="fas fa-home"></i>
                            Home
                        </a>
                    </li>
                    <li class="nav-item @if ($url == '/pos/create') ) active-link @endif">
                        <a href="{{ route('pos.create') }}" class="nav-link link-pos "><i
                                class="fas fa-cash-register"></i>
                            Buat Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link link-pos"><i class="fas fa-store-alt"></i>
                            Inventori
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav nav-center">

                    <li class="nav-item">
                        <img src="{{ asset('img/logo-white.png') }}" alt="Logo" class="login-box-msg"
                            width="230px">
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle link-pos" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-user-cog"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <li>
                                <a href="" class="dropdown-item btn btn-sm btn-success modal-show edit"
                                    title="Change Password">
                                    <i class="fa fa-cogs"></i> {{ __('Change Password') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out-alt"></i> {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item" style="font-size: 12pt;margin-right:2rem">
                        <span>{{ date('l') . ', ' . date('d F Y') }}</span>
                        <br>
                        <span><i class="far fa-clock"></i> <span id="time"> {{ date('H:i:s') }}</span></span>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background-color: white">
            <!-- Main content -->
            <div class="content">
                @yield('content')
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <script>
        const loginUrl = "{{ route('login') }}";
    </script>
    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('adminlte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    @stack('js')
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
    @if (session('alert.success'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Transaksimu Berhasil',
                    text: '{{ session('alert.success') }}',
                    timer: 2000,
                    confirmButtonColor: '#3085d6',
                });
            });
        </script>
    @endif
    @if (session('alert.failed'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Transaksimu Gagal',
                    title: '{{ session('alert.failed') }}',
                    confirmButtonColor: '#3085d6',
                });
            });
        </script>
    @endif
    <script>
        var serverTime = <?php echo time() * 1000; ?>; //this would come from the server
        var localTime = +Date.now();
        var timeDiff = serverTime - localTime;

        setInterval(function() {
            var realtime = +Date.now() + timeDiff;
            var date = new Date(realtime);
            // hours part from the timestamp
            var hours = date.getHours();
            // minutes part from the timestamp
            var minutes = date.getMinutes();
            // seconds part from the timestamp
            var seconds = date.getSeconds();

            // will display time in 10:30:23 format
            var formattedTime = hours + ':' + minutes + ':' + seconds;

            $('#time').html(formattedTime);
        }, 1000);
    </script>


</body>

</html>

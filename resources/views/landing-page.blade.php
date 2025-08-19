<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilihan Product</title>
    <!-- Memuat Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <style>
        /* Style utama untuk body */
        body {
            background-color: #f0f2f5;
            font-family: 'Inter', sans-serif;
        }

        /* Container utama */
        .pilihan-container {
            max-width: 800px;
            margin: 4rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }

        /* Judul */
        .pilihan-title {
            text-align: center;
            margin-bottom: 2.5rem;
            color: #333;
            font-weight: 700;
        }

        /* Styling untuk kartu pilihan */
        .choice-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* padding: 2rem; */
            border-radius: 1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
            min-height: 250px;
            /* Menjamin tinggi minimum */
        }

        /* Warna spesifik untuk setiap kartu */
        .card-soraba {
            background-color: var(--primary);
            width: 346px;
            border: none;
        }

        .card-beib {
            background-color: #00584C;
            width: 346px;
            border: none;
            /* Hijau */
        }

        /* Efek hover pada kartu */
        .choice-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Ikon di dalam kartu */
        .choice-card svg {
            width: -webkit-fill-available;
            height: auto;
            max-width: 120px;
            margin-bottom: 1.5rem;
        }

        /* Teks di dalam kartu */
        .choice-card-text {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Menyembunyikan radio button asli */
        .btn-check {
            display: none;
        }

        /* Style ketika radio button terpilih */
        .btn-check:checked+.choice-card {
            box-shadow: 0 0 0 4px white, 0 0 0 8px var(--primary);
            /* Efek outline biru saat dipilih */
        }

        .btn-check:checked+.card-beib {
            box-shadow: 0 0 0 4px white, 0 0 0 8px #00584C;
            /* Efek outline hijau saat dipilih */
        }
    </style>
</head>

<body>

    <div class="container pilihan-container">
        <h3 class="pilihan-title">Silahkan pilih produk</h3>

        <form action="{{ route('produckChoice') }}" method="POST" id="form-pilihan">
            @csrf
            <div class="row g-4">
                <div class="col-md-6 btn-pilih">
                    <input type="radio" class="btn-check" name="produk" value="1" id="select-soraba"
                        autocomplete="off">
                    <label class="choice-card card-soraba " for="select-soraba">
                        <img src="{{ asset('img/logo-white.png') }}" alt="">
                    </label>
                </div>

                <div class="col-md-6 btn-pilih">
                    <input type="radio" class="btn-check" name="produk" value="2" id="select-beib"
                        autocomplete="off">
                    <label class="choice-card card-beib" for="select-beib">
                        <img src="{{ asset('img/logo-beib.svg') }}" alt="" style="border-radius: 1rem">
                    </label>
                </div>
            </div>

        </form>
    </div>
    <!-- SweetAlert2 -->
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script>
        $(".btn-check").click(function(event) {
            event.preventDefault();

            var form = $("#form-pilihan"),
                url = form.attr("action"),
                method = "POST";


            form.find(".help-block").remove();
            form.find(".form-group").removeClass("has-error");

            $.ajax({
                url: url,
                method: method,
                data: form.serialize(),
                success: function(response) {
                    form.trigger("reset");
                    console.log(response.success);
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Data has been saved!",
                            timer: 2000,
                            confirmButtonColor: "#3085d6",
                        });
                        window.location.replace(response.data.url);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: response.data.message,
                            confirmButtonColor: '#3085d6',
                        });
                    }
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    if (res.message != "") {}
                    console.log(res.errors);
                    if ($.isEmptyObject(res.errors) == false) {
                        $.each(res.errors, function(key, value) {
                            $("#" + key)
                                .closest(".form-control")
                                .addClass("is-invalid");
                            $(
                                '<span class="invalid-feedback" role="alert"><strong>' +
                                value +
                                "</strong></span>"
                            ).insertAfter($("#" + key));
                        });
                    }

                    Swal.fire({
                        icon: "error",
                        title: "Something went wrong!",
                        text: "Check your values",
                        confirmButtonColor: "#3085d6",
                    });
                },
            });
        });
    </script>
</body>


</html>

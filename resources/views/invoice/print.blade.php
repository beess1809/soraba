<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <style>
    .header {
        text-align: center;
        font-size: 20px;
    }
    .header .logo {
        float: left;
    }

    header .logo img {
        left: 0px;
        width: 30%;
        height: 50%;
        margin-left: -10px
    }

    .content {
        margin-top:15px
    }
    /* .content .employee {
        float: left;
    } */
  </style>
  <body>

    <div class="header">
        <h3>PERFORMANCE APPRAISAL FORM</h3>
        <h3>PT. Phintraco Technology</h3>
        <div class="logo">
            <img src="{{ public_path('img/logo.png') }}" alt="">
        </div>
    </div>
    <div class="content">
        <div class="employee">
            <table>
                <tr>
                    <th>Nama</th><th>Brigitta Nathasya Geraldine</th>
                </tr>
                <tr>
                    <th>Employee ID</th><th>2220624011-BNG</th>
                </tr>
                <tr>
                    <th>Company</th><th>PT. Phintraco Technology</th>
                </tr>
                <tr>
                    <th>Organization</th><th>PT HR Operation Section</th>
                </tr>
                <tr>
                    <th>Grade</th><th>3</th>
                </tr>
                <tr>
                    <th>Level</th><th>Staf</th>
                </tr>
            
            </table>
        </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
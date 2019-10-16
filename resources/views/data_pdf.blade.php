<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-8 text-center">
            <p style="font-size: 15px !important; text-align: center;"><strong>LISTING DATA PAPERLESS UMD (Detail Permohonan)</strong></p>
            <p>All Department</p>
            <p>2001542903 - Jefri Ronaldo</p>
            <p>Final Date : ALL</p>
            <p>PUM Status : ALL</p>
            <p>RESP Status : ALL</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table">
                <p class="text-white">{{ $i = 1 }}</p>
                <thead class="bg-primary text-white text-center">
                <tr>
                    <th>No</th>
                    <th>Dept Id</th>
                    <th>Name Id</th>
                    <th>Org Id</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $data->DEPT_ID }}</td>
                        <td>{{ $data->NAME }}</td>
                        <td>{{ $data->ORG_ID }}</td>
                        <td>{{ $data->DESCRIPTION }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
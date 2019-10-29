<!doctype html>
<html lang="en">
<head>
    <title>REPORT</title>

    <style>
        .headerReport{
            text-align: center;
            font-size: 12px;
            line-height: 5px;
            letter-spacing: 1px;
            font-family: "Helvetica Neue";
            margin-bottom: -10px;
        }
        table{
            border:1px solid #333;
            border-collapse:collapse;
            margin:0 auto;
            font-size: 11px;
        }
        td, tr, th{
            border:1px solid #333;
            height: 40px;
            text-align: center;
        }
        td {
            height: 18px;
        }
        .date{
        text-align: right;
            font-size: 9px;
        }
    </style>
</head>


<body>

<div class="date">
    <p>Tgl Cetak : {{ $TEMP[6] }}</p>
</div>

<div class="headerReport">
    <h2><strong>LISTING DATA PAPERLESS UMD (Detail Permohonan)</strong></h2>
    <p>{{ $DEPT_CODE }} - {{ $DEPT_NAME }}</p>
    <p>{{ $EMP_NUM }} - {{ $EMP_NAME }}</p>
    <p>Periode PUM : {{ $TEMP[0] }} - {{ $TEMP[1] }}</p>
    <p>Validate PUM : {{ $TEMP[2] }} - {{ $TEMP[3] }}</p>
    <p>PUM Status : {{ $TEMP[4] }}</p>
    <p>RESP Status : {{ $TEMP[5] }}</p>
</div>

<p style="color: white">{{ $i = 1 }}</p>
<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th style="width: 30px">No</th>
        <th style="width: 50px">PUM <br> Number</th>
        <th style="width: 50px">Create <br> Date</th>
        <th style="width: 50px">Use Date</th>
        <th style="width: 50px">Emp <br> Number</th>
        <th style="width: 100px">Emp Name</th>
        <th style="width: 35px">PUM <br> Status</th>
        <th style="width: 30px">RESP <br> Status</th>
        <th style="width: 200px">Description</th>
        <th style="width: 65px">Appr 1</th>
        <th style="width: 65px">Appr 2</th>
        <th style="width: 65px">Appr 3</th>
        <th style="width: 65px">Appr 4</th>
        <th style="width: 65px">PUM <br> Amount</th>
    </tr>
    </thead>
    <tbody>
    <tr><td colspan="14"></td></tr>
    @foreach($datas as $data)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $data->PUM_NUM}}</td>
            <td>{{ $data->TRX_DATE }}</td>
            <td>{{ $data->USE_DATE }}</td>
            <td>{{ $data->EMP_NUM}}</td>
            <td>{{ $data->EMP_NAME}}</td>
            <td>{{ $data->PUM_STATUS}}</td>
            <td>{{ $data->RESP_STATUS}}</td>
            <td>{{ $data->DESC_PUM}}</td>
            <td>{{ $data->APPROVAL_EMP_ID1}}</td>
            <td>{{ $data->APPROVAL_EMP_ID2}}</td>
            <td>{{ $data->APPROVAL_EMP_ID3}}</td>
            <td>{{ $data->APPROVAL_EMP_ID4}}</td>
            <td>{{ $data->AMOUNT}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="13"><strong>GRAND TOTAL</strong></td>
        <td>53000000</td>
    </tr>
    </tbody>
</table>


</body>
</html>
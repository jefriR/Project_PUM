<!doctype html>
<html lang="en">
<head>
    <title>REPORTING</title>

    <style>
        .date{
            line-height: 1.5px;
            font-style: italic;
        }
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
    <p>Pkl Cetak : {{ $TEMP[7] }}</p>
</div>

<div class="headerReport">
    <h2><strong>LISTING DATA PAPERLESS UMD</strong></h2>
    <h2><strong>(Pertanggungjawaban)</strong></h2>
    <p>{{ $DEPT_CODE }} - {{ $DEPT_NAME }}</p>
    <p>{{ $EMP_NUM }} - {{ $EMP_NAME }}</p>
    <p>Periode PUM : {{ $TEMP[0] }} - {{ $TEMP[1] }}</p>
    <p>Validate PUM : {{ $TEMP[2] }} - {{ $TEMP[3] }}</p>
    <p>PUM Status : {{ $TEMP[4] }}</p>
    <p>RESP Status : {{ $TEMP[5] }}</p>

</div>

<p style="color: white">{{ $i = 1 }} {{ $grandTotal = 0 }}</p>
<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th style="width: 30px">No</th>
        <th style="width: 110px">Resp. Trx Num</th>
        <th style="width: 90px">Resp. Status</th>
        <th style="width: 90px">Resp. Create <br> Date</th>
        <th style="width: 140px">Resp. Amount</th>
    </tr>
    </thead>
    <tbody>
    <tr><td colspan="5"></td></tr>
    @foreach($datas as $data)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $data->PUM_RESP_TRX_NUM}}</td>
            <td>{{ $data->RESP_STATUS }}</td>
            <td>{{ $data->CREATION_DATE }}</td>
            <td>{{ $data->AMOUNT}}</td>
        </tr>
        {{ $grandTotal = $grandTotal + $data->AMOUNT }}
    @endforeach
    <tr>
        <td colspan="4"><strong>GRAND TOTAL</strong></td>
        <td>Rp.{{$grandTotal}}</td>
    </tr>
    </tbody>
</table>


</body>
</html>
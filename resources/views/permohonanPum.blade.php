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
            /*margin-left: -10px;*/
            /*margin-right: 10px;*/
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
    @if($GROUP == '-')
        {{ $grandTotal = 0 }}
        <thead>
        <tr>
            <th style="width: 20px">No</th>
            <th style="width: 60px">PUM <br> Number</th>
            <th style="width: 60px">Create <br> Date</th>
            <th style="width: 60px">Use Date</th>
            <th style="width: 60px">Emp <br> Number</th>
            <th style="width: 110px">Emp Name</th>
            <th style="width: 35px">PUM <br> Status</th>
            <th style="width: 30px">RESP <br> Status</th>
            <th style="width: 190px">Description</th>
            <th style="width: 70px">Appr 1</th>
            <th style="width: 70px">Appr 2</th>
            <th style="width: 70px">Appr 3</th>
            <th style="width: 70px">Appr 4</th>
            <th style="width: 65px">PUM <br> Amount</th>
        </tr>
        </thead>
        <tbody>
        @if($datas == null)
            <tr>
               <td colspan="15" style="text-align: center">Data Unavailable</td>
            </tr>
        @else
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
                    <td>@if(strlen($data->DESC_PUM) > 45) {{substr($data->DESC_PUM,0,42).'...'}} @else {{$data->DESC_PUM}} @endif</td>
                    <td>{{ $data->APPROVAL_EMP_ID1}}</td>
                    <td>{{ $data->APPROVAL_EMP_ID2}}</td>
                    <td>{{ $data->APPROVAL_EMP_ID3}}</td>
                    <td>{{ $data->APPROVAL_EMP_ID4}}</td>
                    <td>{{ $data->AMOUNT}}</td>
                </tr>
                {{ $grandTotal = $grandTotal + $data->AMOUNT }}
            @endforeach
            <tr>
                <td colspan="13"><strong>GRAND TOTAL</strong></td>
                <td><strong>Rp.{{ $grandTotal}}</strong></td>
            </tr>
        @endif
        </tbody>
    @elseif($GROUP == 'E')
        {{ $grandTotal = 0 }}
        <thead>
        <tr>
            <th style="width: 30px">No</th>
            <th style="width: 50px">PUM <br> Number</th>
            <th style="width: 60px">Create <br> Date</th>
            <th style="width: 60px">Use Date</th>
            <th style="width: 35px">PUM <br> Status</th>
            <th style="width: 30px">RESP <br> Status</th>
            <th style="width: 200px">Description</th>
            <th style="width: 75px">Appr 1</th>
            <th style="width: 75px">Appr 2</th>
            <th style="width: 75px">Appr 3</th>
            <th style="width: 75px">Appr 4</th>
            <th style="width: 65px">PUM <br> Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $data)
            {{ $total = 0 }}
            <tr>
                <td colspan="12" style="text-align: left; padding-left: 10px">{{$data->EMP_NUM}} - {{$data->EMP_NAME}}</td>
            </tr>
            @foreach($data->dataReport as $detail)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $detail->PUM_NUM}}</td>
                    <td>{{ $detail->TRX_DATE }}</td>
                    <td>{{ $detail->USE_DATE }}</td>
                    <td>{{ $detail->PUM_STATUS}}</td>
                    <td>{{ $detail->RESP_STATUS}}</td>
                    <td>@if(strlen($detail->DESC_PUM) > 50) {{substr($detail->DESC_PUM,0,47).'...'}} @else {{$detail->DESC_PUM}} @endif</td>
                    <td>{{ $detail->APPROVAL_EMP_ID1}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID2}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID3}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID4}}</td>
                    <td>Rp.{{ $detail->AMOUNT}}</td>
                </tr>
                {{$total = $total + $detail->AMOUNT}}
            @endforeach
            <tr>
                <td colspan="11"><strong>TOTAL</strong></td>
                <td colspan="1"><strong>{{ $total}}</strong></td>
                {{$grandTotal = $grandTotal + $total}}
            </tr>
        @endforeach
        <tr>
            <td colspan="11"><strong>GRAND TOTAL</strong></td>
            <td><strong>Rp.{{ $grandTotal}}</strong></td>
        </tr>
        </tbody>
    @elseif($GROUP == 'D')
        {{ $grandTotal = 0 }}
        <thead>
        <tr>
            <th style="width: 20px">No</th>
            <th style="width: 60px">PUM <br> Number</th>
            <th style="width: 60px">Create <br> Date</th>
            <th style="width: 60px">Use Date</th>
            <th style="width: 60px">Emp <br> Number</th>
            <th style="width: 110px">Emp Name</th>
            <th style="width: 35px">PUM <br> Status</th>
            <th style="width: 30px">RESP <br> Status</th>
            <th style="width: 190px">Description</th>
            <th style="width: 70px">Appr 1</th>
            <th style="width: 70px">Appr 2</th>
            <th style="width: 70px">Appr 3</th>
            <th style="width: 70px">Appr 4</th>
            <th style="width: 65px">PUM <br> Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $data)
            {{ $total = 0 }}
            <tr>
                <td colspan="14" style="text-align: left; padding-left: 10px">{{$data->DEPT_NAME}}</td>
            </tr>
            @foreach($data->dataReport as $detail)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $detail->PUM_NUM}}</td>
                    <td>{{ $detail->TRX_DATE }}</td>
                    <td>{{ $detail->USE_DATE }}</td>
                    <td>{{ $detail->EMP_NUM }}</td>
                    <td>{{ $detail->EMP_NAME }}</td>
                    <td>{{ $detail->PUM_STATUS}}</td>
                    <td>{{ $detail->RESP_STATUS}}</td>
                    <td>@if(strlen($detail->DESC_PUM) > 40) {{substr($detail->DESC_PUM,0,37).'...'}} @else {{$detail->DESC_PUM}} @endif</td>
                    <td>{{ $detail->APPROVAL_EMP_ID1}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID2}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID3}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID4}}</td>
                    <td>Rp.{{ $detail->AMOUNT}}</td>
                </tr>
                {{$total = $total + $detail->AMOUNT}}

            @endforeach
            <tr>
                <td colspan="13"><strong>TOTAL</strong></td>
                <td colspan="1"><strong>Rp.{{ $total}}</strong></td>
                {{$grandTotal = $grandTotal + $total}}
            </tr>
        @endforeach
        <tr>
            <td colspan="13"><strong>GRAND TOTAL</strong></td>
            <td><strong>Rp.{{$grandTotal}}</strong></td>
        </tr>
        </tbody>
    @elseif($GROUP == 'C')
        {{$grandTotal = 0}}
        <thead>
        <tr>
            <th style="width: 20px">No</th>
            <th style="width: 60px">PUM <br> Number</th>
            <th style="width: 60px">Create <br> Date</th>
            <th style="width: 60px">Use Date</th>
            <th style="width: 60px">Emp <br> Number</th>
            <th style="width: 110px">Emp Name</th>
            <th style="width: 35px">PUM <br> Status</th>
            <th style="width: 30px">RESP <br> Status</th>
            <th style="width: 190px">Description</th>
            <th style="width: 70px">Appr 1</th>
            <th style="width: 70px">Appr 2</th>
            <th style="width: 70px">Appr 3</th>
            <th style="width: 70px">Appr 4</th>
            <th style="width: 65px">PUM <br> Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($datas as $data)
            {{$total = 0}}
            <tr>
                <td colspan="14" style="text-align: left; padding-left: 10px">{{$data->TRX_DATE}}</td>
            </tr>
            @foreach($data->dataReport as $detail)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $detail->PUM_NUM}}</td>
                    <td>{{ $detail->TRX_DATE }}</td>
                    <td>{{ $detail->USE_DATE }}</td>
                    <td>{{ $detail->EMP_NUM }}</td>
                    <td>{{ $detail->EMP_NAME }}</td>
                    <td>{{ $detail->PUM_STATUS}}</td>
                    <td>{{ $detail->RESP_STATUS}}</td>
                    <td>@if(strlen($detail->DESC_PUM) > 40) {{substr($detail->DESC_PUM,0,37).'...'}} @else {{$detail->DESC_PUM}} @endif</td>
                    <td>{{ $detail->APPROVAL_EMP_ID1}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID2}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID3}}</td>
                    <td>{{ $detail->APPROVAL_EMP_ID4}}</td>
                    <td>Rp.{{ $detail->AMOUNT}}</td>
                </tr>
                {{$total = $total + $detail->AMOUNT}}
            @endforeach
            <tr>
                <td colspan="13"><strong>TOTAL</strong></td>
                <td colspan="1"><strong>{{ $total}}</strong></td>
                {{$grandTotal = $grandTotal + $total}}
            </tr>
        @endforeach
        <tr>
            <td colspan="13"><strong>GRAND TOTAL</strong></td>
            <td><strong>Rp.{{$grandTotal}}</strong></td>
        </tr>
        </tbody>
    @endif

</table>


</body>
</html>
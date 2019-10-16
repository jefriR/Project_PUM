<?php

namespace App\Http\Controllers\API;

use App\resp_trx_all;
use App\resp_trx_lines_all;
use App\ResponsePum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ResponsibilityController extends Controller
{
    public function getListData(Request $request){
        $validator  = Validator::make($request->all(), [
            'emp_id'    => 'required | string',

        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_id     = $request->emp_id;
        $model      = new ResponsePum();
        $getDataPum = $model->getDataPum($emp_id);

        foreach ($getDataPum as $data){
            $trxType        = $model->getTrxType($data->PUM_TRX_TYPE_ID);
            $data->TRX_TYPE = $trxType;
        }

        return response()->json(['error'=>false, 'message' => $getDataPum], 200);
    }

    public function submitResponsibility(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emp_id'        => 'required',
            'pum_trx_id'    => 'required ',
            'trx_type'      => 'required | array',
            'amount'        => 'required | array',
            'description'   => 'required | array',
            'store_code'    => 'required | array',
            'image'         => 'required | array',
            'kode'          => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_id     = $request->emp_id;
        $pum_trx_id = $request->pum_trx_id;
        $trx_type   = $request->trx_type;
        $amount     = $request->amount;
        $description= $request->description;
        $store_code = $request->store_code;
        $image      = $request->image;
        $kodeResp   = $request->kode;

        for ($i = 0; $i < sizeof($trx_type); $i++){
            $model      = new ResponsePum();
            $getDataPum = $model->getTrxAll($pum_trx_id,$emp_id);

            if ($getDataPum == null){
                return response()->json(['error' => false, 'message' => "No Data"], 200);
            }

            $getDataPum = $getDataPum[0];
            $trx_num    = $model->getRespTrxNum($pum_trx_id,$getDataPum->TRX_NUM);
            $date       = date('Y-m-d', strtotime(Carbon::today()));

            if ($kodeResp == 1){
                $status = 'P';
            } else {
                $status = 'F';
            }

            $data                   = new resp_trx_all();
            $data->pum_resp_trx_num = $trx_num;
            $data->pum_trx_id       = $request->pum_trx_id;
            $data->resp_date        = $date;
            $data->resp_status      = $status;
            $data->created_by       = $emp_id;
            $data->creation_date    = $date;
            $data->approval_emp_id1 = $getDataPum->APPROVAL_EMP_ID1;
            $data->approval_date1   = $getDataPum->APPROVAL_DATE1;
            $data->approval_emp_id2 = $getDataPum->APPROVAL_EMP_ID2;
            $data->approval_date2   = $getDataPum->APPROVAL_DATE2;
            $data->approval_emp_id3 = $getDataPum->APPROVAL_EMP_ID3;
            $data->approval_date3   = $getDataPum->APPROVAL_DATE3;
            $data->save();

            $getLineNum     = $model->getLineNum($pum_trx_id);
            $resp_trx_id    = $model->getRespTrxId($emp_id);
            $trx_line_id    = $model->getTrxLineId($pum_trx_id);
            $trx_line_id    = $trx_line_id[0]->{'PUM_TRX_LINE_ID'};

            $data                   = new resp_trx_lines_all();
            $data->pum_resp_trx_id  = $resp_trx_id;
            $data->pum_trx_line_id  = $trx_line_id;
            $data->line_num         = $getLineNum;
            $data->pum_resp_trx_type_id = $trx_type[$i];
            $data->description      = $description[$i];
            $data->amount           = $amount[$i];
            $data->store_code       = $store_code[$i];
            $data->save();

            $model->updateRespStatus($pum_trx_id,$status);
            $model->updateDataAmount($pum_trx_id,$amount[$i]);
        }
        return response()->json(['error'=>false, 'message' => "Success"], 200);
    }
}

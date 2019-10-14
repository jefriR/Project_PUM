<?php

namespace App\Http\Controllers\API;

use App\ResponsePum;
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

}

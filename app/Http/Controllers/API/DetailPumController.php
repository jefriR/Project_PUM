<?php

namespace App\Http\Controllers\API;

use App\DetailPum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DetailPumController extends Controller
{
    public function detailPum(Request $request){
        $validator = Validator::make($request->all(), [
            'pum_trx_id'      => ' string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $pum_trx_id = $request->pum_trx_id;
        $model      = new DetailPum();
        $getDataPum = $model->getDataPum($pum_trx_id);

        if ($getDataPum == null){
            return response()->json(['error' => false, 'message' => "No Data"], 200);
        } else {
            return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getDataPum], 200);
        }
    }

    public function summaryPum(Request $request){
        $validator = Validator::make($request->all(), [
            'pum_trx_id'      => ' string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $pum_trx_id = $request->pum_trx_id;
        $model      = new DetailPum();
        $getDataPum = $model->getSummaryPum($pum_trx_id);

        if ($getDataPum == null){
            return response()->json(['error' => false, 'message' => "No Data"], 200);
        } else {
            return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getDataPum], 200);
        }

    }
}


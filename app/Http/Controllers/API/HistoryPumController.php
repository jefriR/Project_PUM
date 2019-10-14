<?php

namespace App\Http\Controllers\API;

use App\HistoryPum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HistoryPumController extends Controller
{
    public function historyCreatePum(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id'        => 'required | string',
            'status'        => 'string',
            'start_date'    => 'date',
            'finish_date'   => 'date',

        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_id = $request->emp_id;
        if ($request->status != null){
            $status     = $request->status;
            $start_date = $request->start_date;
            $end_date   = $request->end_date;
        } else {
            $status = 'A';
            $start_date = date('Y-m-d',mktime(0, 0, 0, date("m")-3, date("d"), date("Y")));
            $end_date   = date('Y-m-d');
        }


        $model  = new HistoryPum();
        $data   = $model->historyCreatePum($emp_id,$status,$start_date,$end_date);

        if ($data == null) {
            return response()->json(['error' => false, 'message' => "No Data"], 200);
        } else {
            return response()->json(['error' => false, 'message' => "Data Available", 'data' => $data], 200);
        }
    }

    public function historyApprovalPum(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id'        => 'required | string',
            'status'        => 'string',
            'start_date'    => 'date',
            'finish_date'   => 'date',

        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_id = $request->emp_id;
        if ($request->status != null){
            $status     = $request->status;
            $start_date = $request->start_date;
            $end_date   = $request->end_date;
        } else {
            $status = 'APP';
            $start_date = date('Y-m-d',mktime(0, 0, 0, date("m")-3, date("d"), date("Y")));
            $end_date   = date('Y-m-d');
        }


        $model  = new HistoryPum();
        $data   = $model->historyAppPum($emp_id,$status,$start_date,$end_date);

        if ($data == null) {
            return response()->json(['error' => false, 'message' => "No Data"], 200);
        } else {
            return response()->json(['error' => false, 'message' => "Data Available", 'data' => $data], 200);
        }
    }

}

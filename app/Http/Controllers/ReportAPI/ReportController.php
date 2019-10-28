<?php

namespace App\Http\Controllers\ReportAPI;

use App\ReportPum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;


class ReportController extends Controller
{
    public function getDeptReport(){
        $model      = new ReportPum();
        $getDept    = $model->getDept();

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getDept], 200);
    }

    public function getEmpReport(){
        $model      = new ReportPum();
        $getEmp     = $model->getEmp();

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getEmp], 200);
    }

//    public function prosesReport(Request $request){
//       $validator = Validator::make($request->all(), [
//            'report_type'           => 'required',
//            'emp_id'                => 'required',
//            'dept_id'               => 'required',
//            'create_start_date'     => 'required',
//            'create_end_date'       => 'required',
//            'validate_start_date'   => 'required',
//            'validate_end_date'     => 'required',
//            'pum_status'            => 'required',
//            'response_status'       => 'required',
//            'detail_report'         => 'required',
//            'group_by'              => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
//        }
//
//        $report_type        = $request->report_type;
//        $emp_id             = $request->emp_id;
//        $dept_id            = $request->dept_id;
//        $create_start_date  = $request->create_start_date;
//        $create_end_date    = $request->create_end_date;
//        $pum_status         = $request->pum_status;
//        $resp_status        = $request->resp_status;
//        $validate_start_date = $request->valdate_start_date;
//        $valdiate_end_date  = $request->valdiate_end_date;
////        $detail_report      = $request->detail_report;
////        $group_by           = $request->group_by;
//
//        if ($pum_status == 'ALL') {
//            $pum_status = ['N', 'APP1', 'APP2', 'APP3', 'APP4', 'A', 'I'];
//        }
//        if ($resp_status == 'ALL'){
//            $resp_status = ['N','F','P','I'];
//        }
//
//        $model      = new ReportPum();
//        $prosesData = $model->prosesData($emp_id, $dept_id, $create_start_date, $create_end_date, $pum_status, $resp_status, $validate_start_date, $valdiate_end_date);
//
//        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $prosesData], 200);
//
//    }

}

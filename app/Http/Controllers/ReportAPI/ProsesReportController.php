<?php

namespace App\Http\Controllers\ReportAPI;

use App\ReportPum;
use Carbon\Carbon;
use Dompdf\Dompdf;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProsesReportController extends Controller
{
    public function prosesReport(Request $request){
        $validator = Validator::make($request->all(), [
            'report_type'           => 'required',
            'emp_id'                => 'required',
            'dept_id'               => 'required',
            'create_start_date'     => 'required',
            'create_end_date'       => 'required',
            'validate_start_date'   => 'required',
            'validate_end_date'     => 'required',
            'pum_status'            => 'required',
            'response_status'       => 'required',
            'detail_report'         => 'required',
            'group_by'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $report_type        = $request->report_type;
        $emp_id             = $request->emp_id;
        $dept_id            = $request->dept_id;
        $create_start_date  = $request->create_start_date;
        $create_end_date    = $request->create_end_date;
        $pum_status         = $request->pum_status;
        $resp_status        = $request->response_status;
        $validate_start_date = $request->validate_start_date;
        $valdiate_end_date  = $request->validate_end_date;
//        $detail_report      = $request->detail_report;
//        $group_by           = $request->group_by;

        if ($pum_status == 'ALL') {
            $pum_sts = ['N', 'APP1', 'APP2', 'APP3', 'APP4', 'A', 'I'];
        } else {
            $pum_sts = [$pum_status];
        }
        if ($resp_status == 'ALL'){
            $resp_sts = ['N','F','P','I'];
        } else {
            $resp_sts = [$resp_status];
        }

        $model  = new ReportPum();
        $user   = $model->findDataUser($emp_id, $dept_id);
        $temp   = null;
        $today  = date('d-m-Y');
        $temp   = [$create_start_date,$create_end_date,$validate_start_date,$valdiate_end_date, $pum_status, $resp_status, $today];

        if ($report_type == 1){
            $dataPum  = $model->permohonanPum($emp_id, $dept_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $valdiate_end_date);
        } elseif ($report_type == 2){
            $dataPum  = $model->responsePum($emp_id, $dept_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $valdiate_end_date);
        }

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $dataPum], 200);



        $pdf = PDF::loadview('permohonanPum',['datas'=>$dataPum, 'EMP_NAME'=>$user[0]->NAME, 'EMP_NUM'=>$user[0]->EMP_NUM, 'DEPT_CODE' => $user[1]->NAME, 'DEPT_NAME'=>$user[1]->DESCRIPTION, 'TEMP' => $temp]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('Reporting');
    }




}

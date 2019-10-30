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
            'org_id'                => 'required',
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
        $validate_end_date  = $request->validate_end_date;
//        $detail_report      = $request->detail_report;
//        $group_by           = $request->group_by;
        $org_id             = $request->org_id;

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

        if ($pum_status == 'N') {
            $pum_status = 'NEW';
        } else if ($pum_status == 'APP1') {
            $pum_status = 'APPROVAL 1';
        } else if ($pum_status == 'APP2') {
            $pum_status = 'APPROVAL 2';
        } else if ($pum_status == 'APP3') {
            $pum_status = 'APPROVAL 3';
        } else if ($pum_status == 'APP4') {
            $pum_status = 'APPROVAL 4';
        } else if ($pum_status == 'A') {
            $pum_status = 'APPROVED';
        } else if ($pum_status == 'I') {
            $pum_status = 'INVOICED';
        }

        if ($resp_status == 'N') {
            $resp_status = 'NEW';
        } else if ($resp_status == 'F') {
            $resp_status = 'FULL';
        } else if ($resp_status == 'P') {
            $resp_status = 'Partial';
        } else if ($resp_status == 'I') {
            $resp_status = 'INVOICED';
        }

        $model  = new ReportPum();
        $user   = $model->findDataUser($emp_id, $dept_id);
        $temp   = null;
        $today  = date('d-m-Y');
        $temp   = [$create_start_date,$create_end_date,$validate_start_date,$validate_end_date, $pum_status, $resp_status, $today];

        if ($report_type == 1){
            $dataPum  = $model->permohonanPum($emp_id, $dept_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $validate_end_date, $org_id);

            $pdf = PDF::loadview('permohonanPum',['datas'=>$dataPum, 'EMP_NAME'=>$user[0]->NAME, 'EMP_NUM'=>$user[0]->EMP_NUM, 'DEPT_CODE' => $user[1]->NAME, 'DEPT_NAME'=>$user[1]->DESCRIPTION, 'TEMP' => $temp]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('LISTING DATA PAPERLESS UMD');
        } elseif ($report_type == 2){
            $dataPum  = $model->responsePum($emp_id, $dept_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $validate_end_date, $org_id);

            $pdf = PDF::loadview('pertanggungjawabanPum',['datas'=>$dataPum, 'EMP_NAME'=>$user[0]->NAME, 'EMP_NUM'=>$user[0]->EMP_NUM, 'DEPT_CODE' => $user[1]->NAME, 'DEPT_NAME'=>$user[1]->DESCRIPTION, 'TEMP' => $temp]);
            $pdf->setPaper('A4', 'potrait');
            return $pdf->download('LISTING DATA PAPERLESS UMD');
        }

//        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $dataPum], 200);




    }




}

<?php

namespace App\Http\Controllers\ReportAPI;

use App\ReportPum;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProsesReportController extends Controller
{
    public function prosesReport(Request $request){
        $validator = Validator::make($request->all(), [
            'report_type'           => 'required',
            'user_id'               => 'required',
            'dept_id'               => 'required',
            'create_start_date'     => 'required',
            'create_end_date'       => 'required',
            'validate_start_date'   => 'required',
            'validate_end_date'     => 'required',
            'pum_status'            => 'required',
            'response_status'       => 'required',
            'group_by'              => 'required',
            'org_id'                => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $report_type        = $request->report_type;
        $user_id            = $request->user_id;
        $dept_id            = $request->dept_id;
        $create_start_date  = $request->create_start_date;
        $create_end_date    = $request->create_end_date;
        $pum_status         = $request->pum_status;
        $resp_status        = $request->response_status;
        $validate_start_date = $request->validate_start_date;
        $validate_end_date  = $request->validate_end_date;
        $group_by           = $request->group_by;
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
        $user   = $model->findDataUser($user_id, $dept_id);
        $temp   = null;
        date_default_timezone_set('Asia/Jakarta');
        $date   = date('d-m-Y');
        $time   = date('H:i:s');
        $temp   = [$create_start_date,$create_end_date,$validate_start_date,$validate_end_date, $pum_status, $resp_status, $date,$time];

        if ($report_type == 1){
            switch ($group_by) {
                case '-' :
                    $dataPum  = $model->permohonanPumNonGroup($user_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $validate_end_date, $org_id);
                    break;
                case 'E' :
                    $dataPum  = $model->permohonanPumGroupEmp($user_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $validate_end_date, $org_id);
                    break;
                case 'D' :
                    $dataPum  = $model->permohonanPumGroupDept($user_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $validate_end_date, $org_id);
                    break;
                case 'C' :
                    $dataPum  = $model->permohonanPumGroupDate($user_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $validate_end_date, $org_id);
                    break;
                default:
                    return 'ERROR';
            }


            $pdf = PDF::loadview('permohonanPum',['datas'=>$dataPum, 'EMP_NAME'=>$user[0]->NAME, 'EMP_NUM'=>$user[0]->EMP_NUM, 'DEPT_CODE' => $user[1]->NAME, 'DEPT_NAME'=>$user[1]->DESCRIPTION, 'TEMP' => $temp, 'GROUP' =>$group_by]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('Report.pdf');
        } elseif ($report_type == 2){
            $dataPum  = $model->responsePum($user_id, $dept_id, $create_start_date, $create_end_date, $pum_sts, $resp_sts, $validate_start_date, $validate_end_date, $org_id);

            $pdf = PDF::loadview('pertanggungjawabanPum',['datas'=>$dataPum, 'EMP_NAME'=>$user[0]->NAME, 'EMP_NUM'=>$user[0]->EMP_NUM, 'DEPT_CODE' => $user[1]->NAME, 'DEPT_NAME'=>$user[1]->DESCRIPTION, 'TEMP' => $temp]);
            $pdf->setPaper('A4', 'potrait');
            $dt = $pdf->download('LISTING DATA PAPERLESS UMD');

            return response()->json(['error' => false, 'message' => 'PROCESS SUCCESS', 'data' => $dt], 200);
        }
    }




}

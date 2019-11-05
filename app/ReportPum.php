<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReportPum extends Model
{
    public function getDept(){
        $dept   = DB::connection('api_pum')->table('pum_trx_all as a')->select('b.DEPT_ID', 'b.NAME', 'b.DESCRIPTION')
            ->leftJoin('api_hr.hr_department as b', 'b.DEPT_ID', 'a.DEPT_ID')
            ->get()->toArray();

        return $dept;
    }

    public function getEmp(){
        $emp = DB::connection('api_pum')->table('pum_trx_all as a')->select('a.DEPT_ID', 'b.NAME')
            ->leftJoin('api_hr.hr_employees as b', 'b.EMP_ID', 'a.EMP_ID')
            ->get()->toArray();

        return $emp;
    }

    public function findDataUser($user_id, $dept_id){
        $getEmpId   = DB::connection('api_sys')->table('sys_user')->select('EMP_ID')->where('USER_ID', $user_id)->get()->toArray();
        $empData    = DB::connection('api_hr')->table('hr_employees')->select('EMP_NUM', 'NAME')->where('EMP_ID', $getEmpId[0]->EMP_ID)->get()->toArray();
        $deptData   = DB::connection('api_hr')->table('hr_departments')->select('DESCRIPTION', 'NAME')->where('DEPT_ID', $dept_id)->get()->toArray();
        $dataUser   = [$empData[0],$deptData[0]];

        return $dataUser;
    }

    public function cekAppName($temp){
        $data = $temp;
        $appId = null;

        foreach ($data as $app){
            for ($i = 1; $i < 5; $i++){
                if ($i == 1) {
                    $appId = $app->APPROVAL_EMP_ID1;
                } else if ($i == 2) {
                    $appId = $app->APPROVAL_EMP_ID2;
                } else if ($i == 3) {
                    $appId = $app->APPROVAL_EMP_ID3;
                } else if ($i == 4) {
                    $appId = $app->APPROVAL_EMP_ID4;
                }

                $search = DB::connection('api_hr')->table('hr_employees')
                    ->select('NAME')
                    ->where('EMP_ID', $appId)
                    ->get()->toArray();

                if ($search != null){
                    if ($i == 1) {
                        $app->APPROVAL_EMP_ID1 = $search[0]->NAME;
                    } else if ($i == 2) {
                        $app->APPROVAL_EMP_ID2 = $search[0]->NAME;
                    } else if ($i == 3) {
                        $app->APPROVAL_EMP_ID3 = $search[0]->NAME;
                    } else if ($i == 4) {
                        $app->APPROVAL_EMP_ID4 = $search[0]->NAME;
                    }
                }
            }
        }

        return $data;
    }

    public function permohonanPumNonGroup($user_id, $create_start_date, $create_end_date, $pum_status, $resp_status, $validate_start_date, $validate_end_date, $org_id){
        $search   = DB::connection('api_pum')->table('pum_trx_all as a')
            ->select('a.*','a.TRX_NUM as PUM_NUM', 'c.EMP_NUM as EMP_NUM', 'c.NAME as EMP_NAME', 'd.DESCRIPTION as DESC_PUM', 'd.AMOUNT as AMOUNT')
            ->leftJoin('api_hr.hr_employees as c', 'c.EMP_ID', 'a.EMP_ID')
            ->leftJoin('pum_trx_lines_all as d', 'd.PUM_TRX_ID', 'a.PUM_TRX_ID')
            ->where('a.ORG_ID', $org_id)
            ->where('a.CREATED_BY', $user_id)
            ->whereBetween('a.TRX_DATE', [$create_start_date, $create_end_date])
            ->whereBetween('a.FINAL_DATE', [$validate_start_date, $validate_end_date])
            ->whereIn('a.PUM_STATUS', $pum_status)
            ->whereIn('a.RESP_STATUS', $resp_status)
            ->orderBy('a.EMP_ID')
            ->get()->toArray();

        foreach ($search as $data){
            $result = $this->cekAppName($search);
        }

        return $result;
    }

    public function permohonanPum($user_id, $create_start_date, $create_end_date, $pum_status, $resp_status, $validate_start_date, $validate_end_date, $org_id){
        $getEmp = DB::connection('api_pum')->table('pum_trx_all as a')
            ->selectRaw("distinct b.NAME as EMP_NAME, b.EMP_NUM as EMP_NUM, a.EMP_ID as EMP_ID, '' as dataReport")
            ->leftJoin('api_hr.hr_employees as b', 'b.EMP_ID', 'a.EMP_ID')
            ->where('a.CREATED_BY', $user_id)
            ->get()->toArray();

        foreach ($getEmp as $data){
            $search   = DB::connection('api_pum')->table('pum_trx_all as a')
                ->select('a.*','a.TRX_NUM as PUM_NUM', 'c.EMP_NUM as EMP_NUM', 'c.NAME as EMP_NAME', 'd.DESCRIPTION as DESC_PUM', 'd.AMOUNT as AMOUNT')
                ->leftJoin('api_hr.hr_employees as c', 'c.EMP_ID', 'a.EMP_ID')
                ->leftJoin('pum_trx_lines_all as d', 'd.PUM_TRX_ID', 'a.PUM_TRX_ID')
                ->where('a.EMP_ID', $data->EMP_ID)
                ->where('a.ORG_ID', $org_id)
                ->where('a.CREATED_BY', $user_id)
                ->whereBetween('a.TRX_DATE', [$create_start_date, $create_end_date])
                ->whereBetween('a.FINAL_DATE', [$validate_start_date, $validate_end_date])
                ->whereIn('a.PUM_STATUS', $pum_status)
                ->whereIn('a.RESP_STATUS', $resp_status)
                ->orderBy('a.EMP_ID')
                ->get()->toArray();

            $result = $this->cekAppName($search);
            $data->dataReport = $result;
        }

        return $getEmp;
    }

    public function responsePum($emp_id, $dept_id, $create_start_date, $create_end_date, $pum_status, $resp_status, $validate_start_date, $validate_end_date, $org_id){
        $data   = DB::connection('api_pum')->table('pum_resp_trx_all as a')
            ->select('a.PUM_RESP_TRX_NUM', 'a.RESP_STATUS', 'a.CREATION_DATE', 'b.AMOUNT')
            ->leftJoin('pum_resp_trx_lines_all as b',  'b.PUM_RESP_TRX_ID', 'a.PUM_RESP_TRX_ID')
            ->leftJoin('pum_trx_all as c', 'c.PUM_TRX_ID', 'a.PUM_TRX_ID')
            ->where('c.EMP_ID', $emp_id)
            ->where('c.DEPT_ID', $dept_id)
            ->where('c.ORG_ID', $org_id)
            ->whereBetween('c.TRX_DATE', [$create_start_date, $create_end_date])
            ->whereIn('c.PUM_STATUS', $pum_status)
            ->whereIn('c.RESP_STATUS', $resp_status)
            ->whereIn('a.RESP_STATUS', ['I','A'])
            ->whereBetween('c.FINAL_DATE', [$validate_start_date, $validate_end_date])
            ->get()->toArray();

        return $data;
    }
}


/*
 SELECT *
FROM `pum_trx_all` as a
LEFT JOIN `history_app_pums` as b ON b.pum_trx_id = a.pum_trx_id
WHERE a.emp_id = 33287
and a.dept_id = 1000
and a.trx_date BETWEEN '2019-10-01' AND '2019-10-21'
and a.pum_status = 'A',
and a.resp_status	= 'N'
and b.created_at BETWEEN '2019-10-01' AND '2019-10-21'
 * */
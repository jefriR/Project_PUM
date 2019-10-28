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

    public function prosesData($emp_id, $dept_id, $create_start_date, $create_end_date, $pum_status, $resp_status, $validate_start_date, $valdiate_end_date){
        $data   = DB::connection('api_pum')->table('pum_trx_all as a')
            ->select('a.*')
            ->leftJoin('history_app_pums as b', 'b.PUM_TRX_ID', 'a.PUM_TRX_ID')
            ->where('a.EMP_ID', $emp_id)
            ->where('a.DEPT_ID', $dept_id)
            ->whereBetween('a.TRX_DATE', [$create_start_date, $create_end_date])
            ->where('a.PUM_STATUS', $pum_status)
            ->where('a.RESP_STATUS', $resp_status)
            ->whereBetween('b.CREATED_AT', [$validate_start_date, $valdiate_end_date])
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
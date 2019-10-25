<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HistoryPum extends Model
{
    public function historyCreatePum($emp_id, $status, $start_date, $end_date){
        $data   = DB::connection('api_pum')->table('pum_trx_all')->select("PUM_TRX_ID", "TRX_NUM", "TRX_DATE", "PUM_STATUS")
            ->where('EMP_ID', $emp_id)
            ->whereIn('PUM_STATUS', $status)
            ->whereBetween('created_at', [$start_date,$end_date])
            ->orderByDesc('TRX_DATE')->get()->toArray();

        return $data;
    }

    public function historyAppPum($emp_id, $status, $start_date, $end_date){
        $data   = DB::connection('api_pum')->table('history_app_pums as hap')
            ->select('hap.created_at as ACTIONDATE','hap.STATUS', 'pta.PUM_TRX_ID', 'pta.TRX_NUM', 'pta.TRX_DATE', 'hr_emp.NAME as USERNAME', 'hr_dept.DESCRIPTION as DEPARTMENT')
            ->leftJoin('pum_trx_all as pta', 'pta.PUM_TRX_ID', 'hap.PUM_TRX_ID')
            ->leftJoin('pum_trx_lines_all as ptla', 'ptla.PUM_TRX_ID', 'hap.PUM_TRX_ID')
            ->leftJoin('api_hr.hr_employees as hr_emp', 'hr_emp.EMP_ID', 'pta.EMP_ID')
            ->leftJoin('api_hr.hr_departments as hr_dept', 'hr_dept.DEPT_ID', 'pta.DEPT_ID')
            ->where('hap.EMP_ID', $emp_id)
            ->whereIn('hap.STATUS', $status)
            ->whereBetween('hap.created_at', [$start_date,$end_date])
            ->orderByDesc('hap.created_at')->get()->toArray();

        return $data;
    }
}

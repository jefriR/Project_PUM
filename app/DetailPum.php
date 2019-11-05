<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailPum extends Model
{
    public function getDataPum($pum_trx_id){
        $getDataPum = DB::connection('api_pum')->table('pum_trx_all as pum_pta')
            ->select("pum_pta.PUM_TRX_ID", "pum_pta.TRX_NUM", "pum_pta.TRX_DATE", "pum_pta.USE_DATE", "pum_pta.EMP_ID", "pum_pta.RESP_ESTIMATE_DATE", "pum_pta.UPLOAD_DATA",
                "pum_ptla.PUM_TRX_TYPE_ID", "pum_ptla.DESCRIPTION", "pum_ptla.AMOUNT", "hr_emp.EMP_NUM", "hr_emp.NAME", "hr_dept.DESCRIPTION as DEPARTMENT", "hr_dept.DEPT_ID as DATA_APP")
            ->leftJoin('pum_trx_lines_all as pum_ptla', 'pum_pta.PUM_TRX_ID','pum_ptla.PUM_TRX_ID')
            ->leftJoin('api_hr.hr_employees as hr_emp', 'hr_emp.EMP_ID', 'pum_pta.EMP_ID')
            ->leftJoin('api_hr.hr_departments as hr_dept', 'hr_dept.DEPT_ID', 'pum_pta.DEPT_ID')
            ->where('pum_pta.pum_trx_id', $pum_trx_id)
            ->get()->toArray();

        $getNameTrxType = DB::connection('api_pum')->table('pum_trx_type_all')->select('DESCRIPTION')->where('PUM_TRX_TYPE_ID', $getDataPum[0]->PUM_TRX_TYPE_ID)->get()->toArray();
        $getDataPum[0]->PUM_TRX_TYPE_ID = $getNameTrxType[0];

        if ($getDataPum == null){
            return $getDataPum;
        }

        $getDataPum = $getDataPum[0];
        $getAppId   = $this->getAppId($getDataPum->EMP_ID,$getDataPum->AMOUNT);

        $data1 = $getAppId[0];
        $data2 = $getAppId[1];
        $getNameApp1    = DB::connection('api_hr')->table('hr_employees')->select("EMP_ID", "NAME")->where('EMP_ID', $data1->APPROVAL_EMP_ID1)->orWhere('EMP_ID', $data2->APPROVAL_EMP_ID1)->get()->toArray();
        $getNameApp2    = DB::connection('api_hr')->table('hr_employees')->select("EMP_ID", "NAME")->where('EMP_ID', $data1->APPROVAL_EMP_ID2)->orWhere('EMP_ID', $data2->APPROVAL_EMP_ID2)->get()->toArray();
        $getNameApp3    = DB::connection('api_hr')->table('hr_employees')->select("EMP_ID", "NAME")->where('EMP_ID', $data1->APPROVAL_EMP_ID3)->orWhere('EMP_ID', $data2->APPROVAL_EMP_ID3)->get()->toArray();
        $getNameApp4    = DB::connection('api_hr')->table('hr_employees')->select("EMP_ID", "NAME")->where('EMP_ID', $data1->APPROVAL_EMP_ID4)->orWhere('EMP_ID', $data2->APPROVAL_EMP_ID4)->get()->toArray();
        $getNameApp5    = DB::connection('api_hr')->table('hr_employees')->select("EMP_ID", "NAME")->where('EMP_ID', $data1->APPROVAL_EMP_ID5)->orWhere('EMP_ID', $data2->APPROVAL_EMP_ID5)->get()->toArray();
        $temp = ['App_1' => $getNameApp1, 'App_2' => $getNameApp2, 'App_3' => $getNameApp3, 'App_4' => $getNameApp4, 'App_5' => $getNameApp5];

        $getDataPum->DATA_APP = $temp;

        return $getDataPum;
    }

    public function getAppId($emp_id, $amount){
        $getAppId   = DB::connection('api_pum')->table('pum_app_hierar')->select('*')
            ->where('EMP_ID', $emp_id)
            ->whereRaw("? BETWEEN PROXY_AMOUNT_FROM AND PROXY_AMOUNT_TO", [$amount])
            ->where('ACTIVE_FLAG', 'Y')
            ->get()->toArray();

        return $getAppId;
    }

    public function getSummaryPum($pum_trx_id){
        $getDataPum = DB::connection('api_pum')->table('pum_trx_all as pum_pta')->select('*')
            ->leftJoin('pum_trx_lines_all as pum_ptla','pum_ptla.PUM_TRX_ID', 'pum_pta.PUM_TRX_ID')
            ->where('pum_pta.PUM_TRX_ID', $pum_trx_id)
            ->get()->toArray();

        return $getDataPum;
    }
}

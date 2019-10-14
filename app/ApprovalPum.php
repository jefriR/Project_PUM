<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApprovalPum extends Model
{
    public function getListPum($app_id){
        $datas  = null;
        $result = null;

        $search = DB::connection('api_pum')->table('pum_trx_all')->select('pum_trx_all.pum_trx_id', 'pum_trx_all.emp_id', 'pum_trx_all.pum_status', 'pum_ptla.amount')
            ->leftJoin('pum_trx_lines_all as pum_ptla', 'pum_trx_all.pum_trx_id', 'pum_ptla.pum_trx_id')
            ->whereIn('pum_trx_all.pum_status',['N','APP1','APP2','APP3','APP4'])
            ->get()->toArray();

        foreach ($search as $data){
            $columntemp   = 'approval_emp_id1';
            if($data->pum_status == 'N') {
                $columntemp = 'approval_emp_id1';
            } elseif ($data->pum_status == 'APP1'){
                $columntemp = 'approval_emp_id2';
            } elseif ($data->pum_status == 'APP2'){
                $columntemp = 'approval_emp_id3';
            } elseif ($data->pum_status == 'APP3'){
                $columntemp = 'approval_emp_id4';
            } elseif ($data->pum_status == 'APP4'){
                $columntemp = 'approval_emp_id5';
            }

            $findAppId  = DB::connection('api_pum')->table('pum_app_hierar')->select('*')
                ->where('EMP_ID', $data->emp_id)
                ->where('ACTIVE_FLAG', 'Y')
                ->whereRaw("? BETWEEN PROXY_AMOUNT_FROM AND PROXY_AMOUNT_TO", [$data->amount])
                ->where($columntemp, $app_id)
                ->get()->toArray();

            if ($findAppId != null) {
                $datas[] = $data->pum_trx_id;
            }
        }

        if ($datas == null) {
            return 1;
        } else {
            foreach ($datas as $data){
                $searchDataPum  = DB::connection('api_pum')->table('pum_trx_all')->select('pum_trx_all.PUM_TRX_ID', 'pum_trx_all.TRX_NUM', 'hr_e.NAME', 'pum_trx_all.TRX_DATE', 'pum_ptla.AMOUNT')
                    ->leftJoin('api_hr.hr_employees as hr_e', 'hr_e.EMP_ID', 'pum_trx_all.EMP_ID')
                    ->leftJoin('pum_trx_lines_all as pum_ptla', 'pum_ptla.pum_trx_id', 'pum_trx_all.pum_trx_id')
                    ->where('pum_trx_all.pum_trx_id', $data)
                    ->get()->toArray();

                $result[] = $searchDataPum[0];
            }
            return $result;
        }
    }

    public function getDataPum($pum_id){
        $dataPum    =    DB::connection('api_pum')->table('pum_trx_all')->select("PUM_TRX_ID", "EMP_ID", "PUM_STATUS")->where("PUM_TRX_ID", $pum_id)->get()->toArray();

        return $dataPum;
    }

    public function rejectPum($pum_id, $columntemp, $app_id, $columndate, $date, $reason_val){
        DB::table('PUM_TRX_ALL')->where('PUM_TRX_ID', $pum_id)->update(['PUM_STATUS' => 'R', $columntemp => $app_id, $columndate => $date, 'REASON_APPROVE' => $reason_val]);
    }

    public function approvePum($pum_id, $columntemp, $app_id, $columndate, $date, $pum_status){
        DB::table('PUM_TRX_ALL')->where('PUM_TRX_ID', $pum_id)->update(['PUM_STATUS' => $pum_status, $columntemp => $app_id, $columndate => $date]);
    }

    public function checkFinalApp($pum_id, $nextApp){
        $cekFinal = DB::connection('api_pum')->table('pum_app_hierar')->select('pum_app_hierar.'.$nextApp)
            ->leftJoin('pum_trx_all as pum_pta', 'pum_pta.emp_id', 'pum_app_hierar.emp_id')
            ->leftJoin('pum_trx_lines_all as pum_ptla', 'pum_ptla.pum_trx_id', 'pum_pta.pum_trx_id')
            ->where('pum_pta.PUM_TRX_ID', $pum_id)
            ->where('pum_app_hierar.ACTIVE_FLAG', 'Y')
            ->whereRaw("? BETWEEN PROXY_AMOUNT_FROM AND PROXY_AMOUNT_TO", ['pum_ptla.AMOUNT'])
            ->get()->toArray();

        return $cekFinal;
    }




}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResponsePum extends Model
{
    public function getDataPum($emp_id){
        $getPum = DB::connection('api_pum')->table('pum_trx_all as pum_pta')->select('EMP_ID', 'pum_pta.PUM_TRX_ID', 'TRX_NUM', 'PO_NUMBER', 'RESP_STATUS',
            'pum_ptla.PUM_TRX_TYPE_ID', 'pum_ptla.AMOUNT', 'pum_ptla.AMOUNT_REMAINING', 'pum_ptla.DESCRIPTION', 'DEPT_ID as TRX_TYPE')
            ->leftJoin('pum_trx_lines_all as pum_ptla', 'pum_ptla.pum_trx_id', 'pum_pta.pum_trx_id')
            ->where('pum_pta.EMP_ID', $emp_id)
            ->get()->toArray();

        return $getPum;
    }

    public function getTrxType($pum_trx_type_id){
        $trxType    = DB::connection('api_pum')->table('pum_resp_trx_types_all')->select('PUM_RESP_TRX_TYPE_ID', 'NAME', 'DESCRIPTION', 'CLEARING_ACCOUNT')->where('PUM_TRX_TYPE_ID', $pum_trx_type_id)->get()->toArray();

        return $trxType;
    }

}

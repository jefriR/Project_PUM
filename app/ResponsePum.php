<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResponsePum extends Model
{
    public function getDataPum($emp_id){
        $getPum = DB::connection('api_pum')->table('pum_trx_all as pum_pta')->select('EMP_ID', 'pum_pta.PUM_TRX_ID', 'TRX_NUM', 'PO_NUMBER', 'RESP_STATUS',
            'pum_ptla.PUM_TRX_TYPE_ID', 'pum_ptta.DESCRIPTION as TRX_TYPE_DESCRIPTION', 'pum_ptla.AMOUNT', 'pum_ptla.AMOUNT_REMAINING', 'pum_ptla.DESCRIPTION', 'DEPT_ID as TRX_TYPE')
            ->leftJoin('pum_trx_lines_all as pum_ptla', 'pum_ptla.pum_trx_id', 'pum_pta.pum_trx_id')
            ->leftJoin('pum_trx_types_all as pum_ptta', 'pum_ptta.PUM_TRX_TYPE_ID', 'pum_ptla.PUM_TRX_TYPE_ID')
            ->where('pum_pta.EMP_ID', $emp_id)
            ->where('pum_pta.PUM_STATUS', 'I')
            ->get()->toArray();

        return $getPum;
    }

    public function getStoreCode($org_id){
        $storeCode  = DB::connection('api_sys')->table('sys_store_code')->select('STORE_CODE', 'STORE_NAME')
            ->where('ORG_ID', $org_id)
            ->where('ACTIVE_FLAG', 'Y')
            ->get()->toArray();

        return $storeCode;
    }

    public function getTrxType($pum_trx_type_id){
        $trxType    = DB::connection('api_pum')->table('pum_resp_trx_types_all')->select('PUM_RESP_TRX_TYPE_ID', 'NAME', 'DESCRIPTION', 'CLEARING_ACCOUNT')->where('PUM_TRX_TYPE_ID', $pum_trx_type_id)->get()->toArray();

        return $trxType;
    }

    public function getTrxAll($pum_trx_id, $emp_id){
        $getDataPum = DB::connection('api_pum')->table('pum_trx_all')->select('*')->where('PUM_TRX_ID', $pum_trx_id)->where('EMP_ID', $emp_id)->get()->toArray();

        return $getDataPum;
    }

    public function getRespTrxId($emp_id){
        $resp_trx_id = DB::connection('api_pum')->table('pum_resp_trx_all')->select('pum_resp_trx_id')->where('CREATED_BY', $emp_id)->max('pum_resp_trx_id');

        return $resp_trx_id;
    }

    public function getTrxLineId($pum_id){
        $trx_line_id    = DB::connection('api_pum')->table('pum_trx_lines_all')->select('PUM_TRX_LINE_ID')->where('PUM_TRX_ID', $pum_id)->get()->toArray();

        return $trx_line_id;
    }

    public function getRespTrxNum($pum_id, $trx_num){
        $respTrxNum = DB::connection('api_pum')->table('pum_resp_trx_all')->select('*')
            ->where('PUM_TRX_ID', $pum_id)->max('PUM_RESP_TRX_NUM');

        $temp   = substr($respTrxNum,(strlen($trx_num)+1));
        $temp   = $temp+1;

        return $trx_num.'_'.$temp;
    }

    public function getLineNum($pum_id){
        $lineNum    = DB::connection('api_pum')->table('pum_resp_trx_all as pum_prta')->select('*')
            ->leftJoin('pum_resp_trx_lines_all as pum_prtla', 'pum_prta.pum_resp_trx_id', 'pum_prtla.pum_resp_trx_id')
            ->where('pum_prta.PUM_TRX_ID', $pum_id)
            ->max('LINE_NUM');

        return $lineNum+1;
    }

    public function updateDataAmount($pum_id, $amount){
        $getAmountResp      = DB::connection('api_pum')->table('pum_trx_lines_all')->select('RESP_AMOUNT')->where('PUM_TRX_ID', $pum_id)->get()->toArray();
        $getAmountResp      = $getAmountResp[0]->{'RESP_AMOUNT'} + $amount;
        $getAmountRemaining = DB::connection('api_pum')->table('pum_trx_lines_all')->select('AMOUNT')->where('PUM_TRX_ID', $pum_id)->get()->toArray();
        $getAmountRemaining = $getAmountRemaining[0]->{'AMOUNT'} - $getAmountResp;

        DB::connection('api_pum')->table('pum_trx_lines_all')->where('pum_trx_id', $pum_id)->update(["RESP_AMOUNT"=>$getAmountResp, "AMOUNT_REMAINING" => $getAmountRemaining]);
    }

    public function updateRespStatus($pum_id,$status){
        DB::connection('api_pum')->table('pum_trx_all')->where('pum_trx_id', $pum_id)->update(["RESP_STATUS"=>$status]);
    }

    public function historyResponse($pum_id){
        $data   = DB::connection('api_pum')->table('pum_resp_trx_all as a')->select('a.PUM_RESP_TRX_NUM', 'a.RESP_DATE', 'b.AMOUNT')
            ->leftJoin('pum_resp_trx_lines_all as b', 'b.PUM_RESP_TRX_ID', 'a.PUM_RESP_TRX_ID')
            ->where('a.PUM_TRX_ID', $pum_id)
            ->get()->toArray();

        return $data;
    }

}

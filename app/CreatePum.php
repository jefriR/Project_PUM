<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreatePum extends Model
{
    public function cekAvailablePum($emp_id){
        $cekAvailable   = DB::connection('api_pum')->table('pum_trx_all')->select('*')->where('EMP_ID', $emp_id)->where('PUM_STATUS', '<>', 'R')->where('PUM_STATUS', '<>', 'I')->get()->toArray();
        $totalPum       = count($cekAvailable);

        return $totalPum;
    }

    public function getDepartment($org_id){
        $getDept    = DB::connection('api_hr')->table('hr_departments')->select("DEPT_ID", "NAME", "DESCRIPTION")
            ->where('ORG_ID', $org_id)->get()->toArray();

        return $getDept;
    }

    public function getTrxType(){
        $trx    = DB::connection('api_pum')->table('pum_trx_types_all')->select("PUM_TRX_TYPE_ID", "DESCRIPTION")->where('ACTIVE_FLAG', 'Y')->get()->toArray();

        return $trx;
    }

    public function getDocDetail($docType){
      $document   = DB::table('pum_ref_doc_all')->select('doc_num', 'doc_date', 'doc_amount')->where('DOC_TYPE', $docType)->paginate(10);
    
        return $document;
    }

    public function getTrxNum(){
        $getTrxNum  = DB::connection('api_pum')->table('pum_trx_all')->select('TRX_NUM')->orderBy('PUM_TRX_ID')->get()->last();
        $getTrxNum  = $getTrxNum->{'TRX_NUM'};
        $substring  = substr($getTrxNum,4);
        $thisYear   = date('Y');
        $trx_num    = $thisYear.($substring+1);

        return $trx_num;
    }

    public function getPumTrxId($emp_id){
        $pumTrxId = DB::connection('api_pum')->table('pum_trx_all')->select("PUM_TRX_ID")->where('EMP_ID', $emp_id)->max('PUM_TRX_ID');

        return $pumTrxId;
    }



}



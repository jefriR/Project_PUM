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
}

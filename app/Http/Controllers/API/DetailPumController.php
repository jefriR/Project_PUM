<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DetailPumController extends Controller
{
    public function detailPum(Request $request){
        $validator = Validator::make($request->all(), [
            'pum_trx_id'      => ' string'
        ]);

//        if ($validator->fails()) {
//            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
//        }

//        $pum_trx_id = $request->pum_trx_id;

//        $getDataPum = DB::select(" SELECT a.pum_trx_id, a.trx_num, a.trx_date,a.use_date, a.emp_id, a.resp_estimate_date, a.upload_data, b.pum_trx_type_id, b.description,
//                                          b.amount, c.emp_num, c.name, d.description as department, '' as data_app
//                                            FROM `pum_trx_all` a
//                                            LEFT JOIN `pum_trx_lines_all` b ON  a.pum_trx_id = b.pum_trx_id
//                                            LEFT JOIN `hr_employees` c ON a.emp_id = c.emp_id
//                                            LEFT JOIN `hr_departments` d ON c.dept_id = d.dept_id
//                                            /*LEFT JOIN `pum_resp_trx_types_all` e ON b.pum_trx_type_id = e.pum_trx_type_id*/
//                                            WHERE a.pum_trx_id = '$request->pum_trx_id'");
//
        $getDataPum = DB::connection('api_pum')->table('pum_trx_all as pum_pta')
            ->leftJoin('pum_trx_lines_all as pum_ptla', 'pum_pta.PUM_TRX_ID','pum_ptla.PUM_TRX_ID')
            ->leftJoin('api_hr.hr_employees as hr_emp', 'hr_emp.EMP_ID', 'pum_pta.EMP_ID')
            ->leftJoin('api_hr.hr_departments as hr_dept', 'hr_dept.DEPT_ID', 'pum_pta.DEPT_ID')
            ->where('pum_pta.pum_trx_id', 7)
            ->get()->toArray();




        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getDataPum], 200);



    }
}

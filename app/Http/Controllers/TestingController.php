<?php

namespace App\Http\Controllers;

use App\Employees;
use App\User;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TestingController extends Controller
{
    public function testingpdf(){
        $data = DB::connection('api_hr')->table('hr_departments')->select('*')->where('NAME', 'DEV')->get()->toArray();

        $pdf = PDF::loadview('pertanggungjawabanPum',['datas'=>$data]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream();
//        return $pdf->download('data-pdf');
    }

    public function testarray(Request $request){
        $file = $request->file('image');
//        $name = $file->getClientOriginalName();
//        $path = $file->storeAs('public',$name);
//        $ph = Storage::disk('public_uploads')->put( $name);

        echo base_path(). '<br>';

// Path to the 'app' folder
        echo app_path(). '<br>';

// Path to the 'public' folder
        echo public_path(). '<br>';

// Path to the 'storage' folder
        echo storage_path(). '<br>';

// Path to the 'storage/app' folder
        echo storage_path('app'). '<br>';

        $get = url('public/storage/Koala.jpg');
        return $get;

    }


    public function testing(Request $request){

        $url = Storage::get('public/storage/login.png');

        return $url;
        dd('asd');



//        $nik    = DB::table('hr_employees')->select("emp_num")->where('name', 'SUGIANTO')->get();
//        $pinUser= DB::table('users')->select('pin')->where('emp_num',$nik[0]->emp_num)->get();


        DB::select("INSERT INTO PUM_UPLOAD_TEMP(TRX_ID, APPROVAL_ID) VALUES ( '1111', '1111')");
//        $cekApproval    =  \App\Http\Controllers\PumController\CekApprovalController::cekStatusPum(99216,1500000);
//        $cekApproval    =  app('App\Http\Controllers\PumContoller\CekApprovalController')->cekStatusPum(99216,1500000);
//            DB::select("SELECT * FROM PUM_APP_HIERAR WHERE EMP_ID = 99216 AND 1500000 BETWEEN PROXY_AMOUNT_FROM AND PROXY_AMOUNT_TO");

        $query   = DB::select("SELECT a.pum_trx_id, a.emp_id, b.amount, a.pum_status
                                    FROM `pum_trx_all` a 
                                    LEFT JOIN `pum_trx_lines_all` b ON a.pum_trx_id = b.pum_trx_id 
                                    WHERE a.PUM_STATUS IN ('N','APP1','APP2','APP3','APP4') ");

        foreach ($query as $data){
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

            $findPumId   = DB::select("SELECT * FROM `pum_app_hierar` 
                                    WHERE emp_id = '$data->emp_id'
                                    and active_flag = 'Y'
                                    AND '$data->amount' BETWEEN proxy_amount_from AND proxy_amount_to
                                    AND  $columntemp = 33337"); /*33337 / 99231*/

          if ($findPumId != null) {
              $datas[] = $data->pum_trx_id;
          }
        }

        foreach ($datas as $data){
            $result = DB::select("SELECT a.pum_trx_id, a.trx_num, b.name, a.trx_date, c.amount 
                                        FROM `pum_trx_all` a 
                                        LEFT JOIN `hr_employees` b on a.emp_id = b.emp_id 
                                        LEFT JOIN `pum_trx_lines_all` c on a.pum_trx_id = c.pum_trx_id 
                                        WHERE a.pum_trx_id= '$data'");

            $tempData[] = $result[0];
        }

        return response()->json(['error' => true,'message' => $tempData],200);

//dd("st");

    }
}

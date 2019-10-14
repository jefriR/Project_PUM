<?php

namespace App\Http\Controllers\API;

use App\ApprovalPum;
use App\historyAppPum;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ApprovalController extends Controller
{
    public function getListPum(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id'      => 'required | string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $app_id = $request->emp_id;
        $model  = new ApprovalPum();
        $getPum = $model->getListPum($app_id);

        if ($getPum == 1){
            return response()->json(['error' => false, 'message' => "Data Empty", 'data' => []], 200);
        } else {
            return response()->json(['error' => false, 'message' => "Data Available", 'data' => $getPum], 200);
        }
    }

    public function saveStatusApprovalPum($emp_id, $pum_id, $status){
        $data               = new historyAppPum();
        $data->emp_id       = $emp_id;
        $data->pum_trx_id   = $pum_id;
        $data->status       = $status;
        $data->save();
    }

    public function approvePum(Request $request) {
        $validator = Validator::make($request->all(), [
            'emp_id' => 'required | string',
            'pum_trx_id' => 'required | array',
            'pin' => 'required | string',
            'kode' => 'required | string',
            'reason_validate' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $date       = date('Y-m-d');
        $app_id     = $request->emp_id;
        $pum_trx_id = $request->pum_trx_id;
        $pin        = $request->pin;
        $kodeApp    = $request->kode;
        $reason_val = $request->reason_validate;

        // Cek PIN user sebelum Approve PUM
        $model  = new User();
        $pinUsr = $model->checkPin($app_id);

        $cekPin = password_verify($pin,$pinUsr[0]->PIN);
        if ($cekPin == false) {
            return response()->json(['error' => true, 'message' => "Pin Not Match"], 400);
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////

        //Looping sejumlah pum_id yg dikirim debgan bentuk array
        foreach ($pum_trx_id as $pum_id) {
            $model  = new ApprovalPum();
            $getDataPum = $model->getDataPum($pum_id);
            foreach ($getDataPum as $data) {
                $columntemp = 'approval_emp_id1';
                $columndate = 'approval_date1';
                if ($data->PUM_STATUS == 'N') {
                    $columntemp = 'approval_emp_id1';
                    $columndate = 'approval_date1';
                    $status = 'APP1';
                    $nextApp = "approval_emp_id2";
                } elseif ($data->PUM_STATUS == 'APP1') {
                    $columntemp = 'approval_emp_id2';
                    $columndate = 'approval_date2';
                    $status = 'APP2';
                    $nextApp = "approval_emp_id3";
                } elseif ($data->PUM_STATUS == 'APP2') {
                    $columntemp = 'approval_emp_id3';
                    $columndate = 'approval_date3';
                    $status = 'APP3';
                    $nextApp = "approval_emp_id4";
                } elseif ($data->PUM_STATUS == 'APP3') {
                    $columntemp = 'approval_emp_id4';
                    $columndate = 'approval_date4';
                    $status = 'APP4';
                    $nextApp = "approval_emp_id5";
                } elseif ($data->PUM_STATUS == 'APP4') {
                    $columntemp = 'approval_emp_id5';
                    $columndate = 'approval_date5';
                    $status = 'A';
                    $nextApp = "reason_validate"; // Pakai column reason_validate supya tidak error, karna reason_validate pasti kosong saati di approve sehingga status akan menjadi A
                }
            }

            // Cek Kode, apakah di Reject atau di Approve
            if ($kodeApp == 0) {
                $model  = new ApprovalPum();
                $reject = $model->rejectPum($pum_id,$columntemp,$app_id,$columndate,$date, $reason_val);

                $this->saveStatusApprovalPum($app_id,$pum_id,'R');
                return response()->json(['error' => false, 'message' => 'REJECT SUCCESS'], 200);

            } elseif ($kodeApp == 1) {
                // Cek apakah sudah final approve atau belum
                $cekFinal   = $model->checkFinalApp($pum_id, $nextApp);

                $flag = 0;
                foreach ($cekFinal as $data) {
                    if ($data->approval > 1) {
                        $flag = $flag + 1;
                    }
                }

                if ($flag == null) {
                    $Approve = $model->approvePum($pum_id,$columntemp,$app_id,$columndate,$date, 'A');
                    $this->saveStatusApprovalPum($app_id,$pum_id,'APP');
                } else {
                    $Approve = $model->approvePum($pum_id,$columntemp,$app_id,$columndate,$date, $status);
                    $this->saveStatusApprovalPum($app_id,$pum_id,'APP');
                }
            } else {
                return response()->json(['error' => true, 'message' => "ERROR"], 400);
            }
        }
        return response()->json(['error' => false, 'message' => 'APPROVAL SUCCESS'], 200);
    }



}



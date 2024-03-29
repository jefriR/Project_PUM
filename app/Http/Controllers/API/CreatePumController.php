<?php

namespace App\Http\Controllers\API;

use App\CreatePum;
use App\DetailPum;
use App\Http\Controllers\NotificationController;
use App\trx_all;
use App\trx_lines_all;
use App\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreatePumController extends Controller
{
    public function cekAvailablePum(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id'            => 'required | string',
            'max_create_pum'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_id     = $request->emp_id;
        $maxPum     = $request->max_create_pum;

        $model      = new CreatePum();
        $totalPum   = $model->cekAvailablePum($emp_id);

        if($totalPum == $maxPum) {
            return response()->json(['error'=>true, 'message' => "Not Available to Create New Pum"], 401);
        } elseif($totalPum < $maxPum) {
            return response()->json(['error' => false, 'message' => "Available to Create New Pum"], 200);
        } else {
            return response()->json(['error' => true,'message' => "Something's Error"],422);
        }
    }

    public function getDepartment(Request $request){
        $validator = Validator::make($request->all(), [
            'org_id'            => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $org_id     = $request->org_id;

        $model      = new CreatePum();
        $department = $model->getDepartment($org_id);

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $department], 200);
    }

    public function getTrxType(){
        $model      = new CreatePum();
        $trxType    = $model->getTrxType();

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $trxType], 200);

    }

    public function getDocDetail(Request $request){
        $validator = Validator::make($request->all(), [
            'doc_type'            => 'required | string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $docType    = $request->doc_type;

        $model      = new CreatePum();
        $docDetail  = $model->getDocDetail($docType);

        return response()->json(['error' => false, 'message' => "Data Available", 'data' => $docDetail], 200);
    }

    public  function createPum(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id'        => 'required | string',
            'user_id'       => 'required',
            'emp_dept'      => 'required',
            'use_date'      => 'required | date',
            'resp_date'     => 'required | date',
            'doc_num'       => 'required',
            'trx_type'      => 'required',
            'description'   => 'required',
            'pin'           => 'required',
            'amount'        => 'required',
            'org_id'        => 'required',
        ]);



        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }
        
        $emp_id         = $request->emp_id;
        $emp_dept       = $request->emp_dept;
        $use_date       = $request->use_date;
        $response_date  = $request->resp_date;
        $doc_num        = $request->doc_num;
        $trx_type       = $request->trx_type;
        $description    = $request->description;
        $amount         = $request->amount;
        $file_data      = $request->file('file_data');
        $pin            = $request->pin;
        $org_id         = $request->org_id;
        $user_id        = $request->user_id;

        $model          = new CreatePum();
        $insertTrx      = new trx_all();
        $insertTrxLines = new trx_lines_all();
        $user           = new User();

        //CekFormatFile
        if($file_data != null) {
            $ext    = $file_data->getClientOriginalExtension();
            if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'pdf'){
                return response()->json(['error' => true, 'message' => "Format File only .jpg, .jpeg, .png, .pdf"], 400);
            }
        }

        // Cek PIN user sebelum Create PUM
        $getPin = $user->checkPin($emp_id);
        $cekPin = password_verify($pin,$getPin[0]->PIN);
        if ($cekPin == false) {
            return response()->json(['error' => true, 'message' => "Pin Not Match"], 400);
        }

        if ($doc_num == '-') {
            $pumStatus = 'N';
        } else {
            $pumStatus = 'A';
        }

        //Rename Image's Name For upload_data
        $trxNum         = $model->getTrxNum();
        if($file_data != null) {
            $upload_data    = $trxNum.'_0';
            $destination    = public_path();
            $getFileName    = $file_data->getClientOriginalName();
            $file_data->move($destination, $getFileName);
        } else {
            $upload_data    = '';
            $getFileName    = '';
        }

        $insertTrx->trx_num          = $trxNum;
        $insertTrx->trx_date         = date('Y-m-d');
        $insertTrx->emp_id           = $emp_id;
        $insertTrx->dept_id          = $emp_dept;
        $insertTrx->po_number        = $doc_num;
        $insertTrx->use_date         = $use_date;
        $insertTrx->resp_estimate_date = $response_date;
        $insertTrx->pum_status       = $pumStatus;
        $insertTrx->resp_status      = 'N';
        $insertTrx->org_id           = $org_id;
        $insertTrx->created_by       = $user_id;
        $insertTrx->creation_date    = date('Y-m-d');
        $insertTrx->files_data       = $getFileName;
        $insertTrx->upload_data      = $upload_data;
        $insertTrx->save();

        $getPumTrxId    = $model->getPumTrxId($emp_id);

        $insertTrxLines->pum_trx_id       = $getPumTrxId;
        $insertTrxLines->line_num         = '1';
        $insertTrxLines->pum_trx_type_id  = $trx_type;
        $insertTrxLines->description      = $description;
        $insertTrxLines->curr_code        = 'Rp';
        $insertTrxLines->amount           = $amount;
        $insertTrxLines->amount_remaining = $amount;
        $insertTrxLines->save();

        $this->sendNotifToApp1($emp_id,$amount);

        return response()->json(['error' => false, 'message' => "Success to Create New Pum"], 200);
    }

    public function sendNotifToApp1($emp_id, $amount){
        $model      = new DetailPum();
        $approval   = $model->getApproval1($emp_id,$amount);

        foreach ($approval as $data){
            $model  = new User();
            $token  = $model->getTokenFcm($data->APPROVAL_EMP_ID1);
            $msg    = 'You have a new Pum Request';

            if ($token != null){
                app('App\Http\Controllers\NotificationController')->sendNotif($token[0]->TOKEN_FCM, $msg);
            }
        }

        return $approval;
    }



}

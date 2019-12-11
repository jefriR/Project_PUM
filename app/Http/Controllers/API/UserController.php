<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function registerPin(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_num' => 'required',
            'password' => 'required | string | MIN : 6',
            'pin' => 'required | MIN : 6 | MAX : 6'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_num    = $request->emp_num;
        $password   = md5($request->password);
        $pin        = $request->pin;

        $model       = new User();
        $return     = $model->registerPin($emp_num,$password,$pin);

        switch ($return){
            case  1:
                return response()->json(['error' => true, 'message' => "ID Not Registered"], 401);
            case 2:
                return response()->json(['error' => true, 'message' => "Password Not Match"], 401);
            case 3:
                return response()->json(['error' => true, 'message' => "User Already Exist"], 401);
            case 4:
                return response()->json(['error' => false,'message' => "User Created Successfully"], 200);
            default:
                return response()->json(['error' => true,'message' => "Something's Error"],422);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_num' => 'required | string',
            'password' => 'required | string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_num    = $request->emp_num;
        $password   = md5($request->password);

        $model      = new User();
        $return     = $model->login($emp_num,$password);

        switch ($return){
            case  1:
                return response()->json(['error' => true, 'message' => "ID Not Registered"], 401);
            case 2:
                return response()->json(['error' => true, 'message' => "Password Not Match"], 401);
            case 3:
                $dataUser   = $model->getDataUser($emp_num);
                return response()->json(['error' => true, 'message' => "Login Successfully", 'data' => $dataUser], 200);
            default:
                return response()->json(['error' => true,'message' => "Something's Error"],422);
        }
    }

    public function changePin(Request $request){
        $validator = Validator::make($request->all(), [
            'old_pin'   => 'required | string',
            'new_pin'   => 'required | string | min:6 | max:6',
            'emp_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $oldPin = $request->old_pin;
        $newPin = $request->new_pin;
        $emp_id = $request->emp_id;

        $model  = new User();
        $getPin = $model->checkPin($emp_id);
        $cekPin = password_verify($oldPin,$getPin[0]->PIN);
        if ($cekPin == false) {
            return response()->json(['error' => true, 'message' => "Pin Not Match"], 400);
        }

        $model->changePin($emp_id,$newPin);

        return response()->json(['error' => false, 'message' => "Update Pin Success "], 200);
    }

    public function profilePicture(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id'    => 'required',
            'image'     => 'required | file',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_id = $request->emp_id;
        $image  = $request->file('image');

        //CekFormatFile
        $ext    = $image->getClientOriginalExtension();
        if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png'){
            return response()->json(['error' => true, 'message' => "Format File only .jpg, .jpeg, .png"], 400);
        }

        $destination    = public_path('images/photo_profile');
        $getExt         = $image->getClientOriginalExtension();
        $fileName       = 'PP_'.$emp_id.'.'.$getExt;
        $link           = url('images/photo_profile/'.$fileName);

        File::delete(['images/photo_profile/'.$fileName]);
        $image->move($destination, $fileName);

        $model  = new User();
        $model->changepicture($emp_id,$fileName);

        return response()->json(['error' => false, 'message' => "Update Picture Success", 'data' => $link], 200);
    }

    public function tokenFcm(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_id'    => 'required',
            'token'     => 'required | string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $emp_id = $request->emp_id;
        $token  = $request->token;

        $model  = new User();
        $model->tokenFcm($emp_id,$token);

        return response()->json(['error' => false, 'message' => "Token  Created Successfully"], 200);
    }


}

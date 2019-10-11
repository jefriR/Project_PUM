<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function registerPin(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_num' => 'required',
            'password' => 'required | string',
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
            'email' => 'required | string',
            'password' => 'required | string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required Parameters are Missing or Empty"], 401);
        }

        $email      = $request->email;
        $password   = md5($request->password);

        $model      = new User();
        $return     = $model->login($email,$password);

        switch ($return){
            case  1:
                return response()->json(['error' => true, 'message' => "ID Not Registered"], 401);
            case 2:
                return response()->json(['error' => true, 'message' => "Password Not Match"], 401);
            case 3:
                $dataUser   = $model->getDataUser($email);
                return response()->json(['error' => true, 'message' => "Login Successfully", 'data' => $dataUser], 200);
            default:
                return response()->json(['error' => true,'message' => "Something's Error"],422);
        }

    }


}

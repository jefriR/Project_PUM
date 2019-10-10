<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function registerPin(Request $request){
        $validator = Validator::make($request->all(), [
            'emp_num' => 'required',
            'password' => 'required',
            'pin' => 'required | MIN : 6 | MAX : 6'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>true, 'message' => "Required parameters are missing or empty"], 401);
        }

        $emp_num    = $request->emp_num;
        $password   = md5($request->password);
        $pin        = $request->pin;

        $data   = new User();
        $return = $data->registerPin($emp_num,$password,$pin);

        switch ($return){
            case  1:
                return response()->json(['error' => true, 'message' => "ID not registered"], 401);
            case 2:
                return response()->json(['error' => true, 'message' => "Password not match"], 401);
            case 3:
                return response()->json(['error' => false,'message' => "User created successfully"], 200);

            default:
                return response()->json(['error' => true,'message' => "Something's Error"],422);
        }
    }


}

<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emp_num', 'password', 'pin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','pin',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function registerPin($emp_num, $password, $pin){
        $cekUser    = DB::connection('api_sys')->table('sys_user')->select("*")->where('user_name', $emp_num)->get()->toArray();
        $cekPswrd   = DB::connection('api_sys')->table('sys_user')->select("*")->where('user_name', $emp_num)->where('pswd', $password)->get()->toArray();

        if ($cekUser == null){
            return 1;
        } elseif ($cekPswrd == null){
            return 2;
        } elseif ($cekUser[0]->PIN != null) {
            return 3;
        } else {
            DB::connection('api_sys')->table('sys_user')->where('user_name', $emp_num)->UPDATE(['PIN' => bcrypt($pin)]);
            return 4;
        }

    }

    public function login($emp_num, $password){
        $checkEmpNum    = DB::connection('api_sys')->table('sys_user')->select('*')->where('USER_NAME', $emp_num)->where('ACTIVE_FLAG', 'Y')->get()->toArray();

        if ($checkEmpNum == null){
            return 1;
        } else {
            $cekAuth    = DB::connection('api_sys')->table('sys_user')->select('PSWD')->where('USER_NAME', $emp_num)->where('ACTIVE_FLAG', 'Y')->get()->toArray();
            if ($password != $cekAuth[0]->PSWD) {
                return 2;
            } else {
                return 3;
            }
        }
    }

    public function getDataUser($emp_num){
        $data_hr        = DB::connection('api_hr')->table('hr_employees')->select("EMP_ID", "EMP_NUM", "NAME", "DEPT_ID", "MAX_CREATE_PUM", "ORG_ID")->where('EMP_NUM', $emp_num)->where('ACTIVE_FLAG', 'Y')->get()->toArray();
        $getMaxAmount   = DB::connection('api_pum')->table('pum_app_hierar')->select('*')->where('EMP_ID', $data_hr[0]->EMP_ID)->where('ACTIVE_FLAG', 'Y')->orderByDesc('PROXY_AMOUNT_TO')->first();

        $dataUser   = DB::connection('api_hr')->table('hr_employees')
            ->select("hr_employees.EMP_ID", "hr_employees.EMP_NUM", "hr_employees.NAME", "hr_employees.DEPT_ID",  "hr_employees.POSITION",
                "hr_employees.MAX_CREATE_PUM", "hr_employees.ORG_ID", "sys_r.RESP_ID", "sys_r.NAME as RESP_NAME", "sys_r.MENU_ID", "sys_r.ROLE_ID", "pum_ah.PROXY_AMOUNT_TO as MAX_AMOUNT", "sys_u.USER_ID", "sys_u.PHOTO_PROFILE")
            ->leftJoin('api_sys.sys_user as sys_u', 'sys_u.USER_NAME', 'hr_employees.EMP_NUM')
            ->leftJoin('api_sys.sys_user_resp as sys_ur', 'sys_ur.USER_ID', 'sys_u.USER_ID')
            ->leftJoin('api_sys.sys_resp as sys_r', 'sys_r.RESP_ID', 'sys_ur.RESP_ID')
            ->leftJoin('api_pum.pum_app_hierar as pum_ah', 'pum_ah.EMP_ID', 'hr_employees.EMP_ID')
            ->where('hr_employees.EMP_NUM', $emp_num)
            ->where('pum_ah.ACTIVE_FLAG', 'Y')
            ->get()->toArray();

        $link = url('laravel/public/images/photo_profile/'.$dataUser[0]->PHOTO_PROFILE);
        $dataUser[0]->PHOTO_PROFILE = $link;

        if ($getMaxAmount == null){
            $dataUser[0]->MAX_AMOUNT = 0;
        } else {
            $dataUser[0]->MAX_AMOUNT = $getMaxAmount->PROXY_AMOUNT_TO;
        }

        return $dataUser[0];
    }

    public function checkPin($emp_id){
        $pinUser    = DB::connection('api_sys')->table('sys_user')->select('PIN')->where('EMP_ID', $emp_id)->get()->toArray();

        return $pinUser;
    }

    public function changePin($emp_id, $newPin){
        $change = DB::connection('api_sys')->table('sys_user')->where('EMP_ID', $emp_id)->update(['PIN' => bcrypt($newPin)]);
    }

    public function changepicture($emp_id,$fileName){
        $change = DB::connection('api_sys')->table('sys_user')->where('EMP_ID', $emp_id)->update(['PHOTO_PROFILE' => $fileName]);
    }


}
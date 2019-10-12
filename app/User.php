<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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

    public function login($email, $password){
        $getEmpNum  = DB::connection('api_hr')->table('hr_employees')->select('EMP_NUM')->where('EMAIL', $email)->get()->toArray();
        $getEmpNum  = $getEmpNum[0]->EMP_NUM;

        if ($getEmpNum == null){
            return 1;
        } else {
            $cekAuth    = DB::connection('api_sys')->table('sys_user')->select('PSWD')->where('USER_NAME', $getEmpNum)->get()->toArray();
            if ($password != $cekAuth[0]->PSWD) {
                return 2;
            } else {
                return 3;
            }
        }

    }

    public function getDataUser($email){
        $data_hr        = DB::connection('api_hr')->table('hr_employees')->select("EMP_ID", "EMP_NUM", "NAME", "DEPT_ID", "MAX_CREATE_PUM", "ORG_ID")->where('EMAIL', $email)->get()->toArray();
        $getMaxAmount   = DB::connection('api_pum')->table('pum_app_hierar')->select('*')->where('EMP_ID', $data_hr[0]->EMP_ID)->where('ACTIVE_FLAG', 'Y')->orderByDesc('PROXY_AMOUNT_TO')->get()->toArray();

        $dataUser   = DB::connection('api_hr')->table('hr_employees')
            ->select("hr_employees.EMP_ID", "hr_employees.EMP_NUM", "hr_employees.NAME", "hr_employees.DEPT_ID",
                "hr_employees.MAX_CREATE_PUM", "hr_employees.ORG_ID", "sys_r.RESP_ID", "sys_r.NAME as RESP_NAME", "sys_r.MENU_ID", "sys_r.ROLE_ID", "pum_ah.PROXY_AMOUNT_TO as MAX_AMOUNT")
            ->leftJoin('api_sys.sys_user as sys_u', 'sys_u.USER_NAME', 'hr_employees.EMP_NUM')
            ->leftJoin('api_sys.sys_user_resp as sys_ur', 'sys_ur.USER_ID', 'sys_u.USER_ID')
            ->leftJoin('api_sys.sys_resp as sys_r', 'sys_r.RESP_ID', 'sys_ur.RESP_ID')
            ->leftJoin('api_pum.pum_app_hierar as pum_ah', 'pum_ah.EMP_ID', 'hr_employees.EMP_ID')
            ->where('hr_employees.EMAIL', $email)
            ->where('pum_ah.ACTIVE_FLAG', 'Y')
            ->where('pum_ah.ID', $getMaxAmount[0]->ID)->get()->toArray();

        return $dataUser;
    }

}